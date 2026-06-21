<?php

namespace App\Repositories;

use App\Models\Booking;

class BookingRepository
{
    public function create(array $data)
    {
        return Booking::create($data);
    }

    public function findByReference(string $reference)
    {
        return Booking::where('reference', $reference)->first();
    }

    public function findByFlightIdAndEmail(string $flightId, string $email)
    {
        return Booking::where('flight_id', $flightId)->where('passenger_email', $email)->first();
    }
}
