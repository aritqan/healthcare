<?php

namespace Modules\Zms\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Zms\Models\City;
use Modules\Zms\Models\State;
use Modules\Zms\Models\Country;
use Modules\Zms\Models\CityTranslation;
use Modules\Zms\Models\StateTranslation;
use Modules\Zms\Models\CountryTranslation;

class ZmsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedCountriesAndStatesAndCities();
    }

    public function seedCountriesAndStatesAndCities()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $countries  = Country::count();
        $states     = State::count();

        if ($countries > 0 || $states > 0) {
            return;
        }

        $countries              = require module_path('Zms', 'database/seederTemplates/countries.php');
        $countryTranslations    = require module_path('Zms', 'database/seederTemplates/country_translations.php');
        $states                 = require module_path('Zms', 'database/seederTemplates/states.php');
        $stateTranslations      = require module_path('Zms', 'database/seederTemplates/state_translations.php');
        $cities                 = require module_path('Zms', 'database/seederTemplates/cities.php');
        $cityTranslations       = require module_path('Zms', 'database/seederTemplates/city_translations.php');

        foreach(collect($countries)->chunk(100) as $chunkedCountries) {
            Country::insert($chunkedCountries->toArray());
        }
        foreach(collect($countryTranslations)->chunk(100) as $chunkedCountryTranslations) {
            CountryTranslation::insert($chunkedCountryTranslations->toArray());
        }
        foreach(collect($states)->chunk(100) as $chunkedStates) {
            State::insert($chunkedStates->toArray());
        }
        foreach(collect($stateTranslations)->chunk(100) as $chunkedStateTranslations) {
            StateTranslation::insert($chunkedStateTranslations->toArray());
        }
        foreach(collect($cities)->chunk(100) as $chunkedCities) {
            City::insert($chunkedCities->toArray());
        }
        foreach(collect($cityTranslations)->chunk(100) as $chunkedCityTranslations) {
            CityTranslation::insert($chunkedCityTranslations->toArray());
        }
    }
}
