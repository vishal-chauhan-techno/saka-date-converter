<?php

class DateConverterOld
{
    private $weekDays = [];
    protected $daysOfHinduMonth = [];
    protected $beginningDayOfMonth = [];
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
     * Initializes the DateConverter with a given Gregorian date.
     *
     * @param string $date A date in string format YYYY-MM-DD.
     */
    public function __construct(string $date)
    {
        $this->weekDays = ["Ravivāra", "Somavāra", "Maṅgalavāra", "Budhavāra", "Bṛhaspativāra", "Śukravāra", "Śanivāra"];
        $this->daysOfHinduMonth = [1 => 30 + ($this->dateIsLeapYear() ? 1 : 0), 2 => 31, 3 => 31, 4 => 31, 5 => 31, 6 => 31, 7 => 30, 8 => 30, 9 => 30, 10 => 30, 11 => 30, 12 => 30];
        $this->beginningDayOfMonth = [1 => 21, 2 => 20, 3 => 22 + ($this->dateIsLeapYear() ? -1 : 0), 4 => 21, 5 => 22, 6 => 22, 7 => 23, 8 => 23, 9 => 23, 10 => 23, 11 => 22, 12 => 22];
        $this->shakaMonths = ['Chhaitra', 'Vaishakha', 'Jyeshtha', 'Ashadha', 'Shravana', 'Bhaadra', 'Ashwin', 'Kartika', 'Agrahayana', 'Pausha', 'Magha', 'Phalguna'];

        $this->timeStamp = strtotime($date);
        $this->day = (int)date('d', $this->timeStamp);
        $this->month = (int)date('m', $this->timeStamp);
        $this->year = (int)date('Y', $this->timeStamp);
        $this->weekDay = (int)date("w", $this->timeStamp);

        $this->createNewYear();
        $this->createNewMonth();
        $this->createNewDay();
    }

    /**
     * Create saka year
     * @return void
     */
    protected function createNewYear()
    {
        $yearBefore = $this->month < 3 || ($this->month == 3 && $this->day < $this->beginningDayOfMonth[3]);
        $this->newYear = $this->year - 78 + ($yearBefore ? -1 : 0);
    }

    /**
     * Create saka month
     * @return void
     */
    protected function createNewMonth()
    {
        $monthBefore = $this->day < $this->beginningDayOfMonth[$this->month];
        $this->newMonth = $this->month - 2 + ($monthBefore ? -1 : 0);

        if ($this->newMonth < 1) $this->newMonth = 12 + $this->newMonth;

        $this->newMonth -= 1;
    }

    /**
     * Create saka day
     * @return void
     */
    protected function createNewDay()
    {
        $this->newDay = $this->day - $this->beginningDayOfMonth[$this->month];

        if ($this->newDay < 1) $this->newDay = $this->daysOfHinduMonth[$this->newMonth] + $this->newDay;

        $this->newDay += 1;
    }

    /**
     * Return the converted date in the format "Saka Day, Saka Month, Saka Day, Saka Year".
     *
     * @return string
     */
    public function convertDateToHinduDate(): string
    {
        return $this->getWeekDay() . ", " . $this->getMonth() . ", " . $this->newDay . ", " . $this->newYear;
    }

    /**
     * Check request date is from leap year or not
     * @return bool
     */
    protected function dateIsLeapYear(): bool
    {
        return (bool)date('L', $this->timeStamp);
    }

    /**
     * Get Indian National Calendar Day Name
     * @return string
     */
    protected function getWeekDay(): string
    {
        return $this->weekDays[$this->weekDay];
    }

    /**
     * Get Indian National Calendar Month Name
     * @return string
     */
    protected function getMonth(): string
    {
        return $this->shakaMonths[$this->newMonth];
    }
}

//Usage
$date = $_GET['date'];
$get_date = new DateConverter($date);
echo $get_date->convertDateToHinduDate();