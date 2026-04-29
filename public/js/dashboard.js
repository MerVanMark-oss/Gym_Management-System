window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        modal.offsetHeight; // Force reflow
        modal.classList.add('is-open');
    }
};

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        // 1. Remove the 'is-open' class to trigger the CSS fade-out
        modal.classList.remove('is-open');

        // 2. Instead of waiting for 'transitionend', hide it after 200ms
        // This is usually faster than the browser's transition event
        setTimeout(() => {
            if (!modal.classList.contains('is-open')) {
                modal.style.display = 'none';
            }
        }, 200); // 200ms is the "sweet spot" for UI snappiness
    }
};

console.log("✅ Sidebar & Modal Controllers Ready");


document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('membershipDonut').getContext('2d');
    
    // Data passed from Controller (using Blade json directive)
   const data = window.membershipStats;
    
    const labels = data.map(item => item.name);
    const counts = data.map(item => item.members_count);

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: counts,
                backgroundColor: [
                    '#e0e0dd', // Red (Monthly)
                    '#bd9c42', // Blue (Weekly)
                    '#353532', // Yellow (Walk-in)
                    '#70c04b', // Teal (Other)
                    '#9966ff'  // Purple
                ],
                borderColor: '#020202',
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'center',
                    labels: {
                        boxWidth: 10,
                        padding: 10,
                        color: '#000', // Black as requested for your theme
                        font: { size: 10, weight: 'bold' }
                    }
                }
            },
            cutout: '60%' // This creates the "hole" in the middle
        }
    });
});

 // --- Member Growth Bar Chart ---
document.addEventListener('DOMContentLoaded', function() {
    // --- Member Growth Bar Chart ---
    const growthCanvas = document.getElementById('growthBarChart');
    if (growthCanvas) {
        const growthCtx = growthCanvas.getContext('2d');
        const growthData = window.growthStats || [];

        new Chart(growthCtx, {
            type: 'bar',
            data: {
                labels: growthData.map(item => item.month),
                datasets: [{
                    label: 'New Members',
                    data: growthData.map(item => item.count),
                    backgroundColor: '#686767', // Blue accent
                    borderColor: '#000000',     // Black borders
                    borderWidth: 2,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
        padding: 20
    },
                plugins: {
                    legend: { display: false } // Hide legend for a cleaner look
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#000', stepSize: 1 },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        ticks: { color: '#000' },
                        grid: { display: false }
                    }
                }
            }
        });
    }
});