/**
 * MN WooProduct Widget JavaScript
 */
(function($) {
    'use strict';

    // Initialize on document ready
    $(document).ready(function() {
        initGalleryNavigation();
        initAjaxAddToCart();
        initVariationPrice();
    });

    // Re-initialize on Elementor frontend init
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-wooproduct.default', function($scope) {
            initGalleryNavigation($scope);
            initAjaxAddToCart($scope);
            initVariationPrice($scope);
        });
    });

    /**
     * Initialize Gallery Navigation
     */
    function initGalleryNavigation($scope) {
        var $container = $scope ? $scope : $(document);
        
        $container.find('.mn-wooproduct-gallery').each(function() {
            var $gallery = $(this);
            var $slides = $gallery.find('.mn-wooproduct-gallery-slide');
            var totalSlides = $slides.length;
            var currentIndex = 0;

            // Previous button
            $gallery.closest('.mn-wooproduct-image-wrapper').find('.mn-wooproduct-gallery-prev').off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                showSlide($slides, currentIndex);
            });

            // Next button
            $gallery.closest('.mn-wooproduct-image-wrapper').find('.mn-wooproduct-gallery-next').off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                currentIndex = (currentIndex + 1) % totalSlides;
                showSlide($slides, currentIndex);
            });
        });
    }

    /**
     * Show specific slide
     */
    function showSlide($slides, index) {
        $slides.removeClass('active').eq(index).addClass('active');
    }

    /**
     * Initialize AJAX Add to Cart
     */
    function initAjaxAddToCart($scope) {
        var $container = $scope ? $scope : $(document);

        $container.find('.mn-ajax-add-to-cart').off('click').on('click', function(e) {
            e.preventDefault();

            var $button = $(this);
            var productId = $button.data('product-id');
            var quantity = $button.data('quantity') || 1;

            if ($button.hasClass('loading') || $button.hasClass('added')) {
                return;
            }

            $button.addClass('loading');

            $.ajax({
                type: 'POST',
                url: wc_add_to_cart_params ? wc_add_to_cart_params.ajax_url : mn_wooproduct_params.ajax_url,
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

                    $button.removeClass('loading').addClass('added');

                    // Update cart fragments
                    if (response.fragments) {
                        $.each(response.fragments, function(key, value) {
                            $(key).replaceWith(value);
                        });
                    }

                    // Trigger WooCommerce event
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);

                    // Reset button after delay
                    setTimeout(function() {
                        $button.removeClass('added');
                    }, 2000);
                },
                error: function() {
                    $button.removeClass('loading');
                    // Fallback: redirect to product page
                    window.location = $button.attr('href');
                }
            });
        });

        // Direct checkout button
        $container.find('.mn-wooproduct-btn-checkout').off('click').on('click', function(e) {
            var $button = $(this);
            var href = $button.attr('href');
            
            // Let the default behavior handle it (redirect to checkout with product)
            if (href && href.indexOf('add-to-cart') !== -1) {
                return true;
            }
        });
    }

    /**
     * Initialize Variation Price Update
     */
    function initVariationPrice($scope) {
        var $container = $scope ? $scope : $(document);

        $container.find('.mn-wooproduct-item').each(function() {
            var $item = $(this);
            var $priceEl = $item.find('.mn-wooproduct-price');

            if (!$priceEl.length) return;

            // Store original price HTML
            $item.data('original-price-html', $priceEl.html());
        });

        $container.find('.mn-wooproduct-variation-item').off('click.mnVariationPrice').on('click.mnVariationPrice', function() {
            var $swatch = $(this);
            var $item = $swatch.closest('.mn-wooproduct-item');
            var $priceEl = $item.find('.mn-wooproduct-price');
            var productId = $item.data('product-id');
            var value = $swatch.data('value');
            var attribute = $swatch.closest('.mn-wooproduct-variations').data('attribute');

            if (!$priceEl.length || !productId || !value || !attribute) return;

            // Toggle active state
            var wasActive = $swatch.hasClass('active');
            $swatch.closest('.mn-wooproduct-variations').find('.mn-wooproduct-variation-item').removeClass('active');

            if (wasActive) {
                // Deselect: restore original price
                var originalPrice = $item.data('original-price-html');
                if (originalPrice) {
                    $priceEl.html(originalPrice);
                }
                return;
            }

            $swatch.addClass('active');

            // Fetch variation price via AJAX
            var ajaxUrl = (typeof mn_wooproduct_params !== 'undefined') ? mn_wooproduct_params.ajax_url : (typeof wc_add_to_cart_params !== 'undefined' ? wc_add_to_cart_params.ajax_url : '');
            var nonce = (typeof mn_wooproduct_params !== 'undefined') ? mn_wooproduct_params.nonce : '';

            if (!ajaxUrl || !nonce) return;

            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: {
                    action: 'mn_wooproduct_get_variation_price',
                    product_id: productId,
                    attribute: attribute,
                    value: value,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success && response.data && response.data.price_html) {
                        $priceEl.html(response.data.price_html);
                    }
                }
            });
        });
    }

    // WooCommerce AJAX add to cart handler (if not already defined)
    if (typeof wc_add_to_cart_params === 'undefined') {
        // Fallback AJAX handler
        $(document).on('click', '.mn-ajax-add-to-cart', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var productId = $button.data('product-id');
            
            if ($button.hasClass('loading')) return;
            
            $button.addClass('loading');
            
            // Simple fallback - just redirect to add to cart URL
            setTimeout(function() {
                window.location = $button.attr('href');
            }, 500);
        });
    }

})(jQuery);
