<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\MembershipType;
use Carbon\Carbon;
use App\Models\ActivityLog;

class MemberController extends Controller
{
    // 1. List all members (The 'Members' page)
public function index(Request $request)
{
    $members = Member::with('membershipType')
                ->latest()
                ->paginate(6);
    $query = Member::with('membershipType');
    // You MUST fetch this here to use it in the modal!
    $membershipTypes = MembershipType::all(); 

   if ($request->filled('search')) {
        $searchTerm = $request->get('search');
        
        $query->where(function($q) use ($searchTerm) {
            $q->where('first_name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('member_id', 'LIKE', "%{$searchTerm}%");
        });
    }

    $members = $query->latest()
                    ->paginate(6)
                    ->appends($request->query());

    $membershipTypes = MembershipType::all();

    // Both variables must be inside this compact()
    return view('members', compact('members', 'membershipTypes'));
}


    // 2. Show the Registration Form
    public function create()
    {
        $types = MembershipType::all(); // To fill the 'Subscription Plan' dropdown
        return view('members_create', compact('types'));
    }

    // 3. Store a new member in the database
    public function store(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'contact_number' => 'required',
        'membership_type_id' => 'required|exists:membership_types,id',
    ]);

    $plan = MembershipType::findOrFail($request->membership_type_id);

    $member = Member::create([
        'first_name'         => $request->first_name,
        'last_name'          => $request->last_name,
        'contact_number'     => $request->contact_number,
        'membership_type_id' => $request->membership_type_id,
        'status'             => 'active',
        'expiry_date'        => now()->addDays($plan->duration_days), 
    ]);

    // FIX: Change $member->id to $member->member_id
    \App\Models\Payment::create([
        'member_id'      => $member->member_id, 
        'transaction_id' => 'REG-' . strtoupper(uniqid()),
        'amount'         => $plan->price,
        'type'           => 'Initial Enrollment',
        'payment_method' => 'Cash',
        'status'         => 'completed',
    ]);

     ActivityLog::record('Added a new member', $member->first_name . ' ' . $member->last_name, 'fa-user-plus', 'blue');

    return redirect()->route('members.index')->with('success', 'Member registered and payment recorded!');
}

    // 4. Update status (For the 'Cancel' or 'Renew' buttons)
    public function updateStatus(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $member->status = $request->status;
        $member->save();

        return back()->with('status', 'Member status updated.');
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'contact_number' => 'required',
    ]);

    $member = Member::findOrFail($id);
    
    // We update the names and contact, but IGNORE membership_type_id
    $member->update([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'contact_number' => $request->contact_number,
    ]);

    return redirect()->route('members.index')->with('success', 'Profile updated successfully!');
}
}
