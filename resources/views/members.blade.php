@extends('layout.app')

@section('content')
<div class="members-page"> 

    <div class="members-header">
        <div class="title-group">
        <h1>Members</h1>
       <p class="subtitle">Manage your members</p>
       </div>

       <button type="button" class="add-member-btn" onclick="openModal('addMemberModal')">
            <i class="fa-solid fa-plus"></i> add member
        </button>
    </div>

    <div class="search-filter-container">
        <form action="{{ route('members.index') }}" method="GET" class="search-bar">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Pangitaa sa ngalan..." onchange="this.form.submit()">
        </form>
        
        <button class="filter-btn"><i class="fa-solid fa-filter"></i> Filter</button>
    </div>

    <div class="table-wrapper">
    <div class="member-grid-row header-row">
        <div>ID</div>
        <div>First Name</div>
        <div>Last Name</div>
        <div>Contact #</div>
        <div>Join Date</div>
        <div>Subscription</div>
        <div>Status</div>
        <div>Action</div>
    </div>

    @foreach($members as $member)
    <div class="member-grid-row">
        <div>{{ $member->member_id }}</div>
        <div>{{ $member->first_name }}</div>
        <div>{{ $member->last_name }}</div>
        <div>{{ $member->contact_number }}</div>
        <div>{{ $member->join_date }}</div>
        <div>{{ $member->membershipType->name ?? 'N/A' }}</div>
        <div>
            <span class="status-pill {{ strtolower($member->status) }}">
                {{ $member->status }}
            </span>
        </div>
        <div>
          <button type="button" class="edit-btn" 
                onclick="openEditMemberModal(
                    '{{ $member->member_id }}', 
                    '{{ $member->first_name }}', 
                    '{{ $member->last_name }}', 
                    '{{ $member->contact_number }}', 
                    '{{ $member->membership_type_id }}'
                )">
                <i class="fa-solid fa-pen"></i>
            </button>
        </div>
    </div>
    @endforeach
</div> {{-- End Table Wrapper --}}

<div class="pagination-container">
    {{ $members->links() }}
</div>

<div id="addMemberModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add Member</h3>
        </div>

        <form action="{{ route('members.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" required placeholder="Ex: John">
                </div>

                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" required placeholder="Ex: Smith">
                </div>

                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact_number" required placeholder="09xxxxxxxxx">
                </div>

                <div class="form-group">
                    <label>Type of Subscription</label>
                    <select name="membership_type_id" required>
                        <option value="" disabled selected>Select Plan</option>
                        @forelse($membershipTypes as $type)
                            <option value="{{ $type->id }}">
                                {{ $type->name }} (₱{{ number_format($type->price, 2) }})
                            </option>
                        @empty
                            <option value="" disabled>No plans available</option>
                        @endforelse
                    </select>
                </div>
            </div>

            <div class="modal-footer">
              
                 <button type="button" class="btn-cancel" onclick="closeModal('addMemberModal')">Cancel</button>
                <button type="submit" class="btn-register">Register</button>
                
            </div>
        </form>
    </div>
</div>

<!-- EDIT MODAL -->
<!-- EDIT MODAL -->

<div id="editMemberModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Member Profile</h3>
        </div>

        <form id="editMemberForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" id="edit_first_name" required>
                </div>

                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" id="edit_last_name" required>
                </div>

                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact_number" id="edit_contact_number" required>
                </div>

                <div class="form-group">
                    <label>Membership Plan (Locked)</label>
                    <select id="edit_membership_type_display" disabled style="background-color: #575454; cursor: not-allowed;">
                        @foreach($membershipTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

          <div class="modal-footer">
           <button type="button" class="btn-cancel" onclick="closeModal('editMemberModal')">Cancel</button>
            <button type="submit" class="btn-register">Update Profile</button>
         </div>

        </form>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/members.js') }}"></script>
@endpush
@endsection