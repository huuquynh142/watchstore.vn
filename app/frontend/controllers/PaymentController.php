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
        $message = $_GET["vpc_Message"];
        if(isset($_GET["vpc_Message"])) {
            return $this->response->redirect(array('for' => "trang-chu"));
        }
        $op = new OnePay();
        $stt = $op->getNoidiaResponse($_GET);
        if ($stt != 'OK') {

            $stt = $op->getNoidiaResponseString();
            return $this->flash->error($stt);
        }

        if ($stt == 'OK') {
            $this->sendMtSuccess($this->ticket);
            return $this->flash->success('thanh toán thành công');
        }

        return $this->renderText('<script>alert(\'' . $stt . '\');' . $script . '</script>');
    }

}
