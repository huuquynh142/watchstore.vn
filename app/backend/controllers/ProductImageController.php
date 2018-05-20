<?php
namespace Multiple\Backend\Controllers;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use App\Models\ProductImage;

class ProductImageController extends ControllerBase
{

    public function indexAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, ProductImage::class, $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters["order"] = "id";

        $product_image = ProductImage::find($parameters);

        $paginator = new Paginator([
            'data' => $product_image,
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
     * Edits a product_image
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $product_image = ProductImage::findFirstByid($id);
            if (!$product_image) {
                $this->flash->error("product_image was not found");

                $this->dispatcher->forward([
                    'controller' => "product_image",
                    'action' => 'index'
                ]);

                return;
            }

            $this->view->id = $product_image->id;
            $this->view->image = $product_image->image;

            $this->tag->setDefault("id", $product_image->id);
            $this->tag->setDefault("product_id", $product_image->productId);
            $this->tag->setDefault("member", $product_image->image);
            
        }
    }

    /**
     * Creates a new product_image
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "product_image",
                'action' => 'index'
            ]);

            return;
        }

        $product_image = new ProductImage();
        $product_image->setProductId($this->request->getPost("product_id"));
        if ($this->request->hasFiles()) {
            $baseLocation = BASE_PATH . '/public/uploads/product/';
            $file = $this->request->getUploadedFiles()[0];
            $fileName = md5(strtok($file->getName(),'.')) . rand() . "." . $file->getExtension();
            $product_image->image = $fileName;
            $file->moveTo($baseLocation . $fileName);
        }

        if (!$product_image->save()) {
            foreach ($product_image->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_image",
                'action' => 'index'
            ]);

            return;
        }

        $this->flash->success("product_image was created successfully");

        $this->dispatcher->forward([
            'controller' => "product_image",
            'action' => 'index'
        ]);
    }

    /**
     * Saves a product_image edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "product_image",
                'action' => 'index'
            ]);

            return;
        }

        $id = $this->request->getPost("id");
        $product_image = ProductImage::findFirstByid($id);

        if (!$product_image) {
            $this->flash->error("product_image does not exist " . $id);

            $this->dispatcher->forward([
                'controller' => "product_image",
                'action' => 'index'
            ]);

            return;
        }
        $product_image->productId = $this->request->getPost("product_id");
        if ($this->request->hasFiles() == true) {
            $baseLocation = BASE_PATH . '/public/uploads/product/';
            $file = $this->request->getUploadedFiles()[0];
            if($product_image->image && $file->getName()){
                unlink($baseLocation. $product_image->image);
                $fileName = md5(strtok($file->getName(),'.')) . rand() . "." . $file->getExtension();
                $product_image->image = $fileName;
                $file->moveTo($baseLocation . $fileName);
            }

        }
        

        if (!$product_image->save()) {

            foreach ($product_image->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_image",
                'action' => 'edit',
                'params' => [$product_image->id]
            ]);

            return;
        }

        $this->flash->success("product_image was updated successfully");

        $this->dispatcher->forward([
            'controller' => "product_image",
            'action' => 'index'
        ]);
    }

    /**
     * Deletes a product_image
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $product_image = ProductImage::findFirstByid($id);
        $baseLocation = BASE_PATH . '/public/uploads/product/';
        if($product_image->image)
            unlink($baseLocation. $product_image->image);
        if (!$product_image) {
            $this->flash->error("product_image was not found");

            $this->dispatcher->forward([
                'controller' => "product_image",
                'action' => 'index'
            ]);

            return;
        }

        if (!$product_image->delete()) {

            foreach ($product_image->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "product_image",
                'action' => 'search'
            ]);

            return;
        }

        $this->flash->success("product_image was deleted successfully");

        $this->dispatcher->forward([
            'controller' => "product_image",
            'action' => "index"
        ]);
    }

}
