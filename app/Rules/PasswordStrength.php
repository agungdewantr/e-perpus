<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PasswordStrength implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        // Pastikan panjang password mencapai minimum tertentu
        if (strlen($value) < 8) {
            return false;
        }

        // Pastikan ada setidaknya satu huruf besar
        if (!preg_match('/[A-Z]/', $value)) {
            return false;
        }

        // Pastikan ada setidaknya satu huruf kecil
        if (!preg_match('/[a-z]/', $value)) {
            return false;
        }

        // Pastikan ada setidaknya satu karakter khusus (misalnya: @, #, $, dll.)
        if (!preg_match('/[@$!#%*?&]/', $value)) {
            return false;
        }

        return true;
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Password harus mengandung huruf besar, huruf kecil, dan karakter khusus.(@$!#%*?&)';
    }
}
