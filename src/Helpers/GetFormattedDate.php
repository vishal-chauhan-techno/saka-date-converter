<?php

namespace Vishal\SakaDateConverter;

class GetFormattedDate
{

    private $timestamp;

    /**
     * @param string $timestamp
     */
    public function __construct(string $timestamp)
    {
        $this->timestamp = $timestamp;
    }


    /**
     * Returns the formatted date string on success or FALSE on failure.
     *
     * @see https://php.net/manual/en/datetime.format.php
     *
     * @param string $format
     *
     * @return string
     */
    public function DateFormat(string $format): string
    {
        return (string)date("$format", $this->timestamp);
    }

    /**
     * @return bool
     */
    public function isLeapYear(): bool
    {
        return (bool)date('L', $this->timestamp);
    }
}