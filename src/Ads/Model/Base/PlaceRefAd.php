<?php

namespace Ads\Model\Base;

abstract class PlaceRefAd extends \Bazalt\ORM\Record
{
    const TABLE_NAME = 'com_ads_ref_places';

    const MODEL_NAME = 'Ads\\Model\\Place';

    public function __construct()
    {
        parent::__construct(self::TABLE_NAME, self::MODEL_NAME);
    }

    protected function initFields()
    {
        $this->hasColumn('id', 'PA:int(10)');
        $this->hasColumn('title', 'N:varchar(255)');
        $this->hasColumn('width', 'N:int(10)');
        $this->hasColumn('height', 'N:int(10)');
        $this->hasColumn('hosts', 'N:int(10)');
        $this->hasColumn('hits', 'N:int(10)');
        $this->hasColumn('clicks', 'N:int(10)');
    }

    public function initRelations()
    {
        //$this->hasRelation('Campaign', new \Bazalt\ORM\Relation\One2One('\Ads\\Model\\Campaign', 'campaign_id', 'id'));
    }

    public function initPlugins()
    {
        //$this->hasPlugin('Bazalt\\ORM\\Plugin\\Timestampable', ['created' => 'created_at', 'updated' => 'updated_at']);
    }
}