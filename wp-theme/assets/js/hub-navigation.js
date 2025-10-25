/**
 * Hub Navigation JavaScript
 * 
 * Interactive functionality for hub and spoke navigation
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize hub navigation
    initHubNavigation();
    
    // Initialize breadcrumb navigation
    initBreadcrumbNavigation();
    
    // Initialize product filtering
    initProductFiltering();
    
    // Initialize smooth scrolling
    initSmoothScrolling();
});

/**
 * Initialize hub navigation functionality
 */
function initHubNavigation() {
    const hubNav = document.querySelector('.hub-navigation');
    if (!hubNav) return;
    
    // Add scroll spy for navigation highlighting
    const navLinks = hubNav.querySelectorAll('.nav-category-link');
    const sections = document.querySelectorAll('section[id]');
    
    // Create intersection observer for scroll spy
    const observerOptions = {
        root: null,
        rootMargin: '-50% 0px -50% 0px',
        threshold: 0
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const sectionId = entry.target.id;
                updateActiveNavLink(sectionId);
            }
        });
    }, observerOptions);
    
    // Observe all sections
    sections.forEach(section => {
        observer.observe(section);
    });
    
    // Add click handlers for navigation links
    navLinks.forEach(link => {
        link.addEventListener('click', handleNavLinkClick);
    });
}

/**
 * Initialize breadcrumb navigation
 */
function initBreadcrumbNavigation() {
    const breadcrumbNav = document.querySelector('.breadcrumb-nav');
    if (!breadcrumbNav) return;
    
    // Add keyboard navigation support
    const breadcrumbLinks = breadcrumbNav.querySelectorAll('a');
    breadcrumbLinks.forEach((link, index) => {
        link.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight' && index < breadcrumbLinks.length - 1) {
                e.preventDefault();
                breadcrumbLinks[index + 1].focus();
            } else if (e.key === 'ArrowLeft' && index > 0) {
                e.preventDefault();
                breadcrumbLinks[index - 1].focus();
            }
        });
    });
}

/**
 * Initialize product filtering in hub navigation
 */
function initProductFiltering() {
    const productCards = document.querySelectorAll('.spoke-product-card');
    if (!productCards.length) return;
    
    // Create filter controls if they don't exist
    createProductFilters();
    
    // Add filter event listeners
    const filterControls = document.querySelectorAll('.product-filter');
    filterControls.forEach(control => {
        control.addEventListener('change', handleProductFilter);
    });
}

/**
 * Create product filter controls
 */
function createProductFilters() {
    const spokeProductsGrid = document.querySelector('.spoke-products-grid');
    if (!spokeProductsGrid) return;
    
    // Check if filters already exist
    if (spokeProductsGrid.querySelector('.product-filters')) return;
    
    const filtersHTML = `
        <div class="product-filters">
            <div class="filter-group">
                <label for="product-type-filter">Filter by Type:</label>
                <select id="product-type-filter" class="product-filter">
                    <option value="">All Types</option>
                    <option value="flower">Flower</option>
                    <option value="preroll">Pre-roll</option>
                    <option value="concentrate">Concentrate</option>
                    <option value="edible">Edible</option>
                    <option value="topical">Topical</option>
                    <option value="vape">Vape Cartridge</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="inventory-filter">Filter by Status:</label>
                <select id="inventory-filter" class="product-filter">
                    <option value="">All Status</option>
                    <option value="in_stock">In Stock</option>
                    <option value="low_stock">Low Stock</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>
        </div>
    `;
    
    const spokeTitle = spokeProductsGrid.querySelector('.spoke-title');
    if (spokeTitle) {
        spokeTitle.insertAdjacentHTML('afterend', filtersHTML);
    }
}

/**
 * Handle product filtering
 */
