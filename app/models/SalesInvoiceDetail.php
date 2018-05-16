<?php
namespace App\Models;
class SalesInvoiceDetail extends \Phalcon\Mvc\Model
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
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $sales_invoice_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $product_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    protected $quantity;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    protected $discount;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    protected $price;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    protected $total;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=true)
     */
    protected $comment;

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
     * Method to set the value of field sales_invoice_id
     *
     * @param integer $sales_invoice_id
     * @return $this
     */
    public function setSalesInvoiceId($sales_invoice_id)
    {
        $this->sales_invoice_id = $sales_invoice_id;

        return $this;
    }

    /**
     * Method to set the value of field product_id
     *
     * @param integer $product_id
     * @return $this
     */
    public function setProductId($product_id)
    {
        $this->product_id = $product_id;

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
     * Method to set the value of field price
     *
     * @param string $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Method to set the value of field total
     *
     * @param string $total
     * @return $this
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Method to set the value of field comment
     *
     * @param string $comment
     * @return $this
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

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
     * Returns the value of field sales_invoice_id
     *
     * @return integer
     */
    public function getSalesInvoiceId()
    {
        return $this->sales_invoice_id;
    }

    /**
     * Returns the value of field product_id
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->product_id;
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
     * Returns the value of field discount
     *
     * @return string
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Returns the value of field price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Returns the value of field total
     *
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Returns the value of field comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("b4_22077174_huuquynh");
        $this->setSource("sales_invoice_detail");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'sales_invoice_detail';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesInvoiceDetail[]|SalesInvoiceDetail|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesInvoiceDetail|\Phalcon\Mvc\Model\ResultInterface
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
            'sales_invoice_id' => 'sales_invoice_id',
            'product_id' => 'product_id',
            'quantity' => 'quantity',
            'discount' => 'discount',
            'price' => 'price',
            'total' => 'total',
            'comment' => 'comment'
        ];
    }

}
