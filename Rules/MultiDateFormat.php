<?php

namespace Modules\Demowebinar\Rules;

use Illuminate\Contracts\Validation\Rule;

class MultiDateFormat implements Rule
{
    protected $formats;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($formats)
    {
        $this->formats = $formats;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // iterate through all formats
        foreach ($this->formats as $format) {

            // parse date with current format
            $parsed = date_parse_from_format($format, $value);

            // if value matches given format return true = validation succeeded 
            if ($parsed['error_count'] === 0 && $parsed['warning_count'] === 0) {
                return true;
            }
        }

        // value did not match any of the provided formats, so return false = validation failed
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This date format is not supported';
    }
}
