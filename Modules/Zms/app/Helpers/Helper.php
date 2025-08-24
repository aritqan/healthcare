<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Modules\Zms\Models\Country;

if(! function_exists('getCountryInfo')) {
    /**
     * Get the country information.
     */
    function getCountryInfo($iso3 = 'SAU', $default = 'Saudi Arabia')
    {
        $iso3 = strtoupper($iso3);

        $country = Cache::get($iso3);

        if(! Schema::hasTable('countries')) {
            return $default;
        }

        if(!$country) {
            $country = Country::where('iso3', $iso3)->first();

            Cache::forever($iso3, $country);
        }

        return $country;
    }
}

if(!function_exists('getCurreny')) {
    /**
     * Get the currency symbol.
     */
    function getCurreny($iso3 = 'SAU', $default = 'SAR')
    {
        $iso3 = strtoupper($iso3);

        $currency = Cache::get($iso3 . '_currency');

        if(! Schema::hasTable('countries')) {
            return $default;
        }

        if(!$currency) {
            $country    = getCountryInfo($iso3);
            $currency   = $country->currency;

            Cache::forever($iso3 . '_currency', $currency);
        }

        return $currency;
    }
}

if(! function_exists('getCountryPhoneCode')) {
    /**
     * Get the country phone code.
     */
    function getCountryPhoneCode($iso3 = 'SAU', $default = '966')
    {
        $iso3 = strtoupper($iso3);

        $phoneCode = Cache::get($iso3 . '_phone_code');

        if(! Schema::hasTable('countries')) {
            return $default;
        }

        if(!$phoneCode) {
            $country    = getCountryInfo($iso3);
            $phoneCode  = $country->phone_code;

            Cache::forever($iso3 . '_phone_code', $phoneCode);
        }

        return $phoneCode;
    }
}

if(!function_exists('getStatesForCountry')) {
    /**
     * Get the states for the country.
     */
    function getStatesForCountry($iso3 = 'SAU')
    {
        $iso3 = strtoupper($iso3);

        $states = Cache::get($iso3 . '_states');

        if(! Schema::hasTable('countries')) {
            return [];
        }

        if(!$states) {
            $country    = getCountryInfo($iso3);
            $states     = $country->states;

            Cache::forever($iso3 . '_states', $states);
        }

        return $states;
    }
}
