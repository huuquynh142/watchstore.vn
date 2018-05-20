<?php
namespace Multiple\Frontend\Controllers;
use App\Models\ProductImage;
use App\Models\Slider;
use App\Models\ProductCredential;
use App\Models\ProductType;
use App\Models\Product;
use App\Models\ProductDetail;
class IndexController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle("Trang chủ");
        $this->session->set('action','home');
    }

    public function indexAction()
    {
        $this->slideShowAction();
        $this->horizontalAnimateAction();
        $this->newProductAction();
    }

    public function bannerShowAction(){
    }
    
    public function slideShowAction(){
        $robos = Slider::query()
            ->where(Slider::class.'.hide = 0')
            ->orderBy(Slider::class.'.priority ASC')
            ->execute();
        $this->view->slider = $robos;
    }
    public function horizontalAnimateAction(){
        $list = [];
        $robos = ProductType::find();
        foreach ($robos as $item)
        {
            $productCredentail = ProductCredential::query()
                ->where(ProductCredential::class.'.product_type_id='.$item->getId())
                ->execute();
            $subList = [
                'count' => count($productCredentail),
                'image' => $item->getImage(),
                'redirect' => $item->getRedirect()
            ];
            $list [$item->getName()] = $subList;
        }

        $this->view->horizontalAnimate = $list;
    }
    public function newProductAction(){
        $robots = Product::query()
            ->innerJoin(ProductDetail::class,Product::class.".product_detail_id =".ProductDetail::class.".id")
            ->innerJoin(ProductImage::class,Product::class.".id =".ProductImage::class.".product_id")
            ->orderBy(Product::class.".created_at DESC")
            ->groupBy(Product::class.".id")
            ->limit(10)
            ->columns([
                ProductDetail::class.".product_name" ,
                Product::class.".id" ,
                Product::class.".quantity",
                Product::class.".description_id",
                Product::class.".sale_price" ,
                Product::class.".discount" ,
                ProductImage::class.".image"
            ])
            ->execute();
        $this->view->newProduct = $robots;
    }

}
