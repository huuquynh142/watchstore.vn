<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Controller;
use Phalcon\Logger;
use Phalcon\Mvc\Dispatcher;
class ControllerBase extends Controller
{

    public function beforeExecuteRoute( Dispatcher $dispatcher)
    {

        if ($dispatcher->getControllerName() != 'login') {
            if (!$this->session->get('login')) {
                 $dispatcher->forward(array(
                    'controller' => 'login',
                    'action' => 'index'
                ));
            }
        }

    }

}
