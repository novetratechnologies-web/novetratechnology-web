<?php

namespace App\Traits;

use App\Models\ChurchBranch;
use Illuminate\Support\Facades\Auth;

trait AssignContentBranch
{
    protected static function bootAssignContentBranch(): void
    {
        static::creating(function ($model) {
            if ($model->branch_id || !Auth::check()) {
                return;
            }

            $user = Auth::user();
            if (!$user->branch_id || (int) $user->church_id !== (int) $model->church_id) {
                return;
            }

            $branch = ChurchBranch::query()->find($user->branch_id);
            if ($branch && !$branch->is_headquarters) {
                $model->branch_id = $branch->id;
            }
        });
    }
}
