<?php

namespace th\l20n;

class IntlLocaleNegotiator implements LocaleNegotiator
{
    public function negotiateLocales(
        Array $availableLocales,
        Array $requestedLocales,
        $defaultLocale
    ) {
        $validLocales = [];

        foreach ($requestedLocales as $requestedLocale) {
            $validLocales += array_filter($availableLocales, function ($availableLocale) use ($requestedLocale) {
                return locale_filter_matches($availableLocale, $requestedLocale);
            });
        }

        return empty($validLocales) ? [$defaultLocale] : array_values(array_unique($validLocales));
    }
}
