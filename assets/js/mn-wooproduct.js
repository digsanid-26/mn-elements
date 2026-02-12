/**
 * MN WooProduct Widget JavaScript
 */
(function($) {
    'use strict';

    // Initialize on document ready
    $(document).ready(function() {
        initGalleryNavigation();
        initAjaxAddToCart();
    });

    // Re-initialize on Elementor frontend init
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-wooproduct.default', function($scope) {
            initGalleryNavigation($scope);
            initAjaxAddToCart($scope);
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
