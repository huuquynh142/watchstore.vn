<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use App\Models\Product;
use App\Models\Producer;
use App\Models\DescriptionProduct;
use App\Models\ProductDetail;
use App\Models\ProductType;
use App\Models\ProductCredential;


class ProductController extends ControllerBase
{

    public function indexAction()
    {
        $numberPage = 1;
        $robots = Product::query()
            ->innerJoin(ProductDetail::class,Product::class.".product_detail_id =".ProductDetail::class.".id")
            ->innerJoin(Producer::class,Product::class.".producer_id =".Producer::class.".id");

        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, Product::class , $_POST);
            $this->persistent->parameters = $query->getParams();
            foreach ($query->getParams()['bind'] as $key => $item)
            {
                $robots = $robots->where(Product::class . ".". $key." = '". str_replace("%","",$item)."'" );
            }
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters['bind'];
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";
        $this->view->producer = Producer::find();
        $this->view->product = Product::find();
        $this->view->productDetail = ProductDetail::find();




        $robots = $robots->columns([ProductDetail::class.".product_name" ,
                Product::class.".id" ,
                Producer::class.".company_name" ,
                Product::class.".description_id",
                ProductDetail::class.".product_name",
                Product::class.".quantity" ,
                Product::class.".import_price" ,
                Product::class.".sale_price" ,
                Product::class.".discount" ,
                Product::class.".view"
            ])
            ->execute();
        $paginator = new Paginator([
            'data' => $robots,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    public function newAction()
    {

        $this->view->producer = Producer::find();
        $this->view->product = Product::find();
        $this->view->productDetail = ProductDetail::find();
        $this->view->productType = ProductType::find();
    }

    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $product = Product::findFirstByid($id);
            if (!$product) {
                $this->flash->error("product was not found");

                $this->dispatcher->forward([
                    'controller' => "product",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->productType = ProductType::find();
            $this->view->productCredential = ProductCredential::query()
                ->where(ProductCredential::class.".product_id =".$product->id)
                ->execute();
            $this->view->id = $product->id;
            $this->view->producer = Producer::find();
            $this->view->product = Product::find();
            $this->view->productDetail = ProductDetail::find();
            $this->tag->setDefault("id", $product->id);
            $this->view->producer_id = $product->producer_id;
            $this->view->description_id = $product->description_id;
            $this->view->product_detail_id = $product->product_detail_id;
            $this->tag->setDefault("quantity", $product->quantity);
            $this->tag->setDefault("import_price", $product->import_price);
            $this->tag->setDefault("sale_price", $product->sale_price);
            $this->tag->setDefault("discount", $product->discount);
            
        }
    }

    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "product",
                'action' => 'index'
            ]);

            return;
        }

        $product = new Product();
        $product->setProducerId($this->request->getPost("producer_id"));
        $product->setDescriptionId($this->request->getPost("description_id"));
        $product->setProductDetailId($this->request->getPost("product_detail_id"));
        $product->setQuantity($this->request->getPost("quantity"));
        $product->setImportPrice($this->request->getPost("import_price"));
        $product->setSalePrice($this->request->getPost("sale_price"));
        $product->setDiscount($this->request->getPost("discount"));
        

        if (!$product->save()) {
            foreach ($product->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product",
                'action' => 'new'
            ]);

            return;
        }
        $this->insertProductCredential($product);


        $this->flash->success("product was created successfully");

        $this->dispatcher->forward([
            'controller' => "product",
            'action' => 'index'
        ]);
        return;
    }

    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "product",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $product = Product::findFirstByid($id);

        if (!$product) {
            $this->flash->error("product does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "product",
                'action' => 'index'
            ]);

            return;
        }

        $product->producerId = $this->request->getPost("producer_id");
        $product->descriptionId = $this->request->getPost("description_id");
        $product->productDetailId = $this->request->getPost("product_detail_id");
        $product->quantity = $this->request->getPost("quantity");
        $product->importPrice = $this->request->getPost("import_price");
        $product->salePrice = $this->request->getPost("sale_price");
        $product->discount = $this->request->getPost("discount");
        

        if (!$product->save()) {

            foreach ($product->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product",
                'action' => 'edit',
                'params' => [$product->id]
            ]);

            return;
        }
        $productCredential = ProductCredential::find(ProductCredential::class.".product_id = " .$product->id);
        $productCredential->delete();
        $this->insertProductCredential($product);

        $this->flash->success("product was updated successfully");

        $this->dispatcher->forward([
            'controller' => "product",
            'action' => 'index'
        ]);
    }

    public function deleteAction($id)
    {
        $product = Product::findFirstByid($id);
        if (!$product) {
            $this->flash->error("product was not found");

            $this->dispatcher->forward([
                'controller' => "product",
                'action' => 'index'
            ]);

            return;
        }

        if (!$product->delete()) {

            foreach ($product->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("product was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "product",
            'action' => "index"
        ]);
    }

    protected function insertProductCredential($product)
    {
        foreach ($this->request->getPost("product_credentials") as $item) {
            $product_redential = new ProductCredential();
            $product_redential->setProductId($product->getId());
            $product_redential->setProductTypeId($item);
            $product_redential->save();
        }
    }

}
