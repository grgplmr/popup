<?php
/**
 * Désinstallation du plugin Pop-up Glassmorphism
 * 
 * Ce fichier est exécuté quand le plugin est supprimé via l'interface WordPress
 */

// Sécurité : vérifier que la désinstallation est légitime
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Supprimer les options du plugin
delete_option('popup_glass_settings');

// Supprimer les métadonnées des utilisateurs (si applicable)
delete_metadata('user', 0, 'popup_glass_user_meta', '', true);

// Nettoyer le cache
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}

// Log de désinstallation (optionnel, pour debug)
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('Pop-up Glassmorphism plugin uninstalled successfully');
}
