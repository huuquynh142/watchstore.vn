<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use App\Models\ProductType;

class ProductTypeController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for product_type
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, ProductType::class, $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $product_type = ProductType::find($parameters);
        if (count($product_type) == 0) {
            $this->flash->notice("The search did not find any product_type");

            $this->dispatcher->forward([
                "controller" => "product_type",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $product_type,
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
     * Edits a product_type
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $product_type = ProductType::findFirstByid($id);
            if (!$product_type) {
                $this->flash->error("product_type was not found");

                $this->dispatcher->forward([
                    'controller' => "product_type",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $product_type->id;

            $this->tag->setDefault("id", $product_type->id);
            $this->tag->setDefault("name", $product_type->name);
            
        }
    }

    /**
     * Creates a new product_type
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "product_type",
                'action' => 'index'
            ]);

            return;
        }

        $product_type = new ProductType();
        $product_type->name = $this->request->getPost("name");
        

        if (!$product_type->save()) {
            foreach ($product_type->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_type",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("product_type was created successfully");

        $this->dispatcher->forward([
            'controller' => "product_type",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a product_type edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "product_type",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $product_type = ProductType::findFirstByid($id);

        if (!$product_type) {
            $this->flash->error("product_type does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "product_type",
                'action' => 'index'
            ]);

            return;
        }

        $product_type->name = $this->request->getPost("name");
        

        if (!$product_type->save()) {

            foreach ($product_type->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_type",
                'action' => 'edit',
                'params' => [$product_type->id]
            ]);

            return;
        }

        $this->flash->success("product_type was updated successfully");

        $this->dispatcher->forward([
            'controller' => "product_type",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a product_type
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $product_type = ProductType::findFirstByid($id);
        if (!$product_type) {
            $this->flash->error("product_type was not found");

            $this->dispatcher->forward([
                'controller' => "product_type",
                'action' => 'index'
            ]);

            return;
        }

        if (!$product_type->delete()) {

            foreach ($product_type->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_type",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("product_type was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "product_type",
            'action' => "index"
        ]);
    }

}
