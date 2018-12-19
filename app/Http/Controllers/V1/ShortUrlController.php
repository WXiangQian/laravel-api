<?php

namespace App\Http\Controllers\V1;


use App\Services\ShortUrlService;
use Illuminate\Http\Request;

class ShortUrlController extends BaseController
{

    /**
     * @SWG\Post(
     *      path="/sina/short_url",
     *      tags={"public"},
     *      operationId="sina_short_url",
     *      summary="长链接转换为短链接-新浪api",
     *      consumes={"application/json"},
     *      produces={"application/json"},
     *     @SWG\Parameter(
     *          in="body",
     *          name="data",
     *          description="",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="url_long",description="长链接",type="string"),
     *          )
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="code", type="string",description="状态码"),
     *              @SWG\Property(property="message", type="string",description="提示信息"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="url_short", type="string", description="短链接"),
     *                  @SWG\Property(property="url_long", type="string", description="长链接"),
     *              ),
     *          )
     *      ),
     * )
     */
    public function getShortUrl(Request $request)
    {
        $urlLong = $request->input('url_long','');
        $shortUrl = ShortUrlService::getSinaShortUrl($urlLong);

        return $this->responseData($shortUrl);
    }

}