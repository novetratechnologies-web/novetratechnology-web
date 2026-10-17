<?php

namespace App\Services\Payment;

use App\Models\Donation;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class VerifiedDonationRecorder
{
    public function record(array $attributes): Donation
    {
        $unique = [
            'church_id' => $attributes['church_id'],
            'method' => $attributes['method'],
            'gateway_ref' => $attributes['gateway_ref'],
        ];

        try {
            return DB::transaction(function () use ($unique, $attributes) {
                $existing = Donation::query()->where($unique)->lockForUpdate()->first();
                if ($existing) {
                    return $this->completeExisting($existing, $attributes);
                }

                return Donation::create($attributes);
            });
        } catch (QueryException $exception) {
            // A concurrent verification may win the unique-index race. Resolve it as
            // an idempotent retry; unrelated database failures still propagate.
            $existing = Donation::query()->where($unique)->first();
            if (!$existing) {
                throw $exception;
            }

            return $this->completeExisting($existing, $attributes);
        }
    }

    private function completeExisting(Donation $donation, array $attributes): Donation
    {
        if ((int) round((float) $donation->amount * 100) !== (int) round((float) $attributes['amount'] * 100)
            || strtoupper($donation->currency) !== strtoupper($attributes['currency'])) {
            throw new RuntimeException('This payment reference is already associated with a different amount or currency.');
        }

        if ($donation->status !== 'completed') {
            $donation->update(['status' => 'completed']);
        }

        return $donation;
    }
}
