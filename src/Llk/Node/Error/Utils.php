<?php

namespace th\l20n\Llk\Node\Error;

use th\l20n\Llk\Node\Entity;

trait Utils
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
