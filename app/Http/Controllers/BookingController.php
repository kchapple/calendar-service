<?php


namespace App\Http\Controllers;

use App\Calendar\CalendarService;
use App\Http\Requests\BookingRequest;
use Carbon\Carbon;

class BookingController
{
    public function handleBookingRequest(BookingRequest $request): array
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $timezone = $request->input('timezone');

        $freeTimes = CalendarService::getCalendarFreeTimes(
            Carbon::parse($startDate, $timezone)->startOfDay(),
            Carbon::parse($endDate, $timezone)->endOfDay(),
            $timezone
        );

        return CalendarService::convertCarbonPeriodsToLocalStrings($freeTimes);
    }
}
