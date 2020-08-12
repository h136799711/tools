<?php


namespace App\third\amap;


use by\component\http\HttpRequest;
use by\infrastructure\helper\CallResultHelper;

class IpAmap
{
    protected $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function get($ip)
    {
        $url = 'https://restapi.amap.com/v3/ip?';
        $params = [
            'ip' => $ip,
            'output' => "json",
        ];
        $sign = $this->sign($params, $this->key);
        $params['sig'] = $sign;
        $params['key'] = $this->key;
        $url .= http_build_query($params);

        $resp = HttpRequest::newSession()->get($url);
        if (!$resp->success) {
            return CallResultHelper::fail($resp->getError());
        }
        $content = $resp->getBody()->getContents();
        $content = json_decode($content, JSON_OBJECT_AS_ARRAY);
        if (is_array($content) && array_key_exists('status', $content)) {
            if (intval($content['status']) == 0) {
                return CallResultHelper::fail($content['info'], $content);
            }
            if (intval($content['status']) == 1) {
                $data = [
                    'province' => $content['province'],
                    'city' => $content['city'],
                    'adcode' => $content['adcode'],
                    'rectangle' => $content['rectangle']
                ];
                return CallResultHelper::success($data);
            }
        }
        return CallResultHelper::fail("未知错误");
    }

    public function sign($params, $key)
    {
        ksort($params, SORT_ASC);
        $sign = "";
        foreach ($params as $k => $v) {
            if (strlen($sign) > 0) {
                $sign .= '&';
            }
            $sign .= $k.'='.$v;
        }
        $sign .= $key;
        return md5($sign);
    }
}
