<?php

namespace App\Services\Flight\MockupProviders;


class MockupProviderServiceB implements MockupProviderInterFace
{
    public function mockupData()
    {
        $jsonData = '{ "data": [
            { "airline_code": "BS", "origin": "DAC", "destination": "DXB", "departure_time": "2026-07-01 09:15", "arrival_time": "2026-07-01 15:00", "segments": 1, "price": { "amount": 295, "currency": "USD" }, "number": "BS220" },
            { "airline_code": "BS", "origin": "DAC", "destination": "DXB", "departure_time": "2026-07-01 14:30", "arrival_time": "2026-07-01 19:20", "segments": 1, "price": { "amount": 265, "currency": "USD" }, "number": "BS118" },
            { "airline_code": "EK", "origin": "DAC", "destination": "DXB", "departure_time": "2026-07-01 03:45", "arrival_time": "2026-07-01 06:50", "segments": 0, "price": { "amount": 399, "currency": "USD" }, "number": "EK585" }
        ]}';

        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON decode error: ' . json_last_error_msg());
        }

        $flight = $data['results'] ?? [];

        return $flight;
    }
}
