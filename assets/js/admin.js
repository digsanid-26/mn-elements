/**
 * MN Elements Admin JavaScript
 * 
 * Handles Element Manager admin page functionality
 * @since 1.4.1
 */

(function($) {
    'use strict';

    /**
     * MN Elements Admin Handler
     */
    class MNElementsAdmin {
        constructor() {
            this.form = $('#mn-elements-settings-form');
            this.checkboxes = $('input[name="active_widgets[]"]');
            this.enableAllBtn = $('#mn-enable-all');
            this.disableAllBtn = $('#mn-disable-all');
            this.submitBtns = $('button[type="submit"]');
            
            this.init();
        }

        init() {
            this.bindEvents();
            this.updateStats();
            this.updateCardStates();
        }

        bindEvents() {
            // Form submission
            this.form.on('submit', this.handleFormSubmit.bind(this));
            
            // Enable/Disable all buttons
            this.enableAllBtn.on('click', this.enableAll.bind(this));
            this.disableAllBtn.on('click', this.disableAll.bind(this));
            
            // Individual checkbox changes
            this.checkboxes.on('change', this.handleCheckboxChange.bind(this));
        }

        handleFormSubmit(e) {
            e.preventDefault();
            
            // Ensure mnElementsAdmin is available
            if (typeof mnElementsAdmin === 'undefined') {
                console.error('mnElementsAdmin is not defined. Cannot save settings.');
                this.showMessage('Configuration error. Please refresh the page.', 'error');
                return;
            }
            
            // Get active widgets
            const activeWidgets = [];
            this.checkboxes.filter(':checked').each(function() {
                activeWidgets.push($(this).val());
            });
            
            this.showLoading();
            
            $.ajax({
                url: mnElementsAdmin.ajaxurl || ajaxurl || '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'mn_elements_save_settings',
                    nonce: mnElementsAdmin.nonce || '',
                    active_widgets: activeWidgets
                },
                success: this.handleSaveSuccess.bind(this),
                error: this.handleSaveError.bind(this)
            });
        }

        handleSaveSuccess(response) {
            this.hideLoading();
            
            if (response.success) {
                this.showMessage(response.data.message, 'success');
                this.updateStats();
            } else {
                const strings = (typeof mnElementsAdmin !== 'undefined' && mnElementsAdmin.strings) ? mnElementsAdmin.strings : {
                    error: 'An error occurred while saving settings.'
                };
                this.showMessage(response.data || strings.error, 'error');
            }
        }

        handleSaveError() {
            const strings = (typeof mnElementsAdmin !== 'undefined' && mnElementsAdmin.strings) ? mnElementsAdmin.strings : {
                error: 'An error occurred while saving settings.'
            };
            
            this.hideLoading();
            this.showMessage(strings.error, 'error');
        }

        enableAll() {
            this.checkboxes.prop('checked', true).trigger('change');
            this.updateStats();
            this.updateCardStates();
        }

        disableAll() {
            this.checkboxes.prop('checked', false).trigger('change');
            this.updateStats();
            this.updateCardStates();
        }

        handleCheckboxChange() {
            this.updateStats();
            this.updateCardStates();
        }

        updateStats() {
            const total = this.checkboxes.length;
            const active = this.checkboxes.filter(':checked').length;
            const inactive = total - active;
            
            // Ensure mnElementsAdmin.strings exists
            const strings = (typeof mnElementsAdmin !== 'undefined' && mnElementsAdmin.strings) ? mnElementsAdmin.strings : {
                total: 'Total Widgets',
                active: 'Active',
                inactive: 'Inactive'
            };
            
            // Update or create stats display
            let statsHtml = `
                <div class="mn-elements-stats">
                    <div class="mn-elements-stat">
                        <span class="mn-elements-stat-number">${total}</span>
                        <span class="mn-elements-stat-label">${strings.total}</span>
                    </div>
                    <div class="mn-elements-stat">
                        <span class="mn-elements-stat-number">${active}</span>
                        <span class="mn-elements-stat-label">${strings.active}</span>
                    </div>
                    <div class="mn-elements-stat">
                        <span class="mn-elements-stat-number">${inactive}</span>
                        <span class="mn-elements-stat-label">${strings.inactive}</span>
                    </div>
                </div>
            `;
            
            // Remove existing stats and add new ones
            $('.mn-elements-stats').remove();
            $('.mn-elements-header-actions').after(statsHtml);
        }

        updateCardStates() {
            this.checkboxes.each(function() {
                const card = $(this).closest('.mn-elements-widget-card');
                if ($(this).is(':checked')) {
                    card.removeClass('disabled');
                } else {
                    card.addClass('disabled');
                }
            });
        }

        showLoading() {
            const strings = (typeof mnElementsAdmin !== 'undefined' && mnElementsAdmin.strings) ? mnElementsAdmin.strings : {
                saving: 'Saving...'
            };
            
            this.form.addClass('mn-elements-loading');
            this.submitBtns.prop('disabled', true).text(strings.saving);
        }

        hideLoading() {
            const strings = (typeof mnElementsAdmin !== 'undefined' && mnElementsAdmin.strings) ? mnElementsAdmin.strings : {
                save: 'Save Settings'
            };
            
            this.form.removeClass('mn-elements-loading');
            this.submitBtns.prop('disabled', false).text(strings.save);
        }

        showMessage(message, type = 'success') {
            // Remove existing messages
            $('.mn-elements-success, .mn-elements-error').remove();
            
            const messageClass = type === 'success' ? 'mn-elements-success' : 'mn-elements-error';
            const messageHtml = `<div class="${messageClass} show">${message}</div>`;
            
            $('.mn-elements-admin h1').after(messageHtml);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                $(`.${messageClass}`).fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
            
            // Scroll to top to show message
            $('html, body').animate({
                scrollTop: $('.mn-elements-admin').offset().top - 50
            }, 300);
        }

        // Utility method to get checkbox by widget key
        getCheckboxByWidget(widgetKey) {
            return this.checkboxes.filter(`[value="${widgetKey}"]`);
        }

        // Public method to enable specific widget
        enableWidget(widgetKey) {
            this.getCheckboxByWidget(widgetKey).prop('checked', true).trigger('change');
        }

        // Public method to disable specific widget
        disableWidget(widgetKey) {
            this.getCheckboxByWidget(widgetKey).prop('checked', false).trigger('change');
        }

        // Public method to toggle specific widget
        toggleWidget(widgetKey) {
            const checkbox = this.getCheckboxByWidget(widgetKey);
            checkbox.prop('checked', !checkbox.is(':checked')).trigger('change');
        }
    }

    /**
     * Initialize when document is ready
     */
    $(document).ready(function() {
        // Debug: Check if mnElementsAdmin is defined
        console.log('mnElementsAdmin check:', typeof mnElementsAdmin !== 'undefined' ? mnElementsAdmin : 'undefined');
        
        // Check if mnElementsAdmin is defined
        if (typeof mnElementsAdmin === 'undefined') {
            console.warn('mnElementsAdmin is not defined. Admin functionality may not work properly.');
            // Create fallback object
            window.mnElementsAdmin = {
                ajaxurl: ajaxurl || '/wp-admin/admin-ajax.php',
                nonce: '',
                strings: {
                    save: 'Save Settings',
                    saving: 'Saving...',
                    saved: 'Settings saved successfully!',
                    error: 'An error occurred while saving settings.',
                    total: 'Total Widgets',
                    active: 'Active',
                    inactive: 'Inactive'
                }
            };
            console.log('Created fallback mnElementsAdmin:', window.mnElementsAdmin);
        } else {
            console.log('mnElementsAdmin is properly defined:', mnElementsAdmin);
        }
        
        // Only initialize on MN Elements admin page
        if ($('.mn-elements-admin').length) {
            window.mnElementsAdminInstance = new MNElementsAdmin();
            
            // Add keyboard shortcuts
            $(document).on('keydown', function(e) {
                // Ctrl/Cmd + S to save
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    window.mnElementsAdminInstance.form.trigger('submit');
                }
                
                // Ctrl/Cmd + A to enable all
                if ((e.ctrlKey || e.metaKey) && e.key === 'a' && e.shiftKey) {
                    e.preventDefault();
                    window.mnElementsAdminInstance.enableAll();
                }
                
                // Ctrl/Cmd + D to disable all
                if ((e.ctrlKey || e.metaKey) && e.key === 'd' && e.shiftKey) {
                    e.preventDefault();
                    window.mnElementsAdminInstance.disableAll();
                }
            });
            
            // Add tooltips or help text
            $('.mn-elements-widget-card').each(function() {
                const $card = $(this);
                const $toggle = $card.find('.mn-elements-switch');
                
                $toggle.attr('title', 'Click to toggle this widget');
                
                // Add click handler to entire card for better UX
                $card.on('click', function(e) {
                    if (!$(e.target).is('input, label, .mn-elements-switch, .mn-elements-slider')) {
                        const checkbox = $card.find('input[type="checkbox"]');
                        checkbox.prop('checked', !checkbox.is(':checked')).trigger('change');
                    }
                });
            });
        }
    });

})(jQuery);
