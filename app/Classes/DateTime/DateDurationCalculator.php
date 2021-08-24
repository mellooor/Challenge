<?php

namespace App\DateTime;

use \DateInterval;

class DateDurationCalculator
{
    /**
     * Calculates the difference in days between 2 dates and returns the difference as an integer.
     *
     * @param DateInterval $dateInterval - The date interval to be formatted.
     * @return int - The difference between the 2 dates. Will return minus values if the first date occurs later than the second date.
     */
    public function calculateDays(DateInterval $dateInterval)
    {
        return intval($dateInterval->format('%r%d'));
    }
}