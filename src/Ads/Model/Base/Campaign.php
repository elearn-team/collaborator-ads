<?php

namespace Ads\Model\Base;

abstract class Campaign extends \Bazalt\ORM\Record
{
    const TABLE_NAME = 'com_ads_campaigns';

    const MODEL_NAME = 'Ads\\Model\\Campaign';

    public function __construct()
    {
        parent::__construct(self::TABLE_NAME, self::MODEL_NAME);
    }

    protected function initFields()
    {
        $this->hasColumn('id', 'PA:int(10)');
        $this->hasColumn('title', 'N:varchar(255)');
        $this->hasColumn('budget', 'float:0');
        $this->hasColumn('budget_spent', 'float:0');
    }

    public function initRelations()
    {
        $this->hasRelation('Ads', new \Bazalt\ORM\Relation\One2Many('\Ads\\Model\\Advertisement', 'id', 'campaign_id'));
    }

    public function initPlugins()
    {
        $this->hasPlugin('Bazalt\\ORM\\Plugin\\Timestampable', ['created' => 'created_at', 'updated' => 'updated_at']);
    }
}