<?php
/**
 * Created by PhpStorm.
 * User: huuqu
 * Date: 1/15/2018
 * Time: 4:45 AM
 */

namespace App\Library;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductImage;

class ShopCart
{
    public $id;
    public $name;
    public $image;
    public $price;
    public $discount;
    public $quatity;
    public $total;

    /**
     * ShopCart constructor.
     * @param $id
     * @param $name
     * @param $image
     * @param $price
     * @param $discount
     * @param $quatity
     * @param $total
     */
    public function __construct($id , $quatitys = 1)
    {
        $product = Product::findFirst($id);
        $productDetail = ProductDetail::findFirst($product->getProductDetailId());
        $robos = ProductImage::query()
            ->where(ProductImage::class.'.product_id = '. $product->getId())
            ->limit(1)
            ->execute();
        $this->id = $id;
        $this->name = $productDetail->getProductName();
        $this->image = $robos[0]->image;
        $this->price = $product->getSalePrice();
        $this->discount = $product->getDiscount();
        $this->quatity = $quatitys;
        if ($this->discount)
            $this->total = ($this->price * $this->quatity) - ($this->price * $this->quatity * $this->discount);
        else
            $this->total = ($this->price * $this->quatity);
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param mixed $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return mixed
     */
    public function getQuatity()
    {
        return $this->quatity;
    }

    /**
     * @param mixed $quatity
     */
    public function setQuatity($quatity)
    {
        $this->quatity = $quatity;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        if ($this->discount)
            return ($this->price * $this->quatity) - ($this->price * $this->quatity * $this->discount);
        else
            return ($this->price * $this->quatity);
    }

    /**
     * @param mixed $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }



}