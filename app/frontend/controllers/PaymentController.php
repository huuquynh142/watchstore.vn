<?php
namespace Multiple\Frontend\Controllers;
use App\Library\OnePay;
class PaymentController extends ControllerBase
{
    public function onePayRequestAction($id , $phonenumber , $price) {
        $op = new OnePay();
        $responseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/payment/onepayresponse?id='.$id;
        header(
            'location:' .
            $op->buildNoidiaUrl(
                $id, $phonenumber, (int)$price * 100, $responseUrl));
        die();
    }

    public function onePayResponseAction() {
        $op = new OnePay();
        $stt = $op->getNoidiaResponse($_GET);
        if ($stt != 'OK') {
            $stt = $op->getNoidiaResponseString();
            return $this->flash->error($stt);
        }

        if ($stt == 'OK') {
            $this->session->set('pay_method', 2);
            return $this->response->redirect(array('for' => "hoan-thanh"));
        }

        return $this->response->redirect(array('for' => "trang-chu"));
    }
}
