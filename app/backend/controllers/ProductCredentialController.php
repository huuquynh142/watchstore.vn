<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ProductCredentialController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for product_credential
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'ProductCredential', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $product_credential = ProductCredential::find($parameters);
        if (count($product_credential) == 0) {
            $this->flash->notice("The search did not find any product_credential");

            $this->dispatcher->forward([
                "controller" => "product_credential",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $product_credential,
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
     * Edits a product_credential
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $product_credential = ProductCredential::findFirstByid($id);
            if (!$product_credential) {
                $this->flash->error("product_credential was not found");

                $this->dispatcher->forward([
                    'controller' => "product_credential",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $product_credential->id;

            $this->tag->setDefault("id", $product_credential->id);
            $this->tag->setDefault("product_id", $product_credential->product_id);
            $this->tag->setDefault("product_type_id", $product_credential->product_type_id);
            
        }
    }

    /**
     * Creates a new product_credential
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "product_credential",
                'action' => 'index'
            ]);

            return;
        }

        $product_credential = new ProductCredential();
        $product_credential->productId = $this->request->getPost("product_id");
        $product_credential->productTypeId = $this->request->getPost("product_type_id");
        

        if (!$product_credential->save()) {
            foreach ($product_credential->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_credential",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("product_credential was created successfully");

        $this->dispatcher->forward([
            'controller' => "product_credential",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a product_credential edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "product_credential",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $product_credential = ProductCredential::findFirstByid($id);

        if (!$product_credential) {
            $this->flash->error("product_credential does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "product_credential",
                'action' => 'index'
            ]);

            return;
        }

        $product_credential->productId = $this->request->getPost("product_id");
        $product_credential->productTypeId = $this->request->getPost("product_type_id");
        

        if (!$product_credential->save()) {

            foreach ($product_credential->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_credential",
                'action' => 'edit',
                'params' => [$product_credential->id]
            ]);

            return;
        }

        $this->flash->success("product_credential was updated successfully");

        $this->dispatcher->forward([
            'controller' => "product_credential",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a product_credential
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $product_credential = ProductCredential::findFirstByid($id);
        if (!$product_credential) {
            $this->flash->error("product_credential was not found");

            $this->dispatcher->forward([
                'controller' => "product_credential",
                'action' => 'index'
            ]);

            return;
        }

        if (!$product_credential->delete()) {

            foreach ($product_credential->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_credential",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("product_credential was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "product_credential",
            'action' => "index"
        ]);
    }

}
