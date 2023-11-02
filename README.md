# Saka Calendar Converter (Indian National Calendar)

## WARNING!!!!

This Package is currently in beta mode.

## Requirements

PHP 7.0 and later.

## Composer

You can install the bindings via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require vishal-chauhan-techno/saka-date-converter:0.1.0
```

```php
$get_date = new DateConverter("2023-11-02");
$date = $get_date->convertDateToHinduDate();

echo $date;  //Return Bṛhaspativāra, Kartika, 11, 1945
```