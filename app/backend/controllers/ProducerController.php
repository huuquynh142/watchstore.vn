<?php
namespace Multiple\Backend\Controllers;
use App\Models\Product;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use App\Models\Producer;


class ProducerController extends ControllerBase
{

    public function indexAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, Producer::class, $_POST);
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

        $paginator = new Paginator([
            'data' => $producer,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }


    public function newAction()
    {

    }


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
            
        }
    }

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
        $producer->setCompanyName($this->request->getPost("company_name"));
        $producer->setTrademark($this->request->getPost("trademark"));
        $producer->setCountryOfOrigin($this->request->getPost("country_of_origin"));
        $producer->setAddress($this->request->getPost("address"));
        $producer->setPhoneNumber($this->request->getPost("phone_number"));
        $producer->setEmail($this->request->getPost("email", "email"));
        $producer->setWebsite($this->request->getPost("website"));
        

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
