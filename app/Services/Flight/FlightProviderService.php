<?php

namespace App\Services\Flight;


class FlightProviderService
{

    protected function generateFlightId(string $hasString)
    {
        return hash('sha256', $hasString);
    }

    protected function getMockupData($provider)
    {
        return json_decode(file_get_contents(storage_path('app/public/mock-data/' . $provider . '.json')), true);
    }
}
