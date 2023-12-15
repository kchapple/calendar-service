<?php

namespace App\Calendar;

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

    const DAY_START = 8;
    const DAY_END = 20;

    public static function getCalendarFreeTimes(Carbon $startDate, Carbon $endDate, string $timezone): array
    {
        // Need to copy date parameters because getCalendarBusyTimes() modifies dates TODO change to not modify inputs
        $busyPeriods = self::getCalendarBusyTimes($startDate->copy(), $endDate->copy());

        // Initialize free periods for the given date range, within the hosts work hours
        // Need to initialize using calendar tz and convert to person booking's timezone
        $freePeriods = [];
        $currentDate = Carbon::parse($startDate->toDateTimeLocalString(), self::getCalendarTimezone());
        while ($currentDate <= $endDate) {
            $startDateTime = $currentDate->copy()->hour(self::DAY_START)->setTimezone($timezone);
            $endDateTime = $currentDate->copy()->hour(self::DAY_END)->setTimezone($timezone);
            $freePeriods[] = [
                'start_date' => $startDateTime,
                'end_date' => $endDateTime,
            ];

            $currentDate->addDay();
        }

        foreach ($busyPeriods as $busyPeriodUnconverted) {
            // Convert busy period to the timezone of the person booking to compare with internal calendar
            $busyPeriod = [
                'start_date' => $busyPeriodUnconverted['start_date']->copy()->setTimezone($timezone),
                'end_date' => $busyPeriodUnconverted['end_date']->copy()->setTimezone($timezone)
            ];

            $updatedFreePeriods = [];
            foreach ($freePeriods as $freePeriod) {
                if ($freePeriod['start_date'] < $busyPeriod['start_date'] &&
                    $freePeriod['end_date'] <= $busyPeriod['end_date']) {
                    $updatedFreePeriods[] = $freePeriod;
                } elseif ($freePeriod['start_date'] >= $busyPeriod['end_date'] &&
                    $freePeriod['end_date'] >= $busyPeriod['start_date']) {
                    $updatedFreePeriods[] = $freePeriod;
                } else {
                    if ($freePeriod['start_date'] < $busyPeriod['start_date']) {
                        $updatedFreePeriods[] = [
                            'start_date' => $freePeriod['start_date'],
                            'end_date' => $busyPeriod['start_date']
                        ];
                    }
                    if ($freePeriod['end_date'] > $busyPeriod['end_date']) {
                        $updatedFreePeriods[] = [
                            'start_date' => $busyPeriod['end_date'],
                            'end_date' => $freePeriod['end_date']
                        ];
                    }
                }
            }

            $freePeriods = $updatedFreePeriods;
        }

        return $freePeriods;
    }

    public static function convertCarbonPeriodsToLocalStrings($periods)
    {
        foreach ($periods as &$period) {
            $period['start_date'] = $period['start_date']->toDateTimeString();
            $period['end_date'] = $period['end_date']->toDateTimeString();
        }

        return $periods;
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
