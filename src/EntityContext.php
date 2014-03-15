<?php

namespace th\l20n;

class EntityContext
{
    private $catalog;
    private $stack;
    private $data;
    private $extra;

    public function __construct(Catalog $catalog, Entity $entity, Array $data)
    {
        $this->catalog = $catalog;
        $this->stack   = [];
        $this->data    = $data;
        $this->extra   = new \stdClass;

        $this->push($entity);
    }

    public function catalog()
    {
        return $this->catalog;
    }

    public function this()
    {
        return end($this->stack);
    }

    public function push(Entity $entity)
    {
        array_push($this->stack, $entity);
    }

    public function pop()
    {
        return array_pop($this->stack);
    }

    public function __get($name)
    {
        return $this->extra->$name;
    }

    public function __isset($name)
    {
        return isset($this->extra->$name);
    }

    public function __set($name, $value)
    {
        return $this->extra->$name = $value;
    }
}
