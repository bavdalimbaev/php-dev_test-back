<?php

namespace App\Utils\App\Core;

use Carbon\Carbon;

class DateHandler
{
    const MONTH_LIST_RU = [1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель', 5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август', 9 => 'Сентябрь', 10 => 'Октябрь',
        11 => 'Ноябрь',
        12 => 'Декабрь'
    ];


    public static function addDays(int $days, Carbon $dateTime = null): Carbon
    {
        if (is_null($dateTime)) {
            $dateTime = now();
        }

        return $dateTime->addDays($days);
    }


    public static function getPastYearsRange(int $range = 10): array
    {
        $year = date('Y');
        if ($range == 0) {
            return [(int)$year];
        }
        $years = [];
        for ($i = 0; $i < $range; $i++) {
            $years[] = (int)$year--;
        }

        return $years;
    }


    public static function getDiffDays($startDate, $endDate): int
    {
        $startDateFormat = DateHandler::dateFormat($startDate, 'dash');
        $endDateFormat = DateHandler::dateFormat($endDate, 'dash');
        if (!$startDateFormat || !$endDateFormat) {
            return 0;
        }
        $days = (date_diff(date_create($startDateFormat), date_create(DateHandler::dateFormat($endDateFormat, 'dash')))->days);
        if ($days) {
            return $days;
        }


        return 0;
    }


    public static function getMonthList(): array
    {
        return self::MONTH_LIST_RU;
    }


    public static function word($num, $words)
    {

        $n = $num;
        $num = $num % 100;
        if ($num > 19) {
            $num = $num % 10;
        }

        $res = match ($num) {
            1 => $words[0],
            2, 3, 4 => $words[1],
            default => $words[2],
        };

        return $n . ' ' . $res;
    }


    public static function format($days, $params = [], $return = null)
    {
        $convert = $days;

        $daysInYear = 365.25;
        $daysInMonth = 30.4375;

        $inYears = intval($convert / $daysInYear);
        $inMonths = intval($convert / $daysInMonth);
        $inDays = $convert;

        $years = intval($convert / $daysInYear);

        $convert = $convert - $years * $daysInYear;

        $months = intval($convert / $daysInMonth);

        $days = intval($convert - $months * $daysInMonth);

        $result = [];
        $result['years'] = 0;
        $result['months'] = 0;
        $result['days'] = 0;

        if (!$days && !$months && !$years) {
            $result['resultString'] = $return;

            return $result;
        }

        $str = null;

        if ($years) {
            $str .= self::word($years, array('год', 'года', 'лет')) . ' ';
            if (!empty($params) && in_array('year', $params)) {
                $result['years'] = $inYears;
            }
        }

        if ($months) {
            $str .= self::word($months, array('месяц', 'месяца', 'месяцев')) . ' ';
            if (!empty($params) && in_array('month', $params)) {
                $result['months'] = $inMonths;
            }
        }

        if (!empty($params) && in_array('days', $params)) {
            $result['days'] = $inDays;
        }

        $result['resultString'] = $str . self::word($days, array('день', 'дня', 'дней'));

        return $result;
    }


    public static function dateFormat($dateString, $format = 'H:i,d.m.Y')
    {
        if ($dateString instanceof \DateTime) {
            return $dateString->format($format);
        }

        if (is_string($dateString)) {
            $date = strtotime($dateString);

            return date($format, $date);
        }

        return null;
    }


    public static function createFromFormat($dateString, string $format)
    {
        return Carbon::createFromFormat($format, $dateString);
    }


    public static function carbonFormat(Carbon $dateTime, $format = 'H:i,d.m.Y', $withTz = false): string
    {
        $str = $dateTime->format($format);

        if ($withTz) {
            $str = $str . ' ' . $dateTime->tzName;
        }

        return $str;
    }
}

