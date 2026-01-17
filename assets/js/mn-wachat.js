/**
 * MN Wachat Widget JavaScript
 * Handles WhatsApp redirect functionality
 */

(function($) {
    'use strict';

    /**
     * MN Wachat Widget Class
     */
    class MNWachatWidget {
        constructor($scope) {
            this.$scope = $scope;
            this.$wrapper = $scope.find('.mn-wachat-wrapper');
            this.$textarea = this.$wrapper.find('.mn-wachat-textarea');
            this.$button = this.$wrapper.find('.mn-wachat-button');
            this.phoneNumber = this.$button.data('phone');
            
            // Floating mode elements
            this.$floatingButton = this.$wrapper.find('.mn-wachat-floating-button');
            this.$popup = this.$wrapper.find('.mn-wachat-popup');
            this.$popupClose = this.$wrapper.find('.mn-wachat-popup-close');
            this.isFloatingMode = this.$wrapper.hasClass('mn-wachat-floating-mode');

            this.init();
        }

        init() {
            // Bind button click event
            this.$button.on('click', (e) => this.handleButtonClick(e));

            // Allow Enter key with Ctrl/Cmd to submit
            this.$textarea.on('keydown', (e) => this.handleKeydown(e));

            // Floating mode handlers
            if (this.isFloatingMode) {
                this.$floatingButton.on('click', () => this.togglePopup());
                this.$popupClose.on('click', () => this.closePopup());
                
                // Close popup when clicking outside
                $(document).on('click', (e) => this.handleOutsideClick(e));
                
                // Close popup on ESC key
                $(document).on('keydown', (e) => {
                    if (e.key === 'Escape' && this.$popup.hasClass('show')) {
                        this.closePopup();
                    }
                });
            }

            console.log('MN Wachat initialized');
        }

        togglePopup() {
            if (this.$popup.hasClass('show')) {
                this.closePopup();
            } else {
                this.openPopup();
            }
        }

        openPopup() {
            this.$popup.show();
            // Use setTimeout to trigger transition
            setTimeout(() => {
                this.$popup.addClass('show');
            }, 10);
        }

        closePopup() {
            this.$popup.removeClass('show');
            setTimeout(() => {
                this.$popup.hide();
            }, 300); // Match transition duration
        }

        handleOutsideClick(e) {
            if (!this.$popup.hasClass('show')) return;
            
            // Check if click is outside popup and floating button
            if (!$(e.target).closest('.mn-wachat-popup, .mn-wachat-floating-button').length) {
                this.closePopup();
            }
        }

        handleButtonClick(e) {
            e.preventDefault();

            const message = this.$textarea.val().trim();

            if (!message) {
                alert('Please enter a message before sending.');
                this.$textarea.focus();
                return;
            }

            if (!this.phoneNumber) {
                alert('WhatsApp number is not configured.');
                return;
            }

            // Encode message for URL
            const encodedMessage = encodeURIComponent(message);

            // Build WhatsApp URL
            const whatsappUrl = `https://wa.me/${this.phoneNumber}?text=${encodedMessage}`;

            // Open WhatsApp in new tab
            window.open(whatsappUrl, '_blank');

            // Optional: Clear textarea after sending
            // this.$textarea.val('');
        }

        handleKeydown(e) {
            // Ctrl+Enter or Cmd+Enter to submit
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                this.$button.trigger('click');
            }
        }
    }

    /**
     * Initialize widget on Elementor frontend
     */
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-wachat.default', function($scope) {
            new MNWachatWidget($scope);
        });
    });

})(jQuery);
