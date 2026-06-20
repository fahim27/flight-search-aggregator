<?php

namespace App\Services\Flight\Providers;


interface ProviderInterFace
{
    public function search(object $request): array;
    public function normalizeSearchData(array $flight): array;
}
