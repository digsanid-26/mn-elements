/**
 * MN WooCommerce Product Gallery Widget Scripts
 */
(function($) {
    'use strict';

    var MNWooProductGallery = {
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            var self = this;

            // Initialize all galleries on page
            $(document).ready(function() {
                self.initGalleries();
            });

            // Re-init on Elementor editor
            $(window).on('elementor/frontend/init', function() {
                if (typeof elementorFrontend !== 'undefined') {
                    elementorFrontend.hooks.addAction('frontend/element_ready/mn-woo-product-gallery.default', function($scope) {
                        self.initGallery($scope.find('.mn-woo-gallery-wrapper'));
                    });
                }
            });
        },

        initGalleries: function() {
            var self = this;
            $('.mn-woo-gallery-wrapper').each(function() {
                self.initGallery($(this));
            });
        },

        initGallery: function($wrapper) {
            if (!$wrapper.length) return;

            var self = this;
            var $thumbnails = $wrapper.find('.mn-woo-gallery-thumbnails');
            var $thumbsInner = $wrapper.find('.mn-woo-gallery-thumbnails-inner');
            var $thumbs = $wrapper.find('.mn-woo-gallery-thumb');
            var $mainImages = $wrapper.find('.mn-woo-gallery-main-image');
            var $navUp = $wrapper.find('.mn-woo-gallery-nav-up');
            var $navDown = $wrapper.find('.mn-woo-gallery-nav-down');
            var $lightbox = $wrapper.siblings('.mn-woo-gallery-lightbox');
            var $mainContainer = $wrapper.find('.mn-woo-gallery-main');

            var visibleThumbs = parseInt($wrapper.data('visible')) || 3;
            var currentIndex = 0;
            var scrollIndex = 0;
            var totalImages = $thumbs.length;

            // Calculate thumbnail height including margin
            function getThumbHeight() {
                var $firstThumb = $thumbs.first();
                return $firstThumb.outerHeight(true);
            }

            // Update thumbnails scroll position
            function updateThumbnailsScroll() {
                var thumbHeight = getThumbHeight();
                var offset = scrollIndex * thumbHeight;
                $thumbsInner.css('transform', 'translateY(-' + offset + 'px)');
                
                // Update nav button states
                $navUp.prop('disabled', scrollIndex <= 0);
                $navDown.prop('disabled', scrollIndex >= totalImages - visibleThumbs);
            }

            // Set thumbnails container height
            function setThumbnailsHeight() {
                var thumbHeight = getThumbHeight();
                var containerHeight = thumbHeight * visibleThumbs - parseInt($thumbs.first().css('margin-bottom'));
                $thumbnails.css('height', containerHeight + 'px');
            }

            // Initialize
            setTimeout(function() {
                setThumbnailsHeight();
                updateThumbnailsScroll();
            }, 100);

            // Thumbnail click
            $thumbs.on('click', function() {
                var index = $(this).data('index');
                self.switchImage(index, $thumbs, $mainImages);
                currentIndex = index;
            });

            // Navigation up
            $navUp.on('click', function() {
                if (scrollIndex > 0) {
                    scrollIndex--;
                    updateThumbnailsScroll();
                }
            });

            // Navigation down
            $navDown.on('click', function() {
                if (scrollIndex < totalImages - visibleThumbs) {
                    scrollIndex++;
                    updateThumbnailsScroll();
                }
            });

            // Lightbox functionality
            if ($mainContainer.data('lightbox') && $lightbox.length) {
                var $lightboxImg = $lightbox.find('.mn-woo-gallery-lightbox-image img');
                var $lightboxPrev = $lightbox.find('.mn-woo-gallery-lightbox-prev');
                var $lightboxNext = $lightbox.find('.mn-woo-gallery-lightbox-next');
                var $lightboxClose = $lightbox.find('.mn-woo-gallery-lightbox-close');
                var $lightboxOverlay = $lightbox.find('.mn-woo-gallery-lightbox-overlay');

                // Open lightbox
                $mainContainer.on('click', function() {
                    var $activeImage = $mainImages.filter('.active');
                    var fullUrl = $activeImage.data('full');
                    $lightboxImg.attr('src', fullUrl);
                    $lightbox.fadeIn(300);
                    $('body').css('overflow', 'hidden');
                });

                // Close lightbox
                $lightboxClose.add($lightboxOverlay).on('click', function() {
                    $lightbox.fadeOut(300);
                    $('body').css('overflow', '');
                });

                // Lightbox navigation
                $lightboxPrev.on('click', function(e) {
                    e.stopPropagation();
                    currentIndex = currentIndex > 0 ? currentIndex - 1 : totalImages - 1;
                    self.switchImage(currentIndex, $thumbs, $mainImages);
                    var fullUrl = $mainImages.filter('.active').data('full');
                    $lightboxImg.attr('src', fullUrl);
                });

                $lightboxNext.on('click', function(e) {
                    e.stopPropagation();
                    currentIndex = currentIndex < totalImages - 1 ? currentIndex + 1 : 0;
                    self.switchImage(currentIndex, $thumbs, $mainImages);
                    var fullUrl = $mainImages.filter('.active').data('full');
                    $lightboxImg.attr('src', fullUrl);
                });

                // Keyboard navigation
                $(document).on('keydown', function(e) {
                    if (!$lightbox.is(':visible')) return;
                    
                    if (e.key === 'Escape') {
                        $lightbox.fadeOut(300);
                        $('body').css('overflow', '');
                    } else if (e.key === 'ArrowLeft') {
                        $lightboxPrev.trigger('click');
                    } else if (e.key === 'ArrowRight') {
                        $lightboxNext.trigger('click');
                    }
                });
            }

            // Window resize
            $(window).on('resize', function() {
                setTimeout(function() {
                    setThumbnailsHeight();
                    updateThumbnailsScroll();
                }, 100);
            });
        },

        switchImage: function(index, $thumbs, $mainImages) {
            $thumbs.removeClass('active');
            $thumbs.filter('[data-index="' + index + '"]').addClass('active');
            
            $mainImages.removeClass('active');
            $mainImages.filter('[data-index="' + index + '"]').addClass('active');
        }
    };

    MNWooProductGallery.init();

})(jQuery);
