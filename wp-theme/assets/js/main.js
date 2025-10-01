/**
 * SkyWorks Main JavaScript
 * Handles core functionality and interactions
 */

class SkyWorks {
    constructor() {
        this.init();
    }

    init() {
        this.initMobileMenu();
        this.initScrollEffects();
        this.initLazyLoading();
        this.initFormHandling();
        this.initSmoothScrolling();
        console.log('SkyWorks initialized');
    }

    // Mobile navigation toggle
    initMobileMenu() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const navigation = document.querySelector('.main-navigation');
        
        if (menuToggle && navigation) {
            menuToggle.addEventListener('click', () => {
                const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
                
                menuToggle.setAttribute('aria-expanded', !isOpen);
                navigation.classList.toggle('is-open');
                document.body.classList.toggle('menu-open');
            });

            // Close menu on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && navigation.classList.contains('is-open')) {
                    menuToggle.setAttribute('aria-expanded', 'false');
                    navigation.classList.remove('is-open');
                    document.body.classList.remove('menu-open');
                }
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!navigation.contains(e.target) && !menuToggle.contains(e.target)) {
                    if (navigation.classList.contains('is-open')) {
                        menuToggle.setAttribute('aria-expanded', 'false');
                        navigation.classList.remove('is-open');
                        document.body.classList.remove('menu-open');
                    }
                }
            });
        }
    }

    // Scroll effects for header
    initScrollEffects() {
        const header = document.querySelector('.skyworks-header');
        let lastScrollY = window.scrollY;

        if (header) {
            window.addEventListener('scroll', () => {
                const currentScrollY = window.scrollY;
                
                // Add scrolled class for styling
                if (currentScrollY > 100) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }

                // Hide/show header on scroll
                if (currentScrollY > lastScrollY && currentScrollY > 200) {
                    header.classList.add('hidden');
                } else {
                    header.classList.remove('hidden');
                }

                lastScrollY = currentScrollY;
            });
        }
    }

    // Lazy loading for images
    initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    // Form handling
    initFormHandling() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    }

    // Form validation
    validateForm(form) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            field.classList.remove('error');
            
            if (!field.value.trim()) {
                field.classList.add('error');
                isValid = false;
            }

            // Email validation
            if (field.type === 'email' && field.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(field.value)) {
                    field.classList.add('error');
                    isValid = false;
                }
            }
        });

        return isValid;
    }

    // Smooth scrolling for anchor links
    initSmoothScrolling() {
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
    }

    // Utility method for AJAX requests
    async fetchData(url, options = {}) {
        try {
            const response = await fetch(url, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': skyworks_ajax.nonce
                },
                ...options
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Fetch error:', error);
            throw error;
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new SkyWorks();
});

// Export for use in other modules
window.SkyWorks = SkyWorks;