<?php

namespace Vishal\SakaDateConverter;

class GetIndianCalendarDays
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
    public function getDays(): array
    {
        return [1 => 30 + $this->IsLeapYear, 2 => 31, 3 => 31, 4 => 31, 5 => 31, 6 => 31, 7 => 30, 8 => 30, 9 => 30, 10 => 30, 11 => 30, 12 => 30];
    }
}