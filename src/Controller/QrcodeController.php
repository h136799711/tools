<?php


namespace App\Controller;


use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class QrcodeController extends AbstractController
{
    /**
     * @param $content
     * @param $size
     * @return Response
     */
    public function index($content, $size, $type)
    {
        $content = urldecode($content);
        if (empty($content)) {
            return new Response("error content");
        }
        $qrCode = new QrCode($content);
        if ($size > 800) $size = 800;
        if ($size < 60) $size = 60;
        $qrCode->setSize($size);
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));

        if ($type == 'base64') {
            return new Response($qrCode->writeDataUri(), 200);
        } else {
            return new Response($qrCode->writeString(), 200, ['Content-Type' => 'image/png']);
        }
    }
}
