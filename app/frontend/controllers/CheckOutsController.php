<?php
namespace Multiple\Frontend\Controllers;
use App\Models\District;
use App\Models\Member;
use App\Models\Province;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceDetail;
use Phalcon\Mvc\View;

class CheckOutsController extends ControllerBase
{
    public function customerInfoAction()
    {
        $member = '';
        if ($this->session->get('user_phone')){
            $phone = $this->session->get('user_phone');
            $member =  Member::findFirst("phone_number = '".$phone."'");
        }
        $this->session->set('checkProcess','customer');
        $this->view->member = $member;
        $this->view->setRenderLevel(View::LEVEL_LAYOUT);
        $this->view->setRenderLevel(View::LEVEL_AFTER_TEMPLATE);
        $this->view->provinces = Province::find(array('order'=>'name ASC'));
        $this->view->showCart =  $this->session->get('cart');
    }

    public function customerInfopostAction()
    {

        if ($this->request->isPost()) {
            $phone = $this->request->getPost("checkout_phone");
            $member = Member::findFirst("phone_number = '".$phone."'");
            if (!$member){
                $member = new Member();
                $member->setPhoneNumber($phone);
                $member->setEmail($this->request->getPost("checkout_email"));
            }
            $member->setAddress($this->request->getPost("checkout_shipping_address"));
            $member->setFullname($this->request->getPost("checkout_name"));
            $member->setDistrictId($this->request->getPost("checkout_city"));
            $member->setProvinceId($this->request->getPost("checkout_province"));
            $member->save();

            $invoice = new SalesInvoice();
            $invoice->setPhone($phone);
            $invoice->setMemberId($member->getId());
            $invoice->setEmail($this->request->getPost("checkout_email"));
            $invoice->setFullname($this->request->getPost("checkout_name"));
            $invoice->setAddress($this->request->getPost("checkout_shipping_address"));
            $invoice->setShipping(str_replace("." , "" , $this->request->getPost("checkout_price_shipper")));
            $invoice->setTotal(str_replace("." , "" ,  $this->request->getPost("checkout_price_total")));
            $invoice->setDistrictId($this->request->getPost("checkout_city"));
            $invoice->setProvinceId($this->request->getPost("checkout_province"));
            $invoice->save();

            foreach ($this->session->get('cart') as $item){
                $invoiceDetail = new SalesInvoiceDetail();
                $invoiceDetail->setSalesInvoiceId($invoice->getId());
                $invoiceDetail->setProductId($item->id);
                $invoiceDetail->setPrice($item->price);
                $invoiceDetail->setQuantity($item->quatity);
                $invoiceDetail->setDiscount($item->discount);
                $invoiceDetail->setTotal($item->total);
                $invoiceDetail->save();
            }
            $this->session->set('checkProcess','customer');
            return json_encode(array('code'=>'success' , 'id' => $invoice->getId()));
        }

        $this->view->setRenderLevel(View::LEVEL_LAYOUT);
        $this->view->setRenderLevel(View::LEVEL_AFTER_TEMPLATE);
        $this->view->provinces = Province::find(array('order'=>'name ASC'));
        $this->view->showCart =  $this->session->get('cart');
    }


    public function paymentAction($id)
    {
        $this->session->set('checkProcess','payment');
        $invoice = SalesInvoice::findFirst($id);
        $this->view->setRenderLevel(View::LEVEL_LAYOUT);
        $this->view->setRenderLevel(View::LEVEL_AFTER_TEMPLATE);
        $this->view->provinces = Province::find(array('order'=>'name ASC'));
        $this->view->showCart =  $this->session->get('cart');
        $this->view->id = $id;
        $this->view->phone =  $invoice->getPhone();
        $this->view->price =  $invoice->getTotal();
    }

    public function confirmAction(){
        if($_POST["checkout"]){
            $this->session->set('checkProcess','confirm');
            $checkout = $_POST["checkout"];
           $paymethod = $checkout["different_billing_address"];
           $id = $checkout["invoice"];
           $invoice = SalesInvoice::findFirst($id);
           $invoice->setPayMethodId($paymethod);
           $invoice->save();

           $robots = SalesInvoice::query()
               ->innerJoin(District::class,SalesInvoice::class.".district_id =".District::class.".districtid")
               ->innerJoin(Province::class,SalesInvoice::class.".province_id =".Province::class.".provinceid")
               ->where(SalesInvoice::class.".id=" . $invoice->getId())
               ->columns([
                   Province::class.".name as province" ,
                   District::class.".name as district" ,
                   SalesInvoice::class.".id" ,
                   SalesInvoice::class.".phone" ,
                   SalesInvoice::class.".fullname" ,
                   SalesInvoice::class.".address" ,
                   SalesInvoice::class.".shipping" ,
                   SalesInvoice::class.".pay_method_id" ,
               ])
               ->execute();
            $this->view->infconfirm =  $robots;
            $this->view->setRenderLevel(View::LEVEL_LAYOUT);
            $this->view->setRenderLevel(View::LEVEL_AFTER_TEMPLATE);
            $this->view->showCart =  $this->session->get('cart');
        }
    }

    public function clearnsessionAction(){
        $this->session->remove('cart');
        $this->session->remove('countCart');
        $this->session->remove('totalCart');
    }

    public function districtAction($provinceid){
        $robot = District::query()
            ->where(District::class.".provinceid = ".$provinceid)
            ->orderBy(District::class.".name DESC")
            ->execute();
        if ($robot)
        {
            $list = null;
            foreach ($robot as $district){
                $list .= '<option value="'.$district->districtid.'">'.$district->type .' ' .$district->name.'</option>';
            }
            return json_encode(array(
                'code'=>'success',
                'data' => $list
            ));
        }
        return json_encode(array(
            'code'=>'fail',
            'message' => 'có lỗi xảy ra'
        ));

    }

    public function updateTotalAction($price){
        $price = str_replace('.0' , '0' , $price);
        $total = $price + $this->session->get('totalAllNotFormat');
        $this->session->set('totalAll' , number_format($total ,0  , 0 , '.'));
        $this->session->set('updateTotalAll' , $total);
        $this->session->set('price_shipper' , number_format($price ,0  , 0 , '.'));
        return json_encode(array(
            'code'=>'success',
            'data' => number_format($total,0  , 0 , '.')
        ));
    }

}
