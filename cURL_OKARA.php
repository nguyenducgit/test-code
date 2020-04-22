<?php
namespace App\Libs;

use Illuminate\Support\Facades\Log;

class Okara
{
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';

    const STATE_OKARA_PROCESSING = 1;
    const STATE_OKARA_NOT_EXIST = 0;
    const STATE_OKARA_SUCCESS = 2;
    const STATE_OKARA_EXPIRED = 3;
    const STATE_OKARA_FAILED = 4;

    private $url, $api_key;

    public function __construct()
    {
        $this->api_key = config('booth.apikey');
        $this->url = config('booth.uri_api');
    }

    public function getData($url, $params = [], $httpMethod = self::HTTP_METHOD_POST, array $extra = [])
    {
        $ch = curl_init();

        Log::info(sprintf('CURL Request: url=%s payload=%s method=%s',
            $url,
            json_encode($params),
            $httpMethod
        ));

        if (self::HTTP_METHOD_GET === $httpMethod) {
            $url = $url . '?' . http_build_query($params);
        }

        curl_setopt($ch, CURLOPT_URL, $url);

        if (self::HTTP_METHOD_POST === $httpMethod) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
//            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        }

        $headers = ['apikey: ' . $this->api_key];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);

        if (!empty($extra)) {
            curl_setopt_array($ch, $extra);
        }

        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        return $result;
    }

    //API để phía merchant gọi sang AirPay khởi tạo đơn hàng, nhận URL hiển thị QR Code từ phía AirPay
    public function getQRcodeImage($package_id, $user_id)
    {
        $params = [
            'package_id' => $package_id,
            'user_id' => $user_id
        ];
        $url = $this->url . '/user/airpay-qrcode-web';

        $response = self::getData($url, $params, self::HTTP_METHOD_GET);
        return json_decode($response, true);
    }

    public function testAli($fields, $productId)
    {
        $params = [
            'fields' => $fields,
            'productId' => $productId
        ];
        $url = 'http://gw.api.alibaba.com/openapi/param2/2/portals.open/api.getPromotionProductDetail/12345';

        $response = self::getData($url, $params, self::HTTP_METHOD_GET);
//        dd($response);
        return json_decode($response, true);
    }

    //API để phía merchant gọi sang AirPay vấn tin trạng thái đơn hàng
    public function getOrderStatus($trans_id)
    {
        $params = [
            'trans_id' => $trans_id
        ];

        $url = $this->url . '/user/check-airpay-order-web';
        $response = self::getData($url, $params, self::HTTP_METHOD_POST);
        return json_decode($response, true);
    }

    public function registerSub($package_id, $user_id)
    {
        $params = [
            'package_id' => $package_id,
            'user_id' => $user_id,
        ];

        $url = $this->url . '/sub-packages/web-register';
        $response = self::getData($url, $params, self::HTTP_METHOD_POST);
        return json_decode($response, true);
    }


}