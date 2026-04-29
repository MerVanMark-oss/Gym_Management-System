@extends('layout.app') 

@section('content')
<div class="staff-page"> 

    <div class="staff-header">
        <div class="title-group">
            <h1>Staff</h1>
            <p class="subtitle">Manage gym staff members</p>
        </div>

        <button type="button" class="add-staff-btn" onclick="openModal('addStaffModal')">
             <i class="fa-solid fa-plus"></i> add staff
        </button>
    </div>

    <div class="staff-stats-row">
        <a href="{{ route('staff.index') }}" class="card-link">
            <div class="card-staff total">
                <div class="card-icon staff-total bg-blue"><i class="fa-solid fa-users"></i></div>
                <div class="card-content-right">
                    <p>Total Staff</p>
                    <h3>{{ $totalStaff }}</h3>
                </div>
            </div>
        </a>

        <a href="{{ route('staff.index', ['status' => 'active']) }}" class="card-link">
            <div class="card-staff active">
                <div class="card-icon staff-active bg-yellow"><i class="fa-solid fa-user-check"></i></div>
                <div class="card-content-right">
                    <p>Active Today</p>
                    <h3>{{ $activeStaff }}</h3>
                </div>
            </div>
        </a>

        <a href="{{ route('staff.index', ['role' => 'all_instructors']) }}" class="card-link">
            <div class="card-staff instructor">
                <div class="card-icon staff-instructor bg-purple">
                    <i class="fa-solid fa-chalkboard-teacher"></i>
                </div>
                <div class="card-content-right">
                    <p>Instructors</p>
                    <h3>{{ $instructorsCount }}</h3>
                </div>
            </div>
        </a>

        <a href="{{ route('staff.index', ['status' => 'on_leave']) }}" class="card-link">
            <div class="card-staff on-leave">
                <div class="card-icon staff-on-leave bg-gray"><i class="fa-solid fa-mug-hot"></i></div>
                <div class="card-content-right">
                    <p>On Leave</p>
                    <h3>{{ $onLeave }}</h3>
                </div>
            </div>
        </a>
    </div>

    <div class="table-wrapper">
        <div class="staff-grid-row header-row">
            <div>StaffID</div>
            <div>Name</div>
            <div>Email</div>
            <div>Contact</div>
            <div>Status</div>
            <div>Shift</div>
            <div>Hire Date</div>
            <div>Action</div>
        </div>

        @foreach($staffMembers as $staff)
        <div class="staff-grid-row">
            <div>#{{ $staff->staff_id }}</div>
            <div>{{ $staff->name }}</div>
            <div>{{ $staff->email }}</div>
            <div>{{ $staff->contact }}</div>
            <div>
                <span class="status-pill {{ $staff->status == 'active' ? 'good' : 'amber' }}">
                    {{ ucfirst(str_replace('_', ' ', $staff->status)) }}
                </span>
            </div>
            <div>{{ $staff->shift }}</div>
            <div>{{ \Carbon\Carbon::parse($staff->hire_date)->format('M d, Y') }}</div>
            <div>
                <button class="edit-btn" onclick="openEditStaffModal({{ json_encode($staff) }})">
                    <i class="fa-solid fa-pen"></i>
                </button>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination centered at bottom --}}
    <div class="pagination-container">
        {{ $staffMembers->links() }}
    </div>
</div>

{{-- --- ADD MODAL --- --}}
<div id="addStaffModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Staff Member</h3>
        </div>
        <form action="{{ route('staff.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required placeholder="John Doe" class="form-control">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required placeholder="john@example.com" class="form-control">
                </div>
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact" required placeholder="09123456789" class="form-control">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control" required>
                        <option value="Coach">Coach</option>
                        <option value="Trainer">Trainer</option>
                        <option value="Receptionist">Receptionist</option>
                        <option value="Manager">Manager</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="active">Active</option>
                        <option value="on_leave">On Leave</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Shift</label>
                    <select name="shift" class="form-control" required>
                        <option value="Morning">Morning</option>
                        <option value="Afternoon">Afternoon</option>
                        <option value="Evening">Evening</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Hire Date</label>
                    <input type="date" name="hire_date" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('addStaffModal')">Cancel</button>
                <button type="submit" class="btn-register">Register Staff</button>
            </div>
        </form>
    </div>
</div>

{{-- --- EDIT MODAL --- --}}
<div id="editStaffModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Update Staff Information</h3>
        </div>
        <form id="editStaffForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" id="edit_staff_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" id="edit_staff_email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact" id="edit_staff_contact" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Shift Schedule</label>
                    <select name="shift" id="edit_staff_shift" class="form-control">
                        <option value="Morning">Morning</option>
                        <option value="Afternoon">Afternoon</option>
                        <option value="Evening">Evening</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Employment Status</label>
                    <select name="status" id="edit_staff_status" class="form-control">
                        <option value="active">Active</option>
                        <option value="on_leave">On Leave</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('editStaffModal')">Cancel</button>
                <button type="submit" class="btn-register">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/staff.js') }}"></script>
@endpush