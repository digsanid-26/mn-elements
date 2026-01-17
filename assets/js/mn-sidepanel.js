/**
 * MN Sidepanel Widget
 */
(function($) {
    'use strict';

    class MNSidepanelWidget {
        constructor(element) {
            this.element = element;
            this.$element = $(element);
            this.closeOutside = this.$element.data('close-outside') === true;
            this.pushBody = this.$element.data('push-body') === true;
            this.autoClose = this.$element.data('auto-close') === true;
            this.autoCloseDelay = parseInt(this.$element.data('auto-close-delay')) || 5;
            this.pushBodyOffset = this.$element.data('push-body-offset') || '60px';
            this.position = this.$element.data('position') || 'right';
            this.currentPanel = null;
            this.autoCloseTimer = null;
            this.$body = $('body');
            
            this.init();
        }

        init() {
            console.log('MN Sidepanel initialized', {
                pushBody: this.pushBody,
                pushBodyOffset: this.pushBodyOffset,
                position: this.position
            });
            
            // Setup push body if enabled (apply fixed padding)
            if (this.pushBody) {
                this.applyBodyPadding();
            }
            
            // Bind events
            this.bindEvents();
            
            // Check if should be open by default
            if (this.$element.hasClass('open')) {
                this.openPanel(0);
            }
        }

        applyBodyPadding() {
            // Don't apply padding on tablet and mobile (screen width < 1024px)
            if ($(window).width() < 1024) {
                console.log('Push body disabled on tablet/mobile');
                return;
            }
            
            // Apply fixed padding to body based on push body offset and position
            const paddingProperty = this.position === 'left' ? 'padding-left' : 'padding-right';
            
            this.$body.css(paddingProperty, this.pushBodyOffset);
            this.$body.addClass('mn-sidepanel-push-mode');
            
            console.log('Body padding applied:', paddingProperty, this.pushBodyOffset);
        }

        bindEvents() {
            const self = this;
            
            // Trigger item click
            this.$element.find('.mn-sidepanel__trigger-item').on('click', function(e) {
                e.preventDefault();
                const index = $(this).data('panel-index');
                
                if (self.currentPanel === index && self.$element.hasClass('open')) {
                    self.closePanel();
                } else {
                    self.openPanel(index);
                }
            });
            
            // Close button click
            this.$element.find('.mn-sidepanel__close').on('click', function(e) {
                e.preventDefault();
                self.closePanel();
            });
            
            // Overlay click
            if (this.closeOutside) {
                this.$element.find('.mn-sidepanel__overlay').on('click', function(e) {
                    e.preventDefault();
                    self.closePanel();
                });
            }
            
            // Click outside trigger and panel to close
            $(document).on('click.mn-sidepanel-' + self.$element.attr('data-position'), function(e) {
                if (!self.$element.hasClass('open')) {
                    return;
                }
                
                const $target = $(e.target);
                const isInsideTrigger = $target.closest('.mn-sidepanel__trigger').length > 0;
                const isInsidePanel = $target.closest('.mn-sidepanel__content').length > 0;
                const isCloseButton = $target.closest('.mn-sidepanel__close').length > 0;
                
                // Close if click is outside trigger and panel (but not close button, it has its own handler)
                if (!isInsideTrigger && !isInsidePanel && !isCloseButton) {
                    console.log('Click outside detected, closing panel');
                    self.closePanel();
                }
            });
            
            // ESC key to close
            $(document).on('keydown.mn-sidepanel', function(e) {
                if (e.key === 'Escape' && self.$element.hasClass('open')) {
                    self.closePanel();
                }
            });
        }

        openPanel(index) {
            console.log('Opening panel:', index);
            
            // Clear any existing auto-close timer
            this.clearAutoCloseTimer();
            
            // Set current panel
            this.currentPanel = index;
            
            // Add open class
            this.$element.addClass('open');
            
            // Update active trigger
            this.$element.find('.mn-sidepanel__trigger-item').removeClass('active');
            this.$element.find('.mn-sidepanel__trigger-item[data-panel-index="' + index + '"]').addClass('active');
            
            // Show content
            this.$element.find('.mn-sidepanel__panel-content').removeClass('active').hide();
            this.$element.find('.mn-sidepanel__panel-content[data-panel-index="' + index + '"]').addClass('active').show();
            
            // Prevent body scroll with smooth transition (no layout shift)
            this.lockBodyScroll();
            
            // Start auto-close timer if enabled
            if (this.autoClose) {
                this.startAutoCloseTimer();
            }
            
            // Trigger custom event
            this.$element.trigger('mn-sidepanel:opened', [index]);
        }

        closePanel() {
            console.log('Closing panel');
            
            // Clear auto-close timer
            this.clearAutoCloseTimer();
            
            // Remove open class
            this.$element.removeClass('open');
            
            // Remove active trigger
            this.$element.find('.mn-sidepanel__trigger-item').removeClass('active');
            
            // Hide all content
            this.$element.find('.mn-sidepanel__panel-content').removeClass('active').hide();
            
            // Reset current panel
            this.currentPanel = null;
            
            // Restore body scroll
            this.unlockBodyScroll();
            
            // Trigger custom event
            this.$element.trigger('mn-sidepanel:closed');
        }

        startAutoCloseTimer() {
            const self = this;
            console.log('Starting auto-close timer:', this.autoCloseDelay + 's');
            
            this.autoCloseTimer = setTimeout(function() {
                console.log('Auto-close timer triggered');
                self.closePanel();
            }, this.autoCloseDelay * 1000);
        }

        clearAutoCloseTimer() {
            if (this.autoCloseTimer) {
                console.log('Clearing auto-close timer');
                clearTimeout(this.autoCloseTimer);
                this.autoCloseTimer = null;
            }
        }

        lockBodyScroll() {
            // Calculate scrollbar width to prevent layout shift
            const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
            
            // Store original values
            this.$body.data('mn-original-overflow', this.$body.css('overflow'));
            this.$body.data('mn-original-padding-right', this.$body.css('padding-right'));
            
            // Apply overflow hidden with padding compensation
            if (scrollbarWidth > 0) {
                const currentPadding = parseInt(this.$body.css('padding-right')) || 0;
                this.$body.css('padding-right', (currentPadding + scrollbarWidth) + 'px');
            }
            
            this.$body.css('overflow', 'hidden');
            
            console.log('Body scroll locked, scrollbar width:', scrollbarWidth);
        }

        unlockBodyScroll() {
            // Restore original values
            const originalOverflow = this.$body.data('mn-original-overflow') || '';
            const originalPaddingRight = this.$body.data('mn-original-padding-right') || '';
            
            this.$body.css({
                'overflow': originalOverflow,
                'padding-right': originalPaddingRight
            });
            
            // Clean up data attributes
            this.$body.removeData('mn-original-overflow');
            this.$body.removeData('mn-original-padding-right');
            
            console.log('Body scroll unlocked');
        }

        removeBodyPadding() {
            // Remove the fixed padding from body
            const paddingProperty = this.position === 'left' ? 'padding-left' : 'padding-right';
            
            this.$body.css(paddingProperty, '');
            this.$body.removeClass('mn-sidepanel-push-mode');
            
            console.log('Body padding removed');
        }

        destroy() {
            // Clear auto-close timer
            this.clearAutoCloseTimer();
            
            // Unbind events
            this.$element.find('.mn-sidepanel__trigger-item').off('click');
            this.$element.find('.mn-sidepanel__close').off('click');
            this.$element.find('.mn-sidepanel__overlay').off('click');
            $(document).off('click.mn-sidepanel-' + this.$element.attr('data-position'));
            $(document).off('keydown.mn-sidepanel');
            
            // Reset body
            if (this.pushBody) {
                this.removeBodyPadding();
            }
            this.$body.css('overflow', '');
            
            console.log('MN Sidepanel destroyed');
        }
    }

    // Elementor Frontend Handler
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-sidepanel.default', function($scope) {
            const $sidepanel = $scope.find('.mn-sidepanel');
            
            if ($sidepanel.length) {
                new MNSidepanelWidget($sidepanel[0]);
            }
        });
    });

    // For non-Elementor pages
    $(document).ready(function() {
        if (typeof elementorFrontend === 'undefined') {
            $('.mn-sidepanel').each(function() {
                new MNSidepanelWidget(this);
            });
        }
    });

    // Handle window resize to update body padding if needed
    $(window).on('resize', function() {
        const windowWidth = $(window).width();
        
        $('.mn-sidepanel').each(function() {
            const $sidepanel = $(this);
            const pushBody = $sidepanel.data('push-body') === true;
            
            if (pushBody) {
                const pushBodyOffset = $sidepanel.data('push-body-offset') || '60px';
                const position = $sidepanel.data('position') || 'right';
                const paddingProperty = position === 'left' ? 'padding-left' : 'padding-right';
                
                // Remove padding on tablet/mobile, apply on desktop
                if (windowWidth < 1024) {
                    $('body').css(paddingProperty, '');
                    $('body').removeClass('mn-sidepanel-push-mode');
                } else {
                    $('body').css(paddingProperty, pushBodyOffset);
                    $('body').addClass('mn-sidepanel-push-mode');
                }
            }
        });
    });

})(jQuery);
