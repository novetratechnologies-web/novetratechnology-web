<section class="cd-component">
    <header class="cd-component-heading"><div><span class="cd-component-icon cd-icon-blue"><i class="fas fa-chart-column"></i></span><div><h3>Service attendance</h3><p>Recent attendance sessions</p></div></div><a href="{{ url('/admin/events') }}" class="cd-heading-link">All events <i class="fas fa-arrow-right"></i></a></header>
    @php
        $totalChurchMembers = App\Models\User::where('church_id', auth()->user()->church_id)->ByRole('5')->whereHas('userprofile', function ($query) {
            $query->where('membership_type', 'member')->orWhereNull('membership_type');
        })->count();
    @endphp
    <div class="cd-attendance-list">
        @forelse($dashboard['EventAttendance'] as $session)
            @php
                $presentCount = $session->attendees->count();
                $absentCount = max(0, $totalChurchMembers - $presentCount);
                $attendancePercent = $totalChurchMembers > 0 ? min(100, (int) round(($presentCount / $totalChurchMembers) * 100)) : 0;
            @endphp
            <a class="cd-attendance-row" href="{{ url('/admin/events/attendance/session/' . $session->id) }}">
                <div class="cd-attendance-date"><strong>{{ \Carbon\Carbon::parse($session->attendance_date)->format('d') }}</strong><span>{{ \Carbon\Carbon::parse($session->attendance_date)->format('M') }}</span></div>
                <div class="cd-attendance-main"><div class="cd-attendance-title"><strong>{{ $session->event?->title ?: 'Church service' }}</strong><time>{{ \Carbon\Carbon::parse($session->attendance_date)->format('D, M j, Y') }}</time></div><div class="cd-progress"><span style="width: {{ $attendancePercent }}%"></span></div><div class="cd-attendance-stats"><span><i class="fas fa-circle-check"></i>{{ $presentCount }} present</span><span>{{ $absentCount }} absent</span><b>{{ $attendancePercent }}%</b></div></div>
                <i class="fas fa-chevron-right cd-attendance-arrow"></i>
            </a>
        @empty
            <div class="cd-component-empty"><span><i class="fas fa-chart-column"></i></span><strong>No attendance sessions yet</strong><small>Recorded service attendance will appear here.</small></div>
        @endforelse
    </div>
</section>
