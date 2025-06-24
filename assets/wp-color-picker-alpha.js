(function($){
    'use strict';

    // Sauvegarder la fonction originale
    var wpColorPicker = $.fn.wpColorPicker;

    // Surcharge pour gérer le type rgba
    $.fn.wpColorPicker = function(options) {
        options = options || {};

        if (options.type === 'rgba') {
            var change = options.change;
            var clear = options.clear;

            options.change = function(event, ui) {
                var color = ui.color.toString('rgba');
                $(event.target).val(color);
                if (typeof change === 'function') {
                    change.call(this, event, ui);
                }
            };

            options.clear = function(event) {
                $(event.target).val('');
                if (typeof clear === 'function') {
                    clear.call(this, event);
                }
            };
        }

        return wpColorPicker.call(this, options);
    };
})(jQuery);
