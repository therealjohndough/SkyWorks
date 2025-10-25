/**
 * Product Gallery JavaScript
 * 
 * Thumbnail image switching functionality for product pages
 */

document.addEventListener('DOMContentLoaded', function() {
    initProductGallery();
    initProductDetailsToggle();
});

/**
 * Initialize product gallery functionality
 */
function initProductGallery() {
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.getElementById('main-product-image');
    
    if (!thumbnails.length || !mainImage) return;
    
    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            
            // Add active class to clicked thumbnail
            this.classList.add('active');
            
            // Get image data
            const imageUrl = this.dataset.image;
            const imageAlt = this.dataset.alt;
            
            if (imageUrl) {
                // Create fade effect
                mainImage.style.opacity = '0';
                
                setTimeout(() => {
                    // Update image source
                    mainImage.src = imageUrl;
                    mainImage.alt = imageAlt;
                    
                    // Fade back in
                    mainImage.style.opacity = '1';
                }, 150);
            }
        });
    });
}

/**
 * Initialize product details toggle functionality
 */
function initProductDetailsToggle() {
    const toggleBtn = document.querySelector('.toggle-details-btn');
    const detailsContent = document.querySelector('.product-details-content');
    
    if (!toggleBtn || !detailsContent) return;
    
    toggleBtn.addEventListener('click', function() {
        const isHidden = detailsContent.style.display === 'none';
        
        if (isHidden) {
            detailsContent.style.display = 'block';
            this.textContent = 'Hide Product Details';
            
            // Smooth scroll to details
            detailsContent.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'start' 
            });
        } else {
            detailsContent.style.display = 'none';
            this.textContent = 'View Product Details';
        }
    });
}

/**
 * Add keyboard navigation for thumbnails
 */
function addKeyboardNavigation() {
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            } else if (e.key === 'ArrowRight' && index < thumbnails.length - 1) {
                e.preventDefault();
                thumbnails[index + 1].focus();
            } else if (e.key === 'ArrowLeft' && index > 0) {
                e.preventDefault();
                thumbnails[index - 1].focus();
            }
        });
        
        // Make thumbnails focusable
        thumbnail.setAttribute('tabindex', '0');
        thumbnail.setAttribute('role', 'button');
        thumbnail.setAttribute('aria-label', `View product image ${index + 1}`);
    });
}

// Initialize keyboard navigation
document.addEventListener('DOMContentLoaded', addKeyboardNavigation);

/**
 * Add smooth transitions for image changes
 */
function addImageTransitions() {
    const mainImage = document.getElementById('main-product-image');
    
    if (mainImage) {
        mainImage.style.transition = 'opacity 0.3s ease';
    }
}

// Initialize image transitions
document.addEventListener('DOMContentLoaded', addImageTransitions);

/**
 * Add touch/swipe support for mobile
 */
function addTouchSupport() {
    const productImage = document.querySelector('.product-image');
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    if (!productImage || !thumbnails.length) return;
    
    let startX = 0;
    let startY = 0;
    
    productImage.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
    });
    
    productImage.addEventListener('touchend', function(e) {
        if (!startX || !startY) return;
        
        const endX = e.changedTouches[0].clientX;
        const endY = e.changedTouches[0].clientY;
        
        const diffX = startX - endX;
        const diffY = startY - endY;
        
        // Only handle horizontal swipes
        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
            const currentActive = document.querySelector('.thumbnail.active');
            const currentIndex = Array.from(thumbnails).indexOf(currentActive);
            
            if (diffX > 0 && currentIndex < thumbnails.length - 1) {
                // Swipe left - next image
                thumbnails[currentIndex + 1].click();
            } else if (diffX < 0 && currentIndex > 0) {
                // Swipe right - previous image
                thumbnails[currentIndex - 1].click();
            }
        }
        
        startX = 0;
        startY = 0;
    });
}

// Initialize touch support
document.addEventListener('DOMContentLoaded', addTouchSupport);

/**
 * Add analytics tracking for gallery interactions
 */
function trackGalleryInteractions() {
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', function() {
            // Track with Google Analytics if available
            if (typeof gtag !== 'undefined') {
                gtag('event', 'product_gallery_click', {
                    'image_index': index + 1,
                    'product_name': document.querySelector('.product-title')?.textContent || 'Unknown',
                    'page_location': window.location.href
                });
            }
        });
    });
}

// Initialize analytics tracking
document.addEventListener('DOMContentLoaded', trackGalleryInteractions);
