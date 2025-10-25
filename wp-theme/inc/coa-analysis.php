<?php
/**
 * COA Analysis System
 * 
 * Calculates strain averages from COA data and analyzes future COAs
 * Provides rules and automation for COA processing
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Calculate strain averages from all related product COAs
 */
function skyworld_calculate_strain_averages($strain_id) {
    // Get all products linked to this strain
    $products = get_posts(array(
        'post_type' => 'cannabis_product',
        'meta_query' => array(
            array(
                'key' => 'product_strain',
                'value' => $strain_id,
                'compare' => '='
            )
        ),
        'posts_per_page' => -1
    ));
    
    if (empty($products)) {
        return false;
    }
    
    $thc_values = array();
    $cbd_values = array();
    $cbg_values = array();
    $thcv_values = array();
    $terp_totals = array();
    $terpene_data = array();
    $test_dates = array();
    
    foreach ($products as $product) {
        // Collect cannabinoid data
        $thc = get_field('product_thc_content', $product->ID);
        $cbd = get_field('product_cbd_content', $product->ID);
        $cbg = get_field('product_cbg_content', $product->ID);
        $thcv = get_field('product_thcv_content', $product->ID);
        $terp_total = get_field('product_terp_total', $product->ID);
        $test_date = get_field('product_test_date', $product->ID);
        
        if ($thc) {
            $thc_values[] = floatval(str_replace('%', '', $thc));
        }
        if ($cbd) {
            $cbd_values[] = floatval(str_replace('%', '', $cbd));
        }
        if ($cbg) {
            $cbg_values[] = floatval(str_replace('%', '', $cbg));
        }
        if ($thcv) {
            $thcv_values[] = floatval(str_replace('%', '', $thcv));
        }
        if ($terp_total) {
            $terp_totals[] = floatval(str_replace('%', '', $terp_total));
        }
        if ($test_date) {
            $test_dates[] = $test_date;
        }
        
        // Collect terpene data
        if (have_rows('product_terpenes', $product->ID)) {
            while (have_rows('product_terpenes', $product->ID)) {
                the_row();
                $terpene_name = get_sub_field('terpene_name');
                $terpene_percentage = get_sub_field('terpene_percentage');
                
                if ($terpene_name && $terpene_percentage) {
                    if (!isset($terpene_data[$terpene_name])) {
                        $terpene_data[$terpene_name] = array();
                    }
                    $terpene_data[$terpene_name][] = floatval($terpene_percentage);
                }
            }
        }
    }
    
    // Calculate averages
    $averages = array(
        'thc_avg' => !empty($thc_values) ? round(array_sum($thc_values) / count($thc_values), 2) : 0,
        'cbd_avg' => !empty($cbd_values) ? round(array_sum($cbd_values) / count($cbd_values), 2) : 0,
        'cbg_avg' => !empty($cbg_values) ? round(array_sum($cbg_values) / count($cbg_values), 2) : 0,
        'thcv_avg' => !empty($thcv_values) ? round(array_sum($thcv_values) / count($thcv_values), 2) : 0,
        'terp_total_avg' => !empty($terp_totals) ? round(array_sum($terp_totals) / count($terp_totals), 2) : 0,
        'sample_count' => count($products),
        'latest_test' => !empty($test_dates) ? max($test_dates) : null,
        'terpenes' => array()
    );
    
    // Calculate terpene averages
    foreach ($terpene_data as $terpene_name => $values) {
        $averages['terpenes'][$terpene_name] = round(array_sum($values) / count($values), 3);
    }
    
    // Sort terpenes by average percentage (descending)
    arsort($averages['terpenes']);
    
    return $averages;
}

/**
 * Update strain with calculated averages
 */
