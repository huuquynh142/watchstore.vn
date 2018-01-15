<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ProductController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for product
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Product', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $product = Product::find($parameters);
        if (count($product) == 0) {
            $this->flash->notice("The search did not find any product");

            $this->dispatcher->forward([
                "controller" => "product",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $product,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a product
     *
     * @param string $id
     */
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

            $this->view->id = $product->id;

            $this->tag->setDefault("id", $product->id);
            $this->tag->setDefault("producer_id", $product->producer_id);
            $this->tag->setDefault("description_id", $product->description_id);
            $this->tag->setDefault("product_detail_id", $product->product_detail_id);
            $this->tag->setDefault("quantity", $product->quantity);
            $this->tag->setDefault("import_price", $product->import_price);
            $this->tag->setDefault("sale_price", $product->sale_price);
            $this->tag->setDefault("discount", $product->discount);
            $this->tag->setDefault("image_id", $product->image_id);
            $this->tag->setDefault("view", $product->view);
            $this->tag->setDefault("created_at", $product->created_at);
            
        }
    }

    /**
     * Creates a new product
     */
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
        $product->producerId = $this->request->getPost("producer_id");
        $product->descriptionId = $this->request->getPost("description_id");
        $product->productDetailId = $this->request->getPost("product_detail_id");
        $product->quantity = $this->request->getPost("quantity");
        $product->importPrice = $this->request->getPost("import_price");
        $product->salePrice = $this->request->getPost("sale_price");
        $product->discount = $this->request->getPost("discount");
        $product->imageId = $this->request->getPost("image_id");
        $product->view = $this->request->getPost("view");
        $product->createdAt = $this->request->getPost("created_at");
        

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

        $this->flash->success("product was created successfully");

        $this->dispatcher->forward([
            'controller' => "product",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a product edited
     *
     */
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
        $product->imageId = $this->request->getPost("image_id");
        $product->view = $this->request->getPost("view");
        $product->createdAt = $this->request->getPost("created_at");
        

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

        $this->flash->success("product was updated successfully");

        $this->dispatcher->forward([
            'controller' => "product",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a product
     *
     * @param string $id
     */
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

}
