<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}

// Ne pas afficher sur les pages d'admin
if (is_admin()) {
    return;
}
?>

<!-- Pop-up d'accueil -->
<?php if ($settings['welcome_popup']['enabled']): ?>
<div id="popup-welcome" class="popup-glass-overlay" style="display: none;">
    <div class="popup-glass-container" 
         style="width: <?php echo esc_attr($settings['welcome_popup']['width']); ?>px;
                height: <?php echo esc_attr($settings['welcome_popup']['height']); ?>px;
                background: <?php echo esc_attr($settings['welcome_popup']['background_color']); ?>;
                color: <?php echo esc_attr($settings['welcome_popup']['text_color']); ?>;
                font-size: <?php echo esc_attr($settings['welcome_popup']['font_size']); ?>px;
                --blur-amount: <?php echo esc_attr($settings['welcome_popup']['blur']); ?>px;">
        
        <button class="popup-glass-close" aria-label="<?php _e('Fermer', 'popup-glassmorphism'); ?>">
            <span>&times;</span>
        </button>
        
        <div class="popup-glass-content">
            <h2 class="popup-glass-title"><?php echo esc_html($settings['welcome_popup']['title']); ?></h2>
            <div class="popup-glass-text">
                <?php echo wp_kses_post($settings['welcome_popup']['content']); ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Pop-up Exit Intent -->
<?php if ($settings['exit_intent_popup']['enabled']): ?>
<div id="popup-exit-intent" class="popup-glass-overlay" style="display: none;">
    <div class="popup-glass-container"
         style="width: <?php echo esc_attr($settings['exit_intent_popup']['width']); ?>px;
                height: <?php echo esc_attr($settings['exit_intent_popup']['height']); ?>px;
                background: <?php echo esc_attr($settings['exit_intent_popup']['background_color']); ?>;
                color: <?php echo esc_attr($settings['exit_intent_popup']['text_color']); ?>;
                font-size: <?php echo esc_attr($settings['exit_intent_popup']['font_size']); ?>px;
                --blur-amount: <?php echo esc_attr($settings['exit_intent_popup']['blur']); ?>px;">
        
        <button class="popup-glass-close" aria-label="<?php _e('Fermer', 'popup-glassmorphism'); ?>">
            <span>&times;</span>
        </button>
        
        <div class="popup-glass-content">
            <h2 class="popup-glass-title"><?php echo esc_html($settings['exit_intent_popup']['title']); ?></h2>
            <div class="popup-glass-text">
                <?php echo wp_kses_post($settings['exit_intent_popup']['content']); ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
