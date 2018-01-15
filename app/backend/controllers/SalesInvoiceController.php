<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class SalesInvoiceController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for sales_invoice
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'SalesInvoice', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $sales_invoice = SalesInvoice::find($parameters);
        if (count($sales_invoice) == 0) {
            $this->flash->notice("The search did not find any sales_invoice");

            $this->dispatcher->forward([
                "controller" => "sales_invoice",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $sales_invoice,
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
     * Edits a sales_invoice
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $sales_invoice = SalesInvoice::findFirstByid($id);
            if (!$sales_invoice) {
                $this->flash->error("sales_invoice was not found");

                $this->dispatcher->forward([
                    'controller' => "sales_invoice",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $sales_invoice->id;

            $this->tag->setDefault("id", $sales_invoice->id);
            $this->tag->setDefault("user_id", $sales_invoice->user_id);
            $this->tag->setDefault("member_id", $sales_invoice->member_id);
            $this->tag->setDefault("created_at", $sales_invoice->created_at);
            
        }
    }

    /**
     * Creates a new sales_invoice
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "sales_invoice",
                'action' => 'index'
            ]);

            return;
        }

        $sales_invoice = new SalesInvoice();
        $sales_invoice->userId = $this->request->getPost("user_id");
        $sales_invoice->memberId = $this->request->getPost("member_id");
        $sales_invoice->createdAt = $this->request->getPost("created_at");
        

        if (!$sales_invoice->save()) {
            foreach ($sales_invoice->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "sales_invoice",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("sales_invoice was created successfully");

        $this->dispatcher->forward([
            'controller' => "sales_invoice",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a sales_invoice edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "sales_invoice",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $sales_invoice = SalesInvoice::findFirstByid($id);

        if (!$sales_invoice) {
            $this->flash->error("sales_invoice does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "sales_invoice",
                'action' => 'index'
            ]);

            return;
        }

        $sales_invoice->userId = $this->request->getPost("user_id");
        $sales_invoice->memberId = $this->request->getPost("member_id");
        $sales_invoice->createdAt = $this->request->getPost("created_at");
        

        if (!$sales_invoice->save()) {

            foreach ($sales_invoice->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "sales_invoice",
                'action' => 'edit',
                'params' => [$sales_invoice->id]
            ]);

            return;
        }

        $this->flash->success("sales_invoice was updated successfully");

        $this->dispatcher->forward([
            'controller' => "sales_invoice",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a sales_invoice
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $sales_invoice = SalesInvoice::findFirstByid($id);
        if (!$sales_invoice) {
            $this->flash->error("sales_invoice was not found");

            $this->dispatcher->forward([
                'controller' => "sales_invoice",
                'action' => 'index'
            ]);

            return;
        }

        if (!$sales_invoice->delete()) {

            foreach ($sales_invoice->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "sales_invoice",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("sales_invoice was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "sales_invoice",
            'action' => "index"
        ]);
    }

}
