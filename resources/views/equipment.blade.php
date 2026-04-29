@extends('layout.app') @section('content')
<div class="equipment-page"> 

    <div class="equipments-header">
        <div class="title-group">
            <h1>Equipment</h1>
            <p class="subtitle">track and manage equipment</p>
        </div>
       <button type="button" class="add-equipment-btn" onclick="openModal('addEquipmentModal')">
    <i class="fa-solid fa-plus"></i> Add Equipment
</button>

    </div>

   <div class="equipment-stats-row">

    <a href="{{ route('equipment.index') }}" class="card-link">
        <div class="card-equipment total">
            
                <div class="card-icon equipment-total bg-purple">
                    <i class="fa-solid fa-dumbbell"></i>
                </div>
    
                <div class="card-content-right">
                 <p>Total Equipment</p>
                 <h3>{{ $totalEquipment }}</h3>
             </div>

        </div>
    </a>

    <a href="{{ route('equipment.index', ['status' => 'good']) }}" class="card-link">
        <div class="card-equipment good">
                <div class="card-icon equipment-good bg-blue">
                    <i class="fa-solid fa-thumbs-up"></i>
                </div>

                 <div class="card-content-right">
                <p>Good Condition</p>
                 <h3>{{ $goodCondition }}</h3>
            </div>
        </div>
    </a>

    <a href="{{ route('equipment.index', ['status' => 'maintenance']) }}" class="card-link">
        <div class="card-equipment maintenance">
                <div class="card-icon equipment-maintenance bg-amber">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>

                <div class="card-content-right">
                    <p>Need Maintenance</p>
                 <h3>{{ $needMaintenanceCount }}</h3>
                </div>
        </div>
    </a>

    <a href="{{ route('equipment.index', ['status' => 'repair']) }}" class="card-link">
        <div class="card-equipment repair">
            
                <div class="card-icon equipment-repair bg-gray">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>

               <div class="card-content-right">
                <p>Under Repair</p>
                <h3>{{ $underRepair }}</h3>
                </div>
        </div>
    </a>
</div>

    <div class="filter-section-equipment">
    <form method="GET" action="{{ route('equipment.index') }}">
        <div class="filter-group">
            <label>Category</label>
            <select name="category" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="Cardio" {{ request('category') == 'Cardio' ? 'selected' : '' }}>Cardio</option>
                <option value="Strength" {{ request('category') == 'Strength' ? 'selected' : '' }}>Strength</option>
                <option value="Weights" {{ request('category') == 'Weights' ? 'selected' : '' }}>Weights</option>
                <option value="Flexibility" {{ request('category') == 'Flexibility' ? 'selected' : '' }}>Flexibility</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select name="status" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="good" {{ request('status') == 'good' ? 'selected' : '' }}>Good</option>
                <option value="broken" {{ request('status') == 'broken' ? 'selected' : '' }}>Need Maintenance</option>
                <option value="under_repair" {{ request('status') == 'under_repair' ? 'selected' : '' }}>Under Repair</option>
            </select>
        </div>
    </form>
</div>

    <div class="table-wrapper">
        <div class="equipment-grid-row header-row">
            <div>Equipment</div>
            <div>Category</div>
            <div>Status</div>
            <div>Last Maintenance</div>
            <div>Next Maintenance</div>
            <div>Action</div>
        </div>

        @forelse($equipments as $item)
        <div class="equipment-grid-row">
            <div class="equipment-name"><strong>{{ $item->name }}</strong></div>
            
            <div>{{ $item->category }}</div>
        
            
            <div>
                <span class="status-pill {{ $item->status }}">
                    {{ str_replace('_', ' ', ucfirst($item->status)) }}
                </span>
            </div>
            
            <div>{{ $item->last_maintenance ? \Carbon\Carbon::parse($item->last_maintenance)->format('M d, Y') : 'Never' }}</div>
            <div>{{ $item->next_maintenance ? \Carbon\Carbon::parse($item->next_maintenance)->format('M d, Y') : 'TBD' }}</div>
            
            <div>
                <button type="button" class="edit-btn" 
                    onclick="openEditEquipmentModal(
                        '{{ $item->equipment_id }}', 
                        '{{ addslashes($item->name) }}', 
                        '{{ $item->category }}', 
                        '{{ $item->status }}'
                    )">
                    <i class="fa-solid fa-pen"></i>
                </button>
            </div>
        </div>
    @empty
      <div class="equipment-grid-row" style="grid-template-columns: 1fr; text-align: center; padding: 20px;">
            <div>No equipment found in the database.</div>
        </div>
    @endforelse
    </div>
     <div class="pagination-container">
        {{ $equipments->appends(request()->query())->links() }}
    </div>



<div id="addEquipmentModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Equipment</h3>
        </div>

        <form action="{{ route('equipment.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Equipment Name</label>
                    <input type="text" name="name" required placeholder="Ex: Treadmill X100">
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="" disabled selected>Select Category</option>
                        <option value="Cardio">Cardio</option>
                        <option value="Strength">Strength</option>
                        <option value="Weights">Weights</option>
                        <option value="Flexibility">Flexibility</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Equipment Status</label>
                    <select name="status" required>
                        <option value="good">Good / Functional</option>
                        <option value="under_repair">Under Repair</option>
                        <option value="broken">Needs Maintenance (Broken)</option> 
                    </select>
                </div>

                <div class="form-group">
                    <label>Last Maintenance</label>
                    <input type="date" name="last_maintenance" value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEquipmentModal('addEquipmentModal')">Cancel</button>
                <button type="submit" class="btn-register">Save Equipment</button>
            </div>
        </form>
    </div>
</div>


<div id="editEquipmentModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
    <h3>Edit Equipment</h3>
    <form id="deleteEquipmentForm" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="button" class="delete-equipment-btn" title="Delete Equipment"
            onclick="confirmDeleteEquipment()">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
</div>

        <form id="editEquipmentForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Equipment Name</label>
                    <input type="text" name="name" id="edit_equipment_name" required>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category" id="edit_equipment_category" required>
                        <option value="Cardio">Cardio</option>
                        <option value="Strength">Strength</option>
                        <option value="Weights">Weights</option>
                        <option value="Flexibility">Flexibility</option> </select>
                </div>

                <div class="form-group">
                    <label>Equipment Status</label>
                    <select name="status" id="edit_equipment_status" required>
                        <option value="good">Good / Functional</option>
                        <option value="under_repair">Under Repair</option>
                        <option value="broken">Needs Maintenance</option> 
                    </select>
                </div>
            </div> <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEquipmentModal('editEquipmentModal')">Cancel</button>
                <button type="submit" class="btn-register">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
    <script src="{{ asset('js/equipment.js') }}"></script>
@endpush
