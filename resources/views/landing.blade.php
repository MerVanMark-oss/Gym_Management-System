<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Gym | Welcome</title>

    <script src="{{ asset('js/imageslider.js') }}" defer></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <script src="{{ asset('js/landing.js') }}" defer></script>
</head>

<script>
    // Initialize Slider
    document.addEventListener('DOMContentLoaded', () => {
        const gymImages = [
            "{{ asset('images/GYM-IMAGE1.jpg') }}",
            "{{ asset('images/GYM-IMAGE2.jpg') }}",
            "{{ asset('images/GYM-IMAGE3.jpg') }}"
        ];
        // Ensure this variable is global so buttons can see it
        window.aboutSlider = new ImageSlider('aboutSlider', gymImages);
    });

    function nextSlide() { aboutSlider.changeImage(1); }
    function prevSlide() { aboutSlider.changeImage(-1); }

    // Modal Control Functions
    function openRefundModal() {
        document.getElementById('userRefundModal').style.display = 'flex';
    }

    function closeRefundModal() {
        document.getElementById('userRefundModal').style.display = 'none';
    }

    // Close modal if clicking outside the box
    window.onclick = function(event) {
        let modal = document.getElementById('userRefundModal');
        if (event.target == modal) {
            closeRefundModal();
        }
    }
</script>

<script>

    // Initialize after the DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        const gymImages = [
            "{{ asset('images/GYM-IMAGE1.jpg') }}",
            "{{ asset('images/GYM-IMAGE2.jpg') }}",
            "{{ asset('images/GYM-IMAGE3.jpg') }}"
        ];
   

        // Create the slider instance
        aboutSlider = new ImageSlider('aboutSlider', gymImages);
    });

    // Wrapper functions for the HTML buttons
    function nextSlide() { aboutSlider.changeImage(1); }
    function prevSlide() { aboutSlider.changeImage(-1); }

