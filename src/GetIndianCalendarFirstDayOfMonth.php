<?php

namespace Vishal\SakaDateConverter;

class GetIndianCalendarFirstDayOfMonth
{
    private $IsLeapYear;

    public function __construct(string $timestamp)
    {
        $GetLeapYear = new GetFormattedDate($timestamp);

        $this->IsLeapYear = $GetLeapYear->isLeapYear();
    }

    /**
     * @return string[]
     */
    public function getFirstDay(): array
    {
        return [1 => 21, 2 => 20, 3 => 22 + $this->IsLeapYear, 4 => 21, 5 => 22, 6 => 22, 7 => 23, 8 => 23, 9 => 23, 10 => 23, 11 => 22, 12 => 22];
    }
}