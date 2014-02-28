<?php

namespace spec\th\l20n;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IntlLocaleNegotiatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('th\l20n\IntlLocaleNegotiator');
    }
    
    public function it_should_fallback_on_default_locale()
    {
        $this->negotiateLocales([], [], 'i-default')->shouldReturn(['i-default']);
        $this->negotiateLocales([], [], 'fr')->shouldReturn(['fr']);
        $this->negotiateLocales(['de'], ['i-default'], 'fr')->shouldReturn(['fr']);
    }
    
    public function it_should_return_the_requested_locales_availlable_in_the_requested_order()
    {
        $this->negotiateLocales(['fr'], ['fr'], 'i-default')->shouldReturn(['fr']);
        $this->negotiateLocales(['fr'], ['en', 'fr'], 'i-default')->shouldReturn(['fr']);
        $this->negotiateLocales(['en', 'fr'], ['fr'], 'i-default')->shouldReturn(['fr']);
        $this->negotiateLocales(['fr', 'en'], ['en', 'fr'], 'i-default')->shouldReturn(['en', 'fr']);
    }
    
    public function it_should_match_languages_with_locales()
    {
        $this->negotiateLocales(['en-us'], ['en'], 'i-default')->shouldReturn(['en-us']);
    }
    
    public function it_should_return_all_matching_locales()
    {
        $this->negotiateLocales(['en-us', 'en-gb'], ['en'], 'i-default')->shouldReturn(['en-us', 'en-gb']);
    }
    
    public function it_should_return_locales_only_once()
    {
        $this->negotiateLocales(['en-us', 'en-gb'], ['en', 'en-us'], 'i-default')->shouldReturn(['en-us', 'en-gb']);
        $this->negotiateLocales(['en-us', 'en-us', 'en-gb'], ['en', 'en-us'], 'i-default')->shouldReturn(['en-us', 'en-gb']);
    }
    
    public function it_should_not_match_sub_locales()
    {
        $this->negotiateLocales(['en-us', 'en-gb'], ['en-us'], 'i-default')->shouldReturn(['en-us']);
    }
}
