<?php

namespace Laravel\PricingPlans\Models\Concerns;

use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;
use Laravel\PricingPlans\Period;

trait Resettable
{
    /**
     * Get Interval Name
     *
     * @return mixed string|null
     */
    public function getIntervalNameAttribute()
    {
        $intervals = Period::getAllIntervals();

        return (isset($intervals[$this->interval_unit]) ? $intervals[$this->interval_unit] : null);
    }

    /**
     * Get Interval Description
     *
     * @return string
     */
    public function getIntervalDescriptionAttribute()
    {
        return Lang::choice('plans::messages.interval_description.' . $this->interval_unit, $this->interval_count);
    }

    /**
     * @return bool
     */
    public function isResettable(): bool
    {
        return is_string($this->interval_unit) && is_int($this->interval_count);
    }

    /**
     * @param string|\Carbon\Carbon $startedAt
     * @return \Carbon\Carbon
     */
    public function getResetTime($startedAt = '')
    {
        if (empty($startedAt)) {
             $startedAt = new Carbon();
        }

        return (new Period($this->resettable_interval, $this->resettable_count, $startedAt))->getEndAt();
    }
}
