<?php

namespace App\Services\Flight;

use App\Services\Flight\MockupProviders\MockupProviderServiceA;
use App\Services\Flight\MockupProviders\MockupProviderServiceB;
use App\Services\Flight\MockupProviders\MockupProviderServiceC;

class FlightService
{
    protected $providers;

    public function __construct()
    {
        $this->providers = [
            new MockupProviderServiceA(),
            new MockupProviderServiceB(),
            new MockupProviderServiceC(),
        ];
    }

    public function search($from, $to, $date, $passengers)
    {
        foreach ($this->providers as $provider) {
            $data = $provider->mockupData();
            // Here you would implement the logic to transform the provider's data
            // into a unified format and filter it based on the search criteria.
        }
        dd(12);
    }
}
