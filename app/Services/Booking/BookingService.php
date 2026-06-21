<?php


namespace App\Services\Booking;

use App\Exceptions\ServiceException;
use App\Repositories\BookingRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;



class BookingService
{
    public function __construct(
        protected BookingRepository $repository
    ) {}

    public function create(array $data)
    {
        $searchKey = "flight_search_{$data['search_id']}";
        $flights   = Cache::get($searchKey, []);

        if (empty($flights)) {
            throw new ServiceException("Search session expired or invalid");
        }

        $flight = collect($flights)->firstWhere('flight_id', strtoupper($data['flight_id']));

        if (!$flight) {
            throw new ServiceException("Selected flight is not available");
        }

        $existsCheck = $this->repository->findByFlightIdAndEmail($data['flight_id'], $data['passenger_email']);

        if ($existsCheck) {
            throw new ServiceException("You have already booked this flight with the provided email");
        }

        $data['reference']      = $this->generateReference();
        $data['flight_details'] = $flight;
        return $this->repository->create($data);
    }

    public function getByReference(string $reference)
    {
        return $this->repository->findByReference($reference);
    }

    private function generateReference(): string
    {
        return 'BK-' . strtoupper(Str::random(8));
    }
}
