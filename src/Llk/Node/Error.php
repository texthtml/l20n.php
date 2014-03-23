<?php

namespace th\l20n\Llk\Node;

class Error extends \Exception
{
    private $entity;

    public function entity(Entity $entity = null)
    {
        if ($entity !== null) {
            $this->entity = $entity;
        }

        return $this->entity;
    }
}
