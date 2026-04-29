<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;      
use App\Models\Member;         
use App\Models\MembershipType;
use App\Models\Refund; 
use Carbon\Carbon;            
use App\Models\ActivityLog;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        
    Member::where('status', 'active')
            ->whereDate('expiry_date', '<', today())
            ->update(['status' => 'expired']);

        // 1. Static Totals for Cards
        $grossRevenue = Payment::where('status', 'completed')->sum('amount');

        $totalRefunded = Refund::where('status', 'Approved')
            ->with('membershipType')
            ->get()
            ->sum(function($refund) {
                return $refund->membershipType->price ?? 0;
            });

        $totalRevenue   = $grossRevenue - $totalRefunded;
        $completedToday = Payment::whereDate('created_at', today())->where('status', 'completed')->sum('amount');

        $refundsCount        = Refund::where('status', 'Approved')->count();
        $pendingRefundsCount = Refund::where('status', 'Pending')->count();
        
// FIX: Define the Failed Query first so it can be used for counting AND the table
$failedCount = Member::where(function($q) {
        $q->where('status', 'expired')
          ->orWhere(function($inner) {
              $inner->whereDate('expiry_date', '<', today())
                    ->whereNotIn('status', ['active', 'cancelled']);
          });
    })->count();

// 2. Table Filter Logic
        $status  = $request->get('status');
        $filter  = $request->get('filter');
        $search  = $request->get('search');

        $isRefundTable = false;
        $isFailedTable = false;

        if ($status == 'refunds' || $status == 'pending_refunds') {
            $query = Refund::with(['member', 'membershipType']);

            if ($request->filled('search')) {
                $query->whereHas('member', function($q) use ($search) {
                    $q->where('first_name', 'LIKE', "%{$search}%")
                      ->orWhere('last_name', 'LIKE', "%{$search}%");
                });
            }

            if ($status == 'pending_refunds') {
                $query->where('status', 'Pending');
            }

            $payments      = $query->latest()->paginate(5)->appends(request()->query());
            $isRefundTable = true;

        } elseif ($status == 'failed') {
            $query = Member::with('membershipType')
                ->where(function($q) {
                    $q->where('status', 'expired')
                    ->orWhere(function($inner) {
                        $inner->whereDate('expiry_date', '<', today())
                                ->whereNotIn('status', ['active', 'cancelled']);
                    });
                });

            if ($request->filled('search')) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%");
                });
            }

            $payments = $query->latest('expiry_date')->paginate(5)->appends(request()->query());
            $isFailedTable = true;
} else {
            $query = Payment::with('member');

            if ($request->filled('search')) {
                $query->where(function($q) use ($search) {
                    $q->where('transaction_id', 'LIKE', "%{$search}%")
                      ->orWhereHas('member', function($m) use ($search) {
                          $m->where('first_name', 'LIKE', "%{$search}%")
                            ->orWhere('last_name', 'LIKE', "%{$search}%");
                      });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($filter == 'today') {
                $query->whereDate('created_at', Carbon::today());
            }

            $payments      = $query->latest()->paginate(5)->appends(request()->query());
        }

        // 3. Data for Modals (Only show truly active members for upcoming)
        $upcomingPayments = Member::with('membershipType')
            ->where('status', 'active')
            ->whereDate('expiry_date', '>=', today())
            ->whereDate('expiry_date', '<=', today()->addDays(7))
            ->get();

        $membershipTypes = MembershipType::all();

        // Members eligible for renewal (already expired or expiring very soon)
        $membersForRenewal = Member::with('membershipType')
            ->where('status', 'expired')
            ->orWhereDate('expiry_date', '<=', today()->addDays(3))
            ->get();

        $eligibleForRefund = Member::with('membershipType')
            ->whereHas('refunds', function($q) {
                $q->where('status', 'Approved')
                  ->where('disbursement_status', 'pending_disbursement');
            })
            ->get();

        return view('billing', compact(
            'totalRevenue', 'refundsCount', 'pendingRefundsCount', 'completedToday',
            'failedCount', 'payments', 'upcomingPayments',
            'membershipTypes', 'membersForRenewal', 'eligibleForRefund',
            'isRefundTable', 'isFailedTable'
        ));
    }
    // Store Refund Request (Landing Page)
    public function storeRefund(Request $request)
    {
        $request->validate([
            'member_id'       => 'required',
            'membership_type' => 'required',
            'reason'          => 'required|string',
        ]);

        $member = Member::with('membershipType')->where('member_id', $request->member_id)->first();
        $type   = MembershipType::where('name', $request->membership_type)->first();

        if (!$member || !$type) {
            return back()->with('error', 'Invalid Member ID or Membership Type.');
        }

        if ($request->membership_type !== $member->membershipType->name) {
            return back()->with('error', 'The selected Membership Type does not match your current plan.');
        }

        Refund::create([
            'member_id'          => $member->member_id,
            'membership_type_id' => $type->id,
            'reason'             => $request->reason,
            'status'             => 'Pending',
        ]);

        return back()->with('success', 'Refund request submitted for approval!');
    }

    // Approve Refund
    public function approveRefund($id)
    {
        $currentUser = auth()->guard('admin')->user();

        if (!in_array($currentUser->role, ['admin', 'super_admin'])) {
            return back()->with('error', 'Unauthorized.');
        }

        $refund = Refund::findOrFail($id);
        $refund->update([
            'status'              => 'Approved',
            'disbursement_status' => 'pending_disbursement',
        ]);

        Member::where('member_id', $refund->member_id)->update([
            'status'      => 'cancelled',
            'expiry_date' => now(),
        ]);
       ActivityLog::record('Approved a refund', $refund->member->first_name ?? 'Member', 'fa-check-circle', 'green');


        return back()->with('success', 'Refund approved. Awaiting disbursement.');
    }

    // Decline Refund
    public function declineRefund($id)
    {
        $currentUser = auth()->guard('admin')->user();

        if (!in_array($currentUser->role, ['admin', 'super_admin'])) {
            return back()->with('error', 'Unauthorized. Only admins can decline refunds.');
        }

        $refund = Refund::findOrFail($id);
        $refund->update(['status' => 'Declined']);

       ActivityLog::record('Declined a refund request', $refund->member->first_name ?? 'Member', 'fa-xmark', 'red');
        return back()->with('success', 'Refund request declined.');
    }

    // Store Payment / Renewal
    public function store(Request $request)
    {
        $request->validate([
            'member_id'          => 'required|exists:members,member_id',
            'membership_type_id' => 'required|exists:membership_types,id',
            'payment_method'     => 'required|string',
        ]);

        $member = Member::where('member_id', $request->member_id)->first();
        $plan   = MembershipType::findOrFail($request->membership_type_id);

        Payment::create([
            'member_id'      => $member->member_id,
            'amount'         => $plan->price,
            'type'           => 'renewal',
            'payment_method' => $request->payment_method,
            'status'         => 'completed',
            'transaction_id' => 'TRX-' . strtoupper(uniqid()),
        ]);

        $member->update([
            'membership_type_id' => $plan->id,
            'expiry_date'        => now()->addMonth(),
            'status'             => 'active',
        ]);
        
        ActivityLog::record('Processed a membership renewal', $member->first_name . ' ' . $member->last_name, 'fa-money-bill', 'green');
        return redirect()->route('billing.index')->with('success', 'Membership renewed!');
    }

    // Store Disbursement
    public function storeDisbursement(Request $request)
    {
        $request->validate([
            'member_id'           => 'required',
            'disbursement_status' => 'required|in:disbursed,pending_disbursement',
            'disbursement_date'   => 'nullable|date|required_if:disbursement_status,disbursed',
        ]);

        $refund = Refund::where('member_id', $request->member_id)
                        ->where('status', 'Approved')
                        ->latest()
                        ->firstOrFail();

        $refund->update([
            'disbursement_status' => $request->disbursement_status,
            'disbursement_date'   => $request->disbursement_status === 'disbursed'
                                        ? $request->disbursement_date
                                        : null,
        ]);

        return back()->with('success', 'Refund disbursement recorded successfully.');
    }
}