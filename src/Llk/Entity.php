<?php

namespace th\l20n;

class Entityh
{
    private $ast;
    private $catalog;

    public function __construct(Hoa\Compiler\Llk\TreeNode $ast, Catalog $catalog)
    {
        $this->ast = $ast;
        $this->catalog = $catalog;
    }

    public function addResource($resource)
    {
        $this->resources[] = $resource;
    }

    public function getEntity($id)
    {
        if (!array_key_exists($id, $this->entities)) {
            $this->parseResources();
        }

        return $this->entities[$id];
    }

    public function parseResources()
    {
        while (!empty($this->resources)) {
            $resource = array_shift($this->resources);

            $lol = $this->parser->parse($resource);

            foreach ($lol->getChildren() as $entry) {
                $this->registerEntry($entry);
            }
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
