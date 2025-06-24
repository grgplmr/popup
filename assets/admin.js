/**
 * Pop-up Glassmorphism - JavaScript Admin
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Initialiser les color pickers
    $('.color-picker').wpColorPicker();
    $('.color-picker-rgba').wpColorPicker({ palettes: true, type: 'rgba' });
    
    // Gestion des onglets
    $('.tab-button').on('click', function() {
        const tabId = $(this).data('tab');
        
        // Mettre à jour les boutons
        $('.tab-button').removeClass('active');
        $(this).addClass('active');
        
        // Mettre à jour le contenu
        $('.tab-content').removeClass('active');
        $('#' + tabId + '-tab').addClass('active');
    });
    
    // Gestion du formulaire
    $('#popup-glass-form').on('submit', function(e) {
        e.preventDefault();
        saveSettings();
    });
    
    // Prévisualisation
    $('#preview-welcome').on('click', function() {
        previewPopup('welcome');
    });
    
    $('#preview-exit').on('click', function() {
        previewPopup('exit');
    });
    
    // Fermer la prévisualisation
    $(document).on('click', '#close-preview, .popup-preview-overlay', function(e) {
        if (e.target === this) {
            closePreview();
        }
    });
    
    // Échap pour fermer la prévisualisation
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            closePreview();
        }
    });
    
    /**
     * Sauvegarder les paramètres
     */
    function saveSettings() {
        const $form = $('#popup-glass-form');
        const $submitBtn = $form.find('button[type="submit"]');
        
        // Désactiver le bouton et afficher le loading
        $submitBtn.prop('disabled', true).addClass('loading');
        
        // Préparer les données
        const formData = new FormData($form[0]);
        formData.append('action', 'save_popup_settings');
        formData.append('nonce', popupGlassAjax.nonce);
        
        // Envoyer la requête AJAX
        $.ajax({
            url: popupGlassAjax.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showNotice(popupGlassAjax.strings.save_success, 'success');
                } else {
                    showNotice(response.data || popupGlassAjax.strings.save_error, 'error');
                }
            },
            error: function() {
                showNotice(popupGlassAjax.strings.save_error, 'error');
            },
            complete: function() {
                $submitBtn.prop('disabled', false).removeClass('loading');
            }
        });
    }
    
    /**
     * Prévisualiser un pop-up
     */
    function previewPopup(type) {
        const settings = getFormSettings(type);
        
        // Créer le HTML de prévisualisation
        const previewHtml = createPreviewHtml(settings);
        
        // Afficher la prévisualisation
        $('#popup-preview-container').html(previewHtml);
        $('#popup-preview-overlay').show();
    }
    
    /**
     * Fermer la prévisualisation
     */
    function closePreview() {
        $('#popup-preview-overlay').hide();
    }
    
    /**
     * Récupérer les paramètres du formulaire
     */
    function getFormSettings(type) {
        const prefix = type === 'welcome' ? 'welcome_' : 'exit_';
        
        return {
            title: $('[name="' + prefix + 'title"]').val(),
            content: getEditorContent(prefix + 'content'),
            backgroundColor: $('[name="' + prefix + 'bg_color"]').val(),
            textColor: $('[name="' + prefix + 'text_color"]').val(),
            fontSize: parseInt($('[name="' + prefix + 'font_size"]').val()) || 16,
            blur: parseInt($('[name="' + prefix + 'blur"]').val()) || 20,
            width: parseInt($('[name="' + prefix + 'width"]').val()) || 500,
            height: parseInt($('[name="' + prefix + 'height"]').val()) || 300
        };
    }
    
    /**
     * Récupérer le contenu de l'éditeur WordPress
     */
    function getEditorContent(editorId) {
        if (typeof tinyMCE !== 'undefined' && tinyMCE.get(editorId)) {
            return tinyMCE.get(editorId).getContent();
        }
        return $('#' + editorId).val();
    }
    
    /**
     * Créer le HTML de prévisualisation
     */
    function createPreviewHtml(settings) {
        return `
            <div style="
                width: ${settings.width}px;
                height: ${settings.height}px;
                background: ${settings.backgroundColor};
                color: ${settings.textColor};
                font-size: ${settings.fontSize}px;
                --blur-amount: ${settings.blur}px;
                position: relative;
                border-radius: 20px;
                overflow: hidden;
            ">
                <div class="preview-content">
                    <h2 class="preview-title" style="color: ${settings.textColor};">
                        ${escapeHtml(settings.title)}
                    </h2>
                    <div class="preview-text" style="color: ${settings.textColor}; font-size: ${settings.fontSize}px;">
                        ${settings.content}
                    </div>
                </div>
            </div>
        `;
    }
    
    /**
     * Échapper le HTML
     */
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    
    /**
     * Afficher une notification
     */
    function showNotice(message, type) {
        const noticeClass = type === 'error' ? 'popup-glass-notice error' : 'popup-glass-notice';
        const notice = $(`
            <div class="${noticeClass}">
                <p>${message}</p>
            </div>
        `);
        
        // Insérer la notification
        $('.popup-glass-container').before(notice);
        
        // Supprimer après 5 secondes
        setTimeout(function() {
            notice.fadeOut(function() {
                notice.remove();
            });
        }, 5000);
        
        // Scroll vers le haut pour voir la notification
        $('html, body').animate({
            scrollTop: $('.wrap').offset().top
        }, 500);
    }
    
    
    /**
     * Validation en temps réel
     */
    $('input[type="number"]').on('input', function() {
        const $input = $(this);
        const min = parseInt($input.attr('min'));
        const max = parseInt($input.attr('max'));
        const value = parseInt($input.val());
        
        if (value < min) {
            $input.val(min);
        } else if (value > max) {
            $input.val(max);
        }
    });
    
    /**
     * Auto-save (optionnel)
     */
    let autoSaveTimeout;
    $('#popup-glass-form input, #popup-glass-form textarea').on('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            // Optionnel : auto-save après 2 secondes d'inactivité
            // saveSettings();
        }, 2000);
    });
});