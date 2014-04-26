<?php

namespace Ads\Webservice;

use Bazalt\Rest\Response;
use Ads\Model\Place;
use Ads\Model\Advertisement;

/**
 * @uri /ads/advertisement/:id
 */
class AdvertisementResource extends \Bazalt\Rest\Resource
{
    /**
     * @method GET
     * @provides application/json
     * @priority 2
     * @json
     * @return \Bazalt\Rest\Response
     */
    public function getAd($id)
    {
        $ad = Advertisement::getById($id);
        if (!$ad) {
            return new Response(Response::NOTFOUND, array('id' => 'Not found'));
        }
        $ad = $ad->toArray();
        return new Response(Response::OK, $ad);
    }

    /**
     * @method GET
     * @provides application/json
     * @priority 3
     * @action recomendedBid
     * @json
     * @return \Bazalt\Rest\Response
     */
    public function recomendedBid()
    {
        $res = Advertisement::getRecomendedBid();
        return new Response(Response::OK, $res);
    }

    /**
     * @method POST
     * @provides application/json
     * @action changeStatus
     * @json
     * @return \Bazalt\Rest\Response
     */
    public function changeStatus()
    {
        $data = (array)$this->request->data;
        if (isset($data['id'])) {
            $ad = Advertisement::getById($data['id']);
        } else {
            return new Response(Response::NOTFOUND, array('id' => 'Not found'));
        }
        switch($ad->status) {
        case 0:
            $ad->status = 1;
            break;
        case 1:
            $ad->status = 2;
            break;
        case 2:
            $ad->status = 3;
            break;
        default:
            $ad->status = 0;
        }
        $ad->save();
        return new Response(Response::OK, $ad->toArray());
    }

    /**
     * @method POST
     * @provides application/json
     * @json
     * @return \Bazalt\Rest\Response
     */
    public function saveItem()
    {
        $data = (array)$this->request->data;
        $oldPlace = null;
        if (isset($data['id'])) {
            $ad = Advertisement::getById($data['id']);
            $oldPlace = $ad->place_id;
        } else {
            $ad = new Advertisement();
        }
        $ad->place_id = isset($data['place_id']) ? $data['place_id'] : null;
        $ad->title = isset($data['title']) ? $data['title'] : 'No title';
        $ad->description = isset($data['description']) ? $data['description'] : null;
        $ad->url = isset($data['url']) ? $data['url'] : null;
        $ad->ad_type = isset($data['ad_type']) ? $data['ad_type'] : 0;
        $ad->status = isset($data['status']) ? $data['status'] : 0;

        $ad->options = $data['options'];
        $ad->save();

        if ($oldPlace && ($place = Place::getById((int)$oldPlace))) {
            $place->publishBanners();
        }

        return new Response(Response::OK, $ad->toArray());
    }
}
