<?php

namespace App\Controller;

use App\third\amap\IpAmap;
use by\infrastructure\helper\Object2DataArrayHelper;
use Dbh\SfCoreBundle\Common\ByEnv;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IpController extends AbstractController
{
    /**
     * @param $ip
     * @return Response
     * @throws \ReflectionException
     */
    public function index($ip)
    {
        if (empty($ip)) {
            return new JsonResponse("ip缺失");
        }

        $ret = (new IpAmap(ByEnv::get("AMQP_KEY")))->get($ip);

        return new JsonResponse(Object2DataArrayHelper::getDataArrayFrom($ret));
    }
}
