<?php

namespace Application\Models;

abstract class Table 
{
    #region Properties
    protected ?int $id = null;
    #endregion

    #region Constructor 
    function __construct(array $values = array())
    {
        if(! empty($values)){
            $this->hydrate($values);
        }
    }
    #endregion

    #region Functions
    
    function getId() : ?int
    {
        if(isset($this->id)){
            return $this->id;
        } else {
            return null;
        } 
    }

    function setId(?int $id)
    {
        $this->id = $id;
    }


    function hydrate(array $data) 
    {
        foreach ($data as $key => $value) {
            if(gettype($key) === "string"){
                $method = 'set' . mb_strtoupper($key[0]) . substr($key,1);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
    }

    #endregion
}