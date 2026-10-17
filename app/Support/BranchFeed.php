<?php

namespace App\Support;

use App\Models\ChurchBranch;

class BranchFeed
{
    /** Resolve the signed-in member's branch, falling back to headquarters. */
    public function currentId(?int $churchId): ?int
    {
        if (!$churchId) {
            return null;
        }

        $user = auth()->user();
        if ($user && (int) $user->church_id === $churchId && $user->branch_id) {
            return (int) $user->branch_id;
        }

        return ChurchBranch::query()
            ->forChurch($churchId)
            ->where('is_headquarters', true)
            ->value('id');
    }

    /** Include organization-wide items and items assigned to the active branch. */
    public function apply($query, ?int $branchId)
    {
        return $query->where(function ($branchQuery) use ($branchId) {
            $branchQuery->whereNull('branch_id');
            if ($branchId) {
                $branchQuery->orWhere('branch_id', $branchId);
            }
        });
    }
}
