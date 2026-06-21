<?php

namespace App\Services\Flight\Providers;


interface ProviderInterFace
{
    public function flightSearch(object $request): array;
    public function normalizeFlightSearchData(array $flight): array;
}