function skyworld_update_strain_averages($strain_id) {
    $averages = skyworld_calculate_strain_averages($strain_id);
    
    if (!$averages) {
        return false;
    }
    
    // Update strain fields with averages
    update_field('strain_thc_avg', $averages['thc_avg'] . '%', $strain_id);
    update_field('strain_cbd_avg', $averages['cbd_avg'] . '%', $strain_id);
    update_field('strain_cbg_avg', $averages['cbg_avg'] . '%', $strain_id);
    update_field('strain_thcv_avg', $averages['thcv_avg'] . '%', $strain_id);
    update_field('strain_terp_total_avg', $averages['terp_total_avg'] . '%', $strain_id);
    update_field('strain_sample_count', $averages['sample_count'], $strain_id);
    update_field('strain_latest_test', $averages['latest_test'], $strain_id);
    
    // Update terpene profile with averages
    if (!empty($averages['terpenes'])) {
        // Clear existing terpene data
        delete_field('strain_terpenes', $strain_id);
        
        // Add averaged terpene data
        foreach ($averages['terpenes'] as $terpene_name => $percentage) {
            add_row('strain_terpenes', array(
                'terpene_name' => $terpene_name,
                'terpene_percentage' => $percentage
            ), $strain_id);
        }
    }
    
    return true;
}

/**
 * Analyze new COA against strain averages
 */
function skyworld_analyze_new_coa($product_id) {
    $strain_id = get_field('product_strain', $product_id);
    
    if (!$strain_id) {
        return array('success' => false, 'message' => 'No strain linked to product');
    }
    
    // Get current strain averages
    $current_averages = skyworld_calculate_strain_averages($strain_id);
    
    // Get new product data
    $new_thc = floatval(str_replace('%', '', get_field('product_thc_content', $product_id)));
    $new_cbd = floatval(str_replace('%', '', get_field('product_cbd_content', $product_id)));
    $new_cbg = floatval(str_replace('%', '', get_field('product_cbg_content', $product_id)));
    $new_terp_total = floatval(str_replace('%', '', get_field('product_terp_total', $product_id)));
    
    $analysis = array(
        'product_id' => $product_id,
        'strain_id' => $strain_id,
        'comparisons' => array(),
        'flags' => array(),
        'recommendations' => array()
    );
    
    // THC Analysis
    if ($current_averages['thc_avg'] > 0) {
        $thc_variance = abs($new_thc - $current_averages['thc_avg']);
        $thc_percentage_variance = ($thc_variance / $current_averages['thc_avg']) * 100;
        
        $analysis['comparisons']['thc'] = array(
            'new_value' => $new_thc,
            'strain_avg' => $current_averages['thc_avg'],
            'variance' => $thc_variance,
            'percentage_variance' => round($thc_percentage_variance, 2)
        );
        
        if ($thc_percentage_variance > 20) {
            $analysis['flags'][] = 'High THC variance (' . round($thc_percentage_variance, 1) . '%)';
        }
    }
    
    // CBD Analysis
    if ($current_averages['cbd_avg'] > 0) {
        $cbd_variance = abs($new_cbd - $current_averages['cbd_avg']);
        $cbd_percentage_variance = ($cbd_variance / $current_averages['cbd_avg']) * 100;
        
        $analysis['comparisons']['cbd'] = array(
            'new_value' => $new_cbd,
            'strain_avg' => $current_averages['cbd_avg'],
            'variance' => $cbd_variance,
            'percentage_variance' => round($cbd_percentage_variance, 2)
        );
        
        if ($cbd_percentage_variance > 30) {
            $analysis['flags'][] = 'High CBD variance (' . round($cbd_percentage_variance, 1) . '%)';
        }
    }
    
    // Terpene Analysis
    if ($current_averages['terp_total_avg'] > 0) {
        $terp_variance = abs($new_terp_total - $current_averages['terp_total_avg']);
        $terp_percentage_variance = ($terp_variance / $current_averages['terp_total_avg']) * 100;
        
        $analysis['comparisons']['terp_total'] = array(
            'new_value' => $new_terp_total,
            'strain_avg' => $current_averages['terp_total_avg'],
            'variance' => $terp_variance,
            'percentage_variance' => round($terp_percentage_variance, 2)
        );
        
        if ($terp_percentage_variance > 25) {
            $analysis['flags'][] = 'High terpene variance (' . round($terp_percentage_variance, 1) . '%)';
        }
    }
    
    // Generate recommendations
    if (empty($analysis['flags'])) {
        $analysis['recommendations'][] = 'COA values are within normal variance ranges';
    } else {
        $analysis['recommendations'][] = 'Review flagged variances before publishing';
        $analysis['recommendations'][] = 'Consider updating strain averages after verification';
    }
    
    // Check for quality thresholds
    if ($new_thc < 15) {
        $analysis['flags'][] = 'Low THC content (< 15%)';
    }
    if ($new_terp_total < 1.0) {
        $analysis['flags'][] = 'Low terpene content (< 1.0%)';
    }
    
    return $analysis;
}

