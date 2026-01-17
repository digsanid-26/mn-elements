/**
 * MN WooCart JavaScript
 * Handles cart interactions and AJAX operations
 */

(function($) {
    'use strict';

    class MNWooCart {
        constructor($element) {
            this.$element = $element;
            this.$trigger = $element.find('.mn-woocart-trigger');
            this.$dropdown = $element.find('.mn-woocart-dropdown');
            this.widgetId = $element.data('widget-id');
            this.isOpen = false;
            this.isProcessing = false; // Flag to prevent double clicks

            // Check if already initialized
            if (this.$element.data('mn-woocart-initialized')) {
                return;
            }
            this.$element.data('mn-woocart-initialized', true);

            this.init();
        }

        init() {
            // Toggle dropdown on trigger click
            this.$trigger.on('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.toggleDropdown();
            });

            // Close dropdown when clicking outside
            $(document).on('click', (e) => {
                if (this.isOpen && !this.$element.is(e.target) && this.$element.has(e.target).length === 0) {
                    this.closeDropdown();
                }
            });

            // Quantity controls
            this.$element.on('click', '.mn-woocart-qty-btn', (e) => {
                e.preventDefault();
                e.stopImmediatePropagation();
                
                // Prevent double clicks
                if (this.isProcessing) {
                    return;
                }
                
                this.handleQuantityChange($(e.currentTarget));
            });

            // Remove item
            this.$element.on('click', '.mn-woocart-remove', (e) => {
                e.preventDefault();
                e.stopImmediatePropagation();
                this.handleRemoveItem($(e.currentTarget));
            });

            // Update cart on WooCommerce events
            $(document.body).on('added_to_cart removed_from_cart updated_cart_totals', () => {
                this.refreshCart();
            });
        }

        toggleDropdown() {
            if (this.isOpen) {
                this.closeDropdown();
            } else {
                this.openDropdown();
            }
        }

        openDropdown() {
            this.$element.addClass('active');
            this.isOpen = true;
        }

        closeDropdown() {
            this.$element.removeClass('active');
            this.isOpen = false;
        }

        handleQuantityChange($button) {
            // Set processing flag
            this.isProcessing = true;
            
            const $item = $button.closest('.mn-woocart-item');
            const $input = $item.find('.mn-woocart-qty-input');
            const cartItemKey = $item.data('cart-item-key');
            const action = $button.data('action');
            let currentQty = parseInt($input.val()) || 1;
            let newQty = currentQty;

            if (action === 'plus') {
                newQty = currentQty + 1;
            } else if (action === 'minus') {
                newQty = Math.max(0, currentQty - 1);
            }

            // If quantity is 0, remove item
            if (newQty === 0) {
                this.removeFromCart(cartItemKey, $item);
            } else {
                this.updateCartQuantity(cartItemKey, newQty, $item);
            }
            
            // Reset processing flag after a short delay
            setTimeout(() => {
                this.isProcessing = false;
            }, 300);
        }

        handleRemoveItem($button) {
            const $item = $button.closest('.mn-woocart-item');
            const cartItemKey = $button.data('cart-item-key');
            
            this.removeFromCart(cartItemKey, $item);
        }

        updateCartQuantity(cartItemKey, quantity, $item) {
            $item.addClass('loading');
            const $input = $item.find('.mn-woocart-qty-input');

            // Update input value immediately for better UX
            $input.val(quantity);

            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'mn_update_cart_quantity',
                    cart_item_key: cartItemKey,
                    quantity: quantity,
                    security: wc_add_to_cart_params.wc_ajax_url.toString().split('wc-ajax=')[0]
                },
                success: (response) => {
                    if (response.success) {
                        // Update cart count in trigger
                        if (response.data.cart_count !== undefined) {
                            this.$element.find('.mn-woocart-count').text(response.data.cart_count);
                        }
                        
                        // Update cart total in trigger
                        if (response.data.cart_total) {
                            this.$element.find('.mn-woocart-total').html(response.data.cart_total);
                        }
                        
                        // Update subtotal in footer
                        if (response.data.cart_subtotal) {
                            this.$element.find('.mn-woocart-subtotal-amount').html(response.data.cart_subtotal);
                        }
                        
                        $(document.body).trigger('wc_fragment_refresh');
                    } else {
                        this.showNotice(response.data || 'Error updating cart.');
                    }
                    $item.removeClass('loading');
                },
                error: () => {
                    $item.removeClass('loading');
                    this.showNotice('Error updating cart. Please try again.');
                }
            });
        }

        removeFromCart(cartItemKey, $item) {
            $item.addClass('loading');

            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'mn_remove_cart_item',
                    cart_item_key: cartItemKey,
                    security: wc_add_to_cart_params.wc_ajax_url.toString().split('wc-ajax=')[0]
                },
                success: (response) => {
                    if (response.success) {
                        // Animate item removal
                        $item.slideUp(300, function() {
                            $(this).remove();
                            
                            // Refresh cart to update totals
                            this.refreshCart();
                            $(document.body).trigger('wc_fragment_refresh');
                        }.bind(this));
                    } else {
                        $item.removeClass('loading');
                        this.showNotice(response.data || 'Error removing item.');
                    }
                },
                error: () => {
                    $item.removeClass('loading');
                    this.showNotice('Error removing item. Please try again.');
                }
            });
        }

        refreshCart() {
            const widgetId = this.$element.data('widget-id');
            const $container = this.$element.find('.mn-woocart-dropdown, .mn-woocart-direct');
            
            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'mn_refresh_cart_widget',
                    widget_id: widgetId
                },
                success: (response) => {
                    if (response.success && response.data.html) {
                        // Replace cart content
                        $container.html(response.data.html);
                        
                        // Update cart count and total in trigger
                        if (response.data.cart_count !== undefined) {
                            this.$element.find('.mn-woocart-count').text(response.data.cart_count);
                        }
                        if (response.data.cart_total) {
                            this.$element.find('.mn-woocart-total').html(response.data.cart_total);
                        }
                        
                        // Update subtotal in footer
                        if (response.data.cart_subtotal) {
                            this.$element.find('.mn-woocart-subtotal-amount').html(response.data.cart_subtotal);
                        }
                    }
                },
                error: () => {
                    console.error('Error refreshing cart');
                }
            });
        }

        updateFragments(fragments) {
            // Update cart count
            if (fragments['div.widget_shopping_cart_content']) {
                const $temp = $('<div>').html(fragments['div.widget_shopping_cart_content']);
                const cartCount = $temp.find('.cart_list li').length;
                this.$element.find('.mn-woocart-count').text(cartCount);
            }

            // Update cart total
            if (fragments['.woocommerce-mini-cart__total .woocommerce-Price-amount']) {
                const cartTotal = fragments['.woocommerce-mini-cart__total .woocommerce-Price-amount'];
                this.$element.find('.mn-woocart-total').html(cartTotal);
                this.$element.find('.mn-woocart-subtotal-amount').html(cartTotal);
            }

            // Trigger WooCommerce event
            $(document.body).trigger('wc_fragments_refreshed');
        }

        showNotice(message) {
            // Simple notice implementation
            const $notice = $('<div class="mn-woocart-notice-popup">' + message + '</div>');
            $('body').append($notice);
            
            setTimeout(() => {
                $notice.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
    }

    // Initialize on Elementor frontend
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-woocart.default', function($scope) {
            new MNWooCart($scope.find('.mn-woocart-wrapper'));
        });
    });

    // Initialize for non-Elementor pages
    $(document).ready(function() {
        $('.mn-woocart-wrapper').each(function() {
            new MNWooCart($(this));
        });
    });

})(jQuery);
