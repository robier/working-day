<?php

namespace Robier\WorkingDay;

/**
 * Interface Provider
 *
 * Providing common interface for any day provider you can imagine. For example
 *  - week provider - can take max 7 specifications for a day and returns for every date provided different Day
 *  - odd week dates provider - only provides day if given date is an odd number
 *  - even week dates provider - only provides day if given date is an even number
 * ... and soo on.
 */
interface Provider
{
    /**
     * Gets instance of Day or null depending if provider can provide Day for asked date.
     *
     * @param Date $date
     *
     * @return null|Day
     */
    public function get(Date $date): ?Day;
}
