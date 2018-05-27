<?php

namespace Laravel\PricingPlans\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasCode
{
    /**
     * @param Builder $query
     * @param string $code
     * @return Builder
     */
    public function scopeCode(Builder $query, string $code)
    {
        return $query->where('code', $code);
    }
}
