<?php

namespace Ads\Model\Base;

abstract class AdvertisementLog extends \Bazalt\ORM\Record
{
    const TABLE_NAME = 'com_ads_log';

    const MODEL_NAME = 'Ads\\Model\\AdvertisementLog';

    public function __construct()
    {
        parent::__construct(self::TABLE_NAME, self::MODEL_NAME);
    }

    protected function initFields()
    {
        $this->hasColumn('ad_id', 'P:int(10)');
        $this->hasColumn('date', 'P:date');
        $this->hasColumn('clicks', 'int(10):0');
        $this->hasColumn('views', 'int(10):0');
        $this->hasColumn('budget', 'float:0');
    }

    public function initRelations()
    {
        $this->hasRelation('Advertisement', new \Bazalt\ORM\Relation\One2One('\Ads\\Model\\Advertisement', 'ad_id', 'id'));
    }

    public function initPlugins()
    {
    }
}