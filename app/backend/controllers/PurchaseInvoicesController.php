<?php
namespace Multiple\Backend\Controllers;
use App\Models\Producer;
use App\Models\PurchaseInvoicesDetail;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use App\Models\PurchaseInvoices;

class PurchaseInvoicesController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $numberPage = 1;
        $purchase_invoices = PurchaseInvoices::query()
            ->join(Producer::class , Producer::class.".id = ".PurchaseInvoices::class.".producer_id");
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, PurchaseInvoices::class, $_POST);
            $this->persistent->parameters = $query->getParams();
            foreach ($query->getParams()['bind'] as $key => $item)
            {
                $purchase_invoices = $purchase_invoices->where(PurchaseInvoices::class . ".". $key." = '". str_replace("%","",$item)."'");
            }
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $purchase_invoices = $purchase_invoices->columns([
            PurchaseInvoices::class.".name_seller" ,
            PurchaseInvoices::class.".id" ,
            PurchaseInvoices::class.".total" ,
            Producer::class.".company_name",
            PurchaseInvoices::class.".comment"
        ])
            ->execute();

        $paginator = new Paginator([
            'data' => $purchase_invoices,
            'limit'=> 10,
            'page' => $numberPage
        ]);
        $this->view->producers = Producer::find();
        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
        $this->view->producer = Producer::find();
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
            $this->view->producer = Producer::find();
            $this->view->producer_id = $purchase_invoice->producer_id;
            $this->tag->setDefault("id", $purchase_invoice->id);
            $this->tag->setDefault("name_seller", $purchase_invoice->name_seller);
            $this->tag->setDefault("comment", $purchase_invoice->comment);
            
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
        $purchase_invoice->nameSeller = $this->request->getPost("name_seller");
        $purchase_invoice->comment = $this->request->getPost("comment");
        if ($this->session->has('useId'))
            $purchase_invoice->userId = $this->session->get('useId');
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
        $purchase_invoice->nameSeller = $this->request->getPost("name_seller");
        $purchase_invoice->comment = $this->request->getPost("comment");
        if ($this->session->has('useId'))
            $purchase_invoice->userId = $this->session->get('useId');
        

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
        $this->modelsManager->createBuilder()
            ->from(PurchaseInvoicesDetail::class)
            ->where(PurchaseInvoicesDetail::class.".purchase_invoices_id=".$purchase_invoice->id)
            ->getQuery()
            ->execute()
            ->delete();

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
