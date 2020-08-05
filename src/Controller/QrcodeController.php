<?php


namespace App\Controller;


use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class QrcodeController extends AbstractController
{
    /**
     * @Route("/qrcode/{size<\d+>?}/{content}", name="web_qrcode")
     * @param $content
     * @param $size
     * @return \by\infrastructure\base\CallResult|string
     */
    public function create($content, $size)
    {
        $qrCode = new QrCode(urldecode($content));
        if ($size > 800) $size = 800;
        if ($size < 60) $size = 60;
        $qrCode->setSize($size);
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));

        return new Response($qrCode->writeString(), 200, ['Content-Type'=>'image/png']);
    }
}
