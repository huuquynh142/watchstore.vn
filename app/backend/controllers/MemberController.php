<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use App\Models\Member;


class MemberController extends ControllerBase
{

    public function indexAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, Member::class, $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $member = Member::find($parameters);

        $this->view->member = $member;

        $paginator = new Paginator([
            'data' => $member,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    public function newAction()
    {

    }


    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $member = Member::findFirstByid($id);
            if (!$member) {
                $this->flash->error("member was not found");

                $this->dispatcher->forward([
                    'controller' => "member",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $member->id;
            $this->view->sex = $member->sex;

            $this->tag->setDefault("id", $member->id);
            $this->tag->setDefault("fullname", $member->fullname);
            //$this->tag->setDefault("sex", $member->sex);
            $this->tag->setDefault("phone_number", $member->phone_number);
            $this->tag->setDefault("email", $member->email);
            $this->tag->setDefault("password", $member->password);
            $this->tag->setDefault("number_hits", $member->number_hits);
            $this->tag->setDefault("address", $member->address);
            $this->tag->setDefault("avatar", $member->avatar);
            $this->tag->setDefault("imghine", $member->avatar);
        }
    }


    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "member",
                'action' => 'index'
            ]);

            return;
        }

        $member = new Member();
        $member->setFullname($this->request->getPost("fullname"));
        $member->setSex($this->request->getPost("sex"));
        $member->setPhoneNumber($this->request->getPost("phone_number"));
        $member->setEmail($this->request->getPost("email", "email"));
        $member->setPassword(md5($this->request->getPost("password"))) ;
        $member->setNumberHits($this->request->getPost("number_hits"));
        $member->setAddress($this->request->getPost("address"));
        $member->setAvatar($this->request->getPost("avatar"));
        

        if (!$member->save()) {
            foreach ($member->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "member",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("member was created successfully");

        $this->dispatcher->forward([
            'controller' => "member",
            'action' => 'index'
        ]);
    }


    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "member",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $member = Member::findFirstByid($id);

        if (!$member) {
            $this->flash->error("member does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "member",
                'action' => 'index'
            ]);

            return;
        }
        $img =  $this->request->getPost("imghine") ? $this->request->getPost("imghine") : '';
        $member->fullname = $this->request->getPost("fullname");
        $member->sex = $this->request->getPost("sex");
        $member->phoneNumber = $this->request->getPost("phone_number");
        $member->email = $this->request->getPost("email", "email");
        $member->password = $this->request->getPost("password");
        $member->numberHits = $this->request->getPost("number_hits");
        $member->address = $this->request->getPost("address");
        $member->avatar = $this->request->getPost("avatar") ? $this->request->getPost("avatar") : $img;

        if (!$member->save()) {

            foreach ($member->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "member",
                'action' => 'edit',
                'params' => [$member->id]
            ]);

            return;
        }

        $this->flash->success("member was updated successfully");

        $this->dispatcher->forward([
            'controller' => "member",
            'action' => 'index'
        ]);
    }

    public function deleteAction($id)
    {
        $member = Member::findFirstByid($id);
        if (!$member) {
            $this->flash->error("member was not found");

            $this->dispatcher->forward([
                'controller' => "member",
                'action' => 'index'
            ]);

            return;
        }

        if (!$member->delete()) {

            foreach ($member->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "member",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("member was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "member",
            'action' => "index"
        ]);
    }

}
