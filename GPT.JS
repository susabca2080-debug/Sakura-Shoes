/* Custom styles for enhanced animations and effects */
// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    mobileMenuBtn.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });

    // Close mobile menu when clicking on links
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', function() {
            mobileMenu.classList.add('hidden');
        });
    });
});

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add scroll effect to header
window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    if (window.scrollY > 100) {
        header.classList.add('bg-white/95', 'backdrop-blur-sm');
    } else {
        header.classList.remove('bg-white/95', 'backdrop-blur-sm');
    }
});

// Add to cart functionality
document.querySelectorAll('button').forEach(button => {
    if (button.textContent.includes('Add to Cart')) {
        button.addEventListener('click', function() {
            // Simple animation feedback
            const originalText = this.textContent;
            this.textContent = 'Added! ✓';
            this.classList.add('bg-green-600');
            
            setTimeout(() => {
                this.textContent = originalText;
                this.classList.remove('bg-green-600');
            }, 1000);
        });
    }
});
