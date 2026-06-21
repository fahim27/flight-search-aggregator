<?php

namespace App\Services\Flight;

use App\Services\Flight\Providers\ProviderServiceA;
use App\Services\Flight\Providers\ProviderServiceB;
use App\Services\Flight\Providers\ProviderServiceC;

class FlightService
{

    public function search($request)
    { {
            $providers = [
                new ProviderServiceA(),
                new ProviderServiceB(),
                new ProviderServiceC()
            ];

            $searchResults  = [];
            $providerStatus = [];

            foreach ($providers as $provider) {
                try {

                    $providerResults = $provider->flightSearch($request);
                    if (!isset($providerResults['flights']) || empty($providerResults['flights'])) {
                        continue;
                    }

                    $searchResults = array_merge(
                        $searchResults,
                        $providerResults['flights']
                    );

                    $providerStatus[] = [
                        'provider' => class_basename($provider),
                        'status'   => 'success'
                    ];
                } catch (\Exception $e) {

                    // log error
                    logger()->error($provider::class, ['message' => $e->getMessage()]);
                    $providerStatus[] = [
                        'provider' => class_basename($provider),
                        'status' => 'failed'
                    ];

                    continue;
                }
            }

            $uniqueFlights = collect($searchResults)
                ->groupBy('flight_number')
                ->map(function ($group) {
                    return $group->sortBy('price')->first();
                })
                ->values()
                ->all();

            return [
                'flights' => $uniqueFlights,
                'meta'    => [
                    'providers_queried' => count($providers),
                    'providers_status'  => $providerStatus
                ],
            ];
        }
    }
}
