<?php

namespace th\l20n;

class Catalog
{
    private $parser;

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

    protected function registerEntry($entry)
    {
        if ($entry->getId() === '#entity') {
            $id = $entry->getChild(0)->getValueValue();
            $this->entities[$id] = new Entity($entry);
        }
    }
}
