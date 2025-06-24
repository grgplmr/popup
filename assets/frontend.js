/**
 * Pop-up Glassmorphism - JavaScript Frontend
 */

(function() {
    'use strict';
    
    // Variables globales
    let welcomeShown = false;
    let exitIntentShown = false;
    let isExitIntentActive = false;
    
    // Attendre que le DOM soit chargé
    document.addEventListener('DOMContentLoaded', function() {
        initPopups();
    });
    
    /**
     * Initialiser les pop-ups
     */
    function initPopups() {
        // Vérifier si les paramètres sont disponibles
        if (typeof popupGlassSettings === 'undefined') {
            return;
        }
        
        const settings = popupGlassSettings;
        
        // Initialiser le pop-up d'accueil
        if (settings.welcome_popup && settings.welcome_popup.enabled) {
            initWelcomePopup(settings.welcome_popup);
        }
        
        // Initialiser le pop-up exit intent
        if (settings.exit_intent_popup && settings.exit_intent_popup.enabled) {
            initExitIntentPopup();
        }
        
        // Gérer les événements de fermeture
        setupCloseEvents();
        
        // Gérer l'accessibilité
        setupAccessibility();
    }
    
    /**
     * Initialiser le pop-up d'accueil
     */
    function initWelcomePopup(settings) {
        // Vérifier si déjà affiché dans cette session
        if (sessionStorage.getItem('popup_welcome_shown')) {
            return;
        }
        
        const delay = parseInt(settings.delay) || 3000;
        
        setTimeout(function() {
            if (!welcomeShown) {
                showPopup('popup-welcome');
                welcomeShown = true;
                sessionStorage.setItem('popup_welcome_shown', '1');
            }
        }, delay);
    }
    
    /**
     * Initialiser le pop-up exit intent
     */
    function initExitIntentPopup() {
        // Vérifier si déjà affiché dans cette session
        if (sessionStorage.getItem('popup_exit_shown')) {
            return;
        }
        
        // Détecter l'intention de sortie sur desktop
        document.addEventListener('mouseleave', function(e) {
            if (e.clientY <= 0 && !exitIntentShown && !isExitIntentActive) {
                showPopup('popup-exit-intent');
                exitIntentShown = true;
                sessionStorage.setItem('popup_exit_shown', '1');
            }
        });
        
        // Détecter l'intention de sortie sur mobile (scroll vers le haut rapide)
        let lastScrollTop = 0;
        let scrollUpCount = 0;
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop < lastScrollTop && scrollTop < 100) {
                scrollUpCount++;
                if (scrollUpCount >= 3 && !exitIntentShown && !isExitIntentActive) {
                    showPopup('popup-exit-intent');
                    exitIntentShown = true;
                    sessionStorage.setItem('popup_exit_shown', '1');
                }
            } else {
                scrollUpCount = 0;
            }
            
            lastScrollTop = scrollTop;
        });
    }
    
    /**
     * Afficher un pop-up
     */
    function showPopup(popupId) {
        const popup = document.getElementById(popupId);
        if (!popup) return;
        
        // Empêcher le scroll du body
        document.body.style.overflow = 'hidden';
        
        // Afficher le pop-up
        popup.style.display = 'flex';
        
        // Ajouter la classe show avec un petit délai pour l'animation
        setTimeout(function() {
            popup.classList.add('show');
        }, 10);
        
        // Focus sur le pop-up pour l'accessibilité
        const container = popup.querySelector('.popup-glass-container');
        if (container) {
            container.setAttribute('tabindex', '-1');
            container.focus();
        }
        
        // Marquer comme actif
        if (popupId === 'popup-exit-intent') {
            isExitIntentActive = true;
        }
    }
    
    /**
     * Fermer un pop-up
     */
    function closePopup(popup) {
        if (!popup) return;
        
        popup.classList.remove('show');
        
        setTimeout(function() {
            popup.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
        
        // Marquer comme inactif
        if (popup.id === 'popup-exit-intent') {
            isExitIntentActive = false;
        }
    }
    
    /**
     * Configurer les événements de fermeture
     */
    function setupCloseEvents() {
        // Boutons de fermeture
        document.querySelectorAll('.popup-glass-close').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const popup = button.closest('.popup-glass-overlay');
                closePopup(popup);
            });
        });
        
        // Clic sur l'overlay
        document.querySelectorAll('.popup-glass-overlay').forEach(function(overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    closePopup(overlay);
                }
            });
        });
        
        // Touche Échap
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const activePopup = document.querySelector('.popup-glass-overlay.show');
                if (activePopup) {
                    closePopup(activePopup);
                }
            }
        });
    }
    
    /**
     * Configurer l'accessibilité
     */
    function setupAccessibility() {
        // Gérer le focus trap dans les pop-ups
        document.querySelectorAll('.popup-glass-overlay').forEach(function(popup) {
            popup.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    trapFocus(e, popup);
                }
            });
        });
    }
    
    /**
     * Piéger le focus dans un élément
     */
    function trapFocus(e, container) {
        const focusableElements = container.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        const firstFocusable = focusableElements[0];
        const lastFocusable = focusableElements[focusableElements.length - 1];
        
        if (e.shiftKey) {
            if (document.activeElement === firstFocusable) {
                lastFocusable.focus();
                e.preventDefault();
            }
        } else {
            if (document.activeElement === lastFocusable) {
                firstFocusable.focus();
                e.preventDefault();
            }
        }
    }
    
    /**
     * Utilitaire pour déboguer (à supprimer en production)
     */
    function debug(message) {
        if (window.console && window.console.log) {
            console.log('[Popup Glassmorphism] ' + message);
        }
    }
    
    // Exposer certaines fonctions pour les tests
    window.PopupGlass = {
        showPopup: showPopup,
        closePopup: closePopup
    };
    
})();