<?php

namespace App\Services\Flight;


class FlightProviderService
{

    protected function generateFlightId(string $flligId)
    {
        return 'FL-' . strtoupper($flligId);
    }

    protected function getMockupData($provider)
    {

        return json_decode(file_get_contents(public_path('mockup-data/' . $provider . '.json')), true);
    }
}
