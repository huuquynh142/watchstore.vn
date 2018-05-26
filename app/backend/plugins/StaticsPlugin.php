<?php
namespace Multiple\Backend\Plugins;
use App\Models\Product;
use App\Models\SalesInvoiceDetail;
use Phalcon\Mvc\User\Plugin;
class StaticsPlugin extends Plugin{


    public function __construct($type , $year)
    {
        $this->type = $type;
        $this->year = $year;
    }

    public function execute(){
        if ($this->type == 1)
           return $this->sellNumber();
        else
           return $this->revenue();
    }

    public function revenue(){
        return array(
            'Month 1' => $this->doanhthu('-01-01','-01-31'),
            'Month 2' => $this->doanhthu('-02-01','-02-30'),
            'Month 3' => $this->doanhthu('-03-01','-03-31'),
            'Month 4' => $this->doanhthu('-04-01','-04-30'),
            'Month 5' => $this->doanhthu('-05-01','-05-31'),
            'Month 6' => $this->doanhthu('-06-01','-06-30'),
            'Month 7' => $this->doanhthu('-07-01','-07-31'),
            'Month 8' => $this->doanhthu('-08-01','-08-31'),
            'Month 9' => $this->doanhthu('-09-01','-09-30'),
            'Month 10' => $this->doanhthu('-10-01','-10-31'),
            'Month 11' => $this->doanhthu('-11-01','-11-30'),
            'Month 12' => $this->doanhthu('-12-01','-12-31')
        );
    }

    public function sellNumber(){
        return array(
            'Month 1' => $this->month('-01-01','-01-31'),
            'Month 2' => $this->month('-02-01','-02-30'),
            'Month 3' => $this->month('-03-01','-03-31'),
            'Month 4' => $this->month('-04-01','-04-30'),
            'Month 5' => $this->month('-05-01','-05-31'),
            'Month 6' => $this->month('-06-01','-06-30'),
            'Month 7' => $this->month('-07-01','-07-31'),
            'Month 8' => $this->month('-08-01','-08-31'),
            'Month 9' => $this->month('-09-01','-09-30'),
            'Month 10' => $this->month('-10-01','-10-31'),
            'Month 11' => $this->month('-11-01','-11-30'),
            'Month 12' => $this->month('-12-01','-12-31')
        );
    }

    public function criteria(){
        $robot = SalesInvoiceDetail::query();
        return $robot;
    }

    public function criterias(){
        $robot = SalesInvoiceDetail::query()
            ->join(Product::class ,SalesInvoiceDetail::class.".product_id = ".Product::class.".id");
        return $robot;
    }

    public function month($from,$to){
        $count = 0;
        $robot = $this->criteria();
        $robot = $robot->where(SalesInvoiceDetail::class.".created_at > '". $this->year.$from."'")
            ->andWhere(SalesInvoiceDetail::class.".created_at < '". $this->year.$to."'")
            ->columns(SalesInvoiceDetail::class.".quantity")
            ->execute();
        foreach ($robot as $item){
            $count += (int)$item['quantity'];
        }
        return $count;
    }

    public function doanhthu($from,$to){
        $count = 0;
        $robot = $this->criterias();
        $robot = $robot->where(SalesInvoiceDetail::class.".created_at > '". $this->year.$from."'")
            ->andWhere(SalesInvoiceDetail::class.".created_at < '". $this->year.$to."'")
            ->columns([SalesInvoiceDetail::class.".quantity",
                Product::class.".sale_price",
                Product::class.".import_price",
                Product::class.".discount"
                ])
            ->execute();
        foreach ($robot as $item){
            if ($item['discount']){
                $count +=  (((int)$item['sale_price'] * ((int)$item['discount']/100))* (int)$item['quantity']) - ((int)$item['import_price'] * (int)$item['quantity'] );
            }
            $count += ((int)$item['sale_price'] * (int)$item['quantity']) - ((int)$item['import_price'] * (int)$item['quantity'] );
        }
        return $count;
    }

}