<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use App\Models\Credential;

class CredentialController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, Credential::class, $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $credential = Credential::find($parameters);

        $paginator = new Paginator([
            'data' => $credential,
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
     * Edits a Credentail
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $credential = Credential::findFirstByid($id);
            if (!$credential) {
                $this->flash->error("credential was not found");

                $this->dispatcher->forward([
                    'controller' => "backend/credential",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $credential->id;

            $this->tag->setDefault("id", $credential->id);
            $this->tag->setDefault("name", $credential->name);
            
        }
    }

    /**
     * Creates a new Credential
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "backend/credential",
                'action' => 'index'
            ]);

            return;
        }

        $credential = new Credential();
        $credential->name = $this->request->getPost("name");
        

        if (!$credential->save()) {
            foreach ($credential->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "backend/credential",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("credential was created successfully");

        $this->dispatcher->forward([
            'controller' => "credential",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a credential edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "backend/credential",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $credential = Credential::findFirstByid($id);

        if (!$credential) {
            $this->flash->error("credential does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "backend/credential",
                'action' => 'index'
            ]);

            return;
        }

        $credential->name = $this->request->getPost("name");
        

        if (!$credential->save()) {

            foreach ($credential->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "backend/credential",
                'action' => 'edit',
                'params' => [$credential->id]
            ]);

            return;
        }

        $this->flash->success("credential was updated successfully");

        $this->dispatcher->forward([
            'controller' => "credential",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a credential
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $department = Credential::findFirstByid($id);
        if (!$department) {
            $this->flash->error("credential was not found");

            $this->dispatcher->forward([
                'controller' => "backend/credential",
                'action' => 'index'
            ]);

            return;
        }

        if (!$department->delete()) {

            foreach ($department->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "backend/credential",
                'action' => 'index'
            ]);

            return;
        }

        $this->flash->success("credential was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "credential",
            'action' => "index"
        ]);
    }

}
