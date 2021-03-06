<?php

namespace spec\th\l20n\Llk;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Hoa\Compiler\Visitor\Dump as ASTDumper;

class ParserSpec extends ObjectBehavior
{
    use \spec\th\l20n\Llk\ParserSpec\SimpleValues;
    use \spec\th\l20n\Llk\ParserSpec\Macros;

    public function it_is_initializable()
    {
        $this->shouldHaveType('th\l20n\Llk\Parser');
    }

    public function getMatchers()
    {
        return [
            'matchThisAST' => function ($subject, $referenceAST) {
                $dumper = new ASTDumper;

                $AST = trim($dumper->visit($subject));

                if ($referenceAST === $AST) {
                    return true;
                }

                $diff = new \Diff(
                    explode(PHP_EOL, $referenceAST),
                    explode(PHP_EOL, $AST)
                );

                echo $diff->render(new \Diff_Renderer_Text_Unified);

                return false;
            }
        ];
    }
}
