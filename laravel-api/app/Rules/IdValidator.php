<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IdValidator implements Rule
{
    protected $idType;

    public function __construct($idType)
    {
        $this->idType = $idType;
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
        switch ($this->idType) {
            case 'province':
                return $this->validateProvinceId($value);
            case 'district':
                return $this->validateDistrictId($value);
            default:
                return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is invalid for the specified ID type.';
    }

    /**
     * Validate province ID.
     */
    protected function validateProvinceId($value)
    {
        // Ensure the value is a string and is exactly 2 characters long
        if (!is_string($value) || strlen($value) !== 2) {
            return false;
        }

        // Check for non-numeric characters
        if (!ctype_digit($value)) {
            return false;
        }

        // Check if the value matches the pattern for IDs between '01' and '96'
        return preg_match('/^(0[1-9]|[1-8][0-9]|9[0-6])$/', $value);
    }

    /**
     * Validate district ID.
     */
    protected function validateDistrictId($value)
    {
        // Check if the value is numeric, has 4 or fewer characters, and contains no special characters
        return preg_match('/^\d{1,4}$/', $value);
    }
}
