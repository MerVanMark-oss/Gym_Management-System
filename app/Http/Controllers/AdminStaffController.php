<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth; // Added for cleaner auth calls

class AdminStaffController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::guard('admin')->user();
        $query = Admin::query();

        // ANOMALY FIX: Search was missing in your index logic
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('username', 'LIKE', "%{$search}%")
                  ->orWhere('familyname', 'LIKE', "%{$search}%");
        }

        if ($currentUser && $currentUser->role !== 'super_admin') {
            $query->where('role', '!=', 'super_admin');
        }

        // Keep pagination consistent with your Blade
        $admins = $query->orderBy('familyname', 'asc')->paginate(10);

        return view('adminstaff', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:admins,username|max:255',
            'password' => 'required|string|min:8',
            'contactnum' => 'required|string',
            'email' => 'required|email|unique:admins,email',
            'familyname' => 'required|string',
            'role' => 'required|in:super_admin,admin,staff',
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'contactnum' => $request->contactnum,
            'email' => $request->email,
            'familyname' => $request->familyname,
            'role' => $request->role,
            'status' => 'active', 
        ]);

        ActivityLog::record('Added a new member', $member->first_name . ' ' . $member->last_name, 'fa-user-plus', 'blue');
        return redirect()->route('adminstaff.index')->with('success', 'New staff member added successfully!');
    }

    public function update(Request $request, $id)
{
    $admin = Admin::where('user_id', $id)->firstOrFail();
    $currentUser = auth()->guard('admin')->user();

    if ($currentUser->role === 'admin' && $admin->role !== 'staff') {
        return back()->with('error', 'Unauthorized.');
    }

    $admin->familyname = $request->familyname;

    if ($request->filled('contactnum')) {
        $admin->contactnum = $request->contactnum;
    }

    if ($request->filled('role')) {
        $admin->role = $request->role;
    }

    if ($request->filled('status')) {
        $admin->status = $request->status;
    }

    if ($request->filled('password')) {
        $admin->password = Hash::make($request->password);
    }

    $admin->save();

    return back()->with('success', 'Account updated successfully.');
}
}