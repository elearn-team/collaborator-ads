<?php

namespace Ads\Webservice;

use Bazalt\Rest\Resource;
use Bazalt\Rest\Response;
use Ads\Model\Place;

/**
 * @uri /ads/places
 */
class PlacesResource extends Resource
{
    /**
     * @method GET
     * @provides application/json
     * @json
     * @return \Bazalt\Rest\Response
     */
    public function get()
    {
        $params = $this->params();

        $collection = Place::getCollection();
        $table = new \Bazalt\Rest\Collection($collection);

        $res = $table->fetch($params, function($item) use ($params) {

            return $item;
        });
        return new Response(Response::OK, $res);
    }

    /**
     * @method POST
     * @json
     */
    public function saveArticle()
    {
        $res = new PlaceResource($this->app, $this->request);

        return $res->saveItem();
    }
}
