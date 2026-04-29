<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Equipment;
use App\Models\Payment;
use App\Models\MembershipType;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. General Stats
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $totalEquipment = Equipment::count();
        
        // Using 'completed' status for revenue as per your logic
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');

        // 2. Recent activity
       $recentActivities = ActivityLog::latest()->paginate(3);

        // 3. DONUT CHART DATA: Membership Types distribution
        $membershipData = MembershipType::withCount('members')->get();

        // 4. BAR CHART DATA: Member Growth (Joined per Month)
        $growthData = Member::select(
                DB::raw('MONTHNAME(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('MONTHNAME(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'), 'ASC')
            ->get();

        // 5. FINAL RETURN (One return at the end)
        return view('dashboard', compact(
            'totalMembers', 
            'activeMembers', 
            'totalEquipment', 
            'totalRevenue',
            'recentActivities',
            'membershipData',
            'growthData'
        ));
    }
}