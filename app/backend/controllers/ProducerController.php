<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ProducerController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for producer
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Producer', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $producer = Producer::find($parameters);
        if (count($producer) == 0) {
            $this->flash->notice("The search did not find any producer");

            $this->dispatcher->forward([
                "controller" => "producer",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $producer,
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
     * Edits a producer
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $producer = Producer::findFirstByid($id);
            if (!$producer) {
                $this->flash->error("producer was not found");

                $this->dispatcher->forward([
                    'controller' => "producer",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $producer->id;

            $this->tag->setDefault("id", $producer->id);
            $this->tag->setDefault("company_name", $producer->company_name);
            $this->tag->setDefault("trademark", $producer->trademark);
            $this->tag->setDefault("country_of_origin", $producer->country_of_origin);
            $this->tag->setDefault("address", $producer->address);
            $this->tag->setDefault("phone_number", $producer->phone_number);
            $this->tag->setDefault("email", $producer->email);
            $this->tag->setDefault("website", $producer->website);
            $this->tag->setDefault("created_at", $producer->created_at);
            
        }
    }

    /**
     * Creates a new producer
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "producer",
                'action' => 'index'
            ]);

            return;
        }

        $producer = new Producer();
        $producer->companyName = $this->request->getPost("company_name");
        $producer->trademark = $this->request->getPost("trademark");
        $producer->countryOfOrigin = $this->request->getPost("country_of_origin");
        $producer->address = $this->request->getPost("address");
        $producer->phoneNumber = $this->request->getPost("phone_number");
        $producer->email = $this->request->getPost("email", "email");
        $producer->website = $this->request->getPost("website");
        $producer->createdAt = $this->request->getPost("created_at");
        

        if (!$producer->save()) {
            foreach ($producer->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "producer",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("producer was created successfully");

        $this->dispatcher->forward([
            'controller' => "producer",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a producer edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "producer",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $producer = Producer::findFirstByid($id);

        if (!$producer) {
            $this->flash->error("producer does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "producer",
                'action' => 'index'
            ]);

            return;
        }

        $producer->companyName = $this->request->getPost("company_name");
        $producer->trademark = $this->request->getPost("trademark");
        $producer->countryOfOrigin = $this->request->getPost("country_of_origin");
        $producer->address = $this->request->getPost("address");
        $producer->phoneNumber = $this->request->getPost("phone_number");
        $producer->email = $this->request->getPost("email", "email");
        $producer->website = $this->request->getPost("website");
        $producer->createdAt = $this->request->getPost("created_at");
        

        if (!$producer->save()) {

            foreach ($producer->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "producer",
                'action' => 'edit',
                'params' => [$producer->id]
            ]);

            return;
        }

        $this->flash->success("producer was updated successfully");

        $this->dispatcher->forward([
            'controller' => "producer",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a producer
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $producer = Producer::findFirstByid($id);
        if (!$producer) {
            $this->flash->error("producer was not found");

            $this->dispatcher->forward([
                'controller' => "producer",
                'action' => 'index'
            ]);

            return;
        }

        if (!$producer->delete()) {

            foreach ($producer->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "producer",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("producer was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "producer",
            'action' => "index"
        ]);
    }

}
