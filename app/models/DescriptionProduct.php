<?php
namespace App\Models;
/**
 * DescriptionProduct
 * 
 * @autogenerated by Phalcon Developer Tools
 * @date 2018-01-10, 00:54:14
 */
class DescriptionProduct extends \Phalcon\Mvc\Model
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
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    protected $first_description;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=true)
     */
    protected $last_description;

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
     * Method to set the value of field first_description
     *
     * @param string $first_description
     * @return $this
     */
    public function setFirstDescription($first_description)
    {
        $this->first_description = $first_description;

        return $this;
    }

    /**
     * Method to set the value of field last_description
     *
     * @param string $last_description
     * @return $this
     */
    public function setLastDescription($last_description)
    {
        $this->last_description = $last_description;

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
     * Returns the value of field first_description
     *
     * @return string
     */
    public function getFirstDescription()
    {
        return $this->first_description;
    }

    /**
     * Returns the value of field last_description
     *
     * @return string
     */
    public function getLastDescription()
    {
        return $this->last_description;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("watch_fashion");
        $this->setSource("description_product");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'description_product';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DescriptionProduct[]|DescriptionProduct|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DescriptionProduct|\Phalcon\Mvc\Model\ResultInterface
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
            'first_description' => 'first_description',
            'last_description' => 'last_description'
        ];
    }

}
