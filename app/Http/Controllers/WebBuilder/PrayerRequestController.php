<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Prayer;
use App\Models\PrayerCategory;
use App\Models\PrayerParticipant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PrayerRequestController extends Controller
{
    public function index()
    {
        $church = request()->attributes->get('_church');
        $churchId = optional($church)->id;

        $activeQuery = Prayer::query()
            ->active()
            ->forChurch($churchId)
            ->with('category');

        $query = clone $activeQuery;
        $search = trim((string) request()->query('q', ''));
        $categoryId = request()->query('category_id');

        if ($search !== '') {
            $query->where('text', 'like', '%' . str_replace(['%', '_'], ['\\%', '\\_'], $search) . '%');
        }

        if ($categoryId && ctype_digit((string) $categoryId)) {
            $query->where('category_id', (int) $categoryId);
        }

        $requests = $query->orderByPinned()->paginate(9)->withQueryString();
        $categories = PrayerCategory::query()
            ->forChurch($churchId)
            ->active()
            ->ordered()
            ->get();

        $requestIds = $requests->getCollection()->modelKeys();
        $liftedPrayerIds = [];
        if ($requestIds) {
            $participants = PrayerParticipant::query()->whereIn('prayer_id', $requestIds);
            if (auth()->check()) {
                $participants->where('user_id', auth()->id());
            } else {
                $guestHashes = array_map(
                    fn ($id) => hash('sha256', request()->ip() . '|' . request()->userAgent() . '|' . $id),
                    $requestIds
                );
                $participants->whereIn('anon_hash', $guestHashes);
            }
            $liftedPrayerIds = $participants->pluck('prayer_id')->map(fn ($id) => (int) $id)->all();
        }

        $stats = [
            'active' => (clone $activeQuery)->count(),
            'people_praying' => (int) (clone $activeQuery)->selectRaw('COALESCE(SUM(member_count + guest_count + anonymous_count), 0) as total')->value('total'),
        ];

        return view('theme::prayer_index', compact('requests', 'categories', 'stats', 'search', 'categoryId', 'liftedPrayerIds'));
    }

    public function store(Request $request)
    {
        $church = $request->attributes->get('_church');
        $churchId = optional($church)->id;
        $validated = $request->validate([
            'text' => ['required', 'string', 'min:10', 'max:500'],
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('prayer_categories', 'id')->where(fn ($query) => $query
                    ->where('church_id', $churchId)
                    ->where('is_active', true)),
            ],
        ], [
            'text.required' => 'Please share a prayer request before submitting.',
            'text.min' => 'Please provide at least 10 characters so the prayer team understands your request.',
            'category_id.exists' => 'Please choose an available prayer category.',
        ]);

        Prayer::create([
            'church_id'       => $churchId,
            'user_id'         => auth()->id(),
            'category_id'     => $validated['category_id'] ?? null,
            'text'            => trim($validated['text']),
            'original_text'   => trim($validated['text']),
            'status'          => Prayer::STATUS_PENDING,
            'member_count'    => 0,
            'guest_count'     => 0,
            'anonymous_count' => 0,
        ]);

        return redirect()->back()->with('success', 'Your prayer request has been submitted.');
    }

    public function lift(Request $request, $id)
    {
        $church = $request->attributes->get('_church');

        $prayer = Prayer::where('id', $id)
            ->where('status', Prayer::STATUS_ACTIVE)
            ->when($church, fn ($q) => $q->where('church_id', $church->id))
            ->first();

        if (!$prayer) {
            return response()->json([
                'success' => false,
                'error'   => 'This prayer is no longer active',
                'code'    => 'PRAYER_INACTIVE',
            ], 422);
        }

        if (auth()->check()) {
            $user = auth()->user();
            $type = PrayerParticipant::TYPE_MEMBER;
            $hash = null;
        } else {
            $user = null;
            $type = PrayerParticipant::TYPE_GUEST;
            $hash = hash('sha256', $request->ip() . '|' . $request->userAgent() . '|' . $id);
        }

        $lifted = PrayerParticipant::lift($prayer, $user, $type, $hash);

        if (!$lifted) {
            return response()->json([
                'success' => false,
                'error'   => 'You have already prayed for this',
                'code'    => 'DUPLICATE_PARTICIPATION',
            ], 403);
        }

        $prayer->refresh();

        return response()->json([
            'success'               => true,
            'message'               => 'Prayer recorded',
            'participant_count'     => $prayer->total_participant_count,
            'participant_breakdown' => [
                'total'     => $prayer->total_participant_count,
                'members'   => $prayer->member_count,
                'guests'    => $prayer->guest_count,
                'anonymous' => $prayer->anonymous_count,
            ],
        ]);
    }
}
