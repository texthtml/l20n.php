<?php

namespace th\l20n;

class Context
{
    private $defaultLocale = 'i-default';
    private $availableLocales = ['i-default'];
    private $requestedLocales;
    private $supportedLocales;

    private $parser;
    private $compiler;
    private $localeNegotiator;

    private $catalogues = [];

    public function __construct(
        Parser $parser = null,
        Compiler $compiler = null,
        localeNegotiator $localeNegotiator = null
    ) {
        $this->parser           = $parser;
        $this->compiler         = $compiler;
        $this->localeNegotiator = $localeNegotiator;
    }

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

    public function localeNegotiator()
    {
        if ($this->localeNegotiator === null) {
            $this->localeNegotiator = new IntlLocaleNegotiator;
        }

        return $this->localeNegotiator;
    }

    public function parser()
    {
        if ($this->parser === null) {
            $this->parser = new Llk\Parser;
        }

        return $this->parser;
    }

    public function compiler()
    {
        if ($this->compiler === null) {
            $this->compiler = new Llk\Compiler;
        }

        return $this->compiler;
    }

    public function addResource($resource)
    {
        foreach ($this->availableLocales as $availableLocale) {
            if (!array_key_exists($availableLocale, $this->catalogues)) {
                $this->catalogues[$availableLocale] = new Catalog($this->parser(), $this->compiler());
            }

            if (is_string($resource)) {
                $this->catalogues[$availableLocale]->addResource($resource);
                continue;
            }

            $this->catalogues[$availableLocale]->addResource(
                function () use ($availableLocale, $resource) {
                    return $resource($availableLocale);
                }
            );
        }
    }

    public function catalog($locale)
    {
        if (array_key_exists($locale, $this->catalogues)) {
            return $this->catalogues[$locale];
        }
    }

    public function get($id, Array $data = [])
    {
        $locales = $this->supportedLocales();
        $locales[] = $this->defaultLocale;
        foreach ($locales as $locale) {
            $catalog = $this->catalog($locale);

            if ($catalog === null) {
                continue;
            }

            $entity = $catalog->getEntity($id);

            if ($entity !== null) {
                return $entity($catalog, $data);
            }
        }
    }
}
