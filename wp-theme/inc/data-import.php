<?php
/**
 * Data Import Script
 * 
 * Imports strain and product data from CSV files
 * Handles incomplete data and missing products gracefully
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Import strain data from CSV
 */
function skyworld_import_strains() {
    $csv_file = get_template_directory() . '/../docs/skyworld-strains-library-import.csv';
    
    if (!file_exists($csv_file)) {
        return array('success' => false, 'message' => 'Strain CSV file not found');
    }
    
    $imported = 0;
    $errors = array();
    
    if (($handle = fopen($csv_file, 'r')) !== FALSE) {
        $header = fgetcsv($handle); // Skip header
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            if (count($data) < 12) continue; // Skip incomplete rows
            
            $strain_data = array(
                'post_title' => sanitize_text_field($data[1]),
                'post_content' => sanitize_textarea_field($data[2]),
                'post_type' => 'strain',
                'post_status' => 'publish',
                'meta_input' => array(
                    'strain_genetics' => sanitize_text_field($data[5]),
                    'strain_breeder' => sanitize_text_field($data[6]),
                    'strain_breeder_url' => esc_url_raw($data[7]),
                    'strain_flowering_time' => sanitize_text_field($data[8]),
                    'strain_aroma_profile' => sanitize_textarea_field($data[9]),
                    'strain_flavor_profile' => sanitize_textarea_field($data[10]),
                )
            );
            
            // Check if strain already exists
            $existing = get_posts(array(
                'post_type' => 'strain',
                'name' => sanitize_title($data[1]),
                'posts_per_page' => 1
            ));
            
            if (empty($existing)) {
                $post_id = wp_insert_post($strain_data);
                
                if ($post_id && !is_wp_error($post_id)) {
                    // Import terpene data
                    $terpene_json = $data[11];
                    if (!empty($terpene_json)) {
                        $terpenes = json_decode($terpene_json, true);
                        if (is_array($terpenes)) {
                            foreach ($terpenes as $terpene) {
                                add_row('strain_terpenes', array(
                                    'terpene_name' => sanitize_text_field($terpene['terpene_name']),
                                    'terpene_percentage' => floatval($terpene['terpene_percentage'])
                                ), $post_id);
                            }
                        }
                    }
                    
                    // Set default strain type (Hybrid) - can be updated manually
                    wp_set_post_terms($post_id, 'hybrid', 'strain_type');
                    
                    // Add default effects (can be updated manually)
                    $default_effects = array('relaxed', 'happy', 'creative');
                    wp_set_post_terms($post_id, $default_effects, 'effects');
                    
                    $imported++;
                } else {
                    $errors[] = 'Failed to create strain: ' . $data[1];
                }
            }
        }
        fclose($handle);
    }
    
    return array(
        'success' => true,
        'imported' => $imported,
        'errors' => $errors
    );
}

/**
 * Import product/COA data from CSV
 */
function skyworld_import_products() {
    $csv_file = get_template_directory() . '/../docs/Product Inventory By batch # organized.csv';
    
    if (!file_exists($csv_file)) {
        return array('success' => false, 'message' => 'Product CSV file not found');
    }
    
    $imported = 0;
    $errors = array();
    
    if (($handle = fopen($csv_file, 'r')) !== FALSE) {
        $header = fgetcsv($handle); // Skip header
        
        while (($data = fgetcsv($handle)) !== FALSE) {
            if (count($data) < 20) continue; // Skip incomplete rows
            
            $batch_number = sanitize_text_field($data[0]);
            $strain_name = sanitize_text_field($data[1]);
            $product_type = sanitize_text_field($data[2]);
            
            // Find matching strain
            $strain = get_posts(array(
                'post_type' => 'strain',
                'name' => sanitize_title($strain_name),
                'posts_per_page' => 1
            ));
            
            if (empty($strain)) {
                $errors[] = "Strain not found for product: {$strain_name}";
                continue;
            }
            
            $strain_id = $strain[0]->ID;
            
            // Create product data
            $product_data = array(
                'post_title' => $strain_name . ' ' . $product_type,
                'post_type' => 'cannabis_product',
                'post_status' => 'publish',
                'meta_input' => array(
                    'product_strain' => $strain_id,
                    'product_sku' => $batch_number,
                    'product_type' => strtolower($product_type),
                    'product_weight' => sanitize_text_field($data[6]),
                    'product_thc_content' => floatval($data[7]) . '%',
                    'product_cbd_content' => floatval($data[8]) . '%',
                    'product_cbg_content' => floatval($data[9]) . '%',
                    'product_thcv_content' => floatval($data[10]) . '%',
                    'product_terp_total' => floatval($data[11]) . '%',
                    'product_batch_number' => $batch_number,
                    'product_test_date' => sanitize_text_field($data[4]),
                    'product_lab_name' => sanitize_text_field($data[17]),
                    'product_inventory' => 'in_stock', // Default status
                )
            );
            
            // Check if product already exists
            $existing = get_posts(array(
                'post_type' => 'cannabis_product',
                'meta_query' => array(
                    array(
                        'key' => 'product_sku',
                        'value' => $batch_number,
                        'compare' => '='
                    )
                ),
                'posts_per_page' => 1
            ));
            
            if (empty($existing)) {
                $post_id = wp_insert_post($product_data);
                
                if ($post_id && !is_wp_error($post_id)) {
                    // Add terpene data
                    if (!empty($data[12]) && !empty($data[13])) {
                        add_row('product_terpenes', array(
                            'terpene_name' => sanitize_text_field($data[12]),
                            'terpene_percentage' => floatval($data[13])
                        ), $post_id);
                    }
                    if (!empty($data[14]) && !empty($data[15])) {
                        add_row('product_terpenes', array(
                            'terpene_name' => sanitize_text_field($data[14]),
                            'terpene_percentage' => floatval($data[15])
                        ), $post_id);
                    }
                    if (!empty($data[16]) && !empty($data[17])) {
                        add_row('product_terpenes', array(
                            'terpene_name' => sanitize_text_field($data[16]),
                            'terpene_percentage' => floatval($data[17])
                        ), $post_id);
                    }
                    
                    // Set product category
                    wp_set_post_terms($post_id, strtolower($product_type), 'cannabis_product_category');
                    
                    $imported++;
                } else {
                    $errors[] = 'Failed to create product: ' . $batch_number;
                }
            }
        }
        fclose($handle);
    }
    
    return array(
        'success' => true,
        'imported' => $imported,
        'errors' => $errors
    );
}

