<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class UserInformationController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for user_information
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'UserInformation', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $user_information = UserInformation::find($parameters);
        if (count($user_information) == 0) {
            $this->flash->notice("The search did not find any user_information");

            $this->dispatcher->forward([
                "controller" => "user_information",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $user_information,
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
     * Edits a user_information
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $user_information = UserInformation::findFirstByid($id);
            if (!$user_information) {
                $this->flash->error("user_information was not found");

                $this->dispatcher->forward([
                    'controller' => "user_information",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $user_information->id;

            $this->tag->setDefault("id", $user_information->id);
            $this->tag->setDefault("user_id", $user_information->user_id);
            $this->tag->setDefault("department_id", $user_information->department_id);
            $this->tag->setDefault("last_name", $user_information->last_name);
            $this->tag->setDefault("first_name", $user_information->first_name);
            $this->tag->setDefault("sex", $user_information->sex);
            $this->tag->setDefault("address", $user_information->address);
            $this->tag->setDefault("phone_number", $user_information->phone_number);
            $this->tag->setDefault("email", $user_information->email);
            $this->tag->setDefault("avartar", $user_information->avartar);
            
        }
    }

    /**
     * Creates a new user_information
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user_information",
                'action' => 'index'
            ]);

            return;
        }

        $user_information = new UserInformation();
        $user_information->userId = $this->request->getPost("user_id");
        $user_information->departmentId = $this->request->getPost("department_id");
        $user_information->lastName = $this->request->getPost("last_name");
        $user_information->firstName = $this->request->getPost("first_name");
        $user_information->sex = $this->request->getPost("sex");
        $user_information->address = $this->request->getPost("address");
        $user_information->phoneNumber = $this->request->getPost("phone_number");
        $user_information->email = $this->request->getPost("email", "email");
        $user_information->avartar = $this->request->getPost("avartar");
        

        if (!$user_information->save()) {
            foreach ($user_information->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user_information",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("user_information was created successfully");

        $this->dispatcher->forward([
            'controller' => "user_information",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a user_information edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "user_information",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $user_information = UserInformation::findFirstByid($id);

        if (!$user_information) {
            $this->flash->error("user_information does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "user_information",
                'action' => 'index'
            ]);

            return;
        }

        $user_information->userId = $this->request->getPost("user_id");
        $user_information->departmentId = $this->request->getPost("department_id");
        $user_information->lastName = $this->request->getPost("last_name");
        $user_information->firstName = $this->request->getPost("first_name");
        $user_information->sex = $this->request->getPost("sex");
        $user_information->address = $this->request->getPost("address");
        $user_information->phoneNumber = $this->request->getPost("phone_number");
        $user_information->email = $this->request->getPost("email", "email");
        $user_information->avartar = $this->request->getPost("avartar");
        

        if (!$user_information->save()) {

            foreach ($user_information->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user_information",
                'action' => 'edit',
                'params' => [$user_information->id]
            ]);

            return;
        }

        $this->flash->success("user_information was updated successfully");

        $this->dispatcher->forward([
            'controller' => "user_information",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a user_information
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $user_information = UserInformation::findFirstByid($id);
        if (!$user_information) {
            $this->flash->error("user_information was not found");

            $this->dispatcher->forward([
                'controller' => "user_information",
                'action' => 'index'
            ]);

            return;
        }

        if (!$user_information->delete()) {

            foreach ($user_information->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "user_information",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("user_information was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "user_information",
            'action' => "index"
        ]);
    }

}
