<?php

namespace Laravel\PricingPlans\Models\Concerns;

use Illuminate\Support\Facades\Config;

trait BelongsToPlanModel
{
    /**
     * Get plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(
            Config::get('plans.models.Plan'),
            Config::get('plans.tables.plans'),
            'plan_id',
            'id'
        );
    }

    /**
     * Scope by plan id.
     *
     * @param  \Illuminate\Database\Eloquent\Builder
     * @param  int $plan_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPlan($query, $plan_id)
    {
        return $query->where('plan_id', $plan_id);
    }
}
