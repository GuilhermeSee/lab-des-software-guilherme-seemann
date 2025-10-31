<?php

trait DataAccess{

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {  
        return isset($this->$name) ? $this->$name : null;   
    }

    public function __call($method, $args) {
        $campo = lcfirst(substr($method, 3));
        if(stripos($method, 'set') === 0){
            if(property_exists($this, $campo)){
                $this->$campo = $args[0];
                return;
            }
            throw new Exception('Campo indefinido');

        }
        if(stripos($method, 'get') === 0){
            return $this->$campo;
        }
        throw new Exception('Método indefinido');     
    }
}