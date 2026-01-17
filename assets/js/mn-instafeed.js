/**
 * MN Instafeed Widget JavaScript
 * Handles carousel functionality and masonry layout
 */

(function($) {
    'use strict';

    /**
     * MN Instafeed Widget Class
     */
    class MNInstafeedWidget {
        constructor($scope) {
            this.$scope = $scope;
            this.$wrapper = $scope.find('.mn-instafeed-wrapper');
            this.layout = this.$wrapper.data('layout');

            console.log('MN Instafeed constructor called');
            console.log('Wrapper found:', this.$wrapper.length);
            console.log('Layout:', this.layout);

            this.init();
        }

        init() {
            const $items = this.$scope.find('.mn-instafeed-item');
            console.log('MN Instafeed init - Items found:', $items.length);
            
            if (this.layout === 'carousel') {
                this.initCarousel();
            } else if (this.layout === 'masonry') {
                this.initMasonry();
            } else {
                console.log('MN Instafeed - Grid layout (no special init needed)');
            }

            console.log('MN Instafeed initialized with layout:', this.layout);
        }

        initCarousel() {
            const $carousel = this.$wrapper.find('.mn-instafeed-carousel');
            const $items = $carousel.find('.mn-instafeed-item');
            
            if ($items.length === 0) return;

            // Get settings from widget
            const settings = this.getCarouselSettings();
            
            // Wrap items in track
            $items.wrapAll('<div class="mn-instafeed-track"></div>');
            const $track = $carousel.find('.mn-instafeed-track');

            // Initialize carousel state
            this.carousel = {
                $carousel: $carousel,
                $track: $track,
                $items: $items,
                currentIndex: 0,
                itemCount: $items.length,
                slidesToShow: settings.slidesToShow,
                autoplay: settings.autoplay,
                autoplaySpeed: settings.autoplaySpeed,
                showArrows: settings.showArrows,
                showDots: settings.showDots,
                autoplayTimer: null
            };

            // Set item widths
            this.updateCarouselLayout();

            // Add navigation
            if (this.carousel.showArrows) {
                this.addCarouselArrows();
            }

            if (this.carousel.showDots) {
                this.addCarouselDots();
            }

            // Start autoplay
            if (this.carousel.autoplay) {
                this.startAutoplay();
            }

            // Handle window resize
            $(window).on('resize', () => this.updateCarouselLayout());

            // Pause on hover
            $carousel.on('mouseenter', () => this.stopAutoplay());
            $carousel.on('mouseleave', () => {
                if (this.carousel.autoplay) {
                    this.startAutoplay();
                }
            });
        }

        getCarouselSettings() {
            // Try to get settings from data attributes or use defaults
            const $carousel = this.$wrapper.find('.mn-instafeed-carousel');
            
            return {
                slidesToShow: parseInt($carousel.data('slides-to-show')) || 3,
                autoplay: $carousel.data('autoplay') !== false,
                autoplaySpeed: parseInt($carousel.data('autoplay-speed')) || 3000,
                showArrows: $carousel.data('show-arrows') !== false,
                showDots: $carousel.data('show-dots') !== false
            };
        }

        updateCarouselLayout() {
            const itemWidth = 100 / this.carousel.slidesToShow;
            this.carousel.$items.css('width', itemWidth + '%');
        }

        addCarouselArrows() {
            const $prevArrow = $('<button class="mn-instafeed-arrow mn-instafeed-arrow-prev"><i class="fas fa-chevron-left"></i></button>');
            const $nextArrow = $('<button class="mn-instafeed-arrow mn-instafeed-arrow-next"><i class="fas fa-chevron-right"></i></button>');

            this.carousel.$carousel.append($prevArrow, $nextArrow);

            $prevArrow.on('click', () => this.prevSlide());
            $nextArrow.on('click', () => this.nextSlide());
        }

        addCarouselDots() {
            const dotsCount = Math.ceil(this.carousel.itemCount / this.carousel.slidesToShow);
            const $dotsContainer = $('<div class="mn-instafeed-dots"></div>');

            for (let i = 0; i < dotsCount; i++) {
                const $dot = $('<button class="mn-instafeed-dot"></button>');
                if (i === 0) $dot.addClass('active');
                
                $dot.on('click', () => this.goToSlide(i * this.carousel.slidesToShow));
                $dotsContainer.append($dot);
            }

            this.$wrapper.append($dotsContainer);
            this.carousel.$dots = $dotsContainer.find('.mn-instafeed-dot');
        }

        prevSlide() {
            this.carousel.currentIndex -= this.carousel.slidesToShow;
            
            if (this.carousel.currentIndex < 0) {
                this.carousel.currentIndex = Math.max(0, this.carousel.itemCount - this.carousel.slidesToShow);
            }
            
            this.updateCarouselPosition();
        }

        nextSlide() {
            this.carousel.currentIndex += this.carousel.slidesToShow;
            
            if (this.carousel.currentIndex >= this.carousel.itemCount) {
                this.carousel.currentIndex = 0;
            }
            
            this.updateCarouselPosition();
        }

        goToSlide(index) {
            this.carousel.currentIndex = Math.min(index, this.carousel.itemCount - this.carousel.slidesToShow);
            this.updateCarouselPosition();
        }

        updateCarouselPosition() {
            const translateX = -(this.carousel.currentIndex * (100 / this.carousel.slidesToShow));
            this.carousel.$track.css('transform', `translateX(${translateX}%)`);

            // Update dots
            if (this.carousel.$dots) {
                const activeDot = Math.floor(this.carousel.currentIndex / this.carousel.slidesToShow);
                this.carousel.$dots.removeClass('active');
                this.carousel.$dots.eq(activeDot).addClass('active');
            }
        }

        startAutoplay() {
            this.stopAutoplay();
            
            this.carousel.autoplayTimer = setInterval(() => {
                this.nextSlide();
            }, this.carousel.autoplaySpeed);
        }

        stopAutoplay() {
            if (this.carousel.autoplayTimer) {
                clearInterval(this.carousel.autoplayTimer);
                this.carousel.autoplayTimer = null;
            }
        }

        initMasonry() {
            // Masonry layout is handled by CSS column-count
            // This method can be used for additional masonry enhancements if needed
            
            // Ensure images are loaded before calculating layout
            const $masonry = this.$wrapper.find('.mn-instafeed-masonry');
            const $items = $masonry.find('.mn-instafeed-item');

            $items.find('img').on('load', function() {
                // Trigger reflow if needed
                $masonry.css('column-count', $masonry.css('column-count'));
            });
        }
    }

    /**
     * Initialize widget on Elementor frontend
     */
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-instafeed.default', function($scope) {
            new MNInstafeedWidget($scope);
        });
    });

})(jQuery);
