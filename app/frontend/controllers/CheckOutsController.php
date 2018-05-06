<?php
namespace Multiple\Frontend\Controllers;
use App\Models\District;
use App\Models\Province;
use Phalcon\Mvc\View;

class CheckOutsController extends ControllerBase
{
    public function customerInfoAction()
    {
        $this->view->setRenderLevel(View::LEVEL_LAYOUT);
        $this->view->setRenderLevel(View::LEVEL_AFTER_TEMPLATE);
        $this->view->provinces = Province::find(array('order'=>'name ASC'));
        $this->view->showCart =  $this->session->get('cart');
    }

    public function paymentAction()
    {
        $this->view->setRenderLevel(View::LEVEL_LAYOUT);
        $this->view->setRenderLevel(View::LEVEL_AFTER_TEMPLATE);
        $this->view->provinces = Province::find(array('order'=>'name ASC'));
        $this->view->showCart =  $this->session->get('cart');
    }

    public function confirmAction(){
        $this->view->setRenderLevel(View::LEVEL_LAYOUT);
        $this->view->setRenderLevel(View::LEVEL_AFTER_TEMPLATE);
        $this->view->showCart =  $this->session->get('cart');
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
