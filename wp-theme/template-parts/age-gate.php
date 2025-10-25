<?php
/**
 * Age Gate Template Part
 * 
 * 21+ age verification overlay with ACF Pro Extended admin controls
 */

// Get age gate settings using helper functions
$age_gate_settings = skyworld_get_age_gate_settings();

// Only show age gate if enabled
if ($age_gate_settings['enabled']): ?>

<!-- Age Gate Overlay -->
<div class="age-gate" id="ageGate" style="<?php echo skyworld_get_age_gate_styles(); ?>">
    <?php if ($age_gate_settings['background']): ?>
        <div class="age-gate-bg-image" style="background-image: url('<?php echo esc_url($age_gate_settings['background']['url']); ?>');"></div>
    <?php endif; ?>
    
    <div class="age-gate-content">
        <?php if ($age_gate_settings['logo']): ?>
            <div class="age-gate-logo">
                <img src="<?php echo esc_url($age_gate_settings['logo']['url']); ?>" alt="<?php echo esc_attr($age_gate_settings['logo']['alt']); ?>" style="max-width: 200px; max-height: 60px;">
            </div>
        <?php else: ?>
            <div class="age-gate-logo" style="background: <?php echo esc_attr($age_gate_settings['primary_color']); ?>; color: #000; width: 200px; height: 60px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 24px; margin: 0 auto 30px;">
                SKYWORLD
            </div>
        <?php endif; ?>
        
        <h2 style="<?php echo skyworld_get_age_gate_text_styles(); ?>"><?php echo esc_html($age_gate_settings['title']); ?></h2>
        <p style="<?php echo skyworld_get_age_gate_text_styles(); ?>"><?php echo esc_html($age_gate_settings['message']); ?></p>
        <p class="age-gate-disclaimer" style="<?php echo skyworld_get_age_gate_text_styles(); ?> opacity: 0.8;"><?php echo esc_html($age_gate_settings['disclaimer']); ?></p>
        
        <div class="age-buttons">
            <button class="age-btn yes" onclick="enterSite()" style="<?php echo skyworld_get_age_gate_button_styles('yes'); ?>">
                <?php echo esc_html($age_gate_settings['yes_button']); ?>
            </button>
            <button class="age-btn no" onclick="handleUnder21()" style="<?php echo skyworld_get_age_gate_button_styles('no'); ?>">
                <?php echo esc_html($age_gate_settings['no_button']); ?>
            </button>
        </div>
    </div>
</div>

<script>
function enterSite() {
    // Get cookie duration from ACF settings
    const cookieDuration = <?php echo skyworld_get_age_gate_cookie_duration(); ?>;
    
    // Set cookie to remember age verification
    document.cookie = "age_verified=true; path=/; max-age=" + cookieDuration;
    
    // Hide age gate and show main content
    document.getElementById('ageGate').style.display = 'none';
    document.getElementById('mainContent').classList.remove('hidden');
}

function handleUnder21() {
    const deniedMessage = <?php echo json_encode($age_gate_settings['denied_message']); ?>;
    const redirectUrl = <?php echo json_encode($age_gate_settings['redirect_url']); ?>;
    
    if (redirectUrl) {
        // Redirect to specified URL
        window.location.href = redirectUrl;
    } else {
        // Show alert message
        alert(deniedMessage);
    }
}

// Check if user has already verified age
document.addEventListener('DOMContentLoaded', function() {
    if (document.cookie.includes('age_verified=true')) {
        document.getElementById('ageGate').style.display = 'none';
        document.getElementById('mainContent').classList.remove('hidden');
    }
});
</script>

<?php endif; ?>
