<?php
namespace Multiple\Backend\Controllers;
use App\Models\SalesInvoiceDetail;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class SalesInvoiceDetailController extends ControllerBase
{
    /**
     * Index action
     */

    public function indexAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, SalesInvoiceDetail::class, $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $sales_invoice_detail = SalesInvoiceDetail::find($parameters);
        if (count($sales_invoice_detail) == 0) {
            $this->flash->notice("The search did not find any sales_invoice_detail");

            $this->dispatcher->forward([
                "controller" => "sales_invoice_detail",
                "action" => "index"
            ]);

            return;
        }

        $paginator = new Paginator([
            'data' => $sales_invoice_detail,
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
     * Edits a sales_invoice_detail
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $sales_invoice_detail = SalesInvoiceDetail::findFirstByid($id);
            if (!$sales_invoice_detail) {
                $this->flash->error("sales_invoice_detail was not found");

                $this->dispatcher->forward([
                    'controller' => "sales_invoice_detail",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $sales_invoice_detail->id;

            $this->tag->setDefault("id", $sales_invoice_detail->id);
            $this->tag->setDefault("sales_invoice_id", $sales_invoice_detail->sales_invoice_id);
            $this->tag->setDefault("product_id", $sales_invoice_detail->product_id);
            $this->tag->setDefault("quantity", $sales_invoice_detail->quantity);
            $this->tag->setDefault("discount", $sales_invoice_detail->discount);
            $this->tag->setDefault("price", $sales_invoice_detail->price);
            $this->tag->setDefault("total", $sales_invoice_detail->total);
            $this->tag->setDefault("comment", $sales_invoice_detail->comment);
            
        }
    }

    /**
     * Creates a new sales_invoice_detail
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "sales_invoice_detail",
                'action' => 'index'
            ]);

            return;
        }

        $sales_invoice_detail = new SalesInvoiceDetail();
        $sales_invoice_detail->salesInvoiceId = $this->request->getPost("sales_invoice_id");
        $sales_invoice_detail->productId = $this->request->getPost("product_id");
        $sales_invoice_detail->quantity = $this->request->getPost("quantity");
        $sales_invoice_detail->discount = $this->request->getPost("discount");
        $sales_invoice_detail->price = $this->request->getPost("price");
        $sales_invoice_detail->total = $this->request->getPost("total");
        $sales_invoice_detail->comment = $this->request->getPost("comment");
        

        if (!$sales_invoice_detail->save()) {
            foreach ($sales_invoice_detail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "sales_invoice_detail",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("sales_invoice_detail was created successfully");

        $this->dispatcher->forward([
            'controller' => "sales_invoice_detail",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a sales_invoice_detail edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "sales_invoice_detail",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $sales_invoice_detail = SalesInvoiceDetail::findFirstByid($id);

        if (!$sales_invoice_detail) {
            $this->flash->error("sales_invoice_detail does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "sales_invoice_detail",
                'action' => 'index'
            ]);

            return;
        }

        $sales_invoice_detail->salesInvoiceId = $this->request->getPost("sales_invoice_id");
        $sales_invoice_detail->productId = $this->request->getPost("product_id");
        $sales_invoice_detail->quantity = $this->request->getPost("quantity");
        $sales_invoice_detail->discount = $this->request->getPost("discount");
        $sales_invoice_detail->price = $this->request->getPost("price");
        $sales_invoice_detail->total = $this->request->getPost("total");
        $sales_invoice_detail->comment = $this->request->getPost("comment");
        

        if (!$sales_invoice_detail->save()) {

            foreach ($sales_invoice_detail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "sales_invoice_detail",
                'action' => 'edit',
                'params' => [$sales_invoice_detail->id]
            ]);

            return;
        }

        $this->flash->success("sales_invoice_detail was updated successfully");

        $this->dispatcher->forward([
            'controller' => "sales_invoice_detail",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a sales_invoice_detail
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $sales_invoice_detail = SalesInvoiceDetail::findFirstByid($id);
        if (!$sales_invoice_detail) {
            $this->flash->error("sales_invoice_detail was not found");

            $this->dispatcher->forward([
                'controller' => "sales_invoice_detail",
                'action' => 'index'
            ]);

            return;
        }

        if (!$sales_invoice_detail->delete()) {

            foreach ($sales_invoice_detail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "sales_invoice_detail",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("sales_invoice_detail was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "sales_invoice_detail",
            'action' => "index"
        ]);
    }

}
