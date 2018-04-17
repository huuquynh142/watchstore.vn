<?php
namespace Multiple\Backend\Controllers;
use App\Models\Product;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use App\Models\ProductDetail;


class ProductDetailController extends ControllerBase
{

    public function indexAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, ProductDetail::class, $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $product_detail = ProductDetail::find($parameters);

        $paginator = new Paginator([
            'data' => $product_detail,
            'limit'=> 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }


    public function newAction()
    {
        $this->view->productType = ProductType::find();
    }

    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $product_detail = ProductDetail::findFirstByid($id);
            if (!$product_detail) {
                $this->flash->error("product_detail was not found");

                $this->dispatcher->forward([
                    'controller' => "product_detail",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $product_detail->id;
            $this->view->isElectronic = $product_detail->is_electronic;

            $this->tag->setDefault("id", $product_detail->id);
            $this->tag->setDefault("product_name", $product_detail->product_name);
            $this->tag->setDefault("shell_material", $product_detail->shell_material);
            $this->tag->setDefault("wire_material", $product_detail->wire_material);
            $this->tag->setDefault("guarantee", $product_detail->guarantee);
            $this->tag->setDefault("glasses", $product_detail->glasses);
            $this->tag->setDefault("shell_diameter", $product_detail->shell_diameter);
            $this->tag->setDefault("shell_thickness", $product_detail->shell_thickness);
            $this->tag->setDefault("water_resistant", $product_detail->water_resistant);
            $this->tag->setDefault("motor", $product_detail->motor);
            $this->tag->setDefault("comment", $product_detail->comment);
            
        }
    }

    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "product_detail",
                'action' => 'index'
            ]);

            return;
        }


        $product_detail = new ProductDetail();
        $product_detail->setProductName($this->request->getPost("product_name"));
        $product_detail->setShellMaterial($this->request->getPost("shell_material"));
        $product_detail->setWireMaterial($this->request->getPost("wire_material"));
        $product_detail->setGuarantee($this->request->getPost("guarantee"));
        $product_detail->setGlasses($this->request->getPost("glasses"));
        $product_detail->setShellDiameter($this->request->getPost("shell_diameter"));
        $product_detail->setShellThickness($this->request->getPost("shell_thickness"));
        $product_detail->setWaterResistant($this->request->getPost("water_resistant"));
        $product_detail->setIsElectronic($this->request->getPost("is_electronic")? 1 : 0);
        $product_detail->setMotor($this->request->getPost("motor"));
        $product_detail->setComment($this->request->getPost("comment"));

        if (!$product_detail->save()) {
            foreach ($product_detail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_detail",
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success("product_detail was created successfully");

        $this->dispatcher->forward([
            'controller' => "product_detail",
            'action' => 'index'
        ]);
    }

    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "product_detail",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $product_detail = ProductDetail::findFirstByid($id);

        if (!$product_detail) {
            $this->flash->error("product_detail does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "product_detail",
                'action' => 'index'
            ]);

            return;
        }

        $product_detail->productName = $this->request->getPost("product_name");
        $product_detail->shellMaterial = $this->request->getPost("shell_material");
        $product_detail->wireMaterial = $this->request->getPost("wire_material");
        $product_detail->guarantee = $this->request->getPost("guarantee");
        $product_detail->glasses = $this->request->getPost("glasses");
        $product_detail->shellDiameter = $this->request->getPost("shell_diameter");
        $product_detail->shellThickness = $this->request->getPost("shell_thickness");
        $product_detail->waterResistant = $this->request->getPost("water_resistant");
        $product_detail->isElectronic = $this->request->getPost("is_electronic") ? 1 : 0;
        $product_detail->motor = $this->request->getPost("motor");
        $product_detail->comment = $this->request->getPost("comment");
        

        if (!$product_detail->save()) {

            foreach ($product_detail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_detail",
                'action' => 'edit',
                'params' => [$product_detail->id]
            ]);

            return;
        }

        $this->flash->success("product_detail was updated successfully");

        $this->dispatcher->forward([
            'controller' => "product_detail",
            'action' => 'index'
        ]);
    }


    public function deleteAction($id)
    {
        $product_detail = ProductDetail::findFirstByid($id);
        if (!$product_detail) {
            $this->flash->error("product_detail was not found");

            $this->dispatcher->forward([
                'controller' => "product_detail",
                'action' => 'index'
            ]);

            return;
        }

        if (!$product_detail->delete()) {

            foreach ($product_detail->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_detail",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("product_detail was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "product_detail",
            'action' => "index"
        ]);
    }

}
