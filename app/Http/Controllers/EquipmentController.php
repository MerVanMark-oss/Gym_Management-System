<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\ActivityLog;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::query();

        if ($request->filled('status') && $request->status !== '') {
            $statusMap = [
                'good'        => 'good',
                'repair'      => 'under_repair',
                'maintenance' => 'broken',
            ];
            $mapped = $statusMap[$request->status] ?? $request->status;
            $query->where('status', $mapped);
        }

        if ($request->filled('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        $totalEquipment       = Equipment::count();
        $goodCondition        = Equipment::where('status', 'good')->count();
        $underRepair          = Equipment::where('status', 'under_repair')->count();
        $needMaintenanceCount = Equipment::where('status', 'broken')->count();
        $equipments           = $query->paginate(5);

        return view('equipment', compact(
            'totalEquipment', 'goodCondition', 'underRepair', 'needMaintenanceCount', 'equipments'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'category'         => 'required',
            'status'           => 'required|in:good,under_repair,broken',
            'last_maintenance' => 'nullable|date',
        ]);

        $lastDate  = $request->last_maintenance ? \Carbon\Carbon::parse($request->last_maintenance) : now();
        $equipment = Equipment::create([
            'name'             => $request->name,
            'category'         => $request->category,
            'status'           => $request->status,
            'last_maintenance' => $lastDate,
            'next_maintenance' => $lastDate->copy()->addDays(15),
        ]);

        ActivityLog::record('Added new equipment', $equipment->name, 'fa-dumbbell', 'purple');

        return redirect()->route('equipment.index')->with('success', 'Equipment added!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'category' => 'required',
            'status'   => 'required|in:good,under_repair,broken',
        ]);

        $equipment           = Equipment::where('equipment_id', $id)->firstOrFail();
        $equipment->name     = $request->name;
        $equipment->category = $request->category;
        $equipment->status   = $request->status;

        if ($request->status == 'good') {
            $equipment->last_maintenance = now();
            $equipment->next_maintenance = now()->addDays(15);
        }

        $equipment->save();

        ActivityLog::record('Updated equipment status', $equipment->name . ' → ' . ucfirst(str_replace('_', ' ', $equipment->status)), 'fa-screwdriver-wrench', 'amber');

        return redirect()->route('equipment.index')->with('success', 'Equipment updated!');
    }

    public function destroy($id)
    {
        $equipment = Equipment::where('equipment_id', $id)->firstOrFail();

        ActivityLog::record('Deleted equipment', $equipment->name, 'fa-trash', 'red');

        $equipment->delete();

        return redirect()->route('equipment.index')->with('success', 'Equipment deleted!');
    }

    public function create()
    {
        return view('equipment-create');
    }
}