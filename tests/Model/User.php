<?php

namespace Laravel\PricingPlans\Tests\Model;

use Illuminate\Database\Eloquent\Model;
use Laravel\PricingPlans\Contracts\PlanSubscriberInterface;
use Laravel\PricingPlans\Traits\PlanSubscriber;

class User extends Model implements PlanSubscriberInterface
{
    use PlanSubscriber;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
