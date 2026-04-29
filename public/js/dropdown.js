document.addEventListener('DOMContentLoaded', function () {
    // --- PAYMENT MODAL LOGIC ---
    const searchInput = document.getElementById('payment_member_search');
    const memberList  = document.getElementById('member_list');
    const submitBtn   = document.getElementById('submitPaymentBtn');
    const planSelector = document.getElementById('plan_selector');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const typed = this.value.trim().toLowerCase();
            const options = memberList.querySelectorAll('option');
            let matched = null;

            options.forEach(opt => {
                if (opt.value.toLowerCase() === typed) matched = opt;
            });

            if (matched) {
                document.getElementById('payment_member_id').value = matched.getAttribute('data-id');
                document.getElementById('payment_amount_hidden').value = matched.getAttribute('data-price');
                if (planSelector) planSelector.value = matched.getAttribute('data-plan-id');
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        });
    }

    // --- REFUND MODAL LOGIC (ADD THIS SECTION) ---
    const refundSearch = document.getElementById('refund_member_search');
    const refundList   = document.getElementById('refund_eligible_list');
    const refundPlan   = document.getElementById('refund_plan_display');
    const refundId     = document.getElementById('refund_member_id');
    const refundBtn    = document.getElementById('submitRefundBtn');

    if (refundSearch) {
        refundSearch.addEventListener('input', function () {
            const typed = this.value.trim().toLowerCase();
            const options = refundList.querySelectorAll('option');
            let matched = null;

            options.forEach(opt => {
                if (opt.value.toLowerCase() === typed) matched = opt;
            });

            if (matched) {
                // 1. Fill Hidden ID
                refundId.value = matched.getAttribute('data-id');
                
                // 2. Auto-fill the Display Plan name
                refundPlan.value = matched.getAttribute('data-plan-name');

                // 3. Enable Button
                refundBtn.disabled = false;
                refundBtn.style.opacity = "1";
            } else {
                // Reset if cleared
                refundId.value = '';
                refundPlan.value = '';
                refundBtn.disabled = true;
                refundBtn.style.opacity = "0.5";
            }
        });
    }
});

function updateHiddenPrice(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    document.getElementById('payment_amount_hidden').value = price;
}

document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('toggleAdminPass');
    const passInput = document.getElementById('adminPassInput');
    const eyeIcon = document.getElementById('eyeIcon');

    // Toggle Password Visibility
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const isHidden = passInput.type === 'password';
            passInput.type = isHidden ? 'text' : 'password';
            
            eyeIcon.innerHTML = isHidden
                ? `<line x1="2" y1="2" x2="22" y2="22"/><path d="M6.71 6.71A10 10 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 4.38-1.01"/><path d="M9.88 9.88A3 3 0 0 0 12 15a3 3 0 0 0 2.12-.88"/><path d="M17.94 17.94A10 10 0 0 0 22 12s-3-7-10-7a9.74 9.74 0 0 0-4.38 1.01"/>`
                : `<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>`;
        });
    }
});

