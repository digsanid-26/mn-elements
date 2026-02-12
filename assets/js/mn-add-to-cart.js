/**
 * MN Add To Cart Widget JavaScript
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        initAddToCart();
    });

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-add-to-cart.default', function($scope) {
            initAddToCart($scope);
        });
    });

    function initAddToCart($scope) {
        var $container = $scope ? $scope : $(document);

        $container.find('.mn-atc-wrapper').each(function() {
            var $wrapper = $(this);
            var $button = $wrapper.find('.mn-atc-button');
            var $qtyInput = $wrapper.find('.mn-atc-qty-input');
            var $message = $wrapper.find('.mn-atc-message');
            var productId = $wrapper.data('product-id');
            var isAjax = $wrapper.hasClass('mn-atc-ajax');
            var redirect = $wrapper.data('redirect');

            // Initialize variation selection
            initVariations($wrapper);

            // Initialize quantity controls
            initQuantityControls($wrapper);

            // Add to cart button click
            $button.on('click', function(e) {
                e.preventDefault();

                if ($button.prop('disabled')) {
                    return;
                }

                var selectedVariations = getSelectedVariations($wrapper);
                var quantity = $qtyInput.length ? parseInt($qtyInput.val()) : 1;

                // Check if all variations are selected
                if (!validateVariations($wrapper, selectedVariations)) {
                    showMessage($message, 'Please select all options', 'error');
                    return;
                }

                if (isAjax) {
                    addToCartAjax($wrapper, productId, selectedVariations, quantity, redirect);
                } else {
                    addToCartNormal(productId, selectedVariations, quantity);
                }
            });

            // Buy Now button click
            var $buyNowBtn = $wrapper.find('.mn-atc-buy-now');
            $buyNowBtn.on('click', function(e) {
                e.preventDefault();

                if ($buyNowBtn.prop('disabled')) {
                    return;
                }

                var selectedVariations = getSelectedVariations($wrapper);
                var quantity = $qtyInput.length ? parseInt($qtyInput.val()) : 1;

                if (!validateVariations($wrapper, selectedVariations)) {
                    showMessage($message, 'Please select all options', 'error');
                    return;
                }

                // Always use AJAX + redirect to checkout
                addToCartAjax($wrapper, productId, selectedVariations, quantity, 'checkout');
            });
        });
    }

    function initVariations($wrapper) {
        var $variationItems = $wrapper.find('.mn-atc-variation-item');
        var $variationSelects = $wrapper.find('.mn-atc-variation-select');
        var $clearBtn = $wrapper.find('.mn-atc-clear-variations');
        var $button = $wrapper.find('.mn-atc-button');

        // Click on variation item
        $variationItems.on('click', function() {
            var $item = $(this);
            
            if ($item.hasClass('disabled')) {
                return;
            }

            var $group = $item.closest('.mn-atc-variation-group');
            var value = $item.data('value');
            var attribute = $group.data('attribute');

            // Toggle selection
            $group.find('.mn-atc-variation-item').removeClass('active');
            $item.addClass('active');

            // Update label
            updateVariationLabel($group, value);

            // Trigger variation selected event with current attributes
            var selectedAttrs = getSelectedVariations($wrapper);
            $(document).trigger('mn_variation_selected', [{ attributes: selectedAttrs }]);

            // Check if all variations selected
            checkVariationsComplete($wrapper);
        });

        // Change on dropdown
        $variationSelects.on('change', function() {
            var $select = $(this);
            var $group = $select.closest('.mn-atc-variation-group');
            var value = $select.val();

            updateVariationLabel($group, value);

            // Trigger variation selected event with current attributes
            var selectedAttrs = getSelectedVariations($wrapper);
            $(document).trigger('mn_variation_selected', [{ attributes: selectedAttrs }]);

            checkVariationsComplete($wrapper);
        });

        // Clear selections
        $clearBtn.on('click', function() {
            clearVariations($wrapper);
        });
    }

    function updateVariationLabel($group, value) {
        var $label = $group.find('.mn-atc-selected-value');
        $label.text(value);
    }

    function checkVariationsComplete($wrapper) {
        var $button = $wrapper.find('.mn-atc-button');
        var selectedVariations = getSelectedVariations($wrapper);
        var $groups = $wrapper.find('.mn-atc-variation-group');
        
        // Check if all groups have selection
        var allSelected = $groups.length === Object.keys(selectedVariations).length;

        if (allSelected) {
            $button.prop('disabled', false);
            $wrapper.find('.mn-atc-buy-now').prop('disabled', false);
            
            // Get variation ID and update price if available
            var productId = $wrapper.data('product-id');
            getVariationData($wrapper, productId, selectedVariations);
        } else {
            $button.prop('disabled', true);
            $wrapper.find('.mn-atc-buy-now').prop('disabled', true);
        }
    }

    function getSelectedVariations($wrapper) {
        var variations = {};

        // From variation items
        $wrapper.find('.mn-atc-variation-group').each(function() {
            var $group = $(this);
            var attribute = $group.data('attribute');
            var $activeItem = $group.find('.mn-atc-variation-item.active');
            
            if ($activeItem.length) {
                variations[attribute] = $activeItem.data('value');
            }
        });

        // From dropdowns
        $wrapper.find('.mn-atc-variation-select').each(function() {
            var $select = $(this);
            var attribute = $select.data('attribute');
            var value = $select.val();
            
            if (value) {
                variations[attribute] = value;
            }
        });

        return variations;
    }

    function validateVariations($wrapper, selectedVariations) {
        var $groups = $wrapper.find('.mn-atc-variation-group');
        return $groups.length === Object.keys(selectedVariations).length;
    }

    function clearVariations($wrapper) {
        $wrapper.find('.mn-atc-variation-item').removeClass('active');
        $wrapper.find('.mn-atc-variation-select').val('');
        $wrapper.find('.mn-atc-selected-value').text('');
        $wrapper.find('.mn-atc-button').prop('disabled', true);
        $wrapper.find('.mn-atc-buy-now').prop('disabled', true);

        // Trigger variation cleared event
        $(document).trigger('mn_variation_cleared', [{ product_id: $wrapper.data('product-id') }]);
    }

    function initQuantityControls($wrapper) {
        var $qtyInput = $wrapper.find('.mn-atc-qty-input');
        var $plusBtn = $wrapper.find('.mn-atc-qty-plus');
        var $minusBtn = $wrapper.find('.mn-atc-qty-minus');

        // Remove existing handlers to prevent multiple bindings
        $plusBtn.off('click.mnAtcQty');
        $minusBtn.off('click.mnAtcQty');
        $qtyInput.off('change.mnAtcQty');

        $plusBtn.on('click.mnAtcQty', function() {
            var currentVal = parseInt($qtyInput.val());
            var max = $qtyInput.attr('max');
            var newVal = currentVal + 1;

            if (max && newVal > parseInt(max)) {
                return;
            }

            $qtyInput.val(newVal);
        });

        $minusBtn.on('click.mnAtcQty', function() {
            var currentVal = parseInt($qtyInput.val());
            var min = parseInt($qtyInput.attr('min'));
            var newVal = currentVal - 1;

            if (newVal < min) {
                return;
            }

            $qtyInput.val(newVal);
        });

        // Validate input
        $qtyInput.on('change.mnAtcQty', function() {
            var val = parseInt($(this).val());
            var min = parseInt($(this).attr('min'));
            var max = $(this).attr('max');

            if (isNaN(val) || val < min) {
                $(this).val(min);
            } else if (max && val > parseInt(max)) {
                $(this).val(max);
            }
        });
    }

    function getVariationData($wrapper, productId, selectedVariations) {
        console.log('MN Get Variation Data - Sending:', {
            product_id: productId,
            variations: selectedVariations
        });

        $.ajax({
            url: mn_add_to_cart_params.ajax_url,
            type: 'POST',
            data: {
                action: 'mn_get_variation_data',
                product_id: productId,
                variations: selectedVariations,
                nonce: mn_add_to_cart_params.nonce
            },
            success: function(response) {
                console.log('MN Get Variation Data - Response:', response);
                if (response.success && response.data) {
                    // Update price if available
                    if (response.data.price_html) {
                        $wrapper.find('.mn-atc-price').html(response.data.price_html);
                    }
                    
                    // Store variation ID
                    $wrapper.data('variation-id', response.data.variation_id);

                    // Trigger variation selected with variation_id for precise gallery image matching
                    $(document).trigger('mn_variation_selected', [{
                        variation_id: response.data.variation_id,
                        attributes: selectedVariations,
                        product_id: productId
                    }]);
                }
            }
        });
    }

    function addToCartAjax($wrapper, productId, selectedVariations, quantity, redirect) {
        var $button = $wrapper.find('.mn-atc-button');
        var $message = $wrapper.find('.mn-atc-message');
        var variationId = $wrapper.data('variation-id') || 0;

        $wrapper.addClass('loading');
        $button.prop('disabled', true);

        console.log('MN Add to Cart - Sending data:', {
            product_id: productId,
            variation_id: variationId,
            variations: selectedVariations,
            quantity: quantity
        });

        $.ajax({
            url: mn_add_to_cart_params.ajax_url,
            type: 'POST',
            data: {
                action: 'mn_add_to_cart',
                product_id: productId,
                variation_id: variationId,
                quantity: quantity,
                variations: selectedVariations,
                nonce: mn_add_to_cart_params.nonce
            },
            success: function(response) {
                console.log('MN Add to Cart - Response:', response);
                $wrapper.removeClass('loading');

                if (response.success) {
                    showMessage($message, response.data.message || 'Product added to cart!', 'success');
                    
                    // Trigger WooCommerce event
                    $(document.body).trigger('added_to_cart', [response.data.fragments, response.data.cart_hash]);

                    // Redirect if needed
                    if (redirect === 'cart') {
                        window.location.href = mn_add_to_cart_params.cart_url;
                    } else if (redirect === 'checkout') {
                        window.location.href = mn_add_to_cart_params.checkout_url;
                    } else {
                        $button.prop('disabled', false);
                    }
                } else {
                    showMessage($message, response.data.message || 'Error adding to cart', 'error');
                    $button.prop('disabled', false);
                }
            },
            error: function() {
                $wrapper.removeClass('loading');
                showMessage($message, 'Error adding to cart', 'error');
                $button.prop('disabled', false);
            }
        });
    }

    function addToCartNormal(productId, selectedVariations, quantity) {
        // Build URL with parameters
        var url = '?add-to-cart=' + productId + '&quantity=' + quantity;
        
        // Add variation parameters
        for (var attr in selectedVariations) {
            url += '&' + encodeURIComponent(attr) + '=' + encodeURIComponent(selectedVariations[attr]);
        }

        window.location.href = url;
    }

    function showMessage($message, text, type) {
        $message.removeClass('success error').addClass(type + ' show').text(text);
        
        setTimeout(function() {
            $message.removeClass('show');
        }, 5000);
    }

})(jQuery);