</script>
<script>
    function openRefundModal() {
        const modal = document.getElementById('userRefundModal');
        if (modal) {
            modal.style.display = 'flex'; // Use 'flex' to center it
            console.log("Modal opened successfully"); // Check your browser console (F12) for this
        } else {
            console.error("Modal element not found!");
        }
    }

    function closeRefundModal() {
        const modal = document.getElementById('userRefundModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }
</script>
<body>

<div class="landing-container">
    <nav class="landing-nav">
        <div class="nav-links">
            <a href="{{ route('landing') }}" class="active">HOME</a>
            <a href="#about-section">ABOUT US</a>
            <a href="#contact-section">CONTACT</a>
            <a href="#services-section">SERVICES</a>
            <a href="javascript:void(0)" onclick="openRefundModal()">Refund</a>
            <a href="javascript:void(0)" onclick="openAdminLoginModal()" style="color: var(--gym-yellow); margin-left: 50px;">AdminHub</a>
        </div>
    </nav>

    <main class="hero-section">
        <div class="hero-content">
            <div class="fitness-icons">
                <span class="yellow-icon"><i class="fa-solid fa-dumbbell"></i></span>
                <span class="yellow-icon"><i class="fa-solid fa-bicycle"></i></span>
                <span class="yellow-icon"><i class="fa-solid fa-shirt"></i></span>
                <span class="yellow-icon"><i class="fa-solid fa-shoe-prints"></i></span>
            </div>
            <h1 class="hero-title gold-text">CROWN</h1>
            <div class="hero-subtitle">
                <span class="line"></span>
                <h2>FITNESS</h2>
                <span class="line"></span>
            </div>
            <p class="hero-description">
                Elevate your training with world-class equipment and expert coaching. 
                Experience a surge in strength and confidence at Davao's premier fitness center.
            </p>
            <button class="join-btn" onclick="document.getElementById('contact-section').scrollIntoView({behavior: 'smooth'})">
                                            JOIN NOW
                    </button>
        </div>

        <div class="hero-image-wrapper">
            <div class="circle-image-container">
                <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=2070&auto=format&fit=crop" alt="Fitness Model">
            </div>
        </div>
        
        <div class="dot-pattern"></div>
        <div class="slanted-lines"></div>
        <div class="yellow-triangles">
            <span></span><span></span><span></span>
        </div>
    </main>

    <section id="about-section" class="about-container">
        <div class="about-wrapper">
            <div class="about-text">
                <h2 class="about-title">About us</h2>
                <div class="about-description">
                    <p>Crown Fitness is a leading fitness center located in the heart of Davao City...</p>
                    <p>With affordable membership options...</p>
                    <p>Our certified trainers provide expert guidance...</p>
                </div>
            </div>

            <div class="about-visual">
                <div class="image-card">
                    <img src="{{ asset('images/GYM-IMAGE1.jpg') }}" alt="Gym Interior" id="aboutSlider">
                    <div class="slider-controls">
                        <div class="progress-bars">
                            <span class="bar active"></span>
                            <span class="bar"></span>
                            <span class="bar"></span>
                        </div>
                        <div class="arrow-buttons">
                            <button class="prev-btn" onclick="prevSlide()"><i class="fa-solid fa-arrow-left"></i></button>
                            <button class="next-btn" onclick="nextSlide()"><i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact-section" class="contact-container">
        <div class="contact-content">
            <h2 class="section-title">CONTACT <span class="gold-text">US</span></h2>
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="icon-box"><i class="fa-solid fa-location-dot"></i></div>
                    <h3>LOCATION</h3>
                    <p>123 Gym Street, Davao City, <br>Davao del Sur, Philippines</p>
                </div>
                <div class="contact-card">
                    <div class="icon-box"><i class="fa-solid fa-phone"></i></div>
                    <h3>PHONE</h3>
                    <p>+63 912 345 6789</p>
                </div>
                <div class="contact-card">
                    <div class="icon-box"><i class="fa-solid fa-envelope"></i></div>
                    <h3>EMAIL</h3>
                    <p>support@crownfitness.com</p>
                </div>
            </div>
        </div>
    </section>

    <section id="services-section" class="services-container">
    <div class="services-content">
        <h2 class="section-title">OUR <span class="gold-text">MEMBERSHIPS</span></h2>
        <p class="section-subtitle">Choose the plan that fits your fitness goals.</p>

        <div class="membership-grid">
            <div class="membership-card">
                <div class="card-header">
                    <h3>DAILY PASS</h3>
                    <div class="price">₱150<span>/day</span></div>
                </div>
                <ul class="features">
                    <li><i class="fa-solid fa-check"></i> Single Entry Access</li>
                    <li><i class="fa-solid fa-check"></i> Use of All Equipment</li>
                    <li><i class="fa-solid fa-check"></i> Locker Room Access</li>
                    <li class="disabled"><i class="fa-solid fa-xmark"></i> No Trainer Support</li>
                </ul>
                <button class="plan-btn" onclick="document.getElementById('contact-section').scrollIntoView({behavior: 'smooth'})">
                                                             GET STARTED
                </button>
            </div>

            <div class="membership-card featured">
                <div class="badge">POPULAR</div>
                <div class="card-header">
                    <h3>MONTHLY BASIC</h3>
                    <div class="price">₱1,000<span>/month</span></div>
                </div>
                <ul class="features">
                    <li><i class="fa-solid fa-check"></i> Unlimited Monthly Access</li>
                    <li><i class="fa-solid fa-check"></i> Free Gym Orientation</li>
                    <li><i class="fa-solid fa-check"></i> Use of All Equipment</li>
                    <li><i class="fa-solid fa-check"></i> 1 Guest Pass Per Month</li>
                </ul>
                <button class="join-btn gold-btn" onclick="document.getElementById('contact-section').scrollIntoView({behavior: 'smooth'})">
    JOIN NOW
</button>
            </div>

            <div class="membership-card">
                <div class="card-header">
                    <h3>VIP QUARTERLY</h3>
                    <div class="price">₱4,000<span>/3 months</span></div>
                </div>
                <ul class="features">
                    <li><i class="fa-solid fa-check"></i> Priority Equipment Use</li>
                    <li><i class="fa-solid fa-check"></i> 3 Personal Training Sessions</li>
                    <li><i class="fa-solid fa-check"></i> Free Crown Fitness Shirt</li>
                    <li><i class="fa-solid fa-check"></i> 24/7 Access Pass</li>
                </ul>
                <button class="plan-btn " onclick="document.getElementById('contact-section').scrollIntoView({behavior: 'smooth'})"> GO VIP
                    </button>
            </div>
        </div>
    </div>
</section>

</div> 

{{-- REFUND MODAL --}}
<div id="userRefundModal" class="modal-overlay">
    <div class="modal-card">
        <h2 class="modal-title">REQUEST <span class="gold-text">REFUND</span></h2>
        <form action="{{ route('refunds.store') }}" method="POST" class="modal-form" id="refundForm">
            @csrf
            <div class="form-group">
                <input type="text" name="member_id" placeholder="Member ID" required>
            </div>
            <div class="form-group">
                <select name="membership_type" required>
                    <option value="" disabled selected>Select Membership Type</option>
                    <option value="Daily Pass">Daily Pass</option>
                    <option value="Monthly Basic">Monthly Basic</option>
                    <option value="VIP Quarterly">VIP Quarterly</option>
                </select>
            </div>
            <div class="form-group">
                <textarea name="reason" placeholder="Reason" rows="3" required></textarea>
            </div>
            <button type="submit" class="submit-btn">SUBMIT</button>
            <button type="button" class="cancel-link" onclick="closeRefundModal()">Cancel</button>
        </form>
    </div>
</div>

</body>

{{-- ADMIN LOGIN MODAL --}}
<div id="adminLoginModal" class="admin-modal-overlay">
    <div class="login-card">
        <div class="icon-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                <polyline points="10 17 15 12 10 7"/>
                <line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
        </div>

        <h1>Admin Portal</h1>
        <p class="subtitle">Enter your credentials to access the<br>Crown Fitness Management Hub.</p>

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="field">
                <input type="text" name="username" placeholder="Username" required>
            </div>

            <div class="field">
                <input type="password" name="password" id="adminPassInput" placeholder="Password" required>
                <button class="toggle-pass" type="button" id="toggleAdminPass">
                    <i class="fa-solid fa-eye" id="eyeIcon"></i>
                </button>
            </div>

            <button type="submit" class="btn-submit">Sign In</button>
            <button type="button" class="btn-close-link" onclick="closeModal('adminLoginModal')">Cancel</button>
        </form>
    </div>
</div>

{{-- SUCCESS MODAL --}}
<div id="successModal" class="modal-overlay">
    <div class="modal-card" style="max-width: 360px;">
        <div style="width: 56px; height: 56px; background: #1a2e1a; border: 1px solid #2a5c2a; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i class="fa-solid fa-check" style="color: #4caf50; font-size: 1.4rem;"></i>
        </div>
        <h2 class="modal-title" style="font-size: 1.2rem; margin-bottom: 12px;">Request Submitted</h2>
        <p style="color: #aaa; font-size: 0.9rem; line-height: 1.7; font-family: 'Poppins', sans-serif;">
            Your refund request has been submitted successfully.<br>
            Please wait for approval — a staff member will contact you shortly.
        </p>
        <button
            onclick="closeModal('successModal')"
            style="
                margin-top: 24px;
                display: block;
                width: fit-content;
                margin-left: auto;
                margin-right: auto;
                background: transparent;
                border: 1px solid #444;
                border-radius: 8px;
                color: #aaa;
                font-family: 'Poppins', sans-serif;
                font-size: 0.85rem;
                padding: 8px 28px;
                cursor: pointer;
                transition: all 0.2s ease;
            "
            onmouseover="this.style.borderColor='#C9A84C'; this.style.color='#C9A84C';"
            onmouseout="this.style.borderColor='#444'; this.style.color='#aaa';"
        >
            Close
        </button>
    </div>
</div>


</html>