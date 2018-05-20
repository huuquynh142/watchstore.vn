<?php
namespace Multiple\Backend\Controllers;
use App\Library\PHPExcel;
use App\Models\District;
use App\Models\Member;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Province;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceDetail;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class SalesInvoiceController extends ControllerBase
{
    public function indexAction()
    {
        $numberPage = 1;
        $sales_invoice = SalesInvoice::query()
            ->join(Province::class , Province::class.".provinceid = ".SalesInvoice::class.".province_id")
            ->join(District::class , District::class.".districtid = ".SalesInvoice::class.".district_id");
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, SalesInvoice::class, $_POST);
            $this->persistent->parameters = $query->getParams();
            foreach ($query->getParams()['bind'] as $key => $item)
            {
                $sales_invoice = $sales_invoice->where(SalesInvoice::class . ".". $key." = '". str_replace("%","",$item)."'");
            }
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";
        
         $sales_invoice = $sales_invoice->columns([
                SalesInvoice::class.".status" ,
                SalesInvoice::class.".id" ,
                SalesInvoice::class.".phone" ,
                SalesInvoice::class.".email",
                SalesInvoice::class.".pay_method_id",
                SalesInvoice::class.".fullname",
                SalesInvoice::class.".address" ,
                SalesInvoice::class.".total" ,
                SalesInvoice::class.".shipping" ,
                District::class.".name as district" ,
                Province::class.".name as province"
            ])
            ->execute();
        $paginator = new Paginator([
            'data' => $sales_invoice,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->provinces = Province::find();
        $this->view->districts = District::find();
        $this->view->page = $paginator->getPaginate();
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
            $this->tag->setDefault("member_id", $sales_invoice->member_id);
            $this->tag->setDefault("province_id", $sales_invoice->province_id);
            $this->tag->setDefault("district_id", $sales_invoice->district_id);
            $this->tag->setDefault("pay_method_id", $sales_invoice->pay_method_id);
            $this->tag->setDefault("phone", $sales_invoice->phone);
            $this->tag->setDefault("email", $sales_invoice->email);
            $this->tag->setDefault("fullname", $sales_invoice->fullname);
            $this->tag->setDefault("address", $sales_invoice->address);
            $this->tag->setDefault("shipping", $sales_invoice->shipping);
            $this->tag->setDefault("total", $sales_invoice->total);
            $this->tag->setDefault("status", $sales_invoice->status);
            $this->tag->setDefault("created_at", $sales_invoice->created_at);
            
        }
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

        $sales_invoice->memberId = $this->request->getPost("member_id");
        $sales_invoice->provinceId = $this->request->getPost("province_id");
        $sales_invoice->districtId = $this->request->getPost("district_id");
        $sales_invoice->payMethodId = $this->request->getPost("pay_method_id");
        $sales_invoice->phone = $this->request->getPost("phone");
        $sales_invoice->email = $this->request->getPost("email", "email");
        $sales_invoice->fullname = $this->request->getPost("fullname");
        $sales_invoice->address = $this->request->getPost("address");
        $sales_invoice->shipping = $this->request->getPost("shipping");
        $sales_invoice->total = $this->request->getPost("total");
        $sales_invoice->status = $this->request->getPost("status");
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

    public function exportDataAction($id){
        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()->setTitle('Hóa đơn bán hàng');

        $excel->getActiveSheet()->setCellValue('A1', 'ID');
        $excel->getActiveSheet()->setCellValue('B1', "Tên sản phẩm");
        $excel->getActiveSheet()->setCellValue('C1', "Số lượng");
        $excel->getActiveSheet()->setCellValue('D1', "Đơn giá");
        $excel->getActiveSheet()->setCellValue('E1', "Thành tiền");

        $sales_invoice_detail = SalesInvoiceDetail::find("sales_invoice_id = " . $id."");
        foreach ($sales_invoice_detail as $key => $row){
            $key = $key + 2;
            $product = Product::findFirst("id = ".$row->getProductId()."");
            $product = ProductDetail::findFirst("id = ".$product->getProductDetailId()."");
            $excel->getActiveSheet()->setCellValue('A'.$key , $row->getId());
            $excel->getActiveSheet()->setCellValue('B'.$key , $product->getProductName());
            $excel->getActiveSheet()->setCellValue('C'.$key , $row->getQuantity());
            $excel->getActiveSheet()->setCellValue('D'.$key , $row->getPrice());
            $excel->getActiveSheet()->setCellValue('D'.$key , $row->getTotal());
        }
//        $temp_file = tempnam(sys_get_temp_dir(), 'phpexcel');
        $obwiter = new \PHPExcel_Writer_Excel2007($excel);
        $filename='Hoa_Don_'.date("Ymd_his").'.xlsx';
        $obwiter->save(BASE_PATH. '/public/uploads/excel/'. $filename);
        header('location:/public/uploads/excel/' . $filename);
        die();

    }

}
