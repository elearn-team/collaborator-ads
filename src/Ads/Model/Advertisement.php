<?php

namespace Ads\Model;

use Bazalt\ORM;

class Advertisement extends Base\Advertisement
{

    public static function getCollection()
    {
        $q = Advertisement::select();
        return new \Bazalt\ORM\Collection($q);
    }

    public static function getRandomAd($width, $height)
    {
        $q = Advertisement::select()
            ->andWhere('width = ?', $width)
            ->andWhere('height = ?', $height)
            ->orderBy('RAND() * bid DESC');

        return $q->fetch();
    }

    public static function getRecomendedBid()
    {
        $q = Advertisement::select('ROUND(MIN(bid), 2) AS min_bid, ROUND(AVG(bid), 2) AS avg_bid, ROUND(MAX(bid), 2) AS max_bid')
            ->where('bid > 0');

        return $q->fetch('stdClass');
    }

    public function impression()
    {
        ORM::update('\Ads\\Model\\Advertisement a')
            ->set('views = views + 1')
            ->where('id = ?', $this->id)
            ->exec();

        // update statistic
        $count = ORM::update('\Ads\\Model\\AdvertisementLog l')
            ->set('views = views + 1')
            ->where('ad_id = ?', $this->id)
            ->andWhere('`date` = ?', date('Y-m-d'))
            ->exec();

        if ($count < 1) {
            $adLog = new AdvertisementLog();
            $adLog->views = 1;
            $adLog->ad_id = $this->id;
            $adLog->date = date('Y-m-d');
            $adLog->save();
        }
    }

    public function click()
    {
        ORM::update('\Ads\\Model\\Advertisement a')
            ->set('clicks = clicks + 1, budget = budget + bid ')
            ->where('id = ?', $this->id)
            ->exec();

        $q = Advertisement::select('SUM(budget)')
            ->where('campaign_id = ?', $this->campaign_id);

        ORM::update('\Ads\\Model\\Campaign c')
            ->set('budget_spent = (' . $q->toSQL() . ')')
            ->where('id = ?', $this->campaign_id)
            ->exec();

        // update statistic
        $count = ORM::update('\Ads\\Model\\AdvertisementLog l')
            ->set('clicks = clicks + 1, budget = budget + ' . (float)$this->bid)
            ->where('ad_id = ?', $this->id)
            ->andWhere('`date` = ?', date('Y-m-d'))
            ->exec();
        if ($count < 1) {
            $adLog = new AdvertisementLog();
            $adLog->clicks = 1;
            $adLog->budget = $this->bid;
            $adLog->ad_id = $this->id;
            $adLog->date = date('Y-m-d');
            $adLog->save();
        }
    }

    public function display()
    {
        echo $this->options;
    }

    public function toArray()
    {
        $res = parent::toArray();

        $res['ad_type'] = (int)$this->ad_type;

        if ($place = $this->Place) {
            $res['place'] = $place->toArray();
        }

        if (property_exists($this->options, 'image')) {
            $config = \Bazalt\Config::container();
            $image = str_replace($config['uploads.prefix'],'',$this->options->image);
            $res['image'] = array(
                'url' => $image,
                'thumbnailUrl' => thumb(PUBLIC_DIR . $image, '100x100', ['crop' => true, 'fit' => true])
            );
        }

        return $res;
    }

    public function save()
    {
        parent::save();

        if ($place = $this->Place) {
            $place->publishBanners();
        }
    }
}