<?php

namespace App\Services\Flight\MockupProviders;

use Exception;

class MockupProviderServiceA implements MockupProviderInterFace
{
    public function mockupData()
    {
        return '{ "flights": [
        { "carrier": "AA", "from": "DAC", "to": "DXB", "depart": "2026-07-01T08:00:00", "arrive": "2026-07-01T12:30:00", "stops": 0, "fare_usd": 320.00, "flight_no": "AA101" },
        { "carrier": "AA", "from": "DAC", "to": "DXB", "depart": "2026-07-01T22:10:00", "arrive": "2026-07-02T02:40:00", "stops": 0, "fare_usd": 280.00, "flight_no": "AA205" },
        { "carrier": "BS", "from": "DAC", "to": "DXB", "depart": "2026-07-01T09:15:00", "arrive": "2026-07-01T15:00:00", "stops": 1, "fare_usd": 310.00, "flight_no": "BS220" },
        { "carrier": "EK", "from": "DAC", "to": "DXB", "depart": "2026-07-01T03:45:00", "arrive": "2026-07-01T06:50:00", "stops": 0, "fare_usd": 410.00, "flight_no": "EK585" }
        ]}';
    }

    public function search($data)
    {

        $flightsData = $this->mockupData();
               
        $data = json_decode($flightsData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON decode error: ' . json_last_error_msg());
        }

        $flights = collect($data['flights'] ?? [])->each(function ($flight) {
            $flight['depart'] = now()->parse($flight['depart'])->format('Y-m-d H:i:s');
            $flight['arrive'] = now()->parse($flight['arrive'])->format('Y-m-d H:i:s');
            return $flight;
        });

        return $flights;
    }
}
