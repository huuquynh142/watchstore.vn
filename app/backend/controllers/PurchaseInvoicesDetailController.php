<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class PurchaseInvoicesDetailController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for purchase_invoices_detail
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'PurchaseInvoicesDetail', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $purchase_invoices_detail = PurchaseInvoicesDetail::find($parameters);
        if (count($purchase_invoices_detail) == 0) {
            $this->flash->notice("The search did not find any purchase_invoices_detail");

            $this->dispatcher->forward([
                "controller" => "purchase_invoices_detail",
                "action" => "index"
            ]);

            return;
        }

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
    public function newAction()
    {

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

            $this->tag->setDefault("id", $purchase_invoices_detail->id);
            $this->tag->setDefault("purchase_invoices_id", $purchase_invoices_detail->purchase_invoices_id);
            $this->tag->setDefault("product_id", $purchase_invoices_detail->product_id);
            $this->tag->setDefault("quantity", $purchase_invoices_detail->quantity);
            $this->tag->setDefault("price", $purchase_invoices_detail->price);
            $this->tag->setDefault("name_seller", $purchase_invoices_detail->name_seller);
            $this->tag->setDefault("created_at", $purchase_invoices_detail->created_at);
            
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

        $purchase_invoices_detail = new PurchaseInvoicesDetail();
        $purchase_invoices_detail->purchaseInvoicesId = $this->request->getPost("purchase_invoices_id");
        $purchase_invoices_detail->productId = $this->request->getPost("product_id");
        $purchase_invoices_detail->quantity = $this->request->getPost("quantity");
        $purchase_invoices_detail->price = $this->request->getPost("price");
        $purchase_invoices_detail->nameSeller = $this->request->getPost("name_seller");
        $purchase_invoices_detail->createdAt = $this->request->getPost("created_at");
        

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
            'controller' => "purchase_invoices_detail",
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

        $purchase_invoices_detail->purchaseInvoicesId = $this->request->getPost("purchase_invoices_id");
        $purchase_invoices_detail->productId = $this->request->getPost("product_id");
        $purchase_invoices_detail->quantity = $this->request->getPost("quantity");
        $purchase_invoices_detail->price = $this->request->getPost("price");
        $purchase_invoices_detail->nameSeller = $this->request->getPost("name_seller");
        $purchase_invoices_detail->createdAt = $this->request->getPost("created_at");
        

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
            'action' => 'index'
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
            'action' => "index"
        ]);
    }

}
