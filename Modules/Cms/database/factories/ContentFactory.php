<?php

namespace Modules\Cms\database\factories;

use Modules\Cms\Models\Content;
use Modules\Cms\Traits\ContentTrait;
use Illuminate\Database\Eloquent\Factories\Factory;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Cms\Enums\contents\BaseContentTypes;

class ContentFactory extends Factory
{
    use ContentTrait;
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Content::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // get random elemnt from $typeList form keys
        $type    = $this->faker->randomElement(array_keys(self::$typeList));

        if($type == BaseContentTypes::PAGES) return [];

        return [
            'type'              => $type,
            'can_be_deleted'    => 1,
            'link'              => self::typeHasField($type, 'link') ? $this->faker->url : null,
            'custom_properties' => self::typeHasField($type, 'select') ? ['placement' => 'home'] : null,
            'published_at'      => self::typeHasField($type, 'published_at') ? $this->faker->dateTimeBetween('-1 year', 'now') : null,
        ];
    }

    public function configure()
    {
        ini_set('memory_limit', '1024M');

        return $this->afterCreating(function (Content $content) {
            $type = $content->type;
            $transModel = [];

            $localeMapping = [
                'en' => 'en_US',
                'ar' => 'ar_SA',
            ];

            foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
                $faker = \Faker\Factory::create($localeMapping[$localeCode]);

                $transModel[$localeCode] = $content->translations()->create([
                    'locale'            => $localeCode,
                    'title'             => $faker->realText(20),
                    'short_description' => self::typeHasField($type, 'short_description') ? $faker->realText(100) : null,
                    'long_description'  => self::typeHasField($type, 'long_description') ? $faker->realText(150) : null,
                ]);
            }

            if (!self::typeHasField($type, 'image')) {
                return;
            }

            $randomImagePath = public_path('modules/admin/metronic/demo/media/products/'. rand(1, 22) .'.png');

            foreach ($transModel as $locale => $translation) {
                $translation->addMedia($randomImagePath)
                ->preservingOriginal()
                ->toMediaCollection(Content::MEDIA_COLLECTION);
            }
        });
    }
}

