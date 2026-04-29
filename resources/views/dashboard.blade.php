@extends('layout.app')

@section('content')
<h1>Dashboard</h1>


<div class="dashboard-page">

    <div class="dashboard-stats-row">
        <a href="{{ route('members.index') }}" class="card-dashboard total-members">
            <div class="card-icon bg-blue">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="card-content-right">
                <p>Total Members</p>
                <h3>{{ $totalMembers ?? 0 }}</h3>
            </div>
        </a>

        <a href="{{ route('members.index', ['status' => 'active']) }}" class="card-dashboard active-members">
            <div class="card-icon bg-yellow">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div class="card-content-right">
                <p>Active Members</p>
                <h3>{{ $activeMembers ?? 0 }}</h3>
            </div>
        </a>

        <a href="{{ route('equipment.index') }}" class="card-dashboard equipment">
            <div class="card-icon bg-purple">
                <i class="fa-solid fa-dumbbell"></i>
            </div>
            <div class="card-content-right">
                <p>Total Equipment</p>
                <h3>{{ $totalEquipment ?? 0 }}</h3>
            </div>
        </a>

        <a href="{{ route('billing.index') }}" class="card-dashboard revenue">
            <div class="card-icon bg-green">
                <i class="fa-solid fa-money-bill-trend-up"></i>
            </div>
            <div class="card-content-right">
                <p>Total Revenue</p>
                <h3>₱{{ number_format($totalRevenue ?? 0, 0) }}</h3>
            </div>
        </a>
    </div>
</div>

<div class="dashboard-grid">
    <div class="dashboard-grid-upper">
        <div class="card-membershiptype">
            <h4>Membership Types</h4>
            <div class="chart-wrapper">
                <canvas id="membershipDonut"></canvas>
            </div>
        </div>

        <div class="card-member-growth">
            <h4>Member Growth</h4>
            <div class="chart-wrapper">
                <canvas id="growthBarChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card-recentactivity">
        <h4>Recent Activity</h4>
        <div class="activity-list">
            @forelse($recentActivities as $log)
                <div class="activity-item">
                    <div class="activity-icon bg-{{ $log->color }}">
                        <i class="fa-solid {{ $log->icon }}"></i>
                    </div>
                    <div class="activity-details">
                        <p class="activity-text">
                            <strong>{{ ucfirst(str_replace('_', ' ', $log->role)) }} {{ $log->performed_by }} :</strong>
                            {{ $log->action }}
                            @if($log->target)
                                — <span class="activity-target">{{ $log->target }}</span>
                            @endif
                        </p>
                        <small class="activity-time">
                            <i class="fa-regular fa-clock"></i> {{ $log->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            @empty
                <p class="no-activity">No recent activity yet.</p>
            @endforelse
        </div>
        
        <div class="pagination-container">
            {{ $recentActivities->links() }}
        </div>
    </div>
</div>

<script>
    {{-- FIX: Added ?? [] to prevent JS errors --}}
    window.membershipStats = @json($membershipData ?? []);
    window.growthStats = @json($growthData ?? []);
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>

@endsection

