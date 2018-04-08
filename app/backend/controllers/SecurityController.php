<?php

namespace Multiple\Backend\Controllers;

use App\Models\Users;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\View;

class SecurityController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->setRenderLevel(View::LEVEL_LAYOUT);
        $this->view->setRenderLevel(View::LEVEL_AFTER_TEMPLATE);
    }

    public function saveAction()
    {

      $userName = $this->request->get('user_name');
      $password = md5($this->request->get('password'));
      $c = Users::query()
          ->where(Users::class.".username = '" .$userName."'")
          ->andWhere(Users::class.".password = '" .$password."'")
          ->execute();
      if (count($c) > 0)
          $this->session->set('security',$userName);
        $this->dispatcher->forward(array(
            'controller' => 'product',
            'action' => 'index'
        ));

    }
    public function logoutAction()
    {
        $this->session->set('security','');
        $this->dispatcher->forward(array(
            'controller' => 'product',
            'action' => 'index'
        ));

    }


}
