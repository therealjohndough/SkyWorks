<?php
/**
 * Contact Form Handler
 * 
 * Handles wholesale contact form submissions with proper validation and security
 */

// Handle contact form submission
function skyworld_handle_contact_form() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['contact_nonce'], 'skyworld_contact_form')) {
        wp_die('Security check failed');
    }
    
    // Sanitize and validate input
    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $phone = sanitize_text_field($_POST['contact_phone']);
    $business_name = sanitize_text_field($_POST['business_name']);
    $license_number = sanitize_text_field($_POST['license_number']);
    $location = sanitize_text_field($_POST['location']);
    $product_interest = isset($_POST['product_interest']) ? array_map('sanitize_text_field', $_POST['product_interest']) : array();
    $notes = sanitize_textarea_field($_POST['notes']);
    
    // Validate required fields
    $errors = array();
    
    if (empty($name)) {
        $errors[] = 'Name is required';
    }
    
    if (empty($email) || !is_email($email)) {
        $errors[] = 'Valid email is required';
    }
    
    if (empty($business_name)) {
        $errors[] = 'Business name is required';
    }
    
    // If there are errors, return them
    if (!empty($errors)) {
        wp_send_json_error(array('message' => implode(', ', $errors)));
    }
    
    // Prepare email content
    $subject = 'New Wholesale Inquiry from ' . $business_name;
    $message = "New wholesale inquiry received:\n\n";
    $message .= "Name: " . $name . "\n";
    $message .= "Email: " . $email . "\n";
    $message .= "Phone: " . $phone . "\n";
    $message .= "Business: " . $business_name . "\n";
    $message .= "License #: " . $license_number . "\n";
    $message .= "Location: " . $location . "\n";
    $message .= "Product Interest: " . implode(', ', $product_interest) . "\n";
    $message .= "Notes: " . $notes . "\n";
    
    // Get admin email
    $admin_email = get_option('admin_email');
    
    // Send email
    $sent = wp_mail($admin_email, $subject, $message);
    
    if ($sent) {
        wp_send_json_success(array('message' => 'Thank you for your inquiry. We will respond within 48 hours.'));
    } else {
        wp_send_json_error(array('message' => 'There was an error sending your message. Please try again.'));
    }
}

// Hook into AJAX
add_action('wp_ajax_skyworld_contact_form', 'skyworld_handle_contact_form');
add_action('wp_ajax_nopriv_skyworld_contact_form', 'skyworld_handle_contact_form');
