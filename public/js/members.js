(function() {
    // 1. Universal Open Function
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'flex';
            // Force reflow for animation
            modal.offsetHeight; 
            modal.classList.add('is-open');
            modal.classList.add('active'); // Keeping 'active' for your existing CSS
        }
    };

    // 2. Function specifically for filling and opening the Edit Modal
    window.openEditMemberModal = function(memberId, firstName, lastName, contact, typeId) {
        console.log("Editing Member:", memberId);
        const form = document.getElementById('editMemberForm');
        
        if (!form) {
            console.error("Form 'editMemberForm' not found!");
            return;
        }

        // Set the Form Action URL
        form.action = '/members/' + memberId; 

        // Fill the inputs (Ensure these IDs exist in your HTML)
        const fieldMap = {
            'edit_first_name': firstName,
            'edit_last_name': lastName,
            'edit_contact_number': contact,
            'edit_membership_type_display': typeId
        };

        for (const [id, value] of Object.entries(fieldMap)) {
            const el = document.getElementById(id);
            if (el) el.value = value;
        }

        // Open the modal
        window.openModal('editMemberModal');
    };

    // 3. Universal Close Function (Snappy 200ms close)
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

    // 4. Close if clicking outside the box
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            window.closeModal(event.target.id);
        }
    });

    console.log("✅ Members script initialized and globally exposed.");
})();