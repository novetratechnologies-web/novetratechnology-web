<?php

namespace App\Support;

use App\Models\Country;
use libphonenumber\PhoneNumberUtil;

class CountryPhoneCode
{
    public static function for(Country $country): string
    {
        $region = strtoupper((string) $country->short_name);
        $code = $region !== '' ? PhoneNumberUtil::getInstance()->getCountryCodeForRegion($region) : 0;

        return $code > 0 ? (string) $code : ltrim((string) $country->tel_prefix, '+');
    }
}
