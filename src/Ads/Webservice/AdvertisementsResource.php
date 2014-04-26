<?php

namespace Ads\Webservice;

use Bazalt\Rest\Resource;
use Bazalt\Rest\Response;
use Ads\Model\Advertisement;

/**
 * @uri /ads/advertisement
 */
class AdvertisementsResource extends Resource
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

        $collection = Advertisement::getCollection();
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
        $res = new AdvertisementResource($this->app, $this->request);

        return $res->saveItem();
    }

    /**
     * @action upload
     * @method POST
     * @accepts multipart/form-data
     * @json
     */
    public function uploadFiles()
    {
        $uploader = new \Bazalt\Rest\Uploader(['jpg', 'png', 'jpeg', 'bmp', 'gif'], 10 * 1024 * 1024); //10M
        $result = $uploader->handleUpload(UPLOAD_DIR, ['advertisement']);
        $imageInfo = getimagesize(UPLOAD_DIR . $result['file']);

        $file = explode(".", $result['file']);
        $extension = end($file);

        $result['file'] = '/uploads' . $result['file'];
        $result['url'] = $result['file'];
        $result['extension'] = $extension;
        $result['width'] = $imageInfo[0];
        $result['height'] = $imageInfo[1];


        return new Response(Response::OK, $result);
    }
}
