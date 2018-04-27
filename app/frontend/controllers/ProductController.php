<?php
namespace Multiple\Frontend\Controllers;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductImage;
class ProductController extends ControllerBase
{
    public function indexAction()
    {
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
        $this->view->products = $robots;
    }

    public function productDetailAction($id){
        $this->relatedProductsAction();
            $robots = Product::query()
                ->innerJoin(ProductDetail::class,Product::class.".product_detail_id =".ProductDetail::class.".id")
                ->innerJoin(ProductImage::class,Product::class.".id =".ProductImage::class.".product_id")
                ->orderBy(Product::class.".created_at DESC")
                ->where(Product::class.".id = ". $id )
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
            $productImage = ProductImage::query()
                ->where(ProductImage::class.".product_id = ". $id)
                ->columns([
                    ProductImage::class.".image"
                ])
                ->execute();
        $this->view->productImage = $productImage;
        $this->view->productDetail = $robots;
    }

    public function relatedProductsAction(){
        $robots = Product::query()
            ->innerJoin(ProductDetail::class,Product::class.".product_detail_id =".ProductDetail::class.".id")
            ->innerJoin(ProductImage::class,Product::class.".id =".ProductImage::class.".product_id")
            ->orderBy(Product::class.".created_at DESC")
            ->groupBy(Product::class.".id")
            ->limit(10)
            ->columns([
                ProductDetail::class.".product_name" ,
                Product::class.".id" ,
                Product::class.".description_id",
                Product::class.".sale_price" ,
                Product::class.".discount" ,
                ProductImage::class.".image"
            ])
            ->execute();
        $this->view->products = $robots;
    }




}
