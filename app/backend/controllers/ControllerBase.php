<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Controller;
use Phalcon\Logger;

class ControllerBase extends Controller
{

//    public function afterExecuteRoute($dispatcher) {
//        $config = $this->getDi()->get('config');
//        $logDir = realpath($config->application->logDir);
//        $logger = new \Phalcon\Logger\Adapter\File($logDir . '/query'.date('Y-m-d').'.log');
//
//        $profiles = $this->getDi()->get('profiler')->getProfiles();
//
//        if ($profiles) {
//            $total_query_time = 0;
//
//            $logger->log($_SERVER['REQUEST_URI'].' | URI: '.$_SERVER['REQUEST_URI'], Logger::INFO);
//
//            foreach ($profiles as $profile) {
//                $total = (float) $profile->getTotalElapsedSeconds();
//
//                $query_profile = array(
//                    'host' => $_SERVER['HTTP_HOST'],
//                    'total' => round($profile->getTotalElapsedSeconds()*1000).'ms'
//                );
//
//                $jsonProfile = json_encode($query_profile);
//                $logger->log($jsonProfile."\r\n".$profile->getSQLStatement(), Logger::INFO);
//                $logger->log("---------------------------------------------------------------------\r\n", Logger::INFO);
//                $total_query_time += (float) $total;
//            }
//
//            $endPage = "=====================";
//            $logger->log(
//                $endPage.
//                json_encode(array('QueryCount' => sizeof($profiles), 'TotalPageQueryTime' => round($total*1000).'ms')).
//                $endPage."\r\n",
//                Logger::INFO);
//        }
//    }
}
