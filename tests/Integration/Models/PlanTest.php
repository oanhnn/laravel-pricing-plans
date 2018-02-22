<?php

namespace Laravel\PricingPlans\Tests\Integration\Models;

use Laravel\PricingPlans\Models\Feature;
use Laravel\PricingPlans\Models\Plan;
use Laravel\PricingPlans\Tests\TestCase;

class PlanTest extends TestCase
{
    /**
     * It can create a plan and attach features
     */
    public function testItCanCreateAPlanAndAttachFeatures()
    {
        /** @var \Laravel\PricingPlans\Models\Plan $plan */
        $plan = Plan::create([
            'name' => 'Pro',
            'code' => 'pro',
            'description' => 'Pro plan',
            'price' => 19.9,
            'interval_unit' => 'month',
            'interval_count' => 1,
            'trial_period_days' => 15,
            'sort_order' => 1,
        ]);

        $feature1 = Feature::create([
            'name' => 'Upload images',
            'code' => 'upload-images',
            'description' => 'Can upload images in post',
            'interval_unit' => 'day',
            'interval_count' => 1,
            'sort_order' => 1,
        ]);

        $feature2 = Feature::create([
            'name' => 'Upload video',
            'code' => 'upload-video',
            'description' => 'Can upload video in post',
            'interval_unit' => 'day',
            'interval_count' => 1,
            'sort_order' => 2,
        ]);

        $feature3 = Feature::create([
            'name' => 'Comment',
            'code' => 'comment',
            'description' => 'Can comment on post',
            'sort_order' => 3,
        ]);

        $plan->features()->attach($feature1->id, [
            'value' => 5,
            'note' => 'Can upload 5 images daily',
        ]);

        $plan->features()->attach([
            $feature2->id => ['value' => 1, 'note' => 'Can upload 1 video daily'],
            $feature3->id => ['value' => 'YES', 'note' => ''],
        ]);

        // Reload from DB
        $plan->fresh();

        $this->assertEquals('Pro', $plan->name);
        $this->assertEquals('pro', $plan->code);
        $this->assertEquals(3, $plan->features->count());
    }
}
