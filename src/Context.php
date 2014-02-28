<?php

namespace th\l20n;

class Context
{
    private $defaultLocale = 'i-default';
    private $availableLocales = ['i-default'];
    private $requestedLocales;
    private $supportedLocales;
    
    private $localeNegotiator;
    
    public function supportedLocales()
    {
        if ($this->supportedLocales === null) {
            if (empty($this->availableLocales) || empty($this->requestedLocales)) {
                $this->supportedLocales = [$this->defaultLocale];
            } else {
                $this->supportedLocales = $this->localeNegotiator()->negotiateLocales(
                    $this->availableLocales,
                    $this->requestedLocales,
                    $this->defaultLocale
                );
            }
        }
        
        return $this->supportedLocales;
    }
    
    public function registerLocales($defaultLocale, Array $availableLocales)
    {
        $this->defaultLocale    = $defaultLocale;
        $this->availableLocales = $availableLocales;
        
        if (!in_array($this->defaultLocale, $this->availableLocales)) {
            $this->availableLocales[] = $this->defaultLocale;
        }
    }
    
    public function requestLocales()
    {
        $this->supportedLocales = null;
        $this->requestedLocales = func_get_args();
    }
    
    public function setLocaleNegotiator(localeNegotiator $localeNegotiator)
    {
        $this->localeNegotiator = $localeNegotiator;
    }
    
    public function localeNegotiator()
    {
        if ($this->localeNegotiator === null) {
            $this->localeNegotiator = new IntlLocaleNegotiator;
        }
        
        return $this->localeNegotiator;
    }
}
