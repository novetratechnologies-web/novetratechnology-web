<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunicationDelivery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunicationDeliveryController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(in_array((int) Auth::user()->usergroup_id, [3, 4], true), 403);

        $churchId = (int) Auth::user()->church_id;
        $filters = $request->validate([
            'channel' => ['nullable', 'in:email,sms,in_app'],
            'status' => ['nullable', 'in:queued,processing,sent,failed'],
            'event_type' => ['nullable', 'string', 'max:64'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $baseQuery = CommunicationDelivery::where('church_id', $churchId);
        $stats = (clone $baseQuery)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $eventTypes = (clone $baseQuery)->select('event_type')->distinct()->orderBy('event_type')->pluck('event_type');

        $deliveries = CommunicationDelivery::query()
            ->with('user.userprofile')
            ->where('church_id', $churchId)
            ->when(isset($filters['channel']), fn ($query) => $query->where('channel', $filters['channel']))
            ->when(isset($filters['status']), fn ($query) => $query->where('status', $filters['status']))
            ->when(isset($filters['event_type']), fn ($query) => $query->where('event_type', $filters['event_type']))
            ->when(isset($filters['from']), fn ($query) => $query->where('created_at', '>=', Carbon::createFromFormat('Y-m-d', $filters['from'])->startOfDay()))
            ->when(isset($filters['to']), fn ($query) => $query->where('created_at', '<=', Carbon::createFromFormat('Y-m-d', $filters['to'])->endOfDay()))
            ->when(isset($filters['search']), function ($query) use ($filters) {
                $term = '%'.$filters['search'].'%';
                $query->where(function ($search) use ($term) {
                    $search->where('recipient', 'like', $term)
                        ->orWhereRaw('CAST(payload AS CHAR) LIKE ?', [$term])
                        ->orWhereHas('user', function ($user) use ($term) {
                            $user->where('name', 'like', $term)->orWhere('email', 'like', $term);
                        })
                        ->orWhereHas('user.userprofile', function ($profile) use ($term) {
                            $profile->where('firstname', 'like', $term)->orWhere('lastname', 'like', $term);
                        });
                });
            })
            ->latest('id')
            ->paginate(30)
            ->withQueryString();

        return view('admin.communications.deliveries', compact('deliveries', 'stats', 'eventTypes'));
    }
}
