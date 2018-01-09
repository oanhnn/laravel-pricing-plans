<?php

namespace Laravel\PricingPlans\Tests\Unit;

use Carbon\Carbon;
use DateInterval;
use Laravel\PricingPlans\Tests\TestCase;

class CarbonTest extends TestCase
{
    /**
     * Test carbon now with provider function
     *
     * @param \DateTime $expected
     * @dataProvider carbonDataProvider
     */
    public function testCarbonNow($expected)
    {
        $now = Carbon::now();

        $this->assertEquals($expected->getTimestamp(), $now->getTimestamp());
        $this->assertEquals($expected->add(new DateInterval('P1D'))->getTimestamp(), $now->addDay(1)->getTimestamp());
    }

    /**
     * @return array
     */
    public function carbonDataProvider()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        return [
            [$now],
        ];
    }
}
