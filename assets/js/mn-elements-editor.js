/**
 * MN Elements Editor JavaScript
 * 
 * @package mn-elements
 * @version 1.0.9
 */

(function($) {
    'use strict';

    var MNElementsEditor = {
        currentAnimation: null,
        previewTimeout: null,
        cleanupTimeout: null,

        init: function() {
            var self = this;
            
            // Wait for Elementor to be fully loaded
            if (typeof elementor !== 'undefined' && elementor.channels) {
                self.bindEvents();
            } else {
                $(window).on('elementor:init', function() {
                    self.bindEvents();
                });
            }
        },

        bindEvents: function() {
            var self = this;
            
            // Listen to Elementor's editor events like Motion Effects does
            elementor.channels.editor.on('change', function(controlView, elementView) {
                if (!controlView || !controlView.model || !elementView) return;
                
                var controlName = controlView.model.get('name');
                
                // Check if it's an MNTriks control - match Motion Effects pattern
                if (/^mn_entrance/.test(controlName)) {
                    // Use the same timing as Motion Effects
                    setTimeout(function() {
                        self.onElementChange(controlName, controlView, elementView);
                    }, 50);
                }
            });
        },

        // Follow Motion Effects onElementChange pattern exactly
        onElementChange: function(propertyName, controlView, elementView) {
            if (/^mn_entrance/.test(propertyName)) {
                this.animate(elementView);
            }
        },

        // Follow Motion Effects animate pattern exactly  
        animate: function(elementView) {
            var $element = elementView.$el;
            var model = elementView.model;
            var settings = model.get('settings');
            
            var animation = this.getAnimation(settings);
            
            if ('none' === animation || !animation) {
                $element.removeClass('mn-entrance-invisible');
                return;
            }
            
            var animationDelay = settings.get('mn_entrance_delay') || 0;
            
            // Remove existing animation classes like Motion Effects does
            $element.removeClass(animation);
            if (this.currentAnimation) {
                $element.removeClass(this.currentAnimation);
            }
            this.currentAnimation = animation;
            
            // Apply Motion Effects pattern exactly
            setTimeout(function() {
                $element.removeClass('mn-entrance-invisible').addClass('animated mn-' + animation);
            }, animationDelay);
        },

        // Get animation like Motion Effects does
        getAnimation: function(settings) {
            return settings.get('mn_entrance_animation_type');
        }

    };

    // Initialize when Elementor editor is ready
    $(window).on('elementor:init', function() {
        MNElementsEditor.init();
    });

})(jQuery);