/**
 * Auto-update strain averages when new COA is added
 */
function skyworld_auto_update_strain_averages($post_id) {
    if (get_post_type($post_id) !== 'cannabis_product') {
        return;
    }
    
    $strain_id = get_field('product_strain', $post_id);
    if ($strain_id) {
        skyworld_update_strain_averages($strain_id);
    }
}
add_action('save_post', 'skyworld_auto_update_strain_averages');

/**
 * Batch update all strain averages
 */
function skyworld_batch_update_all_averages() {
    $strains = get_posts(array(
        'post_type' => 'strain',
        'posts_per_page' => -1
    ));
    
    $updated = 0;
    $errors = array();
    
    foreach ($strains as $strain) {
        if (skyworld_update_strain_averages($strain->ID)) {
            $updated++;
        } else {
            $errors[] = 'Failed to update strain: ' . $strain->post_title;
        }
    }
    
    return array(
        'success' => true,
        'updated' => $updated,
        'errors' => $errors
    );
}

/**
 * AJAX handler for COA analysis
 */
function skyworld_ajax_analyze_coa() {
    if (!wp_verify_nonce($_POST['nonce'], 'skyworld_nonce')) {
        wp_die('Security check failed');
    }
    
    $product_id = intval($_POST['product_id']);
    $analysis = skyworld_analyze_new_coa($product_id);
    
    wp_send_json($analysis);
}
add_action('wp_ajax_skyworld_analyze_coa', 'skyworld_ajax_analyze_coa');

/**
 * AJAX handler for batch update
 */
function skyworld_ajax_batch_update_averages() {
    if (!wp_verify_nonce($_POST['nonce'], 'skyworld_nonce')) {
        wp_die('Security check failed');
    }
    
    $results = skyworld_batch_update_all_averages();
    wp_send_json($results);
}
add_action('wp_ajax_skyworld_batch_update_averages', 'skyworld_ajax_batch_update_averages');

/**
 * Add COA analysis admin page
 */
function skyworld_add_coa_analysis_page() {
    add_management_page(
        'COA Analysis',
        'COA Analysis',
        'manage_options',
        'skyworld-coa-analysis',
        'skyworld_coa_analysis_admin_page'
    );
}
add_action('admin_menu', 'skyworld_add_coa_analysis_page');

/**
 * COA Analysis admin page
 */
function skyworld_coa_analysis_admin_page() {
    ?>
    <div class="wrap">
        <h1>COA Analysis System</h1>
        
        <div class="analysis-section">
            <h2>Batch Update Strain Averages</h2>
            <p>Recalculate all strain averages from current COA data</p>
            <button id="batch-update-averages" class="button button-primary">Update All Averages</button>
            <div id="batch-result"></div>
        </div>
        
        <div class="analysis-section">
            <h2>Analyze New COA</h2>
            <p>Analyze a specific product's COA against strain averages</p>
            <select id="product-select">
                <option value="">Select a Product</option>
                <?php
                $products = get_posts(array(
                    'post_type' => 'cannabis_product',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC'
                ));
                
                foreach ($products as $product) {
                    echo '<option value="' . $product->ID . '">' . $product->post_title . '</option>';
                }
                ?>
            </select>
            <button id="analyze-coa" class="button button-secondary">Analyze COA</button>
            <div id="analysis-result"></div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#batch-update-averages').click(function() {
                $.post(ajaxurl, {
                    action: 'skyworld_batch_update_averages',
                    nonce: '<?php echo wp_create_nonce('skyworld_nonce'); ?>'
                }, function(response) {
                    $('#batch-result').html('<pre>' + JSON.stringify(response, null, 2) + '</pre>');
                });
            });
            
            $('#analyze-coa').click(function() {
                var productId = $('#product-select').val();
                if (!productId) {
                    alert('Please select a product');
                    return;
                }
                
                $.post(ajaxurl, {
                    action: 'skyworld_analyze_coa',
                    product_id: productId,
                    nonce: '<?php echo wp_create_nonce('skyworld_nonce'); ?>'
                }, function(response) {
                    $('#analysis-result').html('<pre>' + JSON.stringify(response, null, 2) + '</pre>');
                });
            });
        });
        </script>
    </div>
    <?php
}
