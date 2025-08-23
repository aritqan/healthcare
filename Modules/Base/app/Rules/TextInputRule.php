<?php

namespace Modules\Base\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TextInputRule implements ValidationRule
{
    private $min;
    private $max;

    public function __construct(int $min, int $max)
    {
        $this->min = $min;
        $this->max = $max;
    }
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) < $this->min || strlen($value) > $this->max) {
            $errorMessage = trans('validation.between.string', [
                'attribute' => $attribute,
                'min'       => $this->min,
                'max'       => $this->max,
            ]);

            $fail($errorMessage);
        }
    }

}
