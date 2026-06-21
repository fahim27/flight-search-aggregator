<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Booking\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlightBookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function storeBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'flight_id'       => 'required|string',
            'search_id'       => 'required|string',
            'passenger_name'  => 'required|string',
            'passenger_email' => 'required|email',
            'passenger_phone' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return errorJsonResponse('Validation Error', $validator->errors()->all(), 422);
        }

        $booking = $this->bookingService->create($validator->validated());

        return successJsonResponse('Booking created successfully', [
            'booking' => $booking
        ]);
    }

    public function showBooking(string $reference)
    {
        $booking = $this->bookingService->getByReference($reference);

        if (!$booking) {
            return errorJsonResponse('Booking not found', [], 404);
        }

        return successJsonResponse('Booking retrieved successfully', [
            'booking' => $booking
        ]);
    }
}
