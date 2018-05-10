<?php
/**
 * Created by PhpStorm.
 * User: huuqu
 * Date: 4/28/2018
 * Time: 11:04 PM
 */
namespace Multiple\Frontend\Controllers;

use App\Models\Member;

class AccountController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle("người dùng");
    }

    public function loginAction(){
        $this->tag->prependTitle("Đăng nhập ");
        if ($this->request->isPost()) {
          $phone_number =  $this->request->getPost("customer_phone");
          $password = md5($this->request->getPost("customer_password"));
          $robots = Member::query()
              ->where(Member::class.'.phone_number =' . $phone_number)
              ->andWhere(Member::class.".password ='" . $password . "'")
              ->execute();
          if (count($robots))
          {
              $user_names = $robots[0]->fullname ? $robots[0]->fullname : $robots[0]->phone_number;
              $this->session->set('user_name',$user_names);
              $this->session->set('user_phone',$robots[0]->phone_number);
              return json_encode(array('code'=>'success'));
          }
            return json_encode(array('code'=>'fail'));

        }

    }
    public function logoutAction(){
        $this->session->remove('user_phone');
        $this->session->remove('user_name');
        $this->dispatcher->forward([
            'controller' => "index",
            'action' => 'index'
        ]);
        return;
    }

    public function registerAction(){
        $this->tag->prependTitle("Đăng ký ");
        if ($this->request->isPost()) {
            $arr = [];
            $robots = Member::query()
                ->where(Member::class.'.phone_number =' . $this->request->getPost("customer_phone_number"))
                ->execute();
            if (count($robots))
                return json_encode(array(
                    'code'=>'fail',
                    'message' =>'Số điện thoại đã được sử dụng . vui lòng sử dụng số điện thoại khác !'));
            else{
                $member = new Member();
                $member->setFullname($this->request->getPost("customer_full_name"));
                $member->setPhoneNumber($this->request->getPost("customer_phone_number"));
                if ($this->request->getPost("customer_email"))
                    $member->setEmail($this->request->getPost("customer_email"));
                $member->setPassword(md5($this->request->getPost("customer_password"))) ;
                $member->save();
                $user_names = $member->getFullname() ? $member->getFullname() : $member->getPhoneNumber();
                $this->session->set('user_name',$user_names);
                $this->session->set('user_phone',$member->getPhoneNumber());
                return json_encode( array_merge($arr , array('code'=>'success')));
            }

        }

    }

}