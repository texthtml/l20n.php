<?php

namespace spec\th\l20n;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use th\l20n\Llk\Parser;
use th\l20n\Llk\Compiler;
use th\l20n\Llk\Node\Error\IndexError;
use th\l20n\Llk\Node\Error\ValueError;

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
        $this->entity('foo')->shouldReturn(null);
        $this->addResource('<foo "Bar">');
        $this->entity('taz')->shouldReturn(null);
    }

    public function it_should_return_known_entities()
    {
        $this->addResource('<foo "Bar">');
        $this->entity('foo')->shouldNotReturn(null);
    }

    public function it_should_handle_simple_values()
    {
        $this->addResource(self::getResource('simple_values'));

        $this->get('brandName')->shouldReturn('Firefox');

        $this->shouldThrow(new IndexError('Hash key lookup failed.'))->duringGet('brandName21');

        $this->get('brandName22')->shouldReturn('Aurora');

        $this->get('brandName23')->shouldReturn('Aurora');

        $this->shouldThrow(new IndexError('Hash key lookup failed (tried "neutral").'))->duringGet('brandName24');

        $this->get('brandName25')->shouldReturn('Aurora');
    }

    public function it_should_handle_attributes_basic_values()
    {
        $this->addResource(self::getResource('attributes'));

        $this->get('brandName1')->shouldReturn('Firefox');

        $this->get('about1')->shouldReturn('About Mozilla Firefox');

        $this->get('brandName2')->shouldReturn('Firefox');

        $this->get('about2')->shouldReturn('About Firefox on Windows');

        $this->get('about2Win')->shouldReturn('About Firefox on Windows');

        $this->get('about2Linux')->shouldReturn('About Firefox on Linux');
    }

    public function it_should_handle_attributes_relative_references()
    {
        $this->addResource(self::getResource('attributes'));

        $this->get('brandName3')->shouldReturn('Firefox');

        $this->get('about3')->shouldReturn('About Mozilla Firefox');

        $this->get('brandName4')->shouldReturn('Firefox');

        $this->get('about4')->shouldReturn('About Firefox on Windows');

        $this->get('about4Win')->shouldReturn('About Firefox on Windows');

        $this->get('about4Linux')->shouldReturn('About Firefox on Linux');

        $this->get('brandName5')->shouldReturn('Firefox');

        $this->get('brandName6')->shouldReturn('Firefox');

        $this->get('brandName7')->shouldReturn('Mozilla Firefox');
    }

    public function it_should_handle_attributes_indexes()
    {
        $this->addResource(self::getResource('attributes'));

        $this->get('brandName9')->shouldReturn('Firefox Beta');

        $this->get('about9')->shouldReturn('About Firefox Beta');

        $this->shouldThrow(new ValueError('Hash key lookup failed.'))->duringGet('about9Accesskey');
    }

    public function it_should_handle_attributes_cyclic_references()
    {
        $this->addResource(self::getResource('attributes'));

        $this->get('brandName10')->shouldReturn('Firefox');

        $this->get('about10')->shouldReturn('About Firefox on Windows');

        $this->shouldThrow(new ValueError('Cyclic reference detected.'))->duringGet('about11');
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

    public function it_should_handle_complex_strings()
    {
        $this->addResource(self::getResource('complex_strings'));

        $this->get('brandName')->shouldReturn('Firefox');

        $this->shouldThrow(new IndexError('Hash key lookup failed.'))->duringGet('brandNameHash');

        $this->shouldThrow(new IndexError('Hash key lookup failed.'))->duringGet('brandNameDeep');

        $this->shouldThrow(new IndexError('Hash key lookup failed.'))->duringGet('channels');

        $this->get('about1')->shouldReturn('About Firefox');

        $this->get('about2')->shouldReturn('About Firefox');

        $this->get('about3')->shouldReturn('About Aurora');

        $this->get('about4')->shouldReturn('About Firefox');

        $this->get('about5')->shouldReturn('About Aurora');

        $this->shouldThrow(new ValueError('Hash key lookup failed.'))->duringGet('about21');

        $this->shouldThrow(new ValueError('Hash key lookup failed.'))->duringGet('about22');

        $this->shouldThrow(new ValueError('Hash key lookup failed.'))->duringGet('about23');

        $this->shouldThrow(new ValueError('Hash key lookup failed.'))->duringGet('about24');

        $this->shouldThrow(new ValueError('Hash key lookup failed.'))->duringGet('about25');

        $this->shouldThrow(new ValueError('Hash key lookup failed.'))->duringGet('about26');

        $this->get('about27')->shouldReturn('About Mozilla Aurora\'s');

        $this->shouldThrow(new ValueError('Hash key lookup failed.'))->duringGet('about31');

        $this->get('about32')->shouldReturn('About Aurora');

        $this->get('about33')->shouldReturn('About Aurora');
    }

    protected static function getResource($name)
    {
        return function () use ($name) {
            return file_get_contents(__DIR__."/../../../bower_components/lol-fixtures/$name.lol");
        };
    }
}
