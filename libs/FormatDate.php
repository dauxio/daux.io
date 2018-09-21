<?php namespace Todaymade\Daux;

use IntlDateFormatter;

class FormatDate
{
    public static function format($params, $date) {
        $locale = $params['language'];
        $datetype = IntlDateFormatter::LONG;
        $timetype = IntlDateFormatter::SHORT;
        $timezone = null;

        if (!extension_loaded("intl")) {
            $locale = 'en';
            $timezone = 'GMT';
        }

        $formatter = new IntlDateFormatter($locale, $datetype, $timetype, $timezone);

        return $formatter->format($date);
    }
}