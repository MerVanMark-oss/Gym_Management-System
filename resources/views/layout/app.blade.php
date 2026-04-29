<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Management System</title>
@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/sidebar.js', 'resources/js/dashboard.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>


<body data-user-role="{{ auth()->guard('admin')->user()->role }}">
    <div class="container">

        <div class="sidebar">
    <div class="sidebar-logo">
       <a href="{{ auth()->guard('admin')->user()?->role === 'staff' ? route('members.index') : route('dashboard') }}">
            <img src="{{ asset('images/final-logo-crown.png') }}" 
                 alt="Crown Fitness Logo"
                 class="main-logo">
        </a>
    </div>
    
    <ul>
    @can('access-admin-only')
    <li class="{{ Route::is('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> Dashboard</a>
    </li>
    @endcan

    <li class="{{ Route::is('members.*') ? 'active' : '' }}">
        <a href="{{ route('members.index') }}"><i class="fa-solid fa-users"></i> Members</a>
    </li>

    @can('access-admin-only')
    <li class="{{ Route::is('adminstaff.*') ? 'active' : '' }}">
        <a href="{{ route('adminstaff.index') }}">
            <i class="fa-solid fa-user-shield"></i> AdminStaff
        </a>
    </li>
    @endcan

    <li class="{{ Request::is('equipment*') ? 'active' : '' }}">
        <a href="{{ route('equipment.index') }}">
            <i class="fa-solid fa-dumbbell"></i> Equipments
        </a>
    </li>

    <li class="{{ Request::is('staff*') ? 'active' : '' }}">
        <a href="{{ route('staff.index') }}">
            <i class="fa-solid fa-user-tie"></i> Staff
        </a>
    </li>

    <li class="{{ Request::is('billing*') ? 'active' : '' }}">
        <a href="{{ route('billing.index') }}">
            <i class="fa-solid fa-file-invoice-dollar"></i> Billing
        </a>
    </li>

 <li class="sidebar-user-profile" onclick="openModal('logoutConfirmModal')" style="cursor: pointer;">
    <div class="user-avatar" style="pointer-events: none;"> <i class="fa-solid fa-right-from-bracket"></i>
    </div>

    <div class="user-info">
        <h3 class="user-name">{{ auth()->guard('admin')->user()->familyname }}</h3>
        <p class="user-role">{{ ucfirst(auth()->guard('admin')->user()->role) }}</p>
    </div>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</li>
</ul>
</div>

{{-- LOGOUT CONFIRM MODAL --}}
<div id="logoutConfirmModal" class="modal-overlay">
    <div class="modal-card" style="max-width: 340px;">
        
        <div style="width: 52px; height: 52px; background: #2e1a1a; border: 1px solid #5c2a2a; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i class="fa-solid fa-right-from-bracket" style="color: #ff4d4d; font-size: 1.2rem;"></i>
        </div>

        <h2 class="modal-title" style="font-size: 1.1rem; margin-bottom: 10px;">Confirm Logout</h2>
        
        <p style="color: #aaa; font-size: 0.88rem; line-height: 1.7; font-family: 'Poppins', sans-serif; margin-bottom: 24px;">
            Are you sure you want to log out of the Admin Hub?
        </p>

        <div style="display: flex; gap: 10px;">
            <button type="button" 
                    onclick="closeModal('logoutConfirmModal')" 
                    class="btn-modal-secondary">
                Cancel
            </button>
            
            <button type="button" 
                    onclick="document.getElementById('logout-form').submit();" 
                    class="btn-modal-danger">
                Yes, Logout
            </button>
        </div>
    </div>
</div>

        <div class="main-contents">
            @yield('content')
        </div>
        
    </div>
    @stack('scripts')
</body>
</html>