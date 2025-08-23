<?php

namespace Modules\Zms\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class ExportJsonToPhpFiles extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'export:json-to-php';

    /**
     * The console command description.
     */
    protected $description = 'Export JSON data to PHP files for seeding';


    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '9048M');
        set_time_limit(0);
        // get path from module admin / database / data / countries.json
        $jsonFile = module_path('Zms', 'database/data/countries.json');
        $batchSize = 100; // Number of records to process at a time

        $jsonString = file_get_contents($jsonFile);
        $data       = json_decode($jsonString, true);

        $batches = array_chunk($data, $batchSize);

        $countries              = [];
        $countryTranslations    = [];
        $states                 = [];
        $stateTranslations      = [];
        $cities                 = [];
        $cityTranslations       = [];

        foreach ($batches as $batch) {
            foreach ($batch as $countryData) {

                // Insert country
                $countries[] = [
                    'id'                => $countryData['id'],
                    'iso3'              => $countryData['iso3'],
                    'native_name'       => $countryData['name'],
                    'iso2'              => $countryData['iso2'],
                    'phone_code'        => $countryData['phone_code'],
                    'currency'          => $countryData['currency'],
                    'currency_symbol'   => $countryData['currency_symbol'],
                    'lat'               => $countryData['latitude'],
                    'lng'               => $countryData['longitude'],
                ];

                // Insert country translations
                $countryTranslations[] = [
                    'country_id'    => $countries[count($countries) - 1]['id'],
                    'locale'        => 'en',
                    'name'          => $countryData['name'],
                ];

                $countryTranslations[] = [
                    'country_id'    => $countries[count($countries) - 1]['id'],
                    'locale'        => 'ar',
                    'name'          => $countryData['translations']['fa'] ?? $countryData['name'],
                ];

                foreach ($countryData['states'] as $stateData) {
                    // Insert state
                    $states[] = [
                        'id'            => $stateData['id'],
                        'country_id'    => $countries[count($countries) - 1]['id'],
                        'native_name'   => $stateData['name'],
                        'lat'           => $stateData['latitude'],
                        'lng'           => $stateData['longitude'],
                    ];

                    // Insert state translation
                    $stateTranslations[] = [
                        'state_id'  => $states[count($states) - 1]['id'],
                        'locale'    => 'en',
                        'name'      => $stateData['name'],
                    ];

                    foreach ($stateData['cities'] as $cityData) {
                        // Insert city
                        $cities[] = [
                            'id'            => $cityData['id'],
                            'state_id'      => $states[count($states) - 1]['id'],
                            'native_name'   => $cityData['name'],
                            'lat'           => $cityData['latitude'],
                            'lng'           => $cityData['longitude'],
                        ];

                        // Insert city translation
                        $cityTranslations[] = [
                            'city_id'   => $cities[count($cities) - 1]['id'],
                            'locale'    => 'en',
                            'name'      => $cityData['name'],
                        ];
                    }
                }
            }
        }

        // Write to PHP files
        file_put_contents(module_path('Zms', 'database/seederTemplates/countries.php'), '<?php return ' . var_export($countries, true) . ';');
        file_put_contents(module_path('Zms', 'database/seederTemplates/country_translations.php'), '<?php return ' . var_export($countryTranslations, true) . ';');
        file_put_contents(module_path('Zms', 'database/seederTemplates/states.php'), '<?php return ' . var_export($states, true) . ';');
        file_put_contents(module_path('Zms', 'database/seederTemplates/state_translations.php'), '<?php return ' . var_export($stateTranslations, true) . ';');
        file_put_contents(module_path('Zms', 'database/seederTemplates/cities.php'), '<?php return ' . var_export($cities, true) . ';');
        file_put_contents(module_path('Zms', 'database/seederTemplates/city_translations.php'), '<?php return ' . var_export($cityTranslations, true) . ';');

        $this->info('Data exported successfully!');
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
