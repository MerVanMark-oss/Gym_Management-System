@extends('layout.app')

@section('content')
{{-- Added data-user-role so admin.js can restrict Super Admin role assignment --}}
<div class="adminstaff-page" data-user-role="{{ auth()->guard('admin')->user()->role }}"> 
    <link rel="stylesheet" href="{{ asset('css/adminstaff.css') }}">

    <div class="adminstaff-header">
        <div class="title-group">
            <h1>AdminStaff Management</h1>
            <p class="subtitle">Manage system users and access levels</p>
        </div>

        <button type="button" class="Add-admin-staff" onclick="openModal('addAdminModal')">
            <i class="fa-solid fa-plus"></i> add user
        </button>
    </div>

    <div class="search-filter-container">
        <form action="{{ route('adminstaff.index') }}" method="GET" class="search-bar">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Pangitaa sa username..." onchange="this.form.submit()">
        </form>
        <button class="filter-btn"><i class="fa-solid fa-filter"></i> Filter</button>
    </div>

    <div class="table-wrapper">
        <div class="staff-grid-row staff-header-row">
            <div>User ID</div>
            <div>FamilyName</div>
            <div>Username</div>
            <div>Role</div>
            <div>Status</div>
            <div>Password</div>
            <div>Action</div>
        </div>

        @foreach($admins as $user)
        <div class="staff-grid-row">
            <div>#{{ $user->user_id }}</div>
            <div style="font-weight: 600;">{{ $user->familyname }}</div>
            <div>{{ $user->username }}</div>
            <div>
                <span class="role-pill {{ $user->role }}">{{ strtoupper(str_replace('_', ' ', $user->role)) }}</span>
            </div>
            <div>
                {{-- Updated Status Pill with Icons --}}
                <span class="status-pill {{ $user->status }}">
                    @if($user->status === 'suspended')
                        <i class="fa-solid fa-ban"></i> SUSPENDED
                    @elseif($user->status === 'inactive')
                        <i class="fa-solid fa-eye-slash"></i> INACTIVE
                    @else
                        <i class="fa-solid fa-check"></i> ACTIVE
                    @endif
                </span>
            </div>
            <div style="color: #555;">••••••••</div>

            <div class="action-cell">
                {{-- Permission Logic: Superadmin edits anyone, Admin only edits Staff --}}
                @if(auth()->guard('admin')->user()->role === 'super_admin' || (auth()->guard('admin')->user()->role === 'admin' && $user->role === 'staff'))
                    <button type="button" class="edit-btn" 
                        onclick="openEditAdminModal(
                            '{{ $user->user_id }}', 
                            '{{ $user->username }}', 
                            '{{ addslashes($user->familyname) }}', 
                            '{{ $user->contactnum }}', 
                            '{{ $user->role }}',
                            '{{ $user->status }}'
                        )">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                @else
                    <i class="fa-solid fa-lock" title="Protected Account" style="opacity: 0.5;"></i>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="pagination-container">
        @if(isset($admins) && $admins instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $admins->links() }}
        @endif
    </div>

    {{-- ADD ADMIN MODAL --}}
<div id="addAdminModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Staff / Admin</h3>
            <button type="button" class="close-btn" onclick="closeModal('addAdminModal')">&times;</button>
        </div>

        <form action="{{ route('adminstaff.store') }}" method="POST">
            @csrf
            {{-- DISPLAY ERRORS HERE: This is critical for knowing why the save failed --}}
            @if ($errors->any())
                <div style="background: #fee2e2; color: #991b1b; padding: 0.8rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.85rem;">
                    <strong>Dili ma-save:</strong>
                    <ul style="margin: 0; padding-left: 1.2rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-grid"> {{-- START OF SINGLE GRID --}}
                <div class="form-group">
                    <label>Family Name</label>
                    <input type="text" name="familyname" required placeholder="Full Name" value="{{ old('familyname') }}">
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="User_01" value="{{ old('username') }}">
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required placeholder="email@gym.com" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contactnum" required placeholder="09xxxxxxxxx" value="{{ old('contactnum') }}">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff (Receptionist)</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        @if(auth()->guard('admin')->user()->role === 'super_admin')
                            <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label>Initial Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="adminPassInput" required placeholder="********">
                        <button type="button" id="toggleAdminPass" 
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background:none; border:none; color:#aaa; cursor:pointer;">
                            <i id="eyeIcon" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div> {{-- END OF SINGLE GRID --}}

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('addAdminModal')">Cancel</button>
                <button type="submit" class="btn-register">Create Account</button>
            </div>
        </form>
    </div>
</div>

    {{-- EDIT ADMIN MODAL --}}
    <div id="editAdminModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="editModalTitle">Edit User</h3>
                <button type="button" class="close-btn" onclick="closeModal('editAdminModal')">&times;</button>
            </div>

            <form id="editAdminForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="form-group">
                        <label>Family Name</label>
                        <input type="text" name="familyname" id="edit_familyname" required>
                    </div>

                    <div class="form-group">
                        <label>Change Password</label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="edit_password" placeholder="Leave blank to keep current">
                            <button type="button" onclick="toggleEditPassword()" 
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background:none; border:none; color:#aaa; cursor:pointer;">
                                <i id="editEyeIcon" class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group" id="role_group">
                        <label>User Role</label>
                        <select name="role" id="edit_role">
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                            @if(auth()->guard('admin')->user()->role === 'super_admin')
                                <option value="super_admin">Super Admin</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group" id="status_group">
                        <label>Status</label>
                        <select name="status" id="edit_status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('editAdminModal')">Cancel</button>
                    <button type="submit" class="btn-register">Update Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/admin.js') }}"></script>
@endpush
@endsection