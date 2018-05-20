<?php
namespace Multiple\Backend\Controllers;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\SalesInvoiceDetail;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class SalesInvoiceDetailController extends ControllerBase
{
    /**
     * Index action
     */

    public function indexAction($id)
    {
        $numberPage = 1;
        $numberPage = $this->request->getQuery("page", "int");
        $sales_invoice_detail = SalesInvoiceDetail::query()
            ->join(Product::class , SalesInvoiceDetail::class.".product_id = ".Product::class.".id")
            ->join(ProductDetail::class , Product::class.".product_detail_id = ".ProductDetail::class.".id")
            ->where(SalesInvoiceDetail::class.".sales_invoice_id = " . $id."")
            ->columns([SalesInvoiceDetail::class.".quantity" ,
                SalesInvoiceDetail::class.".discount" ,
                SalesInvoiceDetail::class.".price" ,
                SalesInvoiceDetail::class.".total",
                SalesInvoiceDetail::class.".comment",
                SalesInvoiceDetail::class.".id" ,
                ProductDetail::class.".product_name" ,
                    ])
        ->execute();
        $this->session->set("invoice_id",$id);
        $paginator = new Paginator([
            'data' => $sales_invoice_detail,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
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

            $product = Product::query()
                ->join(ProductDetail::class , Product::class.".product_detail_id = ".ProductDetail::class.".id")
//                ->where(Product::class.".id = " . $sales_invoice_detail->product_id."")
                ->columns([
                    Product::class.".id",
                    ProductDetail::class.".product_name"])
                ->execute();
            $this->view->product = $product;
            $this->view->id = $sales_invoice_detail->id;
            $this->view->id = $sales_invoice_detail->id;
            $this->view->invoice_id = $this->session->get('invoice_id');
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

        $sales_invoice_detail->productId = $this->request->getPost("product_id");
        $sales_invoice_detail->quantity = $this->request->getPost("quantity");
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
            'action' => 'index',
            'params' => [$this->session->get('invoice_id')]
        ]);
    }


}
