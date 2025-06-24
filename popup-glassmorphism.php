<?php
/**
 * Plugin Name: Pop-up Glassmorphism
 * Plugin URI: https://example.com/popup-glassmorphism
 * Description: Plugin WordPress pour créer des pop-ups avec effet glassmorphism - pop-up d'accueil et exit intent personnalisables
 * Version: 1.0.0
 * Author: Votre Nom
 * Author URI: https://example.com
 * Text Domain: popup-glassmorphism
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Définir les constantes du plugin
define('POPUP_GLASS_VERSION', '1.0.0');
define('POPUP_GLASS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('POPUP_GLASS_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * Classe principale du plugin
 */
class PopupGlassmorphism {
    
    /**
     * Instance unique du plugin
     */
    private static $instance = null;
    
    /**
     * Options par défaut
     */
    private $default_options = [
        'welcome_popup' => [
            'enabled' => true,
            'delay' => 3000,
            'title' => 'Bienvenue sur notre site !',
            'content' => 'Découvrez nos <strong>offres exclusives</strong> et <a href="#" style="color: #3B82F6;">profitez de -20%</a> sur votre première commande.',
            'background_color' => 'rgba(255, 255, 255, 0.25)',
            'text_color' => '#1F2937',
            'font_size' => 16,
            'width' => 500,
            'height' => 300
        ],
        'exit_intent_popup' => [
            'enabled' => true,
            'title' => 'Attendez, ne partez pas !',
            'content' => 'Avant de partir, découvrez notre <strong>guide gratuit</strong> pour <a href="#" style="color: #10B981;">optimiser votre site web</a>.',
            'background_color' => 'rgba(139, 92, 246, 0.25)',
            'text_color' => '#1F2937',
            'font_size' => 16,
            'width' => 450,
            'height' => 280
        ]
    ];
    
    /**
     * Obtenir l'instance unique
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructeur
     */
    private function __construct() {
        add_action('init', [$this, 'init']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'frontend_enqueue_scripts']);
        add_action('wp_footer', [$this, 'render_popups']);
        add_action('wp_ajax_save_popup_settings', [$this, 'save_popup_settings']);
        add_action('wp_ajax_get_popup_settings', [$this, 'get_popup_settings']);
        add_action('plugins_loaded', [$this, 'load_textdomain']);
        
