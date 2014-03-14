<?php

namespace spec\th\l20n;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use th\l20n\Llk\Parser;
use th\l20n\Llk\Compiler;

class CatalogSpec extends ObjectBehavior
{
    public function let()
    {
        $parser   = new Parser;
        $compiler = new Compiler;

        $this->beConstructedWith($parser, $compiler);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('th\l20n\Catalog');
    }

    public function it_should_return_null_for_unknown_entities()
    {
        $this->getEntity('foo')->shouldReturn(null);
        $this->addResource('<foo "Bar">');
        $this->getEntity('taz')->shouldReturn(null);
    }

    public function it_should_return_known_entities()
    {
        $this->addResource('<foo "Bar">');
        $this->getEntity('foo')->shouldNotReturn(null);
    }

    public function it_should_handle_simple_values()
    {
        $this->addResource(self::getResource('simple_values'));

        $this->get('brandName')->shouldReturn('Firefox');

        $this->shouldThrow('th\l20n\Llk\Node\Exception\IndexError')->duringGet('brandName21');

        $this->get('brandName22')->shouldReturn('Aurora');

        $this->get('brandName23')->shouldReturn('Aurora');

        $this->shouldThrow('th\l20n\Llk\Node\Exception\IndexError')->duringGet('brandName24');

        $this->get('brandName25')->shouldReturn('Aurora');
    }

    public function it_should_handle_attr_and_indexes()
    {
        $this->addResource(self::getResource('attr-indexes'));

        $this->get('brandName7')->shouldReturn('Firefox');

        $this->get('about7')->shouldReturn('About Firefox on Linux');

        $this->get('about7Win')->shouldReturn('About Firefox on Windows');

        $this->get('about7Linux')->shouldReturn('About Firefox on Linux');

        $this->get('brandName8')->shouldReturn('Firefox Beta');

        $this->get('about8')->shouldReturn('About Firefox Beta');

        $this->get('about8Accesskey')->shouldReturn('Press A');
    }

    protected static function getResource($name)
    {
        return function() use ($name) {
            return file_get_contents(__DIR__."/../../../bower_components/lol-fixtures/$name.lol");
        };
    }
}
