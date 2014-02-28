<?php

namespace th\l20n;

interface LocaleNegotiator
{
    public function negotiateLocales(
        Array $availableLocales,
        Array $requestedLocales,
        $defaultLocale
    );
}
