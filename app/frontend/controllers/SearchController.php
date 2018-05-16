<?php
namespace Multiple\Frontend\Controllers;
use App\Models\Product;
use App\Models\ProductCredential;
use App\Models\ProductDetail;
use App\Models\ProductImage;

class SearchController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle("Tìm kiếm");
    }

    public function indexAction($keyword = '' ){
        $robots = null;
        $this->session->set('search-product', $keyword);
        $robots = Product::query()
            ->innerJoin(ProductDetail::class,Product::class.".product_detail_id =".ProductDetail::class.".id")
            ->innerJoin(ProductImage::class,Product::class.".id =".ProductImage::class.".product_id")
            ->innerJoin(ProductCredential::class,Product::class.".id =".ProductCredential::class.".product_id")
            ->where(ProductDetail::class.".product_name LIKE '%" . $keyword . "%'")
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


}
