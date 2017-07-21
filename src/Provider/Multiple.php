<?php

namespace Robier\WorkingDay\Provider;

use Robier\WorkingDay\Date;
use Robier\WorkingDay\Day;
use Robier\WorkingDay\Provider;

class Multiple implements Provider
{
    protected $providers;

    public function __construct(Provider $provider, Provider ...$providers)
    {
        array_unshift($providers, $provider);

        $this->providers = $providers;
    }

    /**
     * Gets instance of Day or null depending if any provider can provide Day for asked date.
     *
     * @param Date $date
     *
     * @return null|Day
     */
    public function get(Date $date): ?Day
    {
        foreach ($this->providers as $provider) {
            $day = $provider->get($date);

            if (null !== $day) {
                return $day;
            }
        }

        return null;
    }
}
