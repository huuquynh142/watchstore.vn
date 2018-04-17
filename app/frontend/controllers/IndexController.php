<?php
namespace Multiple\Frontend\Controllers;
use App\Models\Slider;
class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->slideShowAction();
    }

    public function testAction()
    {

    }
    public function bannerShowAction(){
    }
    public function slideShowAction(){
        $robos = Slider::query()
            ->where(Slider::class.'.hide = 0')
            ->orderBy(Slider::class.'.priority ASC')
            ->execute();
        $this->view->slider = $robos;
    }

}
