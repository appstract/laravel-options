<?php

namespace Appstract\Options;

use DateTime;

/**
 * Trait KeyValidMinutes
 *
 * @property int|string|float $validMinutes;
 */
trait ValidMinutesAttribute
{
    /**
     * @var float|int|string
     */
    private $minutes;

    /**
     * @return float|int|string
     */
    public function getValidMinutesAttribute()
    {
        return $this->minutes;
    }

    /**
     * @param float|int|string $minutes
     *
     * @return mixed
     */
    public function setValidMinutesAttribute($minutes)
    {
        return $this->setValidMinutes($minutes);
    }

    /**
     * @param \DateTime|int|float|string $minutes
     * @return $this
     * @throws \Exception
     */
    public function setValidMinutes($minutes)
    {
        if ($minutes instanceof DateTime) {
            $minutes = $minutes->diff(new DateTime('now'))->s;
            $minutes = (float)bcdiv($minutes, 60, 4);
        }

        $this->minutes = $minutes;

        return $this;
    }
}
