<?php

namespace Ads\Webservice;

use Bazalt\Rest\Resource;
use Bazalt\Rest\Response;
use Ads\Model\Campaign;

/**
 * @uri /ads/campaigns
 */
class CampaignsResource extends Resource
{
    /**
     * @method GET
     * @provides application/json
     * @json
     * @return \Bazalt\Rest\Response
     */
    public function get()
    {
        $campaigns = Campaign::getAll();
        $res = array();
        foreach ($campaigns as $campaign) {
            $res []= $campaign->toArray();
        }
        return new Response(Response::OK, $res);
    }
}
