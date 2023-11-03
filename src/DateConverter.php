<?php

namespace VishalChauhanTechno\SakaDateConverter;

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

    /**
     * @param string $date Allowed format YYYY-MM-DD, DD-MM-YYYY and MM-DD-YYYY
     *
     * @see https://php.net/manual/en/datetime.format.php
     */
    public function __construct(string $date)
    {
        $this->timeStamp = strtotime($date);

        $month = new Helpers\GetIndianCalendarMonth();
        $week = new Helpers\GetIndianCalendarWeek();
        $days = new Helpers\GetIndianCalendarDays($this->timeStamp);
        $firstDay = new Helpers\GetIndianCalendarFirstDayOfMonth($this->timeStamp);

        $dateFormatter = new Helpers\GetFormattedDate($this->timeStamp);

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
     * @return string Format - Day, Month, Day, Year;
     */
    public function convertDateToHinduDate(): string
    {
        return $this->getWeekDay() . ", " . $this->getMonth() . ", " . $this->newDay . ", " . $this->newYear;
    }

    /**
     * @return string ["Ravivāra", "Somavāra", "Maṅgalavāra", "Budhavāra", "Bṛhaspativāra", "Śukravāra", "Śanivāra"]
     */
    public function getWeekDay(): string
    {
        return $this->weekDays[$this->weekDay];
    }

    /**
     * @return string ['Chhaitra', 'Vaishakha', 'Jyeshtha', 'Ashadha', 'Shravana', 'Bhaadra', 'Ashwin', 'Kartika', 'Agrahayana', 'Pausha', 'Magha', 'Phalguna']
     */
    public function getMonth(): string
    {
        return $this->shakaMonths[$this->newMonth];
    }

    /**
     * @return int
     */
    public function getDate(): string
    {
        return $this->newDay;
    }

    /**
     * @return int
     */
    public function getYear(): string
    {
        return $this->newYear;
    }
}
