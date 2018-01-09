<?php

namespace Laravel\PricingPlans\Tests\Unit;

use Carbon\Carbon;
use Closure;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Laravel\PricingPlans\Period;
use Laravel\PricingPlans\Tests\TestCase;

class PeriodTest extends TestCase
{
    /**
     * Can get all intervals with translations.
     */
    public function testItCanGetAllIntervalsWithTranslations()
    {
        App::setLocale('en');
        $intervals = Period::getAllIntervals();
        $this->assertEquals('Month', $intervals['month']);

        App::setLocale('vi');
        $intervals = Period::getAllIntervals();
        $this->assertEquals('Ngày', $intervals['day']);
    }

    /**
     * Can calculate a period.
     *
     * @param string $unit
     * @param int $count
     * @param mixed $start
     * @param \DateTime $expectedStartAt
     * @param \DateTime $expectedEndAt
     * @dataProvider periodDataProvider
     */
    public function testItCanCalculateAPeriod($unit, $count, $start, $expectedStartAt, $expectedEndAt)
    {
        $period = new Period($unit, $count, $start);

        $this->assertSame($unit, $period->getIntervalUnit());
        $this->assertSame($count, $period->getIntervalCount());
        $this->assertTimeRange($expectedStartAt, $expectedEndAt, $period);
    }

    /**
     * @return array
     */
    public function periodDataProvider()
    {
        $st1 = new DateTime('2018-01-04 10:00:09');
        $st2 = new DateTime('2018-01-04 10:10:09');
        $st3 = new DateTime('2018-01-04 10:20:19');

        return [
            // [ $unit, $count, $startAt, $expectedStartAt, $expectedEndAt],
            ['day',   1, $st1,                 $st1, (clone $st1)->add(new DateInterval('P1D')) ],
            ['day',   2, $st2->getTimestamp(), $st2, (clone $st2)->add(new DateInterval('P2D')) ],
            ['day',   3, $st3->format('c'),    $st3, (clone $st3)->add(new DateInterval('P3D')) ],
            ['week',  1, $st1,                 $st1, (clone $st1)->add(new DateInterval('P7D'))],
            ['week',  2, $st2->getTimestamp(), $st2, (clone $st2)->add(new DateInterval('P14D'))],
            ['week',  3, $st3->format('c'),    $st3, (clone $st3)->add(new DateInterval('P21D'))],
            ['month', 1, $st1,                 $st1, (clone $st1)->add(new DateInterval('P1M')) ],
            ['month', 2, $st2->getTimestamp(), $st2, (clone $st2)->add(new DateInterval('P2M')) ],
            ['month', 3, $st3->format('c'),    $st3, (clone $st3)->add(new DateInterval('P3M')) ],
            ['year',  1, $st1,                 $st1, (clone $st1)->add(new DateInterval('P1Y')) ],
            ['year',  2, $st2->getTimestamp(), $st2, (clone $st2)->add(new DateInterval('P2Y')) ],
            ['year',  3, $st3->format('c'),    $st3, (clone $st3)->add(new DateInterval('P3Y')) ],
        ];
    }

    /**
     * Can calculate a period without startAt.
     */
    public function testItCanCalculateAPeriodWithEmptyStart()
    {
        $testNow = Carbon::now();

        $this->wrapWithTestNow(function ($now) {
            // Case: start at is NULL
            $this->testItCanCalculateAPeriod('day', 1, null, $now, (clone $now)->add(new DateInterval('P1D')));

            // Case: start at is empty string
            $this->testItCanCalculateAPeriod('month', 2, '', $now, (clone $now)->add(new DateInterval('P2M')));
        }, $testNow);
    }

    /**
     * It throws exception when a invalid feature is passed.
     */
    public function testItThrowExceptionOnInvalidInterval()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Interval unit `dummy` is invalid');
        new Period('dummy');
    }

    /**
     * It can validate interval unit
     */
    public function testItCanValidateIntervalUnit()
    {
        $this->assertTrue(Period::isValidIntervalUnit('day'));
        $this->assertTrue(Period::isValidIntervalUnit('month'));
        $this->assertTrue(Period::isValidIntervalUnit('week'));
        $this->assertTrue(Period::isValidIntervalUnit('year'));

        $this->assertfalse(Period::isValidIntervalUnit(''));
        $this->assertfalse(Period::isValidIntervalUnit('date'));
        $this->assertfalse(Period::isValidIntervalUnit('minute'));
        $this->assertfalse(Period::isValidIntervalUnit('days'));
        $this->assertfalse(Period::isValidIntervalUnit('wwek'));
        $this->assertfalse(Period::isValidIntervalUnit('months'));
        $this->assertfalse(Period::isValidIntervalUnit(5));
    }

    /**
     * @param Closure $func
     * @param Carbon|null $dt
     */
    protected function wrapWithTestNow(Closure $func, Carbon $dt = null)
    {
        Carbon::setTestNow($dt ?: Carbon::now());
        $func(Carbon::getTestNow());
        Carbon::setTestNow();
    }

    /**
     * @param DateTime $expectedStartAt
     * @param DateTime $expectedEndAt
     * @param Period $period
     */
    protected function assertTimeRange(DateTime $expectedStartAt, DateTime $expectedEndAt, Period $period)
    {
        $this->assertEquals($expectedStartAt->getTimestamp(), $period->getStartAt()->getTimestamp());
        $this->assertEquals($expectedEndAt->getTimestamp(), $period->getEndAt()->getTimestamp());
    }
}
