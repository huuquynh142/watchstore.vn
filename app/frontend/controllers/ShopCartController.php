<?php
namespace Multiple\Frontend\Controllers;
use App\Library\ShopCart;
use Phalcon\Mvc\View;

class ShopCartController extends ControllerBase
{

    public function indexAction(){
       $this->view->showCart =  $this->session->get('cart');
    }


    public function addAction(){
        $id = $this->request->getPost("id");
        if ($this->session->has('cart')) {
            $cart = $this->session->get('cart');
            if (isset($cart[$id])) {
                if ($cart[$id]->quatity > 9)
                    return json_encode(array('code' => "fail" , 'message' => "Số lượng mua tối đa trên một sản phẩm là 10 \n Vui lòng kiểm tra lại")) ;
                $cart[$id]->quatity++;
                if ($cart[$id]->discount)
                    $cart[$id]->total = ($cart[$id]->price * $cart[$id]->quatity) - ($cart[$id]->price * $cart[$id]->quatity * $cart[$id]->discount);
                else
                    $cart[$id]->total = ($cart[$id]->price * $cart[$id]->quatity);

            }
            else {
                $cart[$id] = new ShopCart($id);
            }
        }
        else {
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
                    $cart[$id]->total = ($cart[$id]->price * $cart[$id]->quatity) - ($cart[$id]->price * $cart[$id]->quatity * $cart[$id]->discount);
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
        $this->session->set('countCart',$countProduct);
        $this->session->set('totalCart',number_format($count ,0  , 0 , '.').' VND');
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
                            <form id="form-cart">
                                <div class="cart-content">'


            .$this->listData().


            '<div class="cart-function text-right">
                                        <div class="cart-subtotal">Tổng :  <span class="money">'.$this->session->get('totalCart').'</span></div>



                                        <div class="function-buttons">
                                            <a class="button" href="/frontend/product/index" title="">Tiếp tục mua hàng</a>
                                            <!--<input class="button btn-update" type="submit" name="update" value="Update Cart">-->
                                            <input class="button btn-checkout" type="submit" name="checkout" value="Thanh toán">
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            ';
    }

    public function listData(){
        $row = null;
        foreach ($this->session->get('cart') as $product) {
            $row .=   '<div class="cart-item row">
                            <div class="small-12 large-6 columns">
                                <div class="row">
                                    <div class="small-4 columns">
                                        <a href="/products/jabra-revo-wireless-bluetooth-stereo-headphones">
                                            <img src="/public/img/product/'.$product->image.'" alt="Jabra REVO Wireless Bluetooth Stereo Headphones - X / Red">
                                        </a>
                                    </div>
                                    <div class="small-8 columns">
                                        <h3 class="product-name">
                                            <a href="/products/jabra-revo-wireless-bluetooth-stereo-headphones">
                                                '.$product->name.'
                                            </a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="small-12 large-6 columns">
                                <div class="row product-detail">
                                    <div class="small-6 large-6 columns">
        
        
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