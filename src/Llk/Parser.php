<?php

namespace th\l20n\Llk;

use th\l20n\Parser as l20nParser;
use Hoa\File\Read as FileReader;
use Hoa\Compiler\Llk\Llk as Compiler;
use Hoa\Compiler\Llk\TreeNode as AST;

class Parser implements l20nParser
{

    private $ppCompiler;

    public function ppCompiler()
    {
        if ($this->ppCompiler === null) {
            $this->ppCompiler = Compiler::load(new FileReader('lol.pp'));
        }

        return $this->ppCompiler;
    }

    public function parse($string)
    {
        return $this->ppCompiler()->parse($string, 'lol');
    }
}
