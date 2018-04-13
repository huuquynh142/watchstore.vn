<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use App\Models\Slider;

class SliderController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, Slider::class, $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $slider = Slider::find($parameters);

        $paginator = new Paginator([
            'data' => $slider,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a slider
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $slider = Slider::findFirstByid($id);
            if (!$slider) {
                $this->flash->error("slider was not found");

                $this->dispatcher->forward([
                    'controller' => "slider",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $slider->id;
            $this->view->image = $slider->image;
            $this->view->hide = $slider->hide;
            $this->tag->setDefault("id", $slider->id);
            $this->tag->setDefault("title", $slider->title);
            $this->tag->setDefault("description", $slider->description);
            $this->tag->setDefault("priority", $slider->priority);
            
        }
    }

    /**
     * Creates a new slider
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "slider",
                'action' => 'index'
            ]);

            return;
        }

        $slider = new Slider();
        $slider->title = $this->request->getPost("title");
        $slider->description = $this->request->getPost("description");
        $slider->priority = $this->request->getPost("priority");
        $slider->hide = ( $this->request->getPost("hide") ? 1 : 0 );

        if ($this->request->hasFiles() == true) {
            $baseLocation = BASE_PATH . '/public/uploads/slider/';
            $file = $this->request->getUploadedFiles()[0];
            $fileName = md5(strtok($file->getName(),'.')) . rand() . "." . $file->getExtension();
            $slider->image = $fileName;
            $file->moveTo($baseLocation . $fileName);
        }

        if (!$slider->save()) {
            foreach ($slider->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "slider",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("slider was created successfully");

        $this->dispatcher->forward([
            'controller' => "slider",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a slider edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "slider",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $slider = Slider::findFirstByid($id);

        if (!$slider) {
            $this->flash->error("slider does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "slider",
                'action' => 'index'
            ]);

            return;
        }

        $slider->title = $this->request->getPost("title");
        $slider->description = $this->request->getPost("description");
        $slider->priority = $this->request->getPost("priority");
        $slider->hide = ( $this->request->getPost("hide") ? 1 : 0 );

        if ($this->request->hasFiles() == true) {
            $baseLocation = BASE_PATH . '/public/uploads/slider/';
            $file = $this->request->getUploadedFiles()[0];
            if($slider->image && $file->getName()){
                unlink($baseLocation. $slider->image);
                $fileName = md5(strtok($file->getName(),'.')) . rand() . "." . $file->getExtension();
                $slider->image = $fileName;
                $file->moveTo($baseLocation . $fileName);
            }

        }
        

        if (!$slider->save()) {

            foreach ($slider->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "slider",
                'action' => 'edit',
                'params' => [$slider->id]
            ]);

            return;
        }

        $this->flash->success("slider was updated successfully");

        $this->dispatcher->forward([
            'controller' => "slider",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a slider
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $slider = Slider::findFirstByid($id);
        $baseLocation = BASE_PATH . '/public/uploads/slider/';
        if($slider->image)
            unlink($baseLocation. $slider->image);
        if (!$slider) {
            $this->flash->error("slider was not found");

            $this->dispatcher->forward([
                'controller' => "slider",
                'action' => 'index'
            ]);

            return;
        }

        if (!$slider->delete()) {

            foreach ($slider->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "slider",
                'action' => 'index'
            ]);

            return;
        }

        $this->flash->success("slider was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "slider",
            'action' => "index"
        ]);
    }

}
