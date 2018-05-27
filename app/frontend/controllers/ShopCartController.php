<?php
namespace Multiple\Frontend\Controllers;
use App\Library\ShopCart;
use App\Models\Product;
use Phalcon\Mvc\View;

class ShopCartController extends ControllerBase
{

    public function indexAction(){
        if (!$this->session->get('countCart'))
            return $this->response->redirect(array('for' => "trang-chu"));
        $this->session->remove('price_shipper');
       $this->view->showCart =  $this->session->get('cart');
    }


    public function addAction(){
        $id = $this->request->getPost("id");
        $quatity = $this->request->getPost("quatity");
        if ($this->session->has('cart')) {
            $cart = $this->session->get('cart');
            if (isset($cart[$id])) {
                $product = Product::findFirstByid($id);
                if ($cart[$id]->quatity > ($product->quantity -1))
                    return json_encode(array('code' => "fail" , 'message' => "Số lượng sản phâm trong kho hàng tối đa có ".$product->quantity." sản phẩm")) ;
                if ($cart[$id]->quatity > 9)
                    return json_encode(array('code' => "fail" , 'message' => "Số lượng mua tối đa trên một sản phẩm là 10 \n Vui lòng kiểm tra lại")) ;

                if ($quatity)
                    $quatity = (int)$quatity + (int)$cart[$id]->quatity;
                else
                    $quatity = (int)$cart[$id]->quatity + 1;
                $cart[$id]->quatity = $quatity;
                if ($cart[$id]->discount)
                    $cart[$id]->total = ($cart[$id]->price * $cart[$id]->quatity) - (($cart[$id]->discount/100)* $cart[$id]->price * $cart[$id]->quatity);
                else
                    $cart[$id]->total = ($cart[$id]->price * $cart[$id]->quatity);

            }
            else {
                if ($quatity)
                    $cart[$id] = new ShopCart($id , $quatity);
                else
                    $cart[$id] = new ShopCart($id);
            }
        }
        else {
            if ($quatity)
                $cart[$id] = new ShopCart($id , $quatity);
            else
                $cart[$id] = new ShopCart($id);
        }
        $this->session->set('cart',$cart);
        $this->totalCart();
        return json_encode(array(
            'code' => "success" ,
            'countCart' => $this->session->get('countCart')  ,
            'data' => $this->shopcartDialogAction(),
            'totalCart' => $this->session->get('totalCart'))) ;
    }

    public function editAction($id,$quatity){
            $cart = $this->session->get('cart');
            if (isset($cart[$id])) {
                $cart[$id]->quatity = $quatity;
                if ($cart[$id]->discount)
                    $cart[$id]->total = ($cart[$id]->price * $cart[$id]->quatity) - ($cart[$id]->price * $cart[$id]->quatity * ($cart[$id]->discount/100));
                else
                    $cart[$id]->total = ($cart[$id]->price * $cart[$id]->quatity);
                $this->session->set('cart',$cart);
                return json_encode(array(
                    'code'=>'success',
                    'total' => number_format($cart[$id]->total ,0  , 0 , '.').' VND',
                    'cart' => $this->totalCart(),
                    'countCart' => $this->session->get('countCart')  ,
                    'totalCart' => $this->session->get('totalCart')
                ));
            }
        return json_encode(array('code'=>'fail'));
    }

    public function deleteAction($id){
        $cart = $this->session->get('cart');
        if (isset($cart[$id])) {
            unset($cart[$id]);
            $this->session->set('cart',$cart);
            $this->totalCart();
            if (!$this->session->get('countCart'))
                return json_encode(array('code'=>'empty'));
            return json_encode(array(
                'code' => "success" ,
                'countCart' => $this->session->get('countCart')  ,
                'data' => $this->shopcartDialogAction(),
                'totalCart' => $this->session->get('totalCart'))) ;
        }
        return json_encode(array('code'=>'fail'));
    }


    public function totalCart(){
        $count = 0;
        $countProduct = 0;
        foreach ($this->session->get('cart') as $product){
            $count += $product->total;
            $countProduct += $product->quatity;
        }
        $tax = $count * 0.1;
        $allTotal = $tax + $count;
        $this->session->set('taxes' , number_format($tax ,0  , 0 , '.').' VND' );
        $this->session->set('countCart',$countProduct);
        $this->session->set('totalCart',number_format($count ,0  , 0 , '.').' VND');
        $this->session->set('totalAll' , number_format($allTotal ,0  , 0 , '.'));
        $this->session->set('totalAllNotFormat' , $allTotal);
        $this->session->remove('price_shipper');
        return number_format($count ,0  , 0 , '.').' VND';
    }

    public function emptyCartAction()
    {
        $this->session->remove('cart');
    }

    public function shopcartDialogAction(){
        return '
            <div class="row">
                <div class="row">
                    <div class="small-12 medium-12 large-12 columns">
                        <div class="cart">
                            <div class="cart-content">'


            .$this->listData().


            '<div class="cart-function text-right">
                                        <div class="cart-subtotal">Tổng :  <span class="money">'.$this->session->get('totalCart').'</span></div>



                                        <div class="function-buttons">
                                            <a id="nextOrder" class="button" href="/san-pham/tat-ca-san-pham" title="">Tiếp tục mua hàng</a>
                                            <a class="button btn-checkout" href="/gio-hang" title="">Thanh toán</a>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
            ';
    }

    public function listData(){
        $row = null;
        foreach ($this->session->get('cart') as $product) {
            $products = Product::findFirstByid($product->id);
            $row .=   '<div class="cart-item row">
                            <div class="small-12 large-6 columns">
                                <div class="row">
                                    <div class="small-4 columns">
                                        <a href="/san-pham/chi-tiet-san-pham/'.$product->id.'">
                                            <img src="/public/uploads/product/'.$product->image.'" alt="Jabra REVO Wireless Bluetooth Stereo Headphones - X / Red">
                                        </a>
                                    </div>
                                    <div class="small-8 columns">
                                        <h3 class="product-name">
                                            <a href="/san-pham/chi-tiet-san-pham/'.$product->id.'">
                                                '.$product->name.'
                                            </a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="small-12 large-6 columns">
                                <div class="row product-detail">
                                    <div class="small-6 large-6 columns">
        
                                        <input type="hidden" id="js-qty___quantity_'.$product->id.'" data-id="'.$products->quantity.'">
                                        <div class="js-qty" data-id="'.$product->id.'">
                                            <button type="button" class="js-qty__adjust js-qty__adjust--minus"  data-qty="0">−</button>
                                            <input type="text" class="js-qty__num" value="'.$product->quatity.'" min="1" aria-label="quantity" pattern="[0-9]*" name="updates[]">
                                            <button type="button" class="js-qty__adjust js-qty__adjust--plus" data-qty="10">+</button>
                                        </div>
        
        
                                    </div>
                                    <div class="small-4 large-4 columns text-right">
                                        <span class="price"><span class="money" id="money_'.$product->id.'" data-currency-usd="$149.99">'.number_format($product->total ,0  , 0 , '.').' VND</span></span>
                                    </div>
                                    <div class="small-2 large-2 columns text-right">
                                        <a class="btn-remove" id="btn-remove" data-id="'.$product->id.'" href="#"><i class="fa fa-trash-o"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>';
        }
        return $row;
    }

}