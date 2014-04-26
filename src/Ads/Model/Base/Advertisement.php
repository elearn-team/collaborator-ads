<?php

namespace Ads\Model\Base;

abstract class Advertisement extends \Bazalt\ORM\Record
{
    const TABLE_NAME = 'com_ads_advertisements';

    const MODEL_NAME = 'Ads\\Model\\Advertisement';

    public function __construct()
    {
        parent::__construct(self::TABLE_NAME, self::MODEL_NAME);
    }

    protected function initFields()
    {
        $this->hasColumn('id', 'PA:int(10)');
        $this->hasColumn('campaign_id', 'U:int(10)');
        $this->hasColumn('place_id', 'U:int(10)');
        $this->hasColumn('title', 'N:varchar(255)');
        $this->hasColumn('description', 'N:text');
        $this->hasColumn('options', 'N:text');
        $this->hasColumn('moderator_message', 'N:text');
        $this->hasColumn('url', 'N:varchar(255)');
        $this->hasColumn('pay_type', 'U:tinyint(1)|0');
        $this->hasColumn('ad_type', 'N:int(10)');
        $this->hasColumn('clicks', 'N:int(10)');
        $this->hasColumn('views', 'N:int(10)');
        $this->hasColumn('bid', 'N:float');
        $this->hasColumn('budget', 'N:float');
        $this->hasColumn('status', 'U:tinyint(1)|0');
    }

    public function initRelations()
    {
        $this->hasRelation('Campaign', new \Bazalt\ORM\Relation\One2One('\Ads\\Model\\Campaign', 'campaign_id', 'id'));
        $this->hasRelation('Place', new \Bazalt\ORM\Relation\One2One('\Ads\\Model\\Place', 'place_id', 'id'));
    }

    public function initPlugins()
    {
        $this->hasPlugin('Bazalt\\ORM\\Plugin\\Timestampable', ['created' => 'created_at', 'updated' => 'updated_at']);
        $this->hasPlugin('Bazalt\\ORM\\Plugin\\Serializable', 'options');
    }
}