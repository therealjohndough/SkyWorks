/**
 * Archive Filters JavaScript
 * 
 * AJAX filtering and load more functionality for product archive
 */

document.addEventListener('DOMContentLoaded', function() {
    initArchiveFilters();
    initLoadMore();
    initProductAnimations();
});

/**
 * Initialize archive filtering functionality
 */
function initArchiveFilters() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const productsGrid = document.getElementById('products-grid');
    const productCount = document.getElementById('product-count');
    
    if (!filterBtns.length || !productsGrid) return;
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons in the same group
            const group = this.closest('.filter-group');
            group.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get filter values
            const strainType = getActiveFilter('strain-type');
            const effect = getActiveFilter('effect');
            
            // Apply filters
            filterProducts(strainType, effect);
        });
    });
}

/**
 * Get active filter value from a filter group
 */
function getActiveFilter(groupType) {
    const group = document.querySelector(`[data-filter-group="${groupType}"]`) || 
                  document.querySelector(`.filter-btn[data-${groupType}]`);
    
    if (group) {
        const activeBtn = group.querySelector('.filter-btn.active');
        return activeBtn ? activeBtn.dataset[groupType] : 'all';
    }
    
    // Fallback: check all filter buttons
    const activeBtns = document.querySelectorAll('.filter-btn.active');
    for (let btn of activeBtns) {
        if (btn.dataset.filter && btn.dataset.filter !== 'all') {
            return btn.dataset.filter;
        }
        if (btn.dataset.effect && btn.dataset.effect !== 'all') {
            return btn.dataset.effect;
        }
    }
    
    return 'all';
}

/**
 * Filter products based on strain type and effects
 */
function filterProducts(strainType, effect) {
    const productCards = document.querySelectorAll('.product-card');
    let visibleCount = 0;
    
    productCards.forEach(card => {
        const cardStrainType = card.dataset.strainType || '';
        const cardEffects = card.dataset.effects ? card.dataset.effects.split(',') : [];
        
        let showCard = true;
        
        // Filter by strain type
        if (strainType !== 'all' && cardStrainType !== strainType) {
            showCard = false;
        }
        
        // Filter by effect
        if (effect !== 'all' && !cardEffects.includes(effect)) {
            showCard = false;
        }
        
        if (showCard) {
            card.style.display = 'block';
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            // Animate in
            setTimeout(() => {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, visibleCount * 50);
            
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Update product count
    updateProductCount(visibleCount);
}

/**
 * Update product count display
 */
function updateProductCount(count) {
    const productCount = document.getElementById('product-count');
    if (productCount) {
        productCount.textContent = count;
    }
}

/**
 * Initialize load more functionality
 */
function initLoadMore() {
    const loadMoreBtn = document.getElementById('load-more-btn');
    if (!loadMoreBtn) return;
    
    loadMoreBtn.addEventListener('click', function() {
        const currentPage = parseInt(this.dataset.page);
        const maxPages = parseInt(this.dataset.maxPages);
        
        if (currentPage >= maxPages) {
            this.style.display = 'none';
            return;
        }
        
        // Show loading state
        this.disabled = true;
        this.textContent = 'Loading...';
        
        // Get current filters
        const strainType = getActiveFilter('strain-type');
        const effect = getActiveFilter('effect');
        
        // Load more products via AJAX
        loadMoreProducts(currentPage + 1, strainType, effect)
            .then(response => {
                if (response.success) {
                    appendProducts(response.data.products);
                    this.dataset.page = currentPage + 1;
                    
                    if (currentPage + 1 >= maxPages) {
                        this.style.display = 'none';
                    } else {
                        this.disabled = false;
                        this.textContent = 'Load More Products';
                    }
                } else {
                    console.error('Failed to load more products:', response.data);
                    this.disabled = false;
                    this.textContent = 'Load More Products';
                }
            })
            .catch(error => {
                console.error('Error loading more products:', error);
                this.disabled = false;
                this.textContent = 'Load More Products';
            });
    });
}

/**
 * Load more products via AJAX
 */
function loadMoreProducts(page, strainType, effect) {
    const formData = new FormData();
    formData.append('action', 'skyworld_load_more_products');
    formData.append('page', page);
    formData.append('strain_type', strainType);
    formData.append('effect', effect);
    formData.append('nonce', skyworld_ajax.nonce);
    
    return fetch(skyworld_ajax.ajax_url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json());
}

/**
 * Append new products to the grid
 */
function appendProducts(productsHtml) {
    const productsGrid = document.getElementById('products-grid');
    if (productsGrid && productsHtml) {
        productsGrid.insertAdjacentHTML('beforeend', productsHtml);
        
        // Re-initialize animations for new products
        initProductAnimations();
    }
}

/**
 * Initialize product card animations
 */
function initProductAnimations() {
    const productCards = document.querySelectorAll('.product-card:not(.animated)');
    
    productCards.forEach((card, index) => {
        card.classList.add('animated');
        
        // Stagger animation
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });
}

/**
 * Add keyboard navigation for filters
 */
function addKeyboardNavigation() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
        
        // Make filter buttons focusable
        btn.setAttribute('tabindex', '0');
    });
}

// Initialize keyboard navigation
document.addEventListener('DOMContentLoaded', addKeyboardNavigation);

/**
 * Add URL hash support for filters
 */
function initUrlHashSupport() {
    const urlParams = new URLSearchParams(window.location.search);
    const strainType = urlParams.get('strain_type');
    const effect = urlParams.get('effect');
    
    if (strainType) {
        const btn = document.querySelector(`[data-filter="${strainType}"]`);
        if (btn) {
            btn.click();
        }
    }
    
    if (effect) {
        const btn = document.querySelector(`[data-effect="${effect}"]`);
        if (btn) {
            btn.click();
        }
    }
}

// Initialize URL hash support
document.addEventListener('DOMContentLoaded', initUrlHashSupport);

/**
 * Add analytics tracking for filter interactions
 */
function trackFilterInteractions() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Track with Google Analytics if available
            if (typeof gtag !== 'undefined') {
                gtag('event', 'product_filter_click', {
                    'filter_type': this.dataset.filter ? 'strain_type' : 'effect',
                    'filter_value': this.dataset.filter || this.dataset.effect,
                    'page_location': window.location.href
                });
            }
        });
    });
}

// Initialize analytics tracking
document.addEventListener('DOMContentLoaded', trackFilterInteractions);

/**
 * Add smooth scrolling for load more
 */
function addSmoothScrolling() {
    const loadMoreBtn = document.getElementById('load-more-btn');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            // Scroll to show new products
            setTimeout(() => {
                const productsGrid = document.getElementById('products-grid');
                if (productsGrid) {
                    const lastProduct = productsGrid.lastElementChild;
                    if (lastProduct) {
                        lastProduct.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'start' 
                        });
                    }
                }
            }, 500);
        });
    }
}

// Initialize smooth scrolling
document.addEventListener('DOMContentLoaded', addSmoothScrolling);
