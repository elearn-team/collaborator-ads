<?php

namespace Ads\Model;

use Bazalt\ORM;

class Place extends Base\Place
{
    public static function create()
    {
        $place = new Place();
        return $place;
    }

    public static function getCollection()
    {
        $q = Place::select();
        return new \Bazalt\ORM\Collection($q);
    }

    public function getRandomAd()
    {
        $q = Advertisement::select()
                ->leftJoin('\Ads\\Model\\PlaceRefAd p', ['ad_id', 'id'])
                ->andWhere('place_id = ?', $this->id)
                ->orderBy('RAND() * bid DESC');

        return $q->fetch('\Ads\\Model\\Advertisement');
    }

    public function click()
    {
        ORM::update('\Ads\\Model\\Place')
            ->set('clicks = clicks + 1')
            ->where('id = ?', $this->id)
            ->exec();
    }

    public function impression()
    {
        ORM::update('\Ads\\Model\\Place')
            ->set('hits = hits + 1')
            ->where('id = ?', $this->id)
            ->exec();
    }

    public function publishBanners()
    {
        $dir = PUBLIC_DIR . '/_static/js/zones/s_';

        $banners = $this->Ads->get();

        $content = '_5stars.jz.push({ id: ' . $this->id . ', zone: function(element, zone, _5stars) {';
        $content .= 'element.innerHTML = \'';

        $html = '';
        foreach ($banners as $banner) {

            if ($banner->ad_type == 0) {
                $html = '';
                if (!empty($banner->url)) {
                    $html .= '<a target="_blank" href="http://5starsmedia.com.ua/api/ad.php?z=' . $banner->id . '&link=' . urlencode($banner->url) . '">';
                }

                $html .= '<img style="max-width: 100%" src="http://5starsmedia.com.ua' . $banner->options->image . '">';

                if (!empty($banner->url)) {
                    $html .= '</a>';
                }
            } else {
                $html = stripcslashes($banner->options->code);
            }

        }

        $content .= $html . '\';';

        $content .= '}}); _5stars.deploy();';
        file_put_contents($dir . $this->id . '.js', $content);
    }
}