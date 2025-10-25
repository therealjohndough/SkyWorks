<?php
/**
 * Contact Page Template - Wholesale Focus
 * 
 * For Dispensary Owners & Partners
 */

get_header(); ?>

<div class="skyworld-contact-page">
    <!-- Contact Header -->
    <section class="contact-header">
        <div class="container">
            <h1 class="page-title">For Dispensary Owners & Partners</h1>
            
            <div class="contact-intro">
                <p>Thank you for your interest in featuring Skyworld's Premium Indoor Grown New York cannabis flower in your dispensary. To discuss wholesale opportunities, custom programs, or to request samples, please complete the form below. A member of our team will respond within 48 hours.</p>
            </div>
        </div>
    </section>

    <!-- Contact Form & Info -->
    <section class="contact-content">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Form -->
                <div class="contact-form-section">
                    <h2>Get in Touch</h2>
                    
                    <?php if (get_field('contact_response_sla_text', 'option')): ?>
                        <div class="response-sla">
                            <p><?php echo get_field('contact_response_sla_text', 'option'); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="response-sla">
                            <p>A team member will respond within 48 hours.</p>
                        </div>
                    <?php endif; ?>
                    
                    <form class="wholesale-form" id="wholesaleForm" method="post" action="">
                        <?php wp_nonce_field('skyworld_contact_form', 'contact_nonce'); ?>
                        <div id="form-messages"></div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact_name">Name *</label>
                                <input type="text" id="contact_name" name="contact_name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_email">Email *</label>
                                <input type="email" id="contact_email" name="contact_email" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact_phone">Phone</label>
                                <input type="tel" id="contact_phone" name="contact_phone">
                            </div>
                            
                            <div class="form-group">
                                <label for="business_name">Business Name *</label>
                                <input type="text" id="business_name" name="business_name" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="license_number">License Number</label>
                                <input type="text" id="license_number" name="license_number">
                            </div>
                            
                            <div class="form-group">
                                <label for="location">City/State</label>
                                <input type="text" id="location" name="location">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Product Interest</label>
                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="product_interest[]" value="flower">
                                    <span class="checkmark"></span>
                                    Flower
                                </label>
                                
                                <label class="checkbox-label">
                                    <input type="checkbox" name="product_interest[]" value="prerolls">
                                    <span class="checkmark"></span>
                                    Pre-rolls
                                </label>
                                
                                <label class="checkbox-label">
                                    <input type="checkbox" name="product_interest[]" value="concentrates">
                                    <span class="checkmark"></span>
                                    Concentrates
                                </label>
                                
                                <label class="checkbox-label">
                                    <input type="checkbox" name="product_interest[]" value="custom_programs">
                                    <span class="checkmark"></span>
                                    Custom Programs
                                </label>
                                
                                <label class="checkbox-label">
                                    <input type="checkbox" name="product_interest[]" value="samples">
                                    <span class="checkmark"></span>
                                    Sample Requests
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Additional Notes</label>
                            <textarea id="notes" name="notes" rows="4" placeholder="Tell us more about your business and how we can help..."></textarea>
                        </div>
                        
                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                    
                    <script>
                    document.getElementById('wholesaleForm').addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const formData = new FormData(this);
                        formData.append('action', 'skyworld_contact_form');
                        
                        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            const messagesDiv = document.getElementById('form-messages');
                            if (data.success) {
                                messagesDiv.innerHTML = '<div class="success-message">' + data.data.message + '</div>';
                                this.reset();
                            } else {
                                messagesDiv.innerHTML = '<div class="error-message">' + data.data.message + '</div>';
                            }
                        })
                        .catch(error => {
                            document.getElementById('form-messages').innerHTML = '<div class="error-message">There was an error sending your message. Please try again.</div>';
                        });
                    });
                    </script>
                </div>
                
                <!-- Sidebar Info -->
                <div class="contact-sidebar">
                    <div class="sidebar-section">
                        <h3>Available Products</h3>
                        <p>They're here, and they hit different. The same indoor Skyworld flower is now available in a grab-and-go format. No trim, no additives.</p>
                        
                        <div class="product-highlights">
                            <div class="highlight-item">
                                <span class="highlight-icon">🌿</span>
                                <span class="highlight-text">Premium Indoor Grown</span>
                            </div>
                            
                            <div class="highlight-item">
                                <span class="highlight-icon">📋</span>
                                <span class="highlight-text">Full COA Testing</span>
                            </div>
                            
                            <div class="highlight-item">
                                <span class="highlight-icon">🏪</span>
                                <span class="highlight-text">Wholesale Programs</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="sidebar-section">
                        <h3>Why Choose Skyworld?</h3>
                        <ul class="benefits-list">
                            <li>Indigenous-owned business</li>
                            <li>Licensed New York cultivator</li>
                            <li>Consistent quality & potency</li>
                            <li>Custom wholesale programs</li>
                            <li>Fast response times</li>
                        </ul>
                    </div>
                    
                    <div class="sidebar-section">
                        <h3>Quick Links</h3>
                        <div class="quick-links">
                            <a href="<?php echo get_post_type_archive_link('cannabis_product'); ?>" class="quick-link">
                                View Products
                            </a>
                            <a href="<?php echo home_url('/labs/'); ?>" class="quick-link">
                                View COAs
                            </a>
                            <a href="<?php echo home_url('/store-locator/'); ?>" class="quick-link">
                                Find Stores
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>
