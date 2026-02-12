/**
 * MN Posts Widget JavaScript
 * Handles WooCommerce quantity controls and AJAX add to cart
 */
(function($) {
    'use strict';

    // Initialize when document is ready
    $(document).ready(function() {
        initQuantityControls();
        initAjaxAddToCart();
        initReadmoreActions();
    });

    // Also initialize on Elementor frontend init
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-posts.default', function($scope) {
            initQuantityControls($scope);
            initAjaxAddToCart($scope);
            initReadmoreActions($scope);
        });
    });

    /**
     * Initialize quantity plus/minus controls
     */
    function initQuantityControls($scope) {
        var $container = $scope ? $scope : $(document);

        // Quantity minus button
        $container.on('click', '.mn-quantity-minus', function(e) {
            e.preventDefault();
            var $wrapper = $(this).closest('.mn-quantity-wrapper');
            var $input = $wrapper.find('.mn-quantity-input');
            var currentVal = parseInt($input.val()) || 1;
            var minVal = parseInt($input.attr('min')) || 1;

            if (currentVal > minVal) {
                $input.val(currentVal - 1).trigger('change');
            }
        });

        // Quantity plus button
        $container.on('click', '.mn-quantity-plus', function(e) {
            e.preventDefault();
            var $wrapper = $(this).closest('.mn-quantity-wrapper');
            var $input = $wrapper.find('.mn-quantity-input');
            var currentVal = parseInt($input.val()) || 1;
            var maxVal = parseInt($input.attr('max')) || 99;

            if (currentVal < maxVal) {
                $input.val(currentVal + 1).trigger('change');
            }
        });

        // Update add to cart button data-quantity when input changes
        $container.on('change', '.mn-quantity-input', function() {
            var $wrapper = $(this).closest('.mn-add-to-cart-wrapper');
            var $button = $wrapper.find('.mn-add-to-cart-btn');
            var quantity = parseInt($(this).val()) || 1;

            $button.attr('data-quantity', quantity);
        });

        // Validate input on blur
        $container.on('blur', '.mn-quantity-input', function() {
            var $input = $(this);
            var currentVal = parseInt($input.val()) || 1;
            var minVal = parseInt($input.attr('min')) || 1;
            var maxVal = parseInt($input.attr('max')) || 99;

            if (currentVal < minVal) {
                $input.val(minVal);
            } else if (currentVal > maxVal) {
                $input.val(maxVal);
            }
        });

        // Prevent non-numeric input
        $container.on('keypress', '.mn-quantity-input', function(e) {
            if (e.which < 48 || e.which > 57) {
                e.preventDefault();
            }
        });
    }

    /**
     * Initialize AJAX add to cart functionality
     */
    function initAjaxAddToCart($scope) {
        var $container = $scope ? $scope : $(document);

        $container.on('click', '.mn-add-to-cart-btn.ajax_add_to_cart', function(e) {
            e.preventDefault();

            var $button = $(this);
            
            // Prevent double-click
            if ($button.hasClass('loading')) {
                return;
            }

            var productId = $button.data('product_id');
            var quantity = parseInt($button.attr('data-quantity')) || 1;

            // Check for quantity input in wrapper
            var $wrapper = $button.closest('.mn-add-to-cart-wrapper');
            var $quantityInput = $wrapper.find('.mn-quantity-input');
            if ($quantityInput.length) {
                quantity = parseInt($quantityInput.val()) || 1;
            }

            // Add loading state
            $button.addClass('loading');
            var originalText = $button.text();

            // AJAX request to add to cart
            $.ajax({
                type: 'POST',
                url: wc_add_to_cart_params ? wc_add_to_cart_params.ajax_url : mn_posts_params.ajax_url,
                data: {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    }

                    // Trigger WooCommerce events
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);

                    // Update button state
                    $button.removeClass('loading').addClass('added');
                    
                    // Show success message briefly
                    setTimeout(function() {
                        $button.removeClass('added');
                    }, 2000);
                },
                error: function() {
                    $button.removeClass('loading');
                    console.error('MN Posts: Error adding product to cart');
                }
            });
        });

        // Handle WooCommerce native AJAX add to cart for compatibility
        $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
            if ($button && $button.hasClass('mn-add-to-cart-btn')) {
                $button.removeClass('loading').addClass('added');
                
                setTimeout(function() {
                    $button.removeClass('added');
                }, 2000);
            }
        });
    }

    /**
     * Initialize Read More button actions for Add to Cart and Direct Checkout
     */
    function initReadmoreActions($scope) {
        var $container = $scope ? $scope : $(document);

        // Handle Add to Cart button click
        $container.on('click', '.mn-add-to-cart-button', function(e) {
            e.preventDefault();

            var $button = $(this);
            
            // Prevent double-click
            if ($button.hasClass('loading')) {
                return;
            }

            var productId = $button.data('product-id');
            var action = $button.data('action');

            if (!productId || action !== 'add_to_cart') {
                return;
            }

            // Add loading state
            $button.addClass('loading');
            var originalText = $button.find('.mn-readmore-text').text();
            $button.find('.mn-readmore-text').text('Adding...');

            // AJAX request to add to cart
            $.ajax({
                type: 'POST',
                url: wc_add_to_cart_params ? wc_add_to_cart_params.ajax_url : (typeof mn_posts_params !== 'undefined' ? mn_posts_params.ajax_url : '/wp-admin/admin-ajax.php'),
                data: {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    }

                    // Trigger WooCommerce events
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);

                    // Update button state
                    $button.removeClass('loading').addClass('added');
                    $button.find('.mn-readmore-text').text('Added!');
                    
                    // Reset button after 2 seconds
                    setTimeout(function() {
                        $button.removeClass('added');
                        $button.find('.mn-readmore-text').text(originalText);
                    }, 2000);
                },
                error: function() {
                    $button.removeClass('loading');
                    $button.find('.mn-readmore-text').text(originalText);
                    console.error('MN Posts: Error adding product to cart');
                }
            });
        });

        // Handle Direct Checkout button click
        $container.on('click', '.mn-direct-checkout-button', function(e) {
            e.preventDefault();

            var $button = $(this);
            
            // Prevent double-click
            if ($button.hasClass('loading')) {
                return;
            }

            var productId = $button.data('product-id');
            var action = $button.data('action');

            if (!productId || action !== 'direct_checkout') {
                return;
            }

            // Add loading state
            $button.addClass('loading');
            var originalText = $button.find('.mn-readmore-text').text();
            $button.find('.mn-readmore-text').text('Processing...');

            // AJAX request to add to cart and redirect to checkout
            $.ajax({
                type: 'POST',
                url: wc_add_to_cart_params ? wc_add_to_cart_params.ajax_url : (typeof mn_posts_params !== 'undefined' ? mn_posts_params.ajax_url : '/wp-admin/admin-ajax.php'),
                data: {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    if (response.error && response.product_url) {
                        window.location = response.product_url;
                        return;
                    }

                    // Trigger WooCommerce events
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);

                    // Redirect to checkout page
                    // Use WooCommerce checkout URL if available, otherwise fallback
                    var checkoutUrl = wc_add_to_cart_params && wc_add_to_cart_params.checkout_url 
                        ? wc_add_to_cart_params.checkout_url 
                        : window.location.origin + '/checkout/';
                    
                    window.location.href = checkoutUrl;
                },
                error: function() {
                    $button.removeClass('loading');
                    $button.find('.mn-readmore-text').text(originalText);
                    console.error('MN Posts: Error processing direct checkout');
                }
            });
        });
    }

})(jQuery);
