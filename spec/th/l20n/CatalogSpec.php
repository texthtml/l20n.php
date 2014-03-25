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

        $this->beConstructedWith($parser, $compiler, []);
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

    public function it_should_handle_complex_cyclic_strings()
    {
        $this->addResource(self::getResource('complex_strings_cyclic'));

        $this->get('orgName')->shouldReturn('Mozilla');

        $this->get('brandName1')->shouldReturn('Mozilla Firefox');

        $this->get('about1')->shouldReturn('About Mozilla Firefox');

        $this->shouldThrow(new ValueError('Cyclic reference detected.'))->duringGet('brandName2');

        $this->shouldThrow(new ValueError('Cyclic reference detected.'))->duringGet('about2');

        $this->shouldThrow(new ValueError('Cyclic reference detected.'))->duringGet('about3');

        $this->get('brandName41')->shouldReturn('Firefox');

        $this->get('about41')->shouldReturn('About Firefox');

        $this->get('brandName42')->shouldReturn('Firefox');

        $this->get('about42')->shouldReturn('About Firefox');

        $this->get('brandName43')->shouldReturn('Firefox');

        $this->get('about43')->shouldReturn('About Firefox');

        $this->get('brandName44')->shouldReturn('Firefox');

        $this->get('about44')->shouldReturn('About Firefox');

        $this->shouldThrow(new IndexError('Hash key lookup failed.'))->duringGet('brandName51');

        $this->shouldThrow(new ValueError('Cyclic reference detected.'))->duringGet('about51');

        $this->shouldThrow(new IndexError('Hash key lookup failed.'))->duringGet('brandName52');

        $this->shouldThrow(new ValueError('Cyclic reference detected.'))->duringGet('about52');
    }

    public function it_should_handle_context_data()
    {
        $this->addResource(self::getResource('ctxdata'));

        $this->shouldThrow(new ValueError('Reference to an unknown variable: unreadNotifications.'))->duringGet('unread');

        $this->shouldThrow(new IndexError('Reference to an unknown variable: unreadNotifications.'))->duringGet('unreadPlural');

        $this->shouldThrow(new ValueError('Reference to an unknown variable: user.'))->duringGet('hello');

        $this->shouldThrow(new ValueError('Reference to an unknown variable: user.'))->duringGet('helloLast');


        $data = ['unreadNotifications' => '5'];

        $this->get('unread', $data)->shouldReturn('Unread notifications: 5');

        $this->shouldThrow(new IndexError('The == operator takes two numbers or two strings.'))->duringGet('unreadPlural', $data);


        $data = ['unreadNotifications' => 1];

        $this->get('unread', $data)->shouldReturn('Unread notifications: 1');

        $this->get('unreadPlural', $data)->shouldReturn('One unread notification');


        $data = ['unreadNotifications' => 4];

        $this->get('unreadPlural', $data)->shouldReturn('4 unread notifications');

        $data = ['user' => ['name' => 'Chuck Norris']];

        $this->get('hello', $data)->shouldReturn('Hello Chuck Norris!');

        $this->shouldThrow(new ValueError('Cannot get property of a string: last.'))->duringGet('helloLast', $data);


        $data = ['user' => (object) ['name' => 'Chuck Norris']];

        $this->get('hello', $data)->shouldReturn('Hello Chuck Norris!');

        $data = ['user' => (object) ['name' => (object) ['first' => 'Chuck', 'last' => 'Norris']]];

        $this->shouldThrow(new ValueError('Cannot resolve ctxdata or global of type object.'))->duringGet('hello', $data);

        $this->get('helloLast', $data)->shouldReturn('Hello Mr. Norris!');
    }

    public function it_should_handle_globals()
    {
        $this->addResource(self::getResource('globals'));

        $this->setGlobalExpression('hour', function () {
            return 8;
        });

        $this->get('theHourIs')->shouldReturn('It\'s 8');

        $this->get('greeting')->shouldReturn('Good morning');


        $this->setGlobalExpression('hour', function () {
            return 18;
        });

        $this->get('theHourIs')->shouldReturn('It\'s 18');

        $this->get('greeting')->shouldReturn('Good afternoon');

        $this->shouldThrow(new ValueError('Reference to an unknown global: one.'))->duringGet('one');

        $this->shouldThrow(new IndexError('Reference to an unknown global: one.'))->duringGet('whatIsIt');
    }

    public function it_should_handle_macros()
    {
        $this->addResource(self::getResource('macros'));

        $this->get('callDouble')->shouldReturn('6');

        $this->get('callZero')->shouldReturn('0');

        $this->get('callFib')->shouldReturn('6765');

        $this->get('callFac')->shouldReturn('120');

        $this->get('callPlural0')->shouldReturn('many');

        $this->get('callPlural1')->shouldReturn('one');

        $this->get('callPlural2')->shouldReturn('few');

        $this->get('callPlural5')->shouldReturn('many');

        $this->get('callPlural11')->shouldReturn('many');

        $this->get('callPlural22')->shouldReturn('few');

        $this->get('callPlural101')->shouldReturn('many');

        $this->get('callPlural102')->shouldReturn('few');

        $this->get('callPlural121')->shouldReturn('many');

        $this->get('callPlural122')->shouldReturn('few');

        $this->get('callPlural0')->shouldReturn('many');

        $this->shouldThrow(new ValueError('The || operator takes two booleans.'))->duringGet('callZeroOrFac');

        $this->get('callQuad')->shouldReturn('28');

        $this->get('callCall')->shouldReturn('120');

        $this->shouldThrow(new ValueError('Expected a macro, got a non-callable.'))->duringGet('callCallString');

        $this->get('callGet')->shouldReturn('Firefox');

        // $this->shouldThrow(new ValueError('Cannot get property of a string: nominative'))->duringGet('callGet1');

        // $this->shouldThrow(new ValueError('Cannot get property of a string: genetive'))->duringGet('callGet2');

        $this->get('callGetGenitive')->shouldReturn('Firefox\'s');

        $this->get('brandName')->shouldReturn('Firefox');

        $this->shouldThrow(new ValueError('getBrandName() takes exactly 1 argument(s) (0 given)'))->duringGet('callGetBrandName');

        $this->get('callGetBrandNameCase')->shouldReturn('Firefox\'s');

        $this->get('brandNameLength')->shouldReturn('Firefox');

        $this->get('callGetBrandNameLength1')->shouldReturn('Mozilla Firefox');

        // $this->shouldThrow(new ValueError('Cannot get property of a string: genetive'))->duringGet('callGetBrandNameLength2');

        $this->get('callGetBrandNameLengthGenitive')->shouldReturn('Mozilla Firefox\'s');

        $this->get('brandNameThis')->shouldReturn('Firefox');

        $this->get('callGetBrandNameThisLength1')->shouldReturn('Mozilla Firefox');

        // $this->shouldThrow(new ValueError('Cannot get property of a string: genetive'))->duringGet('callGetBrandNameThisLength2');

        $this->get('callGetBrandNameThisGenitive')->shouldReturn('Mozilla Firefox\'s');

        $this->get('callGetBrandNameThis1')->shouldReturn('Mozilla Firefox');

        $this->get('callGetBrandNameThis2')->shouldReturn('Mozilla Firefox\'s');
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

    protected static function getResource($name)
    {
        return function () use ($name) {
            return file_get_contents(__DIR__."/../../../bower_components/lol-fixtures/$name.lol");
        };
    }
}
