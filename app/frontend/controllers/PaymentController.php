<?php
namespace Multiple\Frontend\Controllers;
use App\Library\OnePay;
class PaymentController extends ControllerBase
{
    public function onePayRequestAction($r) {

        $op = new OnePay();
        $responseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/payment/onepayresponse?ticket_id=100';
        header(
            'location:' .
            $op->buildNoidiaUrl(
                100, 841675241500, 100000 * 100, $responseUrl));
        die();
    }

    public function onePayResponseAction() {
        $message = $_GET["vpc_Message"];
        if(isset($_GET["vpc_Message"])) {
            $this->flashSession->success('<script>alert(cx);</script>');
            return $this->response->redirect(array('for' => "trang-chu"));
        }
        $op = new OnePay();
        $stt = $op->getNoidiaResponse($_GET);
        if ($stt != 'OK') {

            $stt = $op->getNoidiaResponseString();
            return $this->flash->error('<script>alert(\'' . $stt . '\');</script>');
        }

        $script = "window.close();";
        if ($stt == 'OK') {
            $this->sendMtSuccess($this->ticket);
            $stt = LangPeer::getText('Thanh toán thành công');
            $script = 'window.close();' . $script;
        }
        session_regenerate_id(true);

        $this->logTransaction($this->tickets, PartnerPayPeer::ONEPAY);

        return $this->renderText('<script>alert(\'' . $stt . '\');' . $script . '</script>');
    }


}
