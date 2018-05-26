<?php
namespace Multiple\Backend\Controllers;
use App\Models\Product;
use App\Models\PurchaseInvoices;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use App\Models\PurchaseInvoicesDetail;


class PurchaseInvoicesDetailController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction($id)
    {
        $numberPage = 1;
        $numberPage = $this->request->getQuery("page", "int");
        $purchase_invoices_detail = PurchaseInvoicesDetail::query()
            ->where(PurchaseInvoicesDetail::class.".purchase_invoices_id = " . $id."")
            ->columns([PurchaseInvoicesDetail::class.".quantity" ,
                PurchaseInvoicesDetail::class.".price" ,
                PurchaseInvoicesDetail::class.".id" ,
                PurchaseInvoicesDetail::class.".total",
            ])
            ->execute();
        $paginator = new Paginator([
            'data' => $purchase_invoices_detail,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction($id)
    {
        $this->view->id = $id;
    }

    /**
     * Edits a purchase_invoices_detail
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $purchase_invoices_detail = PurchaseInvoicesDetail::findFirstByid($id);
            if (!$purchase_invoices_detail) {
                $this->flash->error("purchase_invoices_detail was not found");

                $this->dispatcher->forward([
                    'controller' => "purchase_invoices_detail",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $purchase_invoices_detail->id;
            $this->view->purchase_invoices_id = $purchase_invoices_detail->purchase_invoices_id;
            $this->tag->setDefault("id", $purchase_invoices_detail->id);
            $this->tag->setDefault("product_id", $purchase_invoices_detail->product_id);
            $this->tag->setDefault("quantity", $purchase_invoices_detail->quantity);
            $this->tag->setDefault("price", $purchase_invoices_detail->price);
            
        }
    }

    /**
     * Creates a new purchase_invoices_detail
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "purchase_invoices_detail",
                'action' => 'index'
            ]);

            return;
        }
        $quantity = $this->request->getPost("quantity");
        $price = $this->request->getPost("price");
        $total = $quantity * $price;
        $purchaseInvoicesId = $this->request->getPost("purchase_invoices_id");
        $purchase_invoices_detail = new PurchaseInvoicesDetail();
        $purchase_invoices_detail->purchaseInvoicesId = $purchaseInvoicesId;
        $purchase_invoices_detail->productId = $this->request->getPost("product_id");
        $purchase_invoices_detail->quantity = $quantity;
        $purchase_invoices_detail->price = $price;
        $purchase_invoices_detail->total = $total;

        $purchase_invoice = PurchaseInvoices::findFirstByid($purchaseInvoicesId);
        $purchase_invoice->total = $purchase_invoice->total + $total;
        $purchase_invoice->save();
        if (!$purchase_invoices_detail->save()) {
            foreach ($purchase_invoices_detail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "purchase_invoices_detail",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("purchase_invoices_detail was created successfully");

        $this->dispatcher->forward([
            'controller' => "purchase_invoices",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a purchase_invoices_detail edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "purchase_invoices_detail",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $purchase_invoices_detail = PurchaseInvoicesDetail::findFirstByid($id);

        if (!$purchase_invoices_detail) {
            $this->flash->error("purchase_invoices_detail does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "purchase_invoices_detail",
                'action' => 'index'
            ]);

            return;
        }
        $quantity = $this->request->getPost("quantity");
        $price = $this->request->getPost("price");
        $total = $quantity * $price;
        $total_old = $purchase_invoices_detail->quantity * $purchase_invoices_detail->price;
        $purchaseInvoicesId = $this->request->getPost("purchase_invoices_id");
        $purchase_invoice = PurchaseInvoices::findFirstByid($purchaseInvoicesId);
        $purchase_invoice->total = $purchase_invoice->total - $total_old;
        $purchase_invoice->save();


        $purchase_invoices_detail->purchaseInvoicesId = $purchaseInvoicesId;
        $purchase_invoices_detail->productId = $this->request->getPost("product_id");
        $purchase_invoices_detail->quantity = $quantity;
        $purchase_invoices_detail->price = $price;
        $purchase_invoices_detail->total = $total;


        $purchase_invoice->total = $purchase_invoice->total + $total;
        $purchase_invoice->save();
        if (!$purchase_invoices_detail->save()) {

            foreach ($purchase_invoices_detail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "purchase_invoices_detail",
                'action' => 'edit',
                'params' => [$purchase_invoices_detail->id]
            ]);

            return;
        }

        $this->flash->success("purchase_invoices_detail was updated successfully");

        $this->dispatcher->forward([
            'controller' => "purchase_invoices_detail",
            'action' => 'index',
            'params' => [$purchaseInvoicesId]
        ]);
    }

    /**
     * Deletes a purchase_invoices_detail
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $purchase_invoices_detail = PurchaseInvoicesDetail::findFirstByid($id);
        $total_old = $purchase_invoices_detail->quantity * $purchase_invoices_detail->price;
        $purchaseInvoicesId = $purchase_invoices_detail->purchaseInvoicesId;
        $purchase_invoice = PurchaseInvoices::findFirstByid($purchaseInvoicesId);
        $purchase_invoice->total = $purchase_invoice->total - $total_old;
        $purchase_invoice->save();
        if (!$purchase_invoices_detail) {
            $this->flash->error("purchase_invoices_detail was not found");

            $this->dispatcher->forward([
                'controller' => "purchase_invoices_detail",
                'action' => 'index'
            ]);

            return;
        }

        if (!$purchase_invoices_detail->delete()) {

            foreach ($purchase_invoices_detail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "purchase_invoices_detail",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("purchase_invoices_detail was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "purchase_invoices_detail",
            'action' => "index",
            'params' => [$purchaseInvoicesId]
        ]);
    }

}