function handleProductFilter() {
    const productCards = document.querySelectorAll('.spoke-product-card');
    const typeFilter = document.getElementById('product-type-filter')?.value || '';
    const inventoryFilter = document.getElementById('inventory-filter')?.value || '';
    
    productCards.forEach(card => {
        let show = true;
        
        // Filter by product type
        if (typeFilter) {
            const productType = card.querySelector('.product-type-badge')?.textContent.toLowerCase();
            if (productType !== typeFilter) {
                show = false;
            }
        }
        
        // Filter by inventory status
        if (inventoryFilter) {
            const inventoryStatus = card.dataset.inventory;
            if (inventoryStatus !== inventoryFilter) {
                show = false;
            }
        }
        
        // Show/hide card
        card.style.display = show ? 'block' : 'none';
    });
    
    // Update results count
    updateResultsCount();
}

/**
 * Update results count after filtering
 */
function updateResultsCount() {
    const visibleCards = document.querySelectorAll('.spoke-product-card[style*="block"], .spoke-product-card:not([style*="none"])');
    const totalCards = document.querySelectorAll('.spoke-product-card');
    
    let resultsText = document.querySelector('.results-count');
    if (!resultsText) {
        resultsText = document.createElement('div');
        resultsText.className = 'results-count';
        const spokeTitle = document.querySelector('.spoke-title');
        if (spokeTitle) {
            spokeTitle.insertAdjacentElement('afterend', resultsText);
        }
    }
    
    resultsText.textContent = `Showing ${visibleCards.length} of ${totalCards.length} products`;
}

/**
 * Initialize smooth scrolling for navigation links
 */
function initSmoothScrolling() {
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            if (href === '#') return;
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

/**
 * Update active navigation link based on current section
 */
function updateActiveNavLink(sectionId) {
    const navLinks = document.querySelectorAll('.nav-category-link');
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href').includes(sectionId)) {
            link.classList.add('active');
        }
    });
}

/**
 * Handle navigation link clicks
 */
function handleNavLinkClick(e) {
    const link = e.currentTarget;
    
    // Add loading state
    link.classList.add('loading');
    
    // Remove loading state after a short delay
    setTimeout(() => {
        link.classList.remove('loading');
    }, 500);
}

/**
 * Add keyboard navigation support for hub navigation
 */
function addKeyboardNavigation() {
    const hubNav = document.querySelector('.hub-navigation');
    if (!hubNav) return;
    
    // Make hub navigation focusable
    hubNav.setAttribute('tabindex', '0');
    
    // Add keyboard event listeners
    hubNav.addEventListener('keydown', (e) => {
        const focusableElements = hubNav.querySelectorAll('a, button, select, input');
        const currentIndex = Array.from(focusableElements).indexOf(document.activeElement);
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                if (currentIndex < focusableElements.length - 1) {
                    focusableElements[currentIndex + 1].focus();
                }
                break;
            case 'ArrowUp':
                e.preventDefault();
                if (currentIndex > 0) {
                    focusableElements[currentIndex - 1].focus();
                }
                break;
            case 'Home':
                e.preventDefault();
                focusableElements[0].focus();
                break;
            case 'End':
                e.preventDefault();
                focusableElements[focusableElements.length - 1].focus();
                break;
        }
    });
}

// Initialize keyboard navigation
document.addEventListener('DOMContentLoaded', addKeyboardNavigation);

/**
 * Add analytics tracking for hub navigation
 */
function trackHubNavigation() {
    const hubNav = document.querySelector('.hub-navigation');
    if (!hubNav) return;
    
    // Track navigation link clicks
    const navLinks = hubNav.querySelectorAll('.nav-category-link, .product-nav-link, .strain-nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            const linkText = e.currentTarget.textContent.trim();
            const linkHref = e.currentTarget.getAttribute('href');
            
            // Track with Google Analytics if available
            if (typeof gtag !== 'undefined') {
                gtag('event', 'hub_navigation_click', {
                    'link_text': linkText,
                    'link_href': linkHref,
                    'page_location': window.location.href
                });
            }
        });
    });
}

// Initialize analytics tracking
document.addEventListener('DOMContentLoaded', trackHubNavigation);