        // Hook d'activation/désactivation
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
    }
    
    /**
     * Initialisation du plugin
     */
    public function init() {
        // Initialiser les options par défaut si elles n'existent pas
        if (!get_option('popup_glass_settings')) {
            add_option('popup_glass_settings', $this->default_options);
        }
    }
    
    /**
     * Charger les traductions
     */
    public function load_textdomain() {
        load_plugin_textdomain('popup-glassmorphism', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    /**
     * Ajouter le menu d'administration
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Pop-up Glassmorphism', 'popup-glassmorphism'),
            __('Pop-up Glassmorphism', 'popup-glassmorphism'),
            'manage_options',
            'popup-glassmorphism',
            [$this, 'admin_page'],
            'dashicons-visibility',
            30
        );
    }
    
    /**
     * Charger les scripts et styles de l'admin
     */
    public function admin_enqueue_scripts($hook) {
        if ('toplevel_page_popup-glassmorphism' !== $hook) {
            return;
        }
        
        wp_enqueue_script(
            'popup-glass-admin',
            POPUP_GLASS_PLUGIN_URL . 'assets/admin.js',
            ['jquery', 'wp-color-picker'],
            POPUP_GLASS_VERSION,
            true
        );
        
        wp_enqueue_style(
            'popup-glass-admin',
            POPUP_GLASS_PLUGIN_URL . 'assets/admin.css',
            ['wp-color-picker'],
            POPUP_GLASS_VERSION
        );
        
        wp_localize_script('popup-glass-admin', 'popupGlassAjax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('popup_glass_nonce'),
            'strings' => [
                'save_success' => __('Paramètres sauvegardés avec succès !', 'popup-glassmorphism'),
                'save_error' => __('Erreur lors de la sauvegarde.', 'popup-glassmorphism')
            ]
        ]);
    }
    
    /**
     * Charger les scripts et styles du frontend
     */
    public function frontend_enqueue_scripts() {
        if (is_admin()) {
            return;
        }
        
        wp_enqueue_script(
            'popup-glass-frontend',
            POPUP_GLASS_PLUGIN_URL . 'assets/frontend.js',
            [],
            POPUP_GLASS_VERSION,
            true
        );
        
        wp_enqueue_style(
            'popup-glass-frontend',
            POPUP_GLASS_PLUGIN_URL . 'assets/frontend.css',
            [],
            POPUP_GLASS_VERSION
        );
        
        // Passer les paramètres au JavaScript
        $settings = get_option('popup_glass_settings', $this->default_options);
        wp_localize_script('popup-glass-frontend', 'popupGlassSettings', $settings);
    }
    
    /**
     * Page d'administration
     */
    public function admin_page() {
        $settings = get_option('popup_glass_settings', $this->default_options);
        include POPUP_GLASS_PLUGIN_PATH . 'templates/admin-page.php';
    }
    
    /**
     * Sauvegarder les paramètres via AJAX
     */
    public function save_popup_settings() {
        check_ajax_referer('popup_glass_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Vous n\'avez pas les permissions nécessaires.', 'popup-glassmorphism'));
        }
        
        $settings = [];
        
        // Pop-up d'accueil
        $settings['welcome_popup'] = [
            'enabled' => isset($_POST['welcome_enabled']) ? true : false,
            'delay' => intval($_POST['welcome_delay']),
            'title' => sanitize_text_field($_POST['welcome_title']),
            'content' => wp_kses_post($_POST['welcome_content']),
            'background_color' => sanitize_text_field($_POST['welcome_bg_color']),
            'text_color' => sanitize_hex_color($_POST['welcome_text_color']),
            'font_size' => intval($_POST['welcome_font_size']),
            'width' => intval($_POST['welcome_width']),
            'height' => intval($_POST['welcome_height'])
        ];
        
        // Pop-up exit intent
        $settings['exit_intent_popup'] = [
            'enabled' => isset($_POST['exit_enabled']) ? true : false,
            'title' => sanitize_text_field($_POST['exit_title']),
            'content' => wp_kses_post($_POST['exit_content']),
            'background_color' => sanitize_text_field($_POST['exit_bg_color']),
            'text_color' => sanitize_hex_color($_POST['exit_text_color']),
            'font_size' => intval($_POST['exit_font_size']),
            'width' => intval($_POST['exit_width']),
            'height' => intval($_POST['exit_height'])
        ];
        
        update_option('popup_glass_settings', $settings);
        
        wp_send_json_success(__('Paramètres sauvegardés avec succès !', 'popup-glassmorphism'));
    }
    
    /**
     * Récupérer les paramètres via AJAX
     */
    public function get_popup_settings() {
        check_ajax_referer('popup_glass_nonce', 'nonce');
        
        $settings = get_option('popup_glass_settings', $this->default_options);
        wp_send_json_success($settings);
    }
    
    /**
     * Rendre les pop-ups sur le frontend
     */
    public function render_popups() {
        if (is_admin()) {
            return;
        }
        
        $settings = get_option('popup_glass_settings', $this->default_options);
        include POPUP_GLASS_PLUGIN_PATH . 'templates/popup-html.php';
    }
    
    /**
     * Activation du plugin
     */
    public function activate() {
        add_option('popup_glass_settings', $this->default_options);
        flush_rewrite_rules();
    }
    
    /**
     * Désactivation du plugin
     */
    public function deactivate() {
        flush_rewrite_rules();
    }
}

// Initialiser le plugin
PopupGlassmorphism::get_instance();