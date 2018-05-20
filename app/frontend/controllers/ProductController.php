<?php
namespace Multiple\Frontend\Controllers;
use App\Models\Producer;
use App\Models\Product;
use App\Models\ProductCredential;
use App\Models\ProductDetail;
use App\Models\ProductImage;
use App\Models\ProductType;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\View;

class ProductController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle("Sản phẩm");
        $this->session->set('action','product');
    }

    public function indexAction($type_id = '' , $brand = '' , $price = '' , $sortBy = '')
    {
        $numberPage = 1;
        $numberPage = $this->request->getQuery("page", "int");
        $productType = ProductType::findFirst("id = '".$type_id."'");
        if (!$productType)
            $productType = ProductType::findFirst("redirect = '".$type_id."'");
        $this->bestSellersAction();
        $this->setSessionProductType($productType);
        $robots = null;
        $robots = Product::query()
            ->innerJoin(ProductDetail::class,Product::class.".product_detail_id =".ProductDetail::class.".id")
            ->innerJoin(ProductImage::class,Product::class.".id =".ProductImage::class.".product_id")
            ->innerJoin(ProductCredential::class,Product::class.".id =".ProductCredential::class.".product_id");
        if ($productType)
            $robots = $robots->where(ProductCredential::class.".product_type_id = ". $productType->getId());
        $robots = $this->brandQuery($brand, $robots);
        $robots = $this->priceQuery($price, $robots);
        $robots = $this->sortProduct($sortBy, $robots);
        $robots = $robots->groupBy(Product::class.".id")
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
        $paginator = new Paginator([
            'data' => $robots,
            'limit'=> 12,
            'page' => $numberPage
        ]);
        $this->view->products = $paginator->getPaginate();
        $this->view->currentType = $productType ? $productType->getRedirect() : 'tat-ca-san-pham';
        $this->view->producttype = ProductType::find();
        $this->view->producer = Producer::find();
    }
    public function searchProductAction($type_id = '' , $brand = '' , $price = '' , $sortBy = ''){
        $numberPage = 1;
        $numberPage = $this->request->getQuery("page", "int");
        $productType = ProductType::findFirst("id = '".$type_id."'");
        if (!$productType)
            $productType = ProductType::findFirst("redirect = '".$type_id."'");
        $this->bestSellersAction();
        $this->setSessionProductType($productType);
        $robots = null;
        $robots = Product::query()
            ->innerJoin(ProductDetail::class,Product::class.".product_detail_id =".ProductDetail::class.".id")
            ->innerJoin(ProductImage::class,Product::class.".id =".ProductImage::class.".product_id")
            ->innerJoin(ProductCredential::class,Product::class.".id =".ProductCredential::class.".product_id");
        if ($productType)
            $robots = $robots->where(ProductCredential::class.".product_type_id = ". $productType->getId());
        $robots = $this->brandQuery($brand, $robots);
        $robots = $this->priceQuery($price, $robots);
        $robots = $this->sortProduct($sortBy, $robots);
        $robots = $robots->groupBy(Product::class.".id")
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
        $paginator = new Paginator([
            'data' => $robots,
            'limit'=> 12,
            'page' => $numberPage
        ]);
        $this->view->products = $paginator->getPaginate();
        $this->view->setRenderLevel(View::LEVEL_LAYOUT);
        $this->view->setRenderLevel(View::LEVEL_BEFORE_TEMPLATE);
    }

    public function updateViewAction($id){
        $product = Product::findFirst($id);
        $view = (int)$product->getView();
        $product->setView($view + 1);
        $product->save();
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
                Product::class.".quantity",
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

    protected function priceQuery($price, $robots)
    {
        if ($price) {
            switch ($price) {
                case "0_to_5000000":
                    $robots = $robots->andWhere(Product::class . ".sale_price >= 0")
                                     ->andWhere(Product::class . ".sale_price <= 5000000");
                    break;
                case '5000000_to_10000000':
                    $robots = $robots->andWhere(Product::class . ".sale_price >= 5000000")
                                     ->andWhere(Product::class . ".sale_price <= 10000000");
                    break;
                case '10000000_to_20000000':
                    $robots = $robots->andWhere(Product::class . ".sale_price >= 10000000")
                                     ->andWhere(Product::class . ".sale_price <= 20000000");
                    break;
                case '20000000_etc':
                    $robots = $robots->andWhere(Product::class . ".sale_price >= 20000000")
                                     ->andWhere(Product::class . ".sale_price <= 1000000000");
                    break;
            }
        }
        return $robots;
    }

    protected function brandQuery($brand, $robots)
    {
        if ($brand) {
            $brand = str_replace("_", " ", $brand);
            $brand = strtoupper($brand);
            $poducer = Producer::findFirst("trademark = '" . $brand . "'");
            $robots = $robots->where(Product::class . ".producer_id = " . $poducer->getId());
        }
        return $robots;
    }

    protected function sortProduct($sortBy, $robots)
    {
        if ($sortBy) {
            switch ($sortBy) {
                case 'manual':
                    $robots = $robots->orderBy(Product::class . ".view");
                    break;
//                case 'best-selling':
//                    $robots = $robots->orderBy(Product::class . ".view");
//                    break;
                case 'title-ascending':
                    $robots = $robots->orderBy(ProductDetail::class . ".product_name ASC");
                    break;
                case 'title-descending':
                    $robots = $robots->orderBy(ProductDetail::class . ".product_name DESC");
                    break;
                case 'price-ascending':
                    $robots = $robots->orderBy(Product::class . ".sale_price ASC");
                    break;
                case 'price-descending':
                    $robots = $robots->orderBy(Product::class . ".sale_price DESC");
                    break;
                case 'created-descending':
                    $robots = $robots->orderBy(Product::class . ".created_at ASC");
                    break;
                case 'created-ascending':
                    $robots = $robots->orderBy(Product::class . ".created_at DESC");
                    break;
            }
        }
        return $robots;
    }

}
