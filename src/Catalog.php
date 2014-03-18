<?php

namespace th\l20n;

class Catalog
{
    private $parser;
    private $compiler;

    private $resources = [];

    public $contextEntity;

    public function __construct(Parser $parser, Compiler $compiler)
    {
        $this->parser   = $parser;
        $this->compiler = $compiler;
    }

    public function addResource($resource)
    {
        $this->resources[] = $resource;
    }

    public function entity($id)
    {
        $entity = $this->compiler->entity($id);
        if ($entity === null) {
            $this->compile();
            $entity = $this->compiler->entity($id);
        }

        return $entity;
    }

    public function get($id, Array $data = [])
    {
        $entity = $this->entity($id);

        $context = new EntityContext($this, $entity, $data);

        return $entity($context);
    }

    public function compile()
    {
        while (($resource = array_shift($this->resources)) !== null) {
            if (is_callable($resource)) {
                $resource = $resource();
            }
            $ast = $this->parser->parse($resource);

            $imports = $this->compiler->compile($ast);
            $this->resources = array_merge($this->resources, $imports);
        }
    }
}
