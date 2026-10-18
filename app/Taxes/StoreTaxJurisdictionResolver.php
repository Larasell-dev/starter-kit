<?php

namespace App\Taxes;

use Larasell\Larasell\Contracts\TaxJurisdictionResolver;
use Larasell\Larasell\Taxes\TaxCalculationContext;
use Larasell\Larasell\Taxes\TaxJurisdiction;
use Larasell\Larasell\Taxes\TaxJurisdictionResolution;

/**
 * Taxes every order in the store's country, configured with `larasell.taxes.country`.
 */
class StoreTaxJurisdictionResolver implements TaxJurisdictionResolver
{
    public function resolve(TaxCalculationContext $context): TaxJurisdictionResolution
    {
        $country = strtoupper((string) config('larasell.taxes.country'));

        return TaxJurisdictionResolution::calculated(new TaxJurisdiction(
            identifier: $country,
            name: $country,
            country: $country,
        ));
    }
}
