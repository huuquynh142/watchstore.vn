<?php
namespace Multiple\Frontend\Controllers;
use App\Library\ShopCart;

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
                $cart[$id]->quatity++;
            }
            else {
                $cart[$id] = new ShopCart($id);
            }
        }
        else {
            $cart[$id] = new ShopCart($id);
        }
        $this->session->set('cart',$cart);
        print_r($this->session->get('cart'));
    }

    public function editAction($id){
        if ($this->session->has('cart')) {
            $cart = $this->session->get('cart');
            if (isset($cart[$id])) {
                $cart[$id]->quatity++;
            }
            else {
                $cart[$id] = new ShopCart($id);
            }
        }
        else {
            $cart[$id] = new ShopCart($id);
        }
        $this->session->set('cart',$cart);
        print_r($this->session->get('cart'));
    }

    public function emptyCartAction()
    {
        $this->session->remove('cart');
    }

}