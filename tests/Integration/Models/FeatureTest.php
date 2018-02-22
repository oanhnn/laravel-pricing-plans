<?php

namespace Laravel\PricingPlans\Tests\Integration\Models;

use Carbon\Carbon;
use DateInterval;
use Laravel\PricingPlans\Models\Feature;
use Laravel\PricingPlans\Tests\TestCase;

class FeatureTest extends TestCase
{
    /**
     * Test it can create feature
     */
    public function testItCanCreateAFeature()
    {
        /** @var \Laravel\PricingPlans\Models\Feature $feature1 */
        $feature1 = Feature::create([
            'name' => 'Upload images',
            'code' => 'upload-images',
            'description' => 'Can upload images in post',
            'interval_unit' => 'day',
            'interval_count' => 1,
            'sort_order' => 1,
        ]);

        /** @var \Laravel\PricingPlans\Models\Feature $feature2 */
        $feature2 = Feature::create([
            'name' => 'Upload video',
            'code' => 'upload-video',
            'description' => 'Can upload video in post',
            'interval_unit' => 'day',
            'interval_count' => 1,
            'sort_order' => 2,
        ]);

        /** @var \Laravel\PricingPlans\Models\Feature $feature3 */
        $feature3 = Feature::create([
            'name' => 'Comment',
            'code' => 'comment',
            'description' => 'Can comment on post',
            'sort_order' => 3,
        ]);

        $feature1->fresh();
        $feature2->fresh();
        $feature3->fresh();

        $this->assertEquals('Upload images', $feature1->name);
        $this->assertEquals('upload-images', $feature1->code);
        $this->assertTrue($feature2->isResettable());
        $this->assertFalse($feature3->isResettable());
    }

    /**
     * Test it can calculate reset time
     */
    public function testItCanCalculateResetTime()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        /** @var \Laravel\PricingPlans\Models\Feature $feature1 */
        $feature1 = Feature::create([
            'name' => 'Upload images',
            'code' => 'upload-images',
            'description' => 'Can upload images in post',
            'interval_unit' => 'day',
            'interval_count' => 1,
            'sort_order' => 1,
        ]);

        $feature1->fresh();

        $this->assertTrue($feature1->isResettable());
        $this->assertEquals('Day', $feature1->interval_name);
        $this->assertEquals('Daily', $feature1->interval_description);
        // Reset time without startAt parameter
        $this->assertEquals(
            (clone $now)->add(new DateInterval('P1D'))->getTimestamp(),
            $feature1->getResetTime()->getTimestamp()
        );

        $oneHourAgo = (clone $now)->sub(new DateInterval('PT1H'));

        // Reset time with startAt parameter
        $this->assertEquals(
            (clone $oneHourAgo)->add(new DateInterval('P1D'))->getTimestamp(),
            $feature1->getResetTime($oneHourAgo)->getTimestamp()
        );
    }
}
