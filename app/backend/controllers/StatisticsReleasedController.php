<?php
namespace Multiple\Backend\Controllers;
use Multiple\Backend\Plugins\StaticsPlugin;

class StatisticsReleasedController extends ControllerBase
{

    public function indexAction()
    {
        $now = getdate();
        $type = 'số lượng bán';
        $year = $now["year"];
        $legendTitle = 'So luong';
        $this->year = $year;
        $arrYear = [];
        $this->type = 1;
        if ($_POST){
            $this->type = $this->request->getPost("type");
            $this->year = $this->request->getPost("year");
            if ($this->type == 1){
                $type = 'số lượng bán';
                $legendTitle = 'So luong';
            }
            else{
                $type = 'doanh thu';
                $legendTitle = 'Doanh so';
            }

        }

        for ($i = 0 ; $i <=3 ; $i++)
        {
            $arrYear [] = $year - $i;
        }
        $this->tag->setDefault("type", $type);
        $this->tag->setDefault("year", $year);
        $this->view->year = $arrYear;
        $this->view->currentYear = $this->year ;
        $this->view->currentType = $this->type ;
        $this->view->legendTitle = $legendTitle;
        $this->view->title = 'Thống kê '. $type .' năm ' . $this->year;
        $static = new StaticsPlugin($this->type ,$this->year );
        $this->view->statics = $static->execute();
    }

}