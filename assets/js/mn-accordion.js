/**
 * MN Accordion Widget JavaScript
 *
 * @package mn-elements
 */

(function($) {
    'use strict';

    /**
     * MN Accordion Class
     */
    class MNAccordion {
        /**
         * Constructor
         * @param {jQuery} $element - The accordion container element
         */
        constructor($element) {
            this.$element = $element;
            this.$items = $element.find('.mn-accordion-item');
            this.$titles = $element.find('.mn-accordion-title');
            this.$contents = $element.find('.mn-accordion-content');
            
            // Settings
            this.accordionType = $element.data('accordion-type') || 'accordion';
            this.defaultActive = parseInt($element.data('default-active')) || 1;
            this.animationDuration = parseInt($element.data('animation-duration')) || 300;
            
            this.init();
        }

        /**
         * Initialize accordion
         */
        init() {
            this.bindEvents();
            this.activateDefaultTab();
        }

        /**
         * Bind events
         */
        bindEvents() {
            const self = this;

            // Click event
            this.$titles.on('click', function(e) {
                e.preventDefault();
                const tabIndex = $(this).data('tab');
                self.toggleTab(tabIndex);
            });

            // Keyboard navigation
            this.$titles.on('keydown', function(e) {
                self.handleKeyboard(e, $(this));
            });
        }

        /**
         * Handle keyboard navigation
         * @param {Event} e - Keyboard event
         * @param {jQuery} $title - Current title element
         */
        handleKeyboard(e, $title) {
            const tabIndex = $title.data('tab');
            const totalTabs = this.$titles.length;

            switch (e.key) {
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    this.toggleTab(tabIndex);
                    break;

                case 'ArrowDown':
                case 'ArrowRight':
                    e.preventDefault();
                    this.focusNextTab(tabIndex, totalTabs);
                    break;

                case 'ArrowUp':
                case 'ArrowLeft':
                    e.preventDefault();
                    this.focusPrevTab(tabIndex, totalTabs);
                    break;

                case 'Home':
                    e.preventDefault();
                    this.$titles.first().focus();
                    break;

                case 'End':
                    e.preventDefault();
                    this.$titles.last().focus();
                    break;
            }
        }

        /**
         * Focus next tab
         * @param {number} currentIndex - Current tab index
         * @param {number} totalTabs - Total number of tabs
         */
        focusNextTab(currentIndex, totalTabs) {
            const nextIndex = currentIndex >= totalTabs ? 1 : currentIndex + 1;
            this.$titles.filter('[data-tab="' + nextIndex + '"]').focus();
        }

        /**
         * Focus previous tab
         * @param {number} currentIndex - Current tab index
         * @param {number} totalTabs - Total number of tabs
         */
        focusPrevTab(currentIndex, totalTabs) {
            const prevIndex = currentIndex <= 1 ? totalTabs : currentIndex - 1;
            this.$titles.filter('[data-tab="' + prevIndex + '"]').focus();
        }

        /**
         * Activate default tab
         */
        activateDefaultTab() {
            if (this.defaultActive > 0 && this.defaultActive <= this.$items.length) {
                const $item = this.$items.eq(this.defaultActive - 1);
                const $content = $item.find('.mn-accordion-content');
                
                $item.addClass('mn-active');
                $content.removeAttr('hidden').show();
                $item.find('.mn-accordion-title').attr('aria-expanded', 'true');
            }
        }

        /**
         * Toggle tab
         * @param {number} tabIndex - Tab index to toggle
         */
        toggleTab(tabIndex) {
            const $item = this.$items.filter(function() {
                return $(this).find('.mn-accordion-title').data('tab') === tabIndex;
            });
            const $title = $item.find('.mn-accordion-title');
            const $content = $item.find('.mn-accordion-content');
            const isActive = $item.hasClass('mn-active');

            if (this.accordionType === 'accordion') {
                // Close all other items first
                if (!isActive) {
                    this.closeAllTabs();
                }
            }

            if (isActive) {
                this.closeTab($item, $title, $content);
            } else {
                this.openTab($item, $title, $content);
            }
        }

        /**
         * Open tab
         * @param {jQuery} $item - Accordion item
         * @param {jQuery} $title - Title element
         * @param {jQuery} $content - Content element
         */
        openTab($item, $title, $content) {
            const self = this;

            // Store current width to prevent shrinking
            const currentWidth = this.$element.outerWidth();
            this.$element.css('min-width', currentWidth + 'px');

            $content.removeAttr('hidden');
            $content.addClass('mn-animating');
            
            // Get the natural height
            $content.css({
                'height': 'auto',
                'width': '100%'
            });
            const height = $content.outerHeight();
            $content.css('height', 0);

            // Animate
            $content.animate({
                height: height
            }, this.animationDuration, function() {
                $content.css('height', '');
                $content.removeClass('mn-animating');
                $item.addClass('mn-active');
                $title.attr('aria-expanded', 'true');
                
                // Remove min-width after animation
                self.$element.css('min-width', '');
            });
        }

        /**
         * Close tab
         * @param {jQuery} $item - Accordion item
         * @param {jQuery} $title - Title element
         * @param {jQuery} $content - Content element
         */
        closeTab($item, $title, $content) {
            const self = this;
            
            // Store current width to prevent shrinking
            const currentWidth = this.$element.outerWidth();
            this.$element.css('min-width', currentWidth + 'px');
            
            $content.addClass('mn-animating');
            $content.css('width', '100%');
            
            $content.animate({
                height: 0
            }, this.animationDuration, function() {
                $content.removeClass('mn-animating');
                $content.attr('hidden', 'hidden');
                $content.css({
                    'height': '',
                    'width': ''
                });
                $item.removeClass('mn-active');
                $title.attr('aria-expanded', 'false');
                
                // Remove min-width after animation
                self.$element.css('min-width', '');
            });
        }

        /**
         * Close all tabs
         */
        closeAllTabs() {
            const self = this;

            this.$items.each(function() {
                const $item = $(this);
                if ($item.hasClass('mn-active')) {
                    const $title = $item.find('.mn-accordion-title');
                    const $content = $item.find('.mn-accordion-content');
                    self.closeTab($item, $title, $content);
                }
            });
        }
    }

    /**
     * Initialize MN Accordion on document ready
     */
    function initMNAccordion() {
        $('.mn-accordion').each(function() {
            const $accordion = $(this);
            
            // Check if already initialized
            if ($accordion.data('mn-accordion-initialized')) {
                return;
            }

            new MNAccordion($accordion);
            $accordion.data('mn-accordion-initialized', true);
        });
    }

    // Initialize on document ready
    $(document).ready(function() {
        initMNAccordion();
    });

    // Initialize for Elementor frontend
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/mn-accordion.default', function($scope) {
                const $accordion = $scope.find('.mn-accordion');
                
                if ($accordion.length && !$accordion.data('mn-accordion-initialized')) {
                    new MNAccordion($accordion);
                    $accordion.data('mn-accordion-initialized', true);
                }
            });
        }
    });

    // Re-initialize on AJAX content load
    $(document).ajaxComplete(function() {
        initMNAccordion();
    });

    // Expose class globally for external use
    window.MNAccordion = MNAccordion;

})(jQuery);
