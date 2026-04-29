// Function to open the Add Staff Modal
// Universal function to open any modal
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
    } else {
        console.error("Modal with ID " + modalId + " not found!");
    }
}

// Function to close any modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// --- NEW: THE EDIT MODAL LOGIC ---
function openEditStaffModal(staff) {
    const modal = document.getElementById('editStaffModal');
    const form = document.getElementById('editStaffForm');

    // 1. Set the dynamic form action (e.g., /staff/5)
    form.action = `/staff/${staff.staff_id}`;

    // 2. Fill the text inputs
    document.getElementById('edit_staff_name').value = staff.name;
    document.getElementById('edit_staff_email').value = staff.email;
    document.getElementById('edit_staff_contact').value = staff.contact;

    // 3. Auto-select the Combo Boxes (Dropdowns)
    // This ensures the current Shift and Status are selected automatically
    const shiftSelect = document.getElementById('edit_staff_shift');
    const statusSelect = document.getElementById('edit_staff_status');

    if (shiftSelect) shiftSelect.value = staff.shift;
    if (statusSelect) statusSelect.value = staff.status;

    // 4. Show the modal
    modal.style.display = 'flex';
}

// Close modals if user clicks outside the modal content box
window.onclick = function(event) {
    const addModal = document.getElementById('addStaffModal');
    const editModal = document.getElementById('editStaffModal');

    if (event.target == addModal) {
        addModal.style.display = 'none';
    }
    if (event.target == editModal) {
        editModal.style.display = 'none';
    }
}