window.aboutSlider = null;


document.addEventListener('DOMContentLoaded', () => {
    // --- Password Toggle Logic ---
    const toggleBtn = document.getElementById('toggleAdminPass');
    const passInput = document.getElementById('adminPassInput');
    const eyeIcon = document.getElementById('eyeIcon');

    if (toggleBtn && passInput) {
        toggleBtn.addEventListener('click', function() {
            const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passInput.setAttribute('type', type);

            if (eyeIcon) {
                eyeIcon.classList.toggle('fa-eye', type === 'password');
                eyeIcon.classList.toggle('fa-eye-slash', type !== 'password');
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const refundForm = document.getElementById('refundForm');
    if (refundForm) {
        refundForm.addEventListener('submit', function(e) {
            e.preventDefault();

            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => {
                if (res.ok) {
                    closeModal('userRefundModal');
                    refundForm.reset();
                    setTimeout(() => openModal('successModal'), 350);
                }
            })
            .catch(() => {
                alert('Something went wrong. Please try again.');
            });
        });
    }
});

/**
 * Image Slider Navigation
 */
window.nextSlide = function() {
    if (window.aboutSlider) window.aboutSlider.changeImage(1);
};

window.prevSlide = function() {
    if (window.aboutSlider) window.aboutSlider.changeImage(-1);
};

/**
 * Refund & Admin Modal Controls (Smooth Transitions)
 */

// Universal function to open any modal smoothly
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add('is-open');
};

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.remove('is-open');
};
window.openRefundModal  = function() { window.openModal('userRefundModal'); };
window.closeRefundModal = function() { window.closeModal('userRefundModal'); };
window.openAdminLoginModal = function() { window.openModal('adminLoginModal'); };

// Shorthand helpers for your specific modals
window.openRefundModal = function() {
    window.openModal('userRefundModal');
};

window.openAdminLoginModal = function() {
    window.openModal('adminLoginModal');
};