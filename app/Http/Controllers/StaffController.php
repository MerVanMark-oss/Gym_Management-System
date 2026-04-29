<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        // 1. Static Counts for the Cards
        $totalStaff = Staff::count();
        $activeStaff = Staff::where('status', 'active')->count();
        $instructorsCount = Staff::whereIn('role', ['Coach', 'Trainer'])->count();
        $onLeave = Staff::where('status', 'on_leave')->count();

        // 2. Filtered Query for the Table
        $query = Staff::query();

        // Filter by Status (Active / On Leave)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Role (Instructor Card logic)
        if ($request->role == 'all_instructors') {
            $query->whereIn('role', ['Coach', 'Trainer']);
        }

        // Apply Pagination (5 per page) and keep filters in the URL
        $staffMembers = $query->latest()
                             ->paginate(5)
                             ->appends($request->query());

        return view('staff', compact(
            'totalStaff', 
            'activeStaff', 
            'instructorsCount', 
            'onLeave', 
            'staffMembers'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'contact' => 'required|string',
            'role' => 'required|string',
            'status' => 'required|string',
            'shift' => 'required|string',
            'hire_date' => 'required|date',
        ]);

        $staff = Staff::create($validated);

        ActivityLog::record('Added a new staff member', $staff->name . ' — ' . $staff->role, 'fa-user-tie', 'blue');
        return redirect()->route('staff.index')->with('success', 'New staff registered!');
    }

    // --- NEW: THE UPDATE METHOD ---
    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $id . ',staff_id',
            'contact' => 'required|string',
            'shift' => 'required|string',
            'status' => 'required|string',
        ]);

        $staff->update($validated);
         ActivityLog::record('Updated staff information', $staff->name . ' — ' . $staff->status, 'fa-user-pen', 'amber');
        return redirect()->route('staff.index')->with('success', 'Staff information updated successfully!');
    }
}