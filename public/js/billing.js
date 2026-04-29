window.openPaymentModal = function(id = null, name = null, planId = null, price = null) {
    const modal = document.getElementById('paymentModal');
    const searchInput = document.getElementById('payment_member_search');
    const memberIdHidden = document.getElementById('payment_member_id');
    const amountHidden = document.getElementById('payment_amount_hidden');
    const planSelector = document.getElementById('plan_selector');
    const submitBtn = document.getElementById('submitPaymentBtn');
    const form = document.getElementById('paymentForm');

    if (modal) {
        // Reset form state
        if(form) form.reset();
        if(submitBtn) submitBtn.disabled = true;

        if (id && name) {
            // TABLE MODE: Triggered from a specific row's "Pay" button
            searchInput.value = name;
            searchInput.readOnly = true; // Prevent changing name if coming from a specific row
            memberIdHidden.value = id;
            amountHidden.value = price;
            
            if(planSelector && planId) planSelector.value = planId;
            if(submitBtn) submitBtn.disabled = false;
        } else {
            // QUICK ACTION MODE: Manual search
            searchInput.value = "";
            searchInput.readOnly = false;
            memberIdHidden.value = "";
            amountHidden.value = "";
        }

        modal.style.display = 'flex';
        modal.classList.add('active');
    }
};

// Logic for the Searchable Datalist
// Updated Searchable Datalist Logic
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('payment_member_search');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const val = this.value;
            const list = document.getElementById('member_list');
            const memberIdHidden = document.getElementById('payment_member_id');
            const amountHidden = document.getElementById('payment_amount_hidden');
            const planSelector = document.getElementById('plan_selector');
            const submitBtn = document.getElementById('submitPaymentBtn');

            let matchFound = false;

            // Iterate through datalist options
            if (list) {
                Array.from(list.options).forEach(option => {
                    if (option.value === val) {
                        const id = option.getAttribute('data-id');
                        const price = option.getAttribute('data-price');
                        const planId = option.getAttribute('data-plan-id'); // Now it works!

                        if (memberIdHidden) memberIdHidden.value = id;
                        if (amountHidden) amountHidden.value = price;
                        
                        // Auto-select the member's current plan in the dropdown
                        if (planSelector && planId) {
                            planSelector.value = planId;
                        }
                        
                        matchFound = true;
                    }
                });
            }

            if (submitBtn) submitBtn.disabled = !matchFound;
        });
    }
});

// Update price if the Admin manually changes the Plan dropdown
window.updateHiddenPrice = function(select) {
    const selectedOption = select.options[select.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    const amountHidden = document.getElementById('payment_amount_hidden');
    
    if (amountHidden && price) {
        amountHidden.value = price;
        console.log("Updated hidden amount to: ₱" + price);
    }
};

window.closeEquipmentModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        modal.style.display = 'none';
        
        // Reset search input if it was set to readOnly
        const searchInput = document.getElementById('payment_member_search');
        if(searchInput) searchInput.readOnly = false;
    }
};

window.viewRefundDetails = function(name, plan, reason, status, disbursementStatus, disbursementDate, createdAt) {
    document.getElementById('view_member_name').value        = name;
    document.getElementById('view_membership_type').value   = plan;
    document.getElementById('view_reason').value            = reason;
    document.getElementById('view_status').value            = status;
    document.getElementById('view_disbursement_status').value = disbursementStatus === 'pending_disbursement' 
                                                                ? 'Awaiting Disbursement' 
                                                                : 'Refund Disbursed';
    document.getElementById('view_disbursement_date').value = disbursementDate;
    document.getElementById('view_created_at').value        = createdAt;

    openModal('viewRefundModal');
};