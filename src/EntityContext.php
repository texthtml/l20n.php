<?php

namespace th\l20n;

class EntityContext
{
    private $catalog;
    private $stack;
    private $data;
    private $globalsExpressions;
    public $bag;

    public function __construct(Catalog $catalog, Entity $entity, Array $data, Array $globalsExpressions)
    {
        $this->catalog            = $catalog;
        $this->stack              = [];
        $this->data               = $data;
        $this->bag                = new \stdClass;
        $this->globalsExpressions = $globalsExpressions;

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

    public function variable($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
    }

    public function globalExpression($name)
    {
        if (array_key_exists($name, $this->globalsExpressions)) {
            return $this->globalsExpressions[$name]();
        }
    }

    public function globalsExpressions()
    {
        return $this->globalsExpressions;
    }
}
