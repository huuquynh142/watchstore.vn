<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ProductImageController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for product_image
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'ProductImage', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $product_image = ProductImage::find($parameters);
        if (count($product_image) == 0) {
            $this->flash->notice("The search did not find any product_image");

            $this->dispatcher->forward([
                "controller" => "product_image",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $product_image,
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
     * Edits a product_image
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $product_image = ProductImage::findFirstByid($id);
            if (!$product_image) {
                $this->flash->error("product_image was not found");

                $this->dispatcher->forward([
                    'controller' => "product_image",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $product_image->id;

            $this->tag->setDefault("id", $product_image->id);
            $this->tag->setDefault("image", $product_image->image);
            
        }
    }

    /**
     * Creates a new product_image
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "product_image",
                'action' => 'index'
            ]);

            return;
        }

        $product_image = new ProductImage();
        $product_image->image = $this->request->getPost("image");
        

        if (!$product_image->save()) {
            foreach ($product_image->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_image",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("product_image was created successfully");

        $this->dispatcher->forward([
            'controller' => "product_image",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a product_image edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "product_image",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $product_image = ProductImage::findFirstByid($id);

        if (!$product_image) {
            $this->flash->error("product_image does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "product_image",
                'action' => 'index'
            ]);

            return;
        }

        $product_image->image = $this->request->getPost("image");
        

        if (!$product_image->save()) {

            foreach ($product_image->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_image",
                'action' => 'edit',
                'params' => [$product_image->id]
            ]);

            return;
        }

        $this->flash->success("product_image was updated successfully");

        $this->dispatcher->forward([
            'controller' => "product_image",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a product_image
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $product_image = ProductImage::findFirstByid($id);
        if (!$product_image) {
            $this->flash->error("product_image was not found");

            $this->dispatcher->forward([
                'controller' => "product_image",
                'action' => 'index'
            ]);

            return;
        }

        if (!$product_image->delete()) {

            foreach ($product_image->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_image",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("product_image was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "product_image",
            'action' => "index"
        ]);
    }

}
