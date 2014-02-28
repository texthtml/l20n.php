<?php

namespace spec\th\l20n;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use th\l20n\LocaleNegotiator;

class ContextSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('th\l20n\Context');
    }
    
    public function it_should_default_to_the_i_default_locale() {
        $this->supportedLocales()->shouldReturn(['i-default']);
    }
    
    public function it_shoud_be_able_to_negotiate_locales(LocaleNegotiator $negotiator)
    {
        $negotiator->negotiateLocales(['en-US', 'fr', 'pl'], ['fr-CA', 'fr'], 'en-US')
            ->shouldBecalled()
            ->willReturn(['fr']);
        
        $this->registerLocales('en-US', ['en-US', 'fr', 'pl']);
        $this->setLocaleNegotiator($negotiator);
        $this->requestLocales('fr-CA', 'fr');
        $this->supportedLocales()->shouldReturn(['fr']);
    }
    
    public function it_should_defaut_to_the_intl_locale_negotiator()
    {
        $this->localeNegotiator()->shouldReturnAnInstanceOf('th\l20n\IntlLocaleNegotiator');
    }
}
