<?php

namespace Ads\Webservice;

use Bazalt\Rest\Response;
use Ads\Model\Campaign;

/**
 * @uri /ads/campaigns/:id
 */
class CampaignResource extends \Bazalt\Auth\Webservice\JWTWebservice
{
    /**
     * @method GET
     * @provides application/json
     * @json
     * @return \Bazalt\Rest\Response
     */
    public function get($id)
    {
        $user = $this->getJWTUser();
        if (!$user || $user->isGuest()) {
            return new Response(Response::UNAUTHORIZED);
        }
        $campaign = Campaign::getById($id);
        $res = $campaign->toArray();
        $ads = $campaign->Ads->get();
        $res['ads'] = array();
        foreach ($ads as $ad) {
            $res['ads'][] = $ad->toArray();
        }
        return new Response(Response::OK, $res);
    }
}
