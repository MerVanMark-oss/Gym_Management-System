function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add('active');
}

function closeEquipmentModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        // We don't need 'display: none' here because the CSS does it 
        // when 'active' is removed.
    }
}

function openEditEquipmentModal(id, name, category, status) {
    const modal = document.getElementById('editEquipmentModal');
    const form = document.getElementById('editEquipmentForm');
    const deleteForm = document.getElementById('deleteEquipmentForm');

    if (modal && form) {
        form.action = '/equipment/' + id;
        deleteForm.action = '/equipment/' + id; // ← ADD THIS

        document.getElementById('edit_equipment_name').value = name;
        document.getElementById('edit_equipment_category').value = category;
        document.getElementById('edit_equipment_status').value = status;

        modal.classList.add('active');
    }
}

window.confirmDeleteEquipment = function() {
    if (confirm('Are you sure you want to permanently delete this equipment?')) {
        document.getElementById('deleteEquipmentForm').submit();
    }
};