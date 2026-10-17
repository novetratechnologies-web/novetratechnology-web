<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\Group;

class MinistryController extends Controller
{
    public function index()
    {
        $church = Church::first();
        $ministries = Group::query()
            ->where('church_id', $church ? $church->id : null)
            ->with('groupCategory')
            ->orderBy('name')
            ->paginate(12);

        return view('theme::ministries', compact('church', 'ministries'));
    }
}
