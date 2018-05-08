<?php
namespace Multiple\Frontend\Controllers;
use App\Models\Producer;
use App\Models\Product;
use App\Models\ProductCredential;
use App\Models\ProductDetail;
use App\Models\ProductImage;
use App\Models\ProductType;

class ProductController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle("Sản phẩm");
    }

    public function indexAction($type_id = '' , $brand = '' , $price = '' , $sortBy = '')
    {
        $productType = ProductType::findFirst("id = '".$type_id."'");
        if (!$productType)
            $productType = ProductType::findFirst("redirect = '".$type_id."'");
        $this->bestSellersAction();
        $this->setSessionProductType($productType);
        $robots = null;
        if ($productType)
            $robots = $this->getDataProductType($productType->getId());
        else
            $robots = $this->getDataDefault();
        $this->view->products = $robots;
        $this->view->currentType = $productType ? $productType->getRedirect() : 'tat-ca-san-pham';
        $this->view->producttype = ProductType::find();
        $this->view->producer = Producer::find();
    }

    public function updateViewAction($id){
        $product = Product::findFirst($id);
        $view = (int)$product->getView();
        $product->setView($view + 1);
        $product->save();
    }

    public function  getDataProductType($type_id){
        $robots = Product::query()
            ->innerJoin(ProductDetail::class,Product::class.".product_detail_id =".ProductDetail::class.".id")
            ->innerJoin(ProductImage::class,Product::class.".id =".ProductImage::class.".product_id")
            ->innerJoin(ProductCredential::class,Product::class.".id =".ProductCredential::class.".product_id")
            ->where(ProductCredential::class.".product_type_id = ". $type_id)
            ->orderBy(Product::class.".created_at DESC")
            ->groupBy(Product::class.".id")
            ->columns([
                ProductDetail::class.".product_name" ,
                Product::class.".id" ,
                Product::class.".description_id",
                Product::class.".sale_price" ,
                Product::class.".discount" ,
                ProductImage::class.".image"
            ])
            ->execute();
        return $robots;
    }

    public function getDataDefault(){
        $robots = Product::query()
            ->innerJoin(ProductDetail::class,Product::class.".product_detail_id =".ProductDetail::class.".id")
            ->innerJoin(ProductImage::class,Product::class.".id =".ProductImage::class.".product_id")
            ->orderBy(Product::class.".created_at DESC")
            ->groupBy(Product::class.".id")
            ->columns([
                ProductDetail::class.".product_name" ,
                Product::class.".id" ,
                Product::class.".description_id",
                Product::class.".sale_price" ,
                Product::class.".discount" ,
                ProductImage::class.".image"
            ])
            ->execute();
        return $robots;
    }

    public function bestSellersAction(){
        $robots = Product::query()
            ->innerJoin(ProductDetail::class,Product::class.".product_detail_id =".ProductDetail::class.".id")
            ->innerJoin(ProductImage::class,Product::class.".id =".ProductImage::class.".product_id")
            ->orderBy(Product::class.".view DESC")
            ->groupBy(Product::class.".id")
            ->limit(5)
            ->columns([
                ProductDetail::class.".product_name" ,
                Product::class.".id" ,
                Product::class.".description_id",
                Product::class.".sale_price" ,
                Product::class.".discount" ,
                ProductImage::class.".image"
            ])
            ->execute();
        $this->view->bestSellers = $robots;
    }

    protected function setSessionProductType($type_name)
    {
        if ($type_name) {
            $this->session->set('product', 'Sản phẩm ' . strtolower($type_name->getName()));
        } else {
            $this->session->set('product', 'Tất cả sản phẩm ');
        }
    }


}
