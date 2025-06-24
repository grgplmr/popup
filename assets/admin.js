/**
 * Pop-up Glassmorphism - JavaScript Admin
 */

document.addEventListener('DOMContentLoaded', () => {
    'use strict';
    
    // Initialiser les color pickers
    document.querySelectorAll('.color-picker').forEach((el) => {
        jQuery(el).wpColorPicker();
    });
    
    // Gestion des onglets
    document.querySelectorAll('.tab-button').forEach((btn) => {
        btn.addEventListener('click', () => {
            const tabId = btn.dataset.tab;

            document.querySelectorAll('.tab-button').forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');

            document.querySelectorAll('.tab-content').forEach((c) => c.classList.remove('active'));
            const tab = document.getElementById(`${tabId}-tab`);
            if (tab) {
                tab.classList.add('active');
            }
        });
    });
    
    // Gestion du formulaire
    const form = document.getElementById('popup-glass-form');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        saveSettings();
    });
    
    // Prévisualisation
    document.getElementById('preview-welcome').addEventListener('click', () => {
        previewPopup('welcome');
    });

    document.getElementById('preview-exit').addEventListener('click', () => {
        previewPopup('exit');
    });
    
    // Fermer la prévisualisation
    document.getElementById('close-preview').addEventListener('click', closePreview);

    const overlay = document.querySelector('.popup-preview-overlay');
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            closePreview();
        }
    });
    
    // Échap pour fermer la prévisualisation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closePreview();
        }
    });
    
    /**
     * Sauvegarder les paramètres
     */
    function saveSettings() {
        const form = document.getElementById('popup-glass-form');
        const submitBtn = form.querySelector('button[type="submit"]');

        submitBtn.disabled = true;
        submitBtn.classList.add('loading');

        const formData = new FormData(form);
        formData.append('action', 'save_popup_settings');
        formData.append('nonce', popupGlassAjax.nonce);

        fetch(popupGlassAjax.ajaxurl, {
            method: 'POST',
            body: formData
        })
            .then((r) => r.json())
            .then((response) => {
                if (response.success) {
                    showNotice(popupGlassAjax.strings.save_success, 'success');
                } else {
                    showNotice(response.data || popupGlassAjax.strings.save_error, 'error');
                }
            })
            .catch(() => {
                showNotice(popupGlassAjax.strings.save_error, 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
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
        document.getElementById('popup-preview-container').innerHTML = previewHtml;
        document.getElementById('popup-preview-overlay').style.display = 'flex';
    }
    
    /**
     * Fermer la prévisualisation
     */
    function closePreview() {
        document.getElementById('popup-preview-overlay').style.display = 'none';
    }
    
    /**
     * Récupérer les paramètres du formulaire
     */
    function getFormSettings(type) {
        const prefix = type === 'welcome' ? 'welcome_' : 'exit_';
        
        return {
            title: document.querySelector(`[name="${prefix}title"]`).value,
            content: getEditorContent(prefix + 'content'),
            backgroundColor: document.querySelector(`[name="${prefix}bg_color"]`).value,
            textColor: document.querySelector(`[name="${prefix}text_color"]`).value,
            fontSize: parseInt(document.querySelector(`[name="${prefix}font_size"]`).value) || 16,
            width: parseInt(document.querySelector(`[name="${prefix}width"]`).value) || 500,
            height: parseInt(document.querySelector(`[name="${prefix}height"]`).value) || 300
        };
    }
    
    /**
     * Récupérer le contenu de l'éditeur WordPress
     */
    function getEditorContent(editorId) {
        if (typeof tinyMCE !== 'undefined' && tinyMCE.get(editorId)) {
            return tinyMCE.get(editorId).getContent();
        }
        const el = document.getElementById(editorId);
        return el ? el.value : '';
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
        const notice = document.createElement('div');
        notice.className = type === 'error' ? 'popup-glass-notice error' : 'popup-glass-notice';
        const p = document.createElement('p');
        p.textContent = message;
        notice.appendChild(p);

        const container = document.querySelector('.popup-glass-container');
        container.parentNode.insertBefore(notice, container);

        setTimeout(() => {
            notice.style.transition = 'opacity 0.4s';
            notice.style.opacity = '0';
            setTimeout(() => notice.remove(), 400);
        }, 5000);

        window.scrollTo({
            top: document.querySelector('.wrap').offsetTop,
            behavior: 'smooth'
        });
    }
    
    /**
     * Gestion des couleurs RGBA personnalisées
     */
    document.querySelectorAll('.color-picker-rgba').forEach((input) => {
        const preview = document.createElement('div');
        preview.className = 'color-preview';
        input.after(preview);

        const updatePreview = () => {
            preview.style.backgroundColor = input.value;
        };

        updatePreview();
        input.addEventListener('input', updatePreview);
        input.addEventListener('change', updatePreview);
    });
    
    /**
     * Validation en temps réel
     */
    document.querySelectorAll('input[type="number"]').forEach((input) => {
        input.addEventListener('input', () => {
            const min = parseInt(input.getAttribute('min'));
            const max = parseInt(input.getAttribute('max'));
            const value = parseInt(input.value);

            if (value < min) {
                input.value = min;
            } else if (value > max) {
                input.value = max;
            }
        });
    });
    
    /**
     * Auto-save (optionnel)
     */
    let autoSaveTimeout;
    document.querySelectorAll('#popup-glass-form input, #popup-glass-form textarea').forEach((field) => {
        field.addEventListener('input', () => {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                // Optionnel : auto-save après 2 secondes d'inactivité
                // saveSettings();
            }, 2000);
        });
    });
});
