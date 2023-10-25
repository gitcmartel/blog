<?php

namespace Application\Models;

abstract class Table 
{
    #region Properties
    protected ?int $id = null;
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

    function setId(int $id)
    {
        $this->id = $id;
    }


    function hydrate(array $data) 
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    #endregion
}