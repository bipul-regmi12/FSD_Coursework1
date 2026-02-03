/**
 * Global Kiyome Logic
 */
document.addEventListener('DOMContentLoaded', () => {
    // Highlight active nav links
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-links a').forEach(link => {
        const href = link.getAttribute('href');
        if (href && href !== '#' && href !== '/' && currentPath.includes(href)) {
            link.style.color = 'var(--primary)';
        }
    });

    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.querySelector('.nav-links');

    if (mobileMenuBtn && navLinks) {
        mobileMenuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            const icon = mobileMenuBtn.querySelector('i');
            if (navLinks.classList.contains('active')) {
                icon.className = 'fas fa-times';
            } else {
                icon.className = 'fas fa-bars';
            }
        });
    }

    // User Profile Dropdown Toggle
    const profileTrigger = document.getElementById('userProfileTrigger');
    const userDropdown = document.getElementById('userDropdown');

    if (profileTrigger && userDropdown) {
        profileTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileTrigger.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove('active');
            }
        });
    }

    // Add subtle scroll reveal effect
    const observerOptions = { threshold: 0.1 };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.glass-container, .pet-card, .testimonial-card').forEach(el => {
        observer.observe(el);
    });
});

/**
 * Hero Slider Logic
 */
document.addEventListener('DOMContentLoaded', () => {
    const sliderItems = document.querySelectorAll('.slider-item');
    const dots = document.querySelectorAll('.dot');
    let currentIndex = 0;
    const intervalTime = 5000;

    if (sliderItems.length === 0) return;

    const updateSlider = (newIndex) => {
        // Remove active class from current
        sliderItems[currentIndex].classList.remove('active');
        if (dots.length > currentIndex) dots[currentIndex].classList.remove('active');

        // Update index
        currentIndex = newIndex;

        // Add active class to next
        sliderItems[currentIndex].classList.add('active');
        if (dots.length > currentIndex) dots[currentIndex].classList.add('active');
    };

    const nextSlide = () => {
        const nextIndex = (currentIndex + 1) % sliderItems.length;
        updateSlider(nextIndex);
    };

    // Auto scroll
    let slideInterval = setInterval(nextSlide, intervalTime);

    // Manual control via dots
    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            clearInterval(slideInterval);
            const targetIndex = parseInt(dot.getAttribute('data-index'));
            updateSlider(targetIndex);
            slideInterval = setInterval(nextSlide, intervalTime);
        });
    });
});
