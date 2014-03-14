<?php

namespace th\l20n;

class Catalog
{
    private $parser;
    private $compiler;

    private $resources = [];

    public function __construct(Parser $parser, Compiler $compiler)
    {
        $this->parser   = $parser;
        $this->compiler = $compiler;
    }

    public function addResource($resource)
    {
        $this->resources[] = $resource;
    }

    public function getEntity($id)
    {
        $entity = $this->compiler->getEntity($id);
        if ($entity === null) {
            $this->compile();
            $entity = $this->compiler->getEntity($id);
        }

        return $entity;
    }

    public function get($id, Array $data = [])
    {
        return $this->getEntity($id)->evaluate($this, $data);
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
