<?php

namespace th\l20n;

class Catalog
{
    private $parser;
    private $compiler;
    private $globalsExpressions;

    private $resources = [];

    public function __construct(Parser $parser, Compiler $compiler, Array $globalsExpressions)
    {
        $this->parser             = $parser;
        $this->compiler           = $compiler;
        $this->globalsExpressions = $globalsExpressions;
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

        if ($entity === null) {
            return null;
        }

        $context = new EntityContext($this, $entity, $data, $this->globalsExpressions);

        return $entity($context);
    }

    public function macro($id)
    {
        $macro = $this->compiler->macro($id);
        if ($macro === null) {
            $this->compile();
            $macro = $this->compiler->macro($id);
        }

        return $macro;
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

    public function setGlobalExpression($name, callable $globalExpression)
    {
        $this->globalsExpressions[$name] = $globalExpression;
    }
}
