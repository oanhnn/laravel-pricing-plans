<?php

namespace Laravel\PricingPlans\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\PricingPlans\Contracts\Subscriber;
use Laravel\PricingPlans\Models\Concerns\Subscribable;

class User extends Model implements Subscriber
{
    use Subscribable;

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
