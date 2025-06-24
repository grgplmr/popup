<?php
// Sécurité
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap popup-glass-admin">
    <h1><?php _e('Configuration Pop-up Glassmorphism', 'popup-glassmorphism'); ?></h1>
    
    <div class="popup-glass-container">
        <div class="popup-glass-tabs">
            <button class="tab-button active" data-tab="welcome">
                <?php _e('Pop-up d\'accueil', 'popup-glassmorphism'); ?>
            </button>
            <button class="tab-button" data-tab="exit">
                <?php _e('Pop-up Exit Intent', 'popup-glassmorphism'); ?>
            </button>
        </div>
        
        <form id="popup-glass-form" method="post">
            <?php wp_nonce_field('popup_glass_nonce', 'popup_glass_nonce'); ?>
            
            <!-- Pop-up d'accueil -->
            <div class="tab-content active" id="welcome-tab">
                <div class="popup-glass-section">
                    <h2><?php _e('Pop-up d\'accueil', 'popup-glassmorphism'); ?></h2>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Activer', 'popup-glassmorphism'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="welcome_enabled" value="1" 
                                           <?php checked($settings['welcome_popup']['enabled']); ?>>
                                    <?php _e('Activer le pop-up d\'accueil', 'popup-glassmorphism'); ?>
                                </label>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Délai d\'apparition (ms)', 'popup-glassmorphism'); ?></th>
                            <td>
                                <input type="number" name="welcome_delay" min="0" max="30000" step="500"
                                       value="<?php echo esc_attr($settings['welcome_popup']['delay']); ?>" class="regular-text">
                                <p class="description"><?php _e('Délai en millisecondes avant l\'affichage du pop-up', 'popup-glassmorphism'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Titre', 'popup-glassmorphism'); ?></th>
                            <td>
                                <input type="text" name="welcome_title" 
                                       value="<?php echo esc_attr($settings['welcome_popup']['title']); ?>" class="large-text">
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Contenu', 'popup-glassmorphism'); ?></th>
                            <td>
                                <?php
                                wp_editor($settings['welcome_popup']['content'], 'welcome_content', [
                                    'textarea_name' => 'welcome_content',
                                    'media_buttons' => false,
                                    'textarea_rows' => 5,
                                    'teeny' => true
                                ]);
                                ?>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Couleur de fond', 'popup-glassmorphism'); ?></th>
                            <td>
                                <input type="text" name="welcome_bg_color" 
                                       value="<?php echo esc_attr($settings['welcome_popup']['background_color']); ?>" 
                                       class="color-picker-rgba">
                                <p class="description"><?php _e('Format: rgba(255,255,255,0.25) pour la transparence', 'popup-glassmorphism'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Couleur du texte', 'popup-glassmorphism'); ?></th>
                            <td>
                                <input type="text" name="welcome_text_color" 
                                       value="<?php echo esc_attr($settings['welcome_popup']['text_color']); ?>" 
                                       class="color-picker">
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Taille de police (px)', 'popup-glassmorphism'); ?></th>
                            <td>
                                <input type="number" name="welcome_font_size" min="12" max="24"
                                       value="<?php echo esc_attr($settings['welcome_popup']['font_size']); ?>" class="small-text">
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Dimensions', 'popup-glassmorphism'); ?></th>
                            <td>
                                <label>
                                    <?php _e('Largeur:', 'popup-glassmorphism'); ?>
                                    <input type="number" name="welcome_width" min="300" max="800"
                                           value="<?php echo esc_attr($settings['welcome_popup']['width']); ?>" class="small-text"> px
                                </label>
                                <br><br>
                                <label>
                                    <?php _e('Hauteur:', 'popup-glassmorphism'); ?>
                                    <input type="number" name="welcome_height" min="200" max="600"
                                           value="<?php echo esc_attr($settings['welcome_popup']['height']); ?>" class="small-text"> px
                                </label>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Pop-up Exit Intent -->
            <div class="tab-content" id="exit-tab">
                <div class="popup-glass-section">
                    <h2><?php _e('Pop-up Exit Intent', 'popup-glassmorphism'); ?></h2>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Activer', 'popup-glassmorphism'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="exit_enabled" value="1" 
                                           <?php checked($settings['exit_intent_popup']['enabled']); ?>>
                                    <?php _e('Activer le pop-up exit intent', 'popup-glassmorphism'); ?>
                                </label>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Titre', 'popup-glassmorphism'); ?></th>
                            <td>
                                <input type="text" name="exit_title" 
                                       value="<?php echo esc_attr($settings['exit_intent_popup']['title']); ?>" class="large-text">
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Contenu', 'popup-glassmorphism'); ?></th>
                            <td>
                                <?php
                                wp_editor($settings['exit_intent_popup']['content'], 'exit_content', [
                                    'textarea_name' => 'exit_content',
                                    'media_buttons' => false,
                                    'textarea_rows' => 5,
                                    'teeny' => true
                                ]);
                                ?>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Couleur de fond', 'popup-glassmorphism'); ?></th>
                            <td>
                                <input type="text" name="exit_bg_color" 
                                       value="<?php echo esc_attr($settings['exit_intent_popup']['background_color']); ?>" 
                                       class="color-picker-rgba">
                                <p class="description"><?php _e('Format: rgba(255,255,255,0.25) pour la transparence', 'popup-glassmorphism'); ?></p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Couleur du texte', 'popup-glassmorphism'); ?></th>
                            <td>
                                <input type="text" name="exit_text_color" 
                                       value="<?php echo esc_attr($settings['exit_intent_popup']['text_color']); ?>" 
                                       class="color-picker">
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Taille de police (px)', 'popup-glassmorphism'); ?></th>
                            <td>
                                <input type="number" name="exit_font_size" min="12" max="24"
                                       value="<?php echo esc_attr($settings['exit_intent_popup']['font_size']); ?>" class="small-text">
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><?php _e('Dimensions', 'popup-glassmorphism'); ?></th>
                            <td>
                                <label>
                                    <?php _e('Largeur:', 'popup-glassmorphism'); ?>
                                    <input type="number" name="exit_width" min="300" max="800"
                                           value="<?php echo esc_attr($settings['exit_intent_popup']['width']); ?>" class="small-text"> px
                                </label>
                                <br><br>
                                <label>
                                    <?php _e('Hauteur:', 'popup-glassmorphism'); ?>
                                    <input type="number" name="exit_height" min="200" max="600"
                                           value="<?php echo esc_attr($settings['exit_intent_popup']['height']); ?>" class="small-text"> px
                                </label>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="popup-glass-actions">
                <button type="button" id="preview-welcome" class="button button-secondary">
                    <?php _e('Prévisualiser Pop-up d\'accueil', 'popup-glassmorphism'); ?>
                </button>
                <button type="button" id="preview-exit" class="button button-secondary">
                    <?php _e('Prévisualiser Pop-up Exit Intent', 'popup-glassmorphism'); ?>
                </button>
                <button type="submit" class="button button-primary">
                    <?php _e('Sauvegarder les paramètres', 'popup-glassmorphism'); ?>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Zone de prévisualisation -->
    <div id="popup-preview-overlay" class="popup-preview-overlay" style="display: none;">
        <div id="popup-preview-container"></div>
        <button id="close-preview" class="close-preview">×</button>
    </div>
</div>