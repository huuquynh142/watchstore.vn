<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use App\Models\DescriptionProduct;

class DescriptionProductController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for description_product
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, DescriptionProduct::class, $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $description_product = DescriptionProduct::find($parameters);
        if (count($description_product) == 0) {
            $this->flash->notice("The search did not find any description_product");

            $this->dispatcher->forward([
                "controller" => "description_product",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $description_product,
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
     * Edits a description_product
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $description_product = DescriptionProduct::findFirstByid($id);
            if (!$description_product) {
                $this->flash->error("description_product was not found");

                $this->dispatcher->forward([
                    'controller' => "description_product",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $description_product->id;

            $this->tag->setDefault("id", $description_product->id);
            $this->tag->setDefault("first_description", $description_product->first_description);
            $this->tag->setDefault("last_description", $description_product->last_description);
            
        }
    }

    /**
     * Creates a new description_product
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "description_product",
                'action' => 'index'
            ]);

            return;
        }

        $description_product = new DescriptionProduct();
        $description_product->firstDescription = $this->request->getPost("first_description");
        $description_product->lastDescription = $this->request->getPost("last_description");
        

        if (!$description_product->save()) {
            foreach ($description_product->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "description_product",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("description_product was created successfully");

        $this->dispatcher->forward([
            'controller' => "description_product",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a description_product edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "description_product",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $description_product = DescriptionProduct::findFirstByid($id);

        if (!$description_product) {
            $this->flash->error("description_product does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "description_product",
                'action' => 'index'
            ]);

            return;
        }

        $description_product->firstDescription = $this->request->getPost("first_description");
        $description_product->lastDescription = $this->request->getPost("last_description");
        

        if (!$description_product->save()) {

            foreach ($description_product->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "description_product",
                'action' => 'edit',
                'params' => [$description_product->id]
            ]);

            return;
        }

        $this->flash->success("description_product was updated successfully");

        $this->dispatcher->forward([
            'controller' => "description_product",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a description_product
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $description_product = DescriptionProduct::findFirstByid($id);
        if (!$description_product) {
            $this->flash->error("description_product was not found");

            $this->dispatcher->forward([
                'controller' => "description_product",
                'action' => 'index'
            ]);

            return;
        }

        if (!$description_product->delete()) {

            foreach ($description_product->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "description_product",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("description_product was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "description_product",
            'action' => "index"
        ]);
    }

}
