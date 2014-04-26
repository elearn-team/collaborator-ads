<?php

namespace Ads\Webservice;

use Bazalt\Rest\Response;
use Ads\Model\Place;

/**
 * @uri /ads/places/:id
 */
class PlaceResource extends \Bazalt\Auth\Webservice\JWTWebservice
{
    /**
     * @method GET
     * @provides application/json
     * @json
     * @return \Bazalt\Rest\Response
     */
    public function get($id)
    {
        $user = \Bazalt\Auth::getUser(); // $this->getJWTUser();
        if (!$user || $user->isGuest()) {
            return new Response(Response::UNAUTHORIZED);
        }
        $campaign = Place::getById($id);
        $res = $campaign->toArray();

        return new Response(Response::OK, $res);
    }

    /**
     * @method POST
     * @json
     */
    public function saveItem($id = null)
    {
        $user = \Bazalt\Auth::getUser(); // $this->getJWTUser();
        $dataValidator = \Bazalt\Site\Data\Validator::create($this->request->data);
        $item = ($id == null) ? Place::create() : Place::getById($id);
        if (!$item) {
            return new Response(Response::NOTFOUND, ['id' => 'Page not found']);
        }
        if (!$id && !$user->hasPermission('ads.can_manage_places')) {
            return new Response(Response::FORBIDDEN, ['id' => 'You can\'t create places']);
        }
        if ($item->user_id != $user->id && !$user->hasPermission('ads.can_manage_places')) {
            return new Response(Response::FORBIDDEN, ['user_id' => 'You haven\'t permissions to edit foreign pages']);
        }

        $dataValidator->field('title')->required()->length(1, 255);

        //$dataValidator->field('is_published')->bool();

        if (!$dataValidator->validate()) {
            return new Response(Response::BADREQUEST, $dataValidator->errors());
        }

        $item->title = $dataValidator['title'];
        $item->width = $dataValidator['width'];
        $item->height = $dataValidator['height'];

        $item->save();

        return new Response(Response::OK, $item->toArray());
    }
}
