<?php
namespace Multiple\Frontend\Controllers;
use App\Models\Producer;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductImage;
class ProductDetailController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle("Chi tiết sản phẩm");
    }

    public function productDetailAction($id){
        $product = Product::findFirst($id);
        $view = (int)$product->getView();
        $product->setView($view + 1);
        $product->save();
        $this->relatedProductsAction();
            $robots = Product::query()
                ->innerJoin(ProductDetail::class,Product::class.".product_detail_id =".ProductDetail::class.".id")
                ->innerJoin(ProductImage::class,Product::class.".id =".ProductImage::class.".product_id")
                ->innerJoin(Producer::class,Product::class.".producer_id =".Producer::class.".id")
                ->orderBy(Product::class.".created_at DESC")
                ->where(Product::class.".id = ". $id )
                ->groupBy(Product::class.".id")
                ->columns([
                    ProductDetail::class.".product_name" ,
                    Product::class.".id" ,
                    Product::class.".description_id",
                    Product::class.".sale_price" ,
                    Product::class.".discount" ,
                    Producer::class.".company_name" ,
                    ProductImage::class.".image",
                    Product::class.".quantity",
                    ProductDetail::class.".shell_material" ,
                    ProductDetail::class.".wire_material" ,
                    ProductDetail::class.".guarantee" ,
                    ProductDetail::class.".glasses" ,
                    ProductDetail::class.".shell_diameter" ,
                    ProductDetail::class.".shell_thickness" ,
                    ProductDetail::class.".water_resistant" ,
                    ProductDetail::class.".is_electronic" ,
                    ProductDetail::class.".motor" ,
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
                Product::class.".quantity",
                Product::class.".sale_price" ,
                Product::class.".discount" ,
                ProductImage::class.".image"
            ])
            ->execute();
        $this->view->products = $robots;
    }

}
