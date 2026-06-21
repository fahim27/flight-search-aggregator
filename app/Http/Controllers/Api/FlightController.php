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
            'date'       => 'required|date|date_format:Y-m-d|after:yesterday',
            'passengers' => 'nullable|integer|min:1' //included for API contract completeness, but not used for validation logic since provider responses do not contain passenger or inventory-related data.
        ], [
            'date.after' => 'The date must be a future date.',
            'date.date_format' => 'The date format must be YYYY-MM-DD.'
        ]);

        if ($validator->fails()) {
            return errorJsonResponse('Validation Error', $validator->errors()->all(), 422);
        }

        $search = $this->flightService->search($request);
        return successJsonResponse('Flight search results', $search);
    }
}
