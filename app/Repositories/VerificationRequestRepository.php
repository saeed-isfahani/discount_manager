<?php

namespace App\Repositories;

use App\Contracts\Repositories\VerificationRequestRepositoryInterface;
use App\Models\VerificationRequest;
use Illuminate\Database\Eloquent\Collection;

class VerificationRequestRepository extends BaseRepository implements VerificationRequestRepositoryInterface
{
    protected function model(): string
    {
        return VerificationRequest::class;
    }

    /**
     * increment
     *
     * @param Collection $incrementable
     * @param string $field
     * @param int $increment
     * @return bool
     */
    public function increment(object $incrementable, string $field, int $increment = 1): ?object
    {
        $incrementable->{$field} += $increment;
        if ($incrementable->save()) {
            return $incrementable;
        }
        return null;
    }
}
