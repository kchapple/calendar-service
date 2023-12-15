<?php

namespace Tests\Feature;

use Tests\TestCase;

class CalendarApiTest extends TestCase
{
    public function testSingleDay()
    {
        $query = http_build_query([
            'startDate' => '2023-12-14',
            'endDate' => '2023-12-14',
            'timezone' => 'America/New_York',
        ]);

        $response = $this->get('/api/free?' . $query);

        $json = [
            [
                'start_date' => '2023-12-14 13:00:00',
                'end_date' => '2023-12-14 15:00:00',
            ],
            [
                'start_date' => '2023-12-14 16:00:00',
                'end_date' => '2023-12-14 17:00:00',
            ],
            [
                'start_date' => '2023-12-14 18:00:00',
                'end_date' => '2023-12-14 19:00:00',
            ],
            [
                'start_date' => '2023-12-14 20:00:00',
                'end_date' => '2023-12-14 23:00:00',
            ],
        ];

        $response->assertStatus(200)
            ->assertJson($json);
    }

    public function testMissingParameters()
    {
        // Arrange query with missing end date
        $query = http_build_query([
            'startDate' => '2023-01-01',
            'timezone' => 'America/New_York',
        ]);

        // Act
        $response = $this->get('/api/free?' . $query);

        // Assert invalid data error code
        $response->assertStatus(302);
    }
}
