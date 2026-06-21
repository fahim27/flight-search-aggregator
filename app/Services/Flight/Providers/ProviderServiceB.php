<?php

namespace App\Services\Flight\Providers;

use App\Services\Flight\FlightProviderService;

class ProviderServiceB extends FlightProviderService implements ProviderInterFace
{
    public function flightSearch(object $request): array
    {
        try {
            $data         = $this->getMockupData('provider-b');
            $data['data'] = collect($data['data'])
                ->filter(function ($flight) use ($request) {
                    return $flight['origin'] === $request->from
                        && $flight['destination'] === $request->to
                        && date('Y-m-d', strtotime($flight['departure_time'])) === $request->date;
                })
                ->map(function ($flight) {
                    return $this->normalizeFlightSearchData($flight);
                })
                ->values()
                ->toArray();
            return [
                'flights' => $data['data']
            ];
        } catch (\Exception $e) {
            throw new \Exception("Provider A failed");
        }
    }


    public function normalizeFlightSearchData(array $flight): array
    {
        return [
            'flight_id'     => $this->generateFlightId($flight['number']),
            'carrier'       => $flight['airline_code'],
            'flight_number' => $flight['number'],
            'from'          => $flight['origin'],
            'to'            => $flight['destination'],
            'departure'     => date('Y-m-d H:i', strtotime($flight['departure_time'])),
            'arrival'       => date('Y-m-d H:i', strtotime($flight['arrival_time'])),
            'stops'         => $flight['segments'] ?? 0,
            'price'         => $flight['price']['amount'],
            'currency'      => $flight['price']['currency'],
        ];
    }
}
