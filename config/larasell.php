<?php

use App\Taxes\GermanyTaxJurisdictionResolver;
use Larasell\Larasell\Enums\TaxPriceMode;
use Larasell\Larasell\Enums\TaxRoundingMode;
use Larasell\Larasell\Images\LqipPlaceholderGenerator;
use Larasell\Larasell\Taxes\ConfigTaxRateResolver;
use Larasell\Larasell\Taxes\ConfiguredTaxCalculator;

return [
    'taxes' => [
        'calculator' => ConfiguredTaxCalculator::class,
        'price_mode' => TaxPriceMode::Inclusive->value,
        'rounding' => TaxRoundingMode::HalfUp->value,
        'rounding_level' => 'line',
        'jurisdiction_resolver' => GermanyTaxJurisdictionResolver::class,
        'rate_resolver' => ConfigTaxRateResolver::class,
        'shipping_category' => 'shipping',
        'rates' => [
            'DE' => [
                'standard' => [
                    'identifier' => 'de-vat-standard',
                    'name' => 'German VAT',
                    'rate' => '19.0000',
                ],
                'reduced' => [
                    'identifier' => 'de-vat-reduced',
                    'name' => 'German reduced VAT',
                    'rate' => '7.0000',
                ],
                'zero' => [
                    'identifier' => 'de-vat-zero',
                    'name' => 'German zero-rated VAT',
                    'rate' => '0.0000',
                    'treatment' => 'zero_rated',
                ],
                'exempt' => [
                    'identifier' => 'de-vat-exempt',
                    'name' => 'German VAT exempt',
                    'rate' => '0.0000',
                    'treatment' => 'exempt',
                ],
                'shipping' => [
                    'identifier' => 'de-vat-shipping',
                    'name' => 'German shipping VAT',
                    'rate' => '19.0000',
                ],
            ],
        ],
    ],
    'images' => [
        'placeholder' => LqipPlaceholderGenerator::class,
    ],
];
