<?php

namespace Platform\Bundle\AdminBundle\Twig\Extension;

use Symfony\Component\Intl\Currencies;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CurrencyExtension extends AbstractExtension
{
    /** {@inheritdoc} */
    public function getFilters(): array
    {
        return [
            new TwigFunction('sylius_currency_symbol', [$this, 'convertCurrencyCodeToSymbol']),
        ];
    }

    public function convertCurrencyCodeToSymbol(string $code): string
    {
        return Currencies::getSymbol($code);
    }
}
