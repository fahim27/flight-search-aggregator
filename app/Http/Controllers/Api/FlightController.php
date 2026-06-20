<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Flight\FlightService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlightController extends Controller
{

    public function __construct(
        protected FlightService $flightService
    ) {}

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from'       => 'required|string',
            'to'         => 'required|string',
            'date'       => 'required|date',
            'passengers' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return errorJsonResponse('Validation Error', $validator->errors()->all(), 422);
        }

        $search = $this->flightService->search(
            $request->input('from'),
            $request->input('to'),
            $request->input('date'),
            $request->input('passengers')
        );
        // This is where you would implement the logic to search for flights
        // using the mockup providers and return the results in a unified format.
        return successJsonResponse('Flight search results', ['flights' => []]);
    }
}
