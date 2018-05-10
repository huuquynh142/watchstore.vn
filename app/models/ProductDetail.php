<?php
namespace App\Models;
/**
 * ProductDetail
 * 
 * @autogenerated by Phalcon Developer Tools
 * @date 2018-01-10, 00:54:15
 */
class ProductDetail extends \Phalcon\Mvc\Model
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
     * @Column(type="string", length=200, nullable=true)
     */
    protected $product_name;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    protected $shell_material;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    protected $wire_material;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    protected $guarantee;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    protected $glasses;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    protected $shell_diameter;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    protected $shell_thickness;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    protected $water_resistant;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    protected $type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=4, nullable=true)
     */
    protected $is_electronic;

    /**
     *
     * @var string
     * @Column(type="string", length=200, nullable=true)
     */
    protected $motor;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
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
     * Method to set the value of field product_name
     *
     * @param string $product_name
     * @return $this
     */
    public function setProductName($product_name)
    {
        $this->product_name = $product_name;

        return $this;
    }

    /**
     * Method to set the value of field shell_material
     *
     * @param string $shell_material
     * @return $this
     */
    public function setShellMaterial($shell_material)
    {
        $this->shell_material = $shell_material;

        return $this;
    }

    /**
     * Method to set the value of field wire_material
     *
     * @param string $wire_material
     * @return $this
     */
    public function setWireMaterial($wire_material)
    {
        $this->wire_material = $wire_material;

        return $this;
    }

    /**
     * Method to set the value of field guarantee
     *
     * @param string $guarantee
     * @return $this
     */
    public function setGuarantee($guarantee)
    {
        $this->guarantee = $guarantee;

        return $this;
    }

    /**
     * Method to set the value of field glasses
     *
     * @param string $glasses
     * @return $this
     */
    public function setGlasses($glasses)
    {
        $this->glasses = $glasses;

        return $this;
    }

    /**
     * Method to set the value of field shell_diameter
     *
     * @param string $shell_diameter
     * @return $this
     */
    public function setShellDiameter($shell_diameter)
    {
        $this->shell_diameter = $shell_diameter;

        return $this;
    }

    /**
     * Method to set the value of field shell_thickness
     *
     * @param string $shell_thickness
     * @return $this
     */
    public function setShellThickness($shell_thickness)
    {
        $this->shell_thickness = $shell_thickness;

        return $this;
    }

    /**
     * Method to set the value of field water_resistant
     *
     * @param string $water_resistant
     * @return $this
     */
    public function setWaterResistant($water_resistant)
    {
        $this->water_resistant = $water_resistant;

        return $this;
    }

    /**
     * Method to set the value of field type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Method to set the value of field is_electronic
     *
     * @param integer $is_electronic
     * @return $this
     */
    public function setIsElectronic($is_electronic)
    {
        $this->is_electronic = $is_electronic;

        return $this;
    }

    /**
     * Method to set the value of field motor
     *
     * @param string $motor
     * @return $this
     */
    public function setMotor($motor)
    {
        $this->motor = $motor;

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
     * Returns the value of field product_name
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * Returns the value of field shell_material
     *
     * @return string
     */
    public function getShellMaterial()
    {
        return $this->shell_material;
    }

    /**
     * Returns the value of field wire_material
     *
     * @return string
     */
    public function getWireMaterial()
    {
        return $this->wire_material;
    }

    /**
     * Returns the value of field guarantee
     *
     * @return string
     */
    public function getGuarantee()
    {
        return $this->guarantee;
    }

    /**
     * Returns the value of field glasses
     *
     * @return string
     */
    public function getGlasses()
    {
        return $this->glasses;
    }

    /**
     * Returns the value of field shell_diameter
     *
     * @return string
     */
    public function getShellDiameter()
    {
        return $this->shell_diameter;
    }

    /**
     * Returns the value of field shell_thickness
     *
     * @return string
     */
    public function getShellThickness()
    {
        return $this->shell_thickness;
    }

    /**
     * Returns the value of field water_resistant
     *
     * @return string
     */
    public function getWaterResistant()
    {
        return $this->water_resistant;
    }

    /**
     * Returns the value of field type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the value of field is_electronic
     *
     * @return integer
     */
    public function getIsElectronic()
    {
        return $this->is_electronic;
    }

    /**
     * Returns the value of field motor
     *
     * @return string
     */
    public function getMotor()
    {
        return $this->motor;
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
        $this->setSchema("b18_22062172_huuquynh");
        $this->setSource("product_detail");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'product_detail';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductDetail[]|ProductDetail|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ProductDetail|\Phalcon\Mvc\Model\ResultInterface
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
            'product_name' => 'product_name',
            'shell_material' => 'shell_material',
            'wire_material' => 'wire_material',
            'guarantee' => 'guarantee',
            'glasses' => 'glasses',
            'shell_diameter' => 'shell_diameter',
            'shell_thickness' => 'shell_thickness',
            'water_resistant' => 'water_resistant',
            'type' => 'type',
            'is_electronic' => 'is_electronic',
            'motor' => 'motor',
            'comment' => 'comment'
        ];
    }

}
