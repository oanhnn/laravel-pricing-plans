<?php

namespace Laravel\PricingPlans;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Lang;
use InvalidArgumentException;

class Period
{
    /**
     * The interval constants.
     */
    const DAY = 'day';
    const WEEK = 'week';
    const MONTH = 'month';
    const YEAR = 'year';

    /**
     * Map Interval to Carbon methods.
     *
     * @var array
     */
    protected static $intervalMapping = [
        self::DAY => 'addDays',
        self::WEEK => 'addWeeks',
        self::MONTH => 'addMonths',
        self::YEAR => 'addYears',
    ];

    /**
     * Starting date of the period.
     *
     * @var \Carbon\Carbon
     */
    protected $startAt;

    /**
     * Ending date of the period.
     *
     * @var \Carbon\Carbon
     */
    protected $endAt;

    /**
     * Interval
     *
     * @var string
     */
    protected $intervalUnit;

    /**
     * Interval count
     *
     * @var int
     */
    protected $intervalCount = 1;

    /**
     * Create a new Period instance.
     *
     * @param string $intervalUnit Interval Unit
     * @param int $intervalCount Interval count
     * @param null|string|int|\DateTime $startAt Starting point
     * @throws InvalidArgumentException
     */
    public function __construct(string $intervalUnit = 'month', int $intervalCount = 1, $startAt = null)
    {
        if ($startAt instanceof DateTime) {
            $this->startAt = Carbon::instance($startAt);
        } elseif (is_int($startAt)) {
            $this->startAt = Carbon::createFromTimestamp($startAt);
        } elseif (empty($startAt)) {
            $this->startAt = new Carbon();
        } else {
            $this->startAt = Carbon::parse($startAt);
        }

        if (!self::isValidIntervalUnit($intervalUnit)) {
            throw new InvalidArgumentException("Interval unit `{$intervalUnit}` is invalid");
        }

        $this->intervalUnit = $intervalUnit;

        if ($intervalCount >= 0) {
            $this->intervalCount = $intervalCount;
        }

        $this->calculate();
    }

    /**
     * Get start date.
     *
     * @return \Carbon\Carbon
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Get end date.
     *
     * @return \Carbon\Carbon
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * Get period interval.
     *
     * @return string
     */
    public function getIntervalUnit()
    {
        return $this->intervalUnit;
    }

    /**
     * Get period interval count.
     *
     * @return int
     */
    public function getIntervalCount()
    {
        return $this->intervalCount;
    }

    /**
     * Calculate the end date of the period.
     *
     * @return void
     */
    protected function calculate()
    {
        $method = $this->getMethod();
        $this->endAt = (clone $this->startAt)->$method($this->intervalCount);
    }

    /**
     * Get computation method.
     *
     * @return string
     */
    protected function getMethod()
    {
        return self::$intervalMapping[$this->intervalUnit];
    }

    /**
     * Get all available intervals.
     *
     * @return array
     */
    public static function getAllIntervals()
    {
        $intervals = [];

        foreach (array_keys(self::$intervalMapping) as $interval) {
            $intervals[$interval] = Lang::get('plans::messages.' . $interval);
        }

        return $intervals;
    }

    /**
     * Check if a given interval is valid.
     *
     * @param  string $intervalUnit
     * @return bool
     */
    public static function isValidIntervalUnit($intervalUnit): bool
    {
        return array_key_exists($intervalUnit, self::$intervalMapping);
    }
}
