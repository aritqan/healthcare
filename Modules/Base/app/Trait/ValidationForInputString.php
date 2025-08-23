<?php

namespace Modules\Base\Trait;

use Illuminate\Validation\Validator;
use Modules\Base\Rules\TextInputRule;

trait ValidationForInputString
{

    /**
     * @var int
     */
    private static $MIN_TEXT_CHARACTER_LENGTH;

    /**
     * @var int
     */
    private static $MAX_TEXT_CHARACTER_LENGTH;

    /**
     * @var int
     */
    private static $MIN_TEXTAREA_CHARACTER_LENGTH;

    /**
     * @var int
     */
    private static $MAX_TEXTAREA_CHARACTER_LENGTH;

    /**
     * @var int
     */
    private static $MIN_LONG_TEXT_CHARACTER_LENGTH;

    /**
     * @var int
     */
    private static $MAX_LONG_TEXT_CHARACTER_LENGTH;

    /**
     * Register the validation input size.
     *
     * @return null
     */
    private function initializeData()
    {
        self::$MIN_TEXT_CHARACTER_LENGTH        = config('base.input_size.text.min');

        self::$MAX_TEXT_CHARACTER_LENGTH        = config('base.input_size.text.max');

        self::$MIN_TEXTAREA_CHARACTER_LENGTH    = config('base.input_size.textarea.min');

        self::$MAX_TEXTAREA_CHARACTER_LENGTH    = config('base.input_size.textarea.max');

        self::$MIN_LONG_TEXT_CHARACTER_LENGTH   = config('base.input_size.long_text.min');

        self::$MAX_LONG_TEXT_CHARACTER_LENGTH   = config('base.input_size.long_text.max');

        return null;
    }

    /**
     * Get the locale key for validation input.
     *
     * @return string
     */
    private function getLocaleKey()
    {
        return config('admin.defalut_locale_key') ?? 'ar';
    }

    /**
     * Validate the string inputs either for at least one locale is required or for input size or both.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @param  array  $data
     * @param  string  $inputName
     * @param  bool  $atLeastOneLocaleWithSize
     * @param  bool  $textarea
     * @param  bool  $longText
     * @return null
     */
    public function validateBaseInput(Validator $validator, array $data, string $inputName, bool $atLeastOneLocaleWithSize = false, bool $textarea = false, bool $longText = false)
    {
        $this->initializeData();

        if($atLeastOneLocaleWithSize) {
            $this->validateAtLeastOneLocaleWithInputSize($validator, $data, $inputName, $textarea, $longText);
        } else {
            $this->validateInputSize($validator, $data, $inputName, $textarea, $longText);
        }

        return null;
    }

    /**
     * Validate the string inputs for at least one locale is required and input size.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @param  array  $data
     * @param  string  $inputName
     * @param  bool  $textarea
     * @param  bool  $longText
     * @return null
     */
    public function validateAtLeastOneLocaleWithInputSize(Validator $validator, array $data, string $inputName, bool $textarea = false, bool $longText = false)
    {
        $this->validateAtLeastOneLocale($validator, $data, $inputName);

        $this->validateInputSize($validator, $data, $inputName, $textarea, $longText);

        return null;
    }

    /**
     * Validate the string inputs for at least one locale is required.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @param  array  $data
     * @param  string  $inputName
     * @return null
     */
    public function validateAtLeastOneLocale(Validator $validator, array $data, string $inputName)
    {
        if(is_array($data)) {
            $is_valid = false;
            foreach($data as $key => $value) {
                if(!empty($value)) {
                    $is_valid = true;
                    break;
                }
            }

            if(!$is_valid) {
                $validator->errors()->add($inputName . '.' . $this->getLocaleKey(), trans('admin::validation.at_least_one_locale'));
            }
        }

        return null;
    }

    /**
     * Validate the string inputs for input size.
     *
     * @param  \Illuminate\\Validation\\Validator  $validator
     * @param  array  $data
     * @param  string  $inputName
     * @param  bool  $textarea
     * @param  bool  $longText
     * @return null
     */
    public function validateInputSize(Validator $validator, array $data, string $inputName, bool $textarea = false, bool $longText = false)
    {
        if($longText) {
            $minLength = self::$MIN_LONG_TEXT_CHARACTER_LENGTH;
            $maxLength = self::$MAX_LONG_TEXT_CHARACTER_LENGTH;
        } elseif($textarea) {
            $minLength = self::$MIN_TEXTAREA_CHARACTER_LENGTH;
            $maxLength = self::$MAX_TEXTAREA_CHARACTER_LENGTH;
        } else {
            $minLength = self::$MIN_TEXT_CHARACTER_LENGTH;
            $maxLength = self::$MAX_TEXT_CHARACTER_LENGTH;
        }

        if(is_array($data)) {
            foreach($data as $locale => $value) {
                if(!empty($value)) {
                    $inputRule = new TextInputRule($minLength, $maxLength);

                    $inputRule->validate($inputName, $value, function($message) use ($validator, $inputName, $locale) {
                        $validator->errors()->add($inputName . '.' . $locale, $message);
                    });
                }
            }
        }

        return null;
    }

    /**
     * Validate if data has fields without title field. if the title field is empty, the other fields should be empty.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @param  array  $data
     * @param  string $titleField
     * @param  array  $fields
     *
     * @return null
     */
    public function validateFieldsWithoutTitle(Validator $validator, array $data, string $titleField, array $fields)
    {
        if(is_array($data) && isset($data[$titleField])) {
            foreach($fields as $field) {
                if(isset($data[$field])) {
                    foreach($data[$field] as $locale => $value) {
                        if(empty($data[$titleField][$locale]) && !empty($value)) {
                            $validator->errors()->add($titleField . '.' . $locale, trans('admin::validation.cant_add_fields_without_title'));
                        }
                    }
                }
            }
        }

        return null;
    }
}
