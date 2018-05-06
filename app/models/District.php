<?php
namespace App\Models;
class District extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     * @Primary
     * @Column(type="string", length=5, nullable=false)
     */
    protected $districtid;

    /**
     *
     * @var string
     * @Column(type="string", length=100, nullable=false)
     */
    protected $name;

    /**
     *
     * @var string
     * @Column(type="string", length=30, nullable=false)
     */
    protected $type;

    /**
     *
     * @var string
     * @Column(type="string", length=30, nullable=false)
     */
    protected $location;

    /**
     *
     * @var string
     * @Column(type="string", length=5, nullable=false)
     */
    protected $provinceid;

    /**
     * Method to set the value of field districtid
     *
     * @param string $districtid
     * @return $this
     */
    public function setDistrictid($districtid)
    {
        $this->districtid = $districtid;

        return $this;
    }

    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Method to set the value of field location
     *
     * @param string $location
     * @return $this
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Method to set the value of field provinceid
     *
     * @param string $provinceid
     * @return $this
     */
    public function setProvinceid($provinceid)
    {
        $this->provinceid = $provinceid;

        return $this;
    }

    /**
     * Returns the value of field districtid
     *
     * @return string
     */
    public function getDistrictid()
    {
        return $this->districtid;
    }

    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Returns the value of field location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Returns the value of field provinceid
     *
     * @return string
     */
    public function getProvinceid()
    {
        return $this->provinceid;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("watch_fashion");
        $this->setSource("district");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'district';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return District[]|District|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return District|\Phalcon\Mvc\Model\ResultInterface
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
            'districtid' => 'districtid',
            'name' => 'name',
            'type' => 'type',
            'location' => 'location',
            'provinceid' => 'provinceid'
        ];
    }

}
