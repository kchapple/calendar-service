<?php

namespace App\Services\Calendar;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CalendarService
{
    /**
     * Returns a collection of Calendar Busy times.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    public static function getCalendarBusyTimes(Carbon $startDate, Carbon $endDate)
    {
        $timezone = self::getCalendarTimezone();
        $period = CarbonPeriod::since($startDate->startOfHour()->tz($timezone))->hours(1)->until($endDate->tz($timezone));
        $hoursBusy = [8, 9, 12, 14, 16];

        $dates = [];

        foreach ($period as $date) {
            $isBusy = array_search($date->hour, $hoursBusy);

            if ($isBusy > -1) {
                $dates[] = [
                    'start_date' => $date,
                    'end_date' => $date->copy()->addHour(),
                ];
            }
        }

        return $dates;
    }

    /**
     * Returns the Calendar Timezone setting.
     *
     * @return string
     */
    public static function getCalendarTimezone()
    {
        return 'America/Los_Angeles';
    }
}
