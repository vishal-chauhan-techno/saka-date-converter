<?php

namespace Vishal\SakaDateConverter;

use Vishal\SakaDateConverter\GetFormattedDate;
use Vishal\SakaDateConverter\GetIndianCalendarDays;
use Vishal\SakaDateConverter\GetIndianCalendarFirstDayOfMonth;
use Vishal\SakaDateConverter\GetIndianCalendarMonth;
use Vishal\SakaDateConverter\GetIndianCalendarWeek;

class DateConverter
{
    private $weekDays = [];
    private $daysOfHinduMonth = [];
    private $beginningDayOfMonth = [];
    private $day;
    private $month;
    private $year;
    private $timeStamp;
    private $weekDay;
    private $shakaMonths = [];
    private $newYear;
    private $newMonth;
    private $newDay;

    public function __construct(string                           $date,
                                GetIndianCalendarMonth           $month,
                                GetIndianCalendarWeek            $week,
                                GetIndianCalendarDays            $days,
                                GetIndianCalendarFirstDayOfMonth $firstDay)
    {
        $this->timeStamp = strtotime($date);

        $dateFormatter = new GetFormattedDate($this->timeStamp);

        $this->weekDays = $week->getWeekDays();
        $this->daysOfHinduMonth = $days->getDays();
        $this->beginningDayOfMonth = $firstDay->getFirstDay();
        $this->shakaMonths = $month->getMonth();

        $this->day = (int)$dateFormatter->DateFormat("d");
        $this->month = (int)$dateFormatter->DateFormat("m");
        $this->year = (int)$dateFormatter->DateFormat("Y");
        $this->weekDay = (int)$dateFormatter->DateFormat("w");

        $this->createNewYear();
        $this->createNewMonth();
        $this->createNewDay();
    }

    /**
     * @return void
     */
    protected function createNewYear()
    {
        $yearBefore = $this->month < 3 || ($this->month == 3 && $this->day < $this->beginningDayOfMonth[3]);
        $this->newYear = $this->year - 78 + ($yearBefore ? -1 : 0);
    }

    /**
     * @return void
     */
    protected function createNewMonth()
    {
        $monthBefore = $this->day < $this->beginningDayOfMonth[$this->month];
        $this->newMonth = $this->month - 2 + ($monthBefore ? -1 : 0);
        if ($this->newMonth < 1) {
            $this->newMonth = 12 + $this->newMonth;
        }
        $this->newMonth -= 1;
    }

    /**
     * @return void
     */
    protected function createNewDay()
    {
        $this->newDay = $this->day - $this->beginningDayOfMonth[$this->month];
        if ($this->newDay < 1) {
            $this->newDay = $this->daysOfHinduMonth[$this->newMonth] + $this->newDay;
        }
        $this->newDay += 1;
    }

    /**
     * @return string
     */
    public function convertDateToHinduDate(): string
    {
        return $this->getWeekDay() . ", " . $this->getMonth() . ", " . $this->newDay . ", " . $this->newYear;
    }

    /**
     * @return string
     */
    protected function getWeekDay(): string
    {
        return $this->weekDays[$this->weekDay];
    }

    /**
     * @return string
     */
    protected function getMonth(): string
    {
        return $this->shakaMonths[$this->newMonth];
    }
}
