// public/js/admin.js

// 1. Universal Open Function
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.style.display = 'flex';
    modal.classList.add('is-open');
    modal.classList.add('active');

    // Security: Restrict Super Admin role assignment
    const pageEl = document.querySelector('.adminstaff-page');
    const currentUserRole = pageEl ? pageEl.getAttribute('data-user-role') : null;
    const roleSelect = modal.querySelector('select[name="role"]');

    if (roleSelect && currentUserRole !== 'super_admin') {
        const superAdminOption = roleSelect.querySelector('option[value="super_admin"]');
        if (superAdminOption && !superAdminOption.text.includes("(Restricted)")) {
            superAdminOption.disabled = true;
            superAdminOption.text += " (Restricted)";
        }
    }
};

// 2. Edit Admin Modal Logic
// public/js/admin.js

window.openEditAdminModal = function(id, username, familyname, contact, role, status) {
    const modal = document.getElementById('editAdminModal');
    const form = document.getElementById('editAdminForm');
    
    if (modal && form) {
        // 1. ANOMALY FIX: Ensure ID exists and use absolute path
        if (!id) {
            console.error("Error: User ID is missing!");
            return;
        }
        
        // Use backticks and a leading slash to ensure the URL is /adminstaff/{id}
        form.action = `/adminstaff/${id}`; 
        
        console.log("Updating User ID:", id);
        console.log("Form Action Target:", form.action);

        document.getElementById('edit_password').value = '';
        document.getElementById('edit_familyname').value = familyname;
        
        const roleGroup = document.getElementById('role_group');
        const statusGroup = document.getElementById('status_group');
        const roleSelect = document.getElementById('edit_role');
        const statusSelect = document.getElementById('edit_status');

        if (roleSelect) roleSelect.value = role;
        if (statusSelect) statusSelect.value = status;

        // Security logic remains the same
        if (role === 'super_admin') {
            roleGroup.style.display = 'none';
            statusGroup.style.display = 'none';
            document.getElementById('editModalTitle').innerText = "Update Super Admin Credentials";
        } else {
            roleGroup.style.display = 'block';
            statusGroup.style.display = 'block';
            document.getElementById('editModalTitle').innerText = "Edit User Profile";
        }

        window.openModal('editAdminModal');
    }
};

// Toggle Visibility for the Edit Password field
window.toggleEditPassword = function() {
    const passInput = document.getElementById('edit_password');
    const eyeIcon = document.getElementById('editEyeIcon');
    
    if (passInput.type === 'password') {
        passInput.type = 'text';
        eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        passInput.type = 'password';
        eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
    }
};

// 3. Universal Close Function
window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('is-open');
        modal.classList.remove('active');
        setTimeout(() => {
            if (!modal.classList.contains('is-open')) {
                modal.style.display = 'none';
            }
        }, 200);
    }
};

// 4. Outside Click Listener
window.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        window.closeModal(event.target.id);
    }
});

// 5. Password Toggle
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('toggleAdminPass');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const passInput = document.getElementById('adminPassInput');
            const eyeIcon = document.getElementById('eyeIcon');
            const isHidden = passInput.type === 'password';
            passInput.type = isHidden ? 'text' : 'password';
            
            eyeIcon.className = isHidden ? "fa-solid fa-eye-slash" : "fa-solid fa-eye";
        });
    }
});