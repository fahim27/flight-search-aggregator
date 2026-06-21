<?php

namespace App\Services\Flight\Providers;

use App\Services\Flight\FlightProviderService;

class ProviderServiceC extends FlightProviderService implements ProviderInterFace
{
    public function flightSearch(object $request): array
    {
        try {
            $data            = $this->getMockupData('provider-c');
            $data['results'] = collect($data['results'])
                ->filter(function ($flight) use ($request) {
                    return $flight['route']['src'] === $request->from
                        && $flight['route']['dst'] === $request->to
                        && date('Y-m-d', $flight['times']['dep']) === $request->date;
                })
                ->map(function ($flight) {
                    return $this->normalizeFlightSearchData($flight);
                })
                ->values()
                ->toArray();
            return [
                'flights' => $data['results'] ?? []
            ];
        } catch (\Exception $e) {
            throw new \Exception("Provider A failed");
        }
    }


    public function normalizeFlightSearchData(array $flight): array
    {

        return [
            'flight_id'     => $this->generateFlightId($flight['code']),
            'carrier'       => $flight['iata'],
            'flight_number' => $flight['code'],
            'from'          => $flight['route']['src'],
            'to'            => $flight['route']['dst'],
            'departure'     => date('Y-m-d H:i', $flight['times']['dep']),
            'arrival'       => date('Y-m-d H:i', $flight['times']['arr']),
            'stops'         => $flight['layovers'] ?? 0,
            'price'         => $flight['total_price'],
            'currency'      => $flight['currency'],
        ];
    }
}