/**
 * Update WordPress Status column in CSV
 */
function skyworld_update_csv_status($batch_number, $status = 'synced') {
    $csv_file = get_template_directory() . '/../docs/Product Inventory By batch # organized.csv';
    
    if (!file_exists($csv_file)) {
        return false;
    }
    
    $lines = file($csv_file);
    $updated = false;
    
    foreach ($lines as $index => $line) {
        $data = str_getcsv($line);
        if (isset($data[0]) && $data[0] === $batch_number) {
            $data[18] = $status; // WordPress Status column
            $lines[$index] = '"' . implode('","', $data) . '"' . "\n";
            $updated = true;
            break;
        }
    }
    
    if ($updated) {
        file_put_contents($csv_file, implode('', $lines));
    }
    
    return $updated;
}

/**
 * AJAX handler for import process
 */
function skyworld_ajax_import_data() {
    if (!wp_verify_nonce($_POST['nonce'], 'skyworld_nonce')) {
        wp_die('Security check failed');
    }
    
    $import_type = sanitize_text_field($_POST['import_type']);
    $results = array();
    
    if ($import_type === 'strains') {
        $results = skyworld_import_strains();
    } elseif ($import_type === 'products') {
        $results = skyworld_import_products();
    } elseif ($import_type === 'all') {
        $strain_results = skyworld_import_strains();
        $product_results = skyworld_import_products();
        
        $results = array(
            'success' => true,
            'strains' => $strain_results,
            'products' => $product_results
        );
    }
    
    wp_send_json($results);
}
add_action('wp_ajax_skyworld_import_data', 'skyworld_ajax_import_data');
add_action('wp_ajax_nopriv_skyworld_import_data', 'skyworld_ajax_import_data');

/**
 * Add import admin page
 */
function skyworld_add_import_admin_page() {
    add_management_page(
        'Data Import',
        'Data Import',
        'manage_options',
        'skyworld-import',
        'skyworld_import_admin_page'
    );
}
add_action('admin_menu', 'skyworld_add_import_admin_page');

/**
 * Import admin page
 */
function skyworld_import_admin_page() {
    ?>
    <div class="wrap">
        <h1>Skyworld Data Import</h1>
        
        <div class="import-section">
            <h2>Import Strains</h2>
            <p>Import strain data from skyworld-strains-library-import.csv</p>
            <button id="import-strains" class="button button-primary">Import Strains</button>
            <div id="strains-result"></div>
        </div>
        
        <div class="import-section">
            <h2>Import Products</h2>
            <p>Import product and COA data from Product Inventory CSV</p>
            <button id="import-products" class="button button-primary">Import Products</button>
            <div id="products-result"></div>
        </div>
        
        <div class="import-section">
            <h2>Import All</h2>
            <p>Import both strains and products</p>
            <button id="import-all" class="button button-primary">Import All Data</button>
            <div id="all-result"></div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            function runImport(type) {
                $.post(ajaxurl, {
                    action: 'skyworld_import_data',
                    import_type: type,
                    nonce: '<?php echo wp_create_nonce('skyworld_nonce'); ?>'
                }, function(response) {
                    $('#' + type + '-result').html('<pre>' + JSON.stringify(response, null, 2) + '</pre>');
                });
            }
            
            $('#import-strains').click(function() { runImport('strains'); });
            $('#import-products').click(function() { runImport('products'); });
            $('#import-all').click(function() { runImport('all'); });
        });
        </script>
    </div>
    <?php
}
