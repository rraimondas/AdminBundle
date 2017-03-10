<?php

namespace Platform\Bundle\AdminBundle\Twig\Extension;

use Symfony\Component\Intl\Intl;

class CurrencyExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('sylius_currency_symbol', [$this, 'convertCurrencyCodeToSymbol']),
        ];
    }

    /**
     * @param string $code
     * 
     * @return null|string
     */
    public function convertCurrencyCodeToSymbol($code)
    {
        return Intl::getCurrencyBundle()->getCurrencySymbol($code);
    }
}
