<?php
namespace App\Models;
class Product extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $producer_id;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    protected $description_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $product_detail_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $quantity;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    protected $import_price;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=true)
     */
    protected $sale_price;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $discount;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $view;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $hot;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $created_at;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field producer_id
     *
     * @param integer $producer_id
     * @return $this
     */
    public function setProducerId($producer_id)
    {
        $this->producer_id = $producer_id;

        return $this;
    }

    /**
     * Method to set the value of field description_id
     *
     * @param string $description_id
     * @return $this
     */
    public function setDescriptionId($description_id)
    {
        $this->description_id = $description_id;

        return $this;
    }

    /**
     * Method to set the value of field product_detail_id
     *
     * @param integer $product_detail_id
     * @return $this
     */
    public function setProductDetailId($product_detail_id)
    {
        $this->product_detail_id = $product_detail_id;

        return $this;
    }

    /**
     * Method to set the value of field quantity
     *
     * @param integer $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Method to set the value of field import_price
     *
     * @param string $import_price
     * @return $this
     */
    public function setImportPrice($import_price)
    {
        $this->import_price = $import_price;

        return $this;
    }

    /**
     * Method to set the value of field sale_price
     *
     * @param string $sale_price
     * @return $this
     */
    public function setSalePrice($sale_price)
    {
        $this->sale_price = $sale_price;

        return $this;
    }

    /**
     * Method to set the value of field discount
     *
     * @param string $discount
     * @return $this
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Method to set the value of field view
     *
     * @param integer $view
     * @return $this
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Method to set the value of field hot
     *
     * @param integer $hot
     * @return $this
     */
    public function setHot($hot)
    {
        $this->hot = $hot;

        return $this;
    }

    /**
     * Method to set the value of field created_at
     *
     * @param string $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field producer_id
     *
     * @return integer
     */
    public function getProducerId()
    {
        return $this->producer_id;
    }

    /**
     * Returns the value of field description_id
     *
     * @return string
     */
    public function getDescriptionId()
    {
        return $this->description_id;
    }

    /**
     * Returns the value of field product_detail_id
     *
     * @return integer
     */
    public function getProductDetailId()
    {
        return $this->product_detail_id;
    }

    /**
     * Returns the value of field quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Returns the value of field import_price
     *
     * @return string
     */
    public function getImportPrice()
    {
        return $this->import_price;
    }

    /**
     * Returns the value of field sale_price
     *
     * @return string
     */
    public function getSalePrice()
    {
        return $this->sale_price;
    }

    /**
     * Returns the value of field discount
     *
     * @return string
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Returns the value of field view
     *
     * @return integer
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Returns the value of field hot
     *
     * @return integer
     */
    public function getHot()
    {
        return $this->hot;
    }

    /**
     * Returns the value of field created_at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("watches_online");
        $this->setSource("product");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'product';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Product[]|Product|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Product|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'producer_id' => 'producer_id',
            'description_id' => 'description_id',
            'product_detail_id' => 'product_detail_id',
            'quantity' => 'quantity',
            'import_price' => 'import_price',
            'sale_price' => 'sale_price',
            'discount' => 'discount',
            'view' => 'view',
            'hot' => 'hot',
            'created_at' => 'created_at'
        ];
    }

}
