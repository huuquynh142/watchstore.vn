<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class PurchaseInvoicesController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for purchase_invoices
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'PurchaseInvoices', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $purchase_invoices = PurchaseInvoices::find($parameters);
        if (count($purchase_invoices) == 0) {
            $this->flash->notice("The search did not find any purchase_invoices");

            $this->dispatcher->forward([
                "controller" => "purchase_invoices",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $purchase_invoices,
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
     * Edits a purchase_invoice
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $purchase_invoice = PurchaseInvoices::findFirstByid($id);
            if (!$purchase_invoice) {
                $this->flash->error("purchase_invoice was not found");

                $this->dispatcher->forward([
                    'controller' => "purchase_invoices",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $purchase_invoice->id;

            $this->tag->setDefault("id", $purchase_invoice->id);
            $this->tag->setDefault("producer_id", $purchase_invoice->producer_id);
            $this->tag->setDefault("user_id", $purchase_invoice->user_id);
            $this->tag->setDefault("created_at", $purchase_invoice->created_at);
            
        }
    }

    /**
     * Creates a new purchase_invoice
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "purchase_invoices",
                'action' => 'index'
            ]);

            return;
        }

        $purchase_invoice = new PurchaseInvoices();
        $purchase_invoice->producerId = $this->request->getPost("producer_id");
        $purchase_invoice->userId = $this->request->getPost("user_id");
        $purchase_invoice->createdAt = $this->request->getPost("created_at");
        

        if (!$purchase_invoice->save()) {
            foreach ($purchase_invoice->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "purchase_invoices",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("purchase_invoice was created successfully");

        $this->dispatcher->forward([
            'controller' => "purchase_invoices",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a purchase_invoice edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "purchase_invoices",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $purchase_invoice = PurchaseInvoices::findFirstByid($id);

        if (!$purchase_invoice) {
            $this->flash->error("purchase_invoice does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "purchase_invoices",
                'action' => 'index'
            ]);

            return;
        }

        $purchase_invoice->producerId = $this->request->getPost("producer_id");
        $purchase_invoice->userId = $this->request->getPost("user_id");
        $purchase_invoice->createdAt = $this->request->getPost("created_at");
        

        if (!$purchase_invoice->save()) {

            foreach ($purchase_invoice->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "purchase_invoices",
                'action' => 'edit',
                'params' => [$purchase_invoice->id]
            ]);

            return;
        }

        $this->flash->success("purchase_invoice was updated successfully");

        $this->dispatcher->forward([
            'controller' => "purchase_invoices",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a purchase_invoice
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $purchase_invoice = PurchaseInvoices::findFirstByid($id);
        if (!$purchase_invoice) {
            $this->flash->error("purchase_invoice was not found");

            $this->dispatcher->forward([
                'controller' => "purchase_invoices",
                'action' => 'index'
            ]);

            return;
        }

        if (!$purchase_invoice->delete()) {

            foreach ($purchase_invoice->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "purchase_invoices",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("purchase_invoice was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "purchase_invoices",
            'action' => "index"
        ]);
    }

}
