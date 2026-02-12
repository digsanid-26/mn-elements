/**
 * MN WooCart/Checkout Widget JavaScript
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        initWooCart();
        
        // Fix epeken form field interactivity
        fixEpekenFormFields();
    });

    // Also run on window load to ensure we run after all scripts
    $(window).on('load', function() {
        fixEpekenFormFields();
    });

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-woocart-checkout.default', function($scope) {
            initWooCart($scope);
            fixEpekenFormFields();
        });
    });

    /**
     * Fix form field interactivity issues caused by epeken plugin
     * This runs after page load and on WooCommerce checkout updates
     */
    function fixEpekenFormFields() {
        var $checkoutPage = $('.mn-woocheckout-page');
        if (!$checkoutPage.length) return;

        // Function to ensure form fields are interactive
        function enableFormFields() {
            // Fix text input fields only (not select fields - let epeken handle those)
            $checkoutPage.find('input[type="text"], input[type="email"], input[type="tel"], input[type="password"], input[type="number"], textarea').each(function() {
                var $el = $(this);
                $el.css({
                    'pointer-events': 'auto',
                    'position': 'relative',
                    'z-index': '10'
                });
                $el.prop('readonly', false);
                $el.prop('disabled', false);
                $el.removeAttr('readonly');
                $el.removeAttr('disabled');
            });

            // Fix checkbox and radio inputs
            $checkoutPage.find('input[type="checkbox"], input[type="radio"]').css({
                'pointer-events': 'auto'
            });

            // Fix buttons
            $checkoutPage.find('button').css({
                'pointer-events': 'auto'
            });

            // Fix labels
            $checkoutPage.find('label').css({
                'pointer-events': 'auto'
            });

            // Ensure select2 containers are visible and clickable (don't change position/z-index)
            $checkoutPage.find('.select2-container').css({
                'pointer-events': 'auto',
                'display': 'block',
                'width': '100%'
            });

            $checkoutPage.find('.select2-selection').css({
                'pointer-events': 'auto',
                'cursor': 'pointer'
            });

            // Ensure native select elements are visible if not using select2
            $checkoutPage.find('select').each(function() {
                var $select = $(this);
                // Only show if no select2 container exists for this select
                if (!$select.hasClass('select2-hidden-accessible')) {
                    $select.css({
                        'pointer-events': 'auto',
                        'display': 'block',
                        'width': '100%'
                    });
                }
            });

            // Fix form-row wrappers
            $checkoutPage.find('.form-row').css({
                'pointer-events': 'auto'
            });

            // Fix woocommerce-input-wrapper
            $checkoutPage.find('.woocommerce-input-wrapper').css({
                'pointer-events': 'auto',
                'display': 'block',
                'width': '100%'
            });

            // Fix col-sm classes
            $checkoutPage.find('.col-sm-6, .col-sm-12').css({
                'pointer-events': 'auto'
            });
        }

        // Run after epeken has initialized (give it time to set up select2)
        // Don't run immediately to avoid interfering with epeken initialization
        setTimeout(enableFormFields, 2000);
        setTimeout(enableFormFields, 4000);

        // Run on WooCommerce checkout update events
        $(document.body).on('updated_checkout', function() {
            setTimeout(enableFormFields, 1000);
        });
    }

    function initWooCart($scope) {
        var $container = $scope ? $scope : $(document);

        // Initialize mini cart
        initMiniCart($container);

        // Initialize cart page
        initCartPage($container);

        // Initialize remove item
        initRemoveItem($container);
    }

    function initMiniCart($container) {
        // Click trigger for dropdown
        $container.find('.mn-woocart-trigger-click').each(function() {
            var $wrapper = $(this);
            var $trigger = $wrapper.find('.mn-woocart-trigger');
            var $dropdown = $wrapper.find('.mn-woocart-dropdown');

            $trigger.on('click', function(e) {
                e.stopPropagation();
                $dropdown.toggleClass('active');
            });

            // Close on outside click
            $(document).on('click', function(e) {
                if (!$wrapper.is(e.target) && $wrapper.has(e.target).length === 0) {
                    $dropdown.removeClass('active');
                }
            });
        });

        // Update mini cart on WooCommerce events
        $(document.body).on('added_to_cart removed_from_cart', function(e, fragments, cart_hash) {
            if (fragments) {
                updateMiniCartFragments(fragments);
            }
        });
    }

    function initCartPage($container) {
        var $cartPage = $container.find('.mn-woocart-page.mn-woocart-ajax');
        if (!$cartPage.length) return;

        var $form = $cartPage.find('.mn-woocart-form');

        // AJAX quantity update
        var updateTimeout;
        $form.find('input[type="number"]').on('change', function() {
            clearTimeout(updateTimeout);
            updateTimeout = setTimeout(function() {
                updateCart($form);
            }, 500);
        });

        // AJAX form submit
        $form.on('submit', function(e) {
            var $submitBtn = $(document.activeElement);
            
            // Allow coupon submission to work normally or via AJAX
            if ($submitBtn.attr('name') === 'apply_coupon') {
                e.preventDefault();
                applyCoupon($form);
                return;
            }

            // Update cart
            if ($submitBtn.attr('name') === 'update_cart') {
                e.preventDefault();
                updateCart($form);
                return;
            }
        });
    }

    function initRemoveItem($container) {
        // Mini cart remove
        $container.find('.mn-woocart-mini .mn-woocart-remove').on('click', function(e) {
            e.preventDefault();
            var $btn = $(this);
            var cartItemKey = $btn.data('cart-item-key');
            
            removeCartItem(cartItemKey, $btn.closest('.mn-woocart-product'));
        });

        // Cart page remove
        $container.find('.mn-woocart-page .mn-woocart-remove').on('click', function(e) {
            e.preventDefault();
            var $btn = $(this);
            var href = $btn.attr('href');
            
            // Extract cart item key from URL
            var match = href.match(/remove_item=([^&]+)/);
            if (match) {
                var cartItemKey = match[1];
                removeCartItem(cartItemKey, $btn.closest('.mn-woocart-row'));
            }
        });
    }

    function removeCartItem(cartItemKey, $element) {
        $element.addClass('mn-woocart-loading');

        $.ajax({
            type: 'POST',
            url: wc_add_to_cart_params ? wc_add_to_cart_params.ajax_url : mn_woocart_checkout_params.ajax_url,
            data: {
                action: 'mn_woocart_remove_item',
                cart_item_key: cartItemKey,
                nonce: mn_woocart_checkout_params.nonce
            },
            success: function(response) {
                if (response.success) {
                    $element.slideUp(300, function() {
                        $(this).remove();
                        
                        // Update cart count and total
                        if (response.data.cart_count !== undefined) {
                            $('.mn-woocart-count').text(response.data.cart_count);
                        }
                        if (response.data.cart_total) {
                            $('.mn-woocart-total').html(response.data.cart_total);
                            $('.mn-woocart-subtotal .mn-woocart-totals-value').html(response.data.cart_total);
                        }

                        // Show empty message if cart is empty
                        if (response.data.cart_count === 0) {
                            $('.mn-woocart-products').html('<div class="mn-woocart-empty"><p>' + (mn_woocart_checkout_params.empty_message || 'Your cart is empty') + '</p></div>');
                            $('.mn-woocart-subtotal, .mn-woocart-buttons').hide();
                        }

                        // Trigger WooCommerce event
                        $(document.body).trigger('removed_from_cart', [response.data.fragments, response.data.cart_hash]);
                    });
                } else {
                    $element.removeClass('mn-woocart-loading');
                    alert(response.data.message || 'Error removing item');
                }
            },
            error: function() {
                $element.removeClass('mn-woocart-loading');
                // Fallback: reload page
                location.reload();
            }
        });
    }

    function updateCart($form) {
        var $wrapper = $form.closest('.mn-woocart-page');
        $wrapper.addClass('mn-woocart-loading');

        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            data: $form.serialize() + '&update_cart=Update+Cart',
            success: function(response) {
                var $newContent = $(response).find('.mn-woocart-page');
                if ($newContent.length) {
                    $wrapper.html($newContent.html());
                    initCartPage($wrapper.parent());
                    initRemoveItem($wrapper.parent());
                }
                $wrapper.removeClass('mn-woocart-loading');

                // Trigger WooCommerce event
                $(document.body).trigger('updated_cart_totals');
            },
            error: function() {
                $wrapper.removeClass('mn-woocart-loading');
                location.reload();
            }
        });
    }

    function applyCoupon($form) {
        var $wrapper = $form.closest('.mn-woocart-page');
        var couponCode = $form.find('input[name="coupon_code"]').val();

        if (!couponCode) return;

        $wrapper.addClass('mn-woocart-loading');

        $.ajax({
            type: 'POST',
            url: wc_add_to_cart_params ? wc_add_to_cart_params.ajax_url : mn_woocart_checkout_params.ajax_url,
            data: {
                action: 'mn_woocart_apply_coupon',
                coupon_code: couponCode,
                nonce: mn_woocart_checkout_params.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Reload cart to show updated totals
                    location.reload();
                } else {
                    alert(response.data.message || 'Invalid coupon');
                    $wrapper.removeClass('mn-woocart-loading');
                }
            },
            error: function() {
                $wrapper.removeClass('mn-woocart-loading');
                $form.submit();
            }
        });
    }

    function updateMiniCartFragments(fragments) {
        $.each(fragments, function(key, value) {
            $(key).replaceWith(value);
        });
    }

})(jQuery);
