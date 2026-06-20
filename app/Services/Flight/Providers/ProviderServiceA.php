<?php

namespace App\Services\Flight\Providers;

use App\Services\Flight\FlightProviderService;

class ProviderServiceA extends FlightProviderService implements ProviderInterFace
{

    public function search(object $request): array
    {
        try {
            $data            = $this->getMockupData('provider-a');
            $data['flights'] = collect($data['flights'])
                ->filter(function ($flight) use ($request) {
                    return $flight['from'] === $request->from && $flight['to'] === $request->to && date('Y-m-d', strtotime($flight['depart'])) === $request->date;
                })
                ->map(function ($flight) {
                    return $this->normalizeSearchData($flight);
                })
                ->values()
                ->toArray();

            return [
                'flights' => $data['flights']
            ];
        } catch (\Exception $e) {
            throw new \Exception("Provider A failed");
        }
    }


    public function normalizeSearchData(array $flight): array
    {

        return [
            'flight_id'     => $this->generateFlightId($flight['flight_no']),
            'carrier'       => $flight['carrier'],
            'flight_number' => $flight['flight_no'],
            'from'          => $flight['from'],
            'to'            => $flight['to'],
            'departure'     => date('Y-m-d H:i', strtotime($flight['depart'])),
            'arrival'       => date('Y-m-d H:i', strtotime($flight['arrive'])),
            'stops'         => $flight['stops'],
            'price'         => $flight['fare_usd'],
            'currency'      => "USD",
        ];
    }
}
