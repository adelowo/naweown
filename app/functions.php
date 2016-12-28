<?php

namespace Naweown;

use Carbon\Carbon;

if (!function_exists('Naweown\\carbon')) {

    /**
     * @param null $time
     * @return Carbon
     */
    function carbon($time = null)
    {
        if (null === $time) {
            return Carbon::now();
        }

        return Carbon::parse($time);
    }
}
