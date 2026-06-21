<?php

namespace App\Services\Flight;

use App\Services\Flight\Providers\ProviderServiceA;
use App\Services\Flight\Providers\ProviderServiceB;
use App\Services\Flight\Providers\ProviderServiceC;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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

            $searchResults = collect($searchResults);

            // Apply additional filters if provided in the request
            if (!empty($request->carrier)) {
                $searchResults = $searchResults->where('carrier', $request->carrier);
            }

            if ($request->has('stops')) {
                $searchResults = $searchResults->where('stops', (int) $request->stops);
            }

            if (!empty($request->min_price)) {
                $searchResults = $searchResults->where('price', '>=', $request->min_price);
            }
            if (!empty($request->max_price)) {
                $searchResults = $searchResults->where('price', '<=', $request->max_price);
            }

            $uniqueFlights = $searchResults
                ->sortBy('price')
                ->unique('flight_number')
                ->values();

            //support sorting
            if (!empty($request->sort)) {
                switch ($request->sort) {
                    case 'price_asc':
                        $uniqueFlights = $uniqueFlights->sortBy('price');
                        break;
                    case 'price_desc':
                        $uniqueFlights = $uniqueFlights->sortByDesc('price');
                        break;
                    case 'departure_asc':
                        $uniqueFlights = $uniqueFlights->sortBy('departure');
                        break;
                    case 'departure_desc':
                        $uniqueFlights = $uniqueFlights->sortByDesc('departure');
                        break;
                }
            }


            $flights  = $uniqueFlights->values()->all();
            $searchId = Str::uuid()->toString();

            Cache::put("flight_search_{$searchId}", $flights, now()->addMinutes(30));

            return [
                'flights' => $flights,
                'meta'    => [
                    'search' => [
                        'id'                => $searchId,
                        'search_time'       => now()->toDateTimeString(),
                        'search_expired_at' => now()->addMinutes(30)->toDateTimeString()
                    ],
                    'provider' =>  [
                        'providers_queried' => count($providers),
                        'providers_status'  => $providerStatus
                    ]
                ],
            ];
        }
    }
}
