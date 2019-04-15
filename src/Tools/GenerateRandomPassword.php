<?php

namespace Orchestra\Foundation\Tools;

use Illuminate\Support\Str;

class GenerateRandomPassword
{
    /**
     * Generate random password.
     *
     * @param  int $length
     *
     * @return string
     */
    public function __invoke(int $length = 6): string
    {
        return Str::random($length);
    }
}
