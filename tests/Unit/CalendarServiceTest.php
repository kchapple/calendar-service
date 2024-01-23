<?php

namespace Tests\Unit;

use App\Calendar\CalendarService;
use Carbon\Carbon;
use Tests\TestCase;

class CalendarServiceTest extends TestCase
{
    public function testGetCalendarFreeTimes()
    {
        // Arrange
        $timezone = 'America/Los_Angeles';
        $startDate = Carbon::parse('2023-12-14', $timezone)->startOfDay();
        $endDate = Carbon::parse('2023-12-14', $timezone)->endOfDay();

        // Act
        $freeTimes = CalendarService::getCalendarFreeTimes($startDate, $endDate, $timezone);

        // Assert
        $expectedOutput = [
            [
                'start_date' => Carbon::parse('2023-12-14T10:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-14T12:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-14T13:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-14T14:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-14T15:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-14T16:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-14T17:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-14T20:00:00', $timezone),
            ],
        ];
        $this->assertCarbonEquals($expectedOutput, $freeTimes);
    }

    public function testGetCalendarFreeTimesDifferentTimezone()
    {
        // Arrange
        $timezone = 'America/New_York';
        $startDate = Carbon::parse('2023-12-14', $timezone)->startOfDay();
        $endDate = Carbon::parse('2023-12-14', $timezone)->endOfDay();

        // Act
        $freeTimes = CalendarService::getCalendarFreeTimes($startDate, $endDate, $timezone);

        // Assert
        $expectedOutput = [
            [
                'start_date' => Carbon::parse('2023-12-14T13:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-14T15:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-14T16:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-14T17:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-14T18:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-14T19:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-14T20:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-14T23:00:00', $timezone),
            ],
        ];
        $this->assertCarbonEquals($expectedOutput, $freeTimes);
    }

    public function testGetCalendarFreeTimesDifferentTimezoneMultipleDays()
    {
        // Arrange
        $timezone = 'America/New_York';
        $startDate = Carbon::parse('2023-12-14', $timezone)->startOfDay();
        $endDate = Carbon::parse('2023-12-16', $timezone)->endOfDay();

        // Act
        $freeTimes = CalendarService::getCalendarFreeTimes($startDate, $endDate, $timezone);

        // Assert
        $expectedOutput = [
            [
                'start_date' => Carbon::parse('2023-12-14T13:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-14T15:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-14T16:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-14T17:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-14T18:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-14T19:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-14T20:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-14T23:00:00', $timezone),
            ],
            // second day
            [
                'start_date' => Carbon::parse('2023-12-15T13:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-15T15:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-15T16:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-15T17:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-15T18:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-15T19:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-15T20:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-15T23:00:00', $timezone),
            ],
            // third day
            [
                'start_date' => Carbon::parse('2023-12-16T13:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-16T15:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-16T16:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-16T17:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-16T18:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-16T19:00:00', $timezone),
            ],
            [
                'start_date' => Carbon::parse('2023-12-16T20:00:00', $timezone),
                'end_date' => Carbon::parse('2023-12-16T23:00:00', $timezone),
            ],
        ];
        $this->assertCarbonEquals($expectedOutput, $freeTimes);
    }

    private function assertCarbonEquals(array $expected, array $actual)
    {
        for ($i = 0; $i < count($expected); $i++) {
            $this->assertEquals(
                $expected[$i]['start_date']->toString(),
                $actual[$i]['start_date']->toString(),
                "Start date at index {$i} is not equal"
            );
            $this->assertEquals(
                $expected[$i]['end_date']->toString(),
                $actual[$i]['end_date']->toString(),
                "End date at index {$i} is not equal"
            );
        }
    }
}
