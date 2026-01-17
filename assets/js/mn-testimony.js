/**
 * MN Testimony Widget JavaScript
 * 
 * @package MN_Elements
 * @since 1.8.3
 */

(function($) {
    'use strict';

    /**
     * MN Testimony Widget Handler
     */
    class MNTestimonyWidget {
        constructor(element) {
            this.$element = $(element);
            this.$wrapper = this.$element.find('.mn-testimony-wrapper');
            this.$carousel = this.$element.find('.mn-testimony-carousel');
            
            if (this.$carousel.length === 0) {
                return;
            }

            this.$track = this.$carousel.find('.mn-testimony-track');
            this.$items = this.$track.find('.mn-testimony-item');
            this.$prevBtn = this.$wrapper.find('.mn-testimony-prev');
            this.$nextBtn = this.$wrapper.find('.mn-testimony-next');
            this.$dots = this.$wrapper.find('.mn-testimony-dot');

            this.settings = {
                autoplay: this.$carousel.data('autoplay') === true,
                autoplaySpeed: parseInt(this.$carousel.data('autoplay-speed')) || 3000,
                animationSpeed: parseInt(this.$carousel.data('animation-speed')) || 500,
                infinite: this.$carousel.data('infinite') === true,
                pauseOnHover: this.$carousel.data('pause-hover') === true,
                slidesToShow: parseInt(this.$carousel.data('slides')) || 3,
                slidesToShowTablet: parseInt(this.$carousel.data('slides-tablet')) || 2,
                slidesToShowMobile: parseInt(this.$carousel.data('slides-mobile')) || 1
            };

            this.currentIndex = 0;
            this.totalSlides = this.$items.length;
            this.autoplayTimer = null;
            this.isAnimating = false;
            this.isPaused = false;

            this.init();
        }

        init() {
            this.calculateSlidesPerView();
            this.setupSlides();
            this.bindEvents();

            if (this.settings.autoplay) {
                this.startAutoplay();
            }
        }

        calculateSlidesPerView() {
            const windowWidth = window.innerWidth;
            
            if (windowWidth <= 768) {
                this.slidesPerView = this.settings.slidesToShowMobile;
            } else if (windowWidth <= 1024) {
                this.slidesPerView = this.settings.slidesToShowTablet;
            } else {
                this.slidesPerView = this.settings.slidesToShow;
            }

            // Ensure we don't show more slides than available
            this.slidesPerView = Math.min(this.slidesPerView, this.totalSlides);
        }

        setupSlides() {
            const slideWidth = 100 / this.slidesPerView;
            
            this.$items.css({
                'flex': `0 0 ${slideWidth}%`,
                'max-width': `${slideWidth}%`
            });

            this.updatePosition(false);
            this.updateDots();
        }

        bindEvents() {
            // Arrow navigation
            this.$prevBtn.on('click', () => this.prev());
            this.$nextBtn.on('click', () => this.next());

            // Dot navigation
            this.$dots.on('click', (e) => {
                const index = $(e.currentTarget).data('index');
                this.goToSlide(index);
            });

            // Pause on hover
            if (this.settings.pauseOnHover) {
                this.$carousel.on('mouseenter', () => {
                    this.isPaused = true;
                    this.stopAutoplay();
                });

                this.$carousel.on('mouseleave', () => {
                    this.isPaused = false;
                    if (this.settings.autoplay) {
                        this.startAutoplay();
                    }
                });
            }

            // Touch/Swipe support
            this.setupTouchEvents();

            // Window resize
            let resizeTimer;
            $(window).on('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    this.calculateSlidesPerView();
                    this.setupSlides();
                }, 250);
            });

            // Visibility change
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    this.stopAutoplay();
                } else if (this.settings.autoplay && !this.isPaused) {
                    this.startAutoplay();
                }
            });
        }

        setupTouchEvents() {
            let startX = 0;
            let startY = 0;
            let isDragging = false;
            let isHorizontalSwipe = null;

            this.$carousel.on('touchstart', (e) => {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                isDragging = true;
                isHorizontalSwipe = null;
            });

            this.$carousel.on('touchmove', (e) => {
                if (!isDragging) return;

                const currentX = e.touches[0].clientX;
                const currentY = e.touches[0].clientY;
                const diffX = startX - currentX;
                const diffY = startY - currentY;

                // Determine swipe direction on first significant movement
                if (isHorizontalSwipe === null && (Math.abs(diffX) > 10 || Math.abs(diffY) > 10)) {
                    isHorizontalSwipe = Math.abs(diffX) > Math.abs(diffY);
                }

                // Prevent vertical scroll if horizontal swipe
                if (isHorizontalSwipe) {
                    e.preventDefault();
                }
            });

            this.$carousel.on('touchend', (e) => {
                if (!isDragging) return;
                isDragging = false;

                const endX = e.changedTouches[0].clientX;
                const diffX = startX - endX;

                if (isHorizontalSwipe && Math.abs(diffX) > 50) {
                    if (diffX > 0) {
                        this.next();
                    } else {
                        this.prev();
                    }
                }

                isHorizontalSwipe = null;
            });
        }

        prev() {
            if (this.isAnimating) return;

            let newIndex = this.currentIndex - 1;

            if (newIndex < 0) {
                if (this.settings.infinite) {
                    newIndex = this.getMaxIndex();
                } else {
                    return;
                }
            }

            this.goToSlide(newIndex);
        }

        next() {
            if (this.isAnimating) return;

            let newIndex = this.currentIndex + 1;
            const maxIndex = this.getMaxIndex();

            if (newIndex > maxIndex) {
                if (this.settings.infinite) {
                    newIndex = 0;
                } else {
                    return;
                }
            }

            this.goToSlide(newIndex);
        }

        getMaxIndex() {
            return Math.max(0, this.totalSlides - this.slidesPerView);
        }

        goToSlide(index) {
            if (this.isAnimating || index === this.currentIndex) return;

            this.isAnimating = true;
            this.currentIndex = index;

            this.updatePosition(true);
            this.updateDots();

            // Reset autoplay timer
            if (this.settings.autoplay && !this.isPaused) {
                this.stopAutoplay();
                this.startAutoplay();
            }

            setTimeout(() => {
                this.isAnimating = false;
            }, this.settings.animationSpeed);
        }

        updatePosition(animate) {
            const offset = -(this.currentIndex * (100 / this.slidesPerView));
            
            this.$track.css({
                'transition': animate ? `transform ${this.settings.animationSpeed}ms ease` : 'none',
                'transform': `translateX(${offset}%)`
            });
        }

        updateDots() {
            this.$dots.removeClass('active');
            this.$dots.eq(this.currentIndex).addClass('active');
        }

        startAutoplay() {
            this.stopAutoplay();
            
            this.autoplayTimer = setInterval(() => {
                this.next();
            }, this.settings.autoplaySpeed);
        }

        stopAutoplay() {
            if (this.autoplayTimer) {
                clearInterval(this.autoplayTimer);
                this.autoplayTimer = null;
            }
        }

        destroy() {
            this.stopAutoplay();
            this.$prevBtn.off('click');
            this.$nextBtn.off('click');
            this.$dots.off('click');
            this.$carousel.off('mouseenter mouseleave touchstart touchmove touchend');
        }
    }

    /**
     * Initialize widget on frontend
     */
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-testimony.default', function($scope) {
            new MNTestimonyWidget($scope[0]);
        });
    });

    /**
     * Initialize for non-Elementor contexts
     */
    $(document).ready(function() {
        if (typeof elementorFrontend === 'undefined') {
            $('.elementor-widget-mn-testimony').each(function() {
                new MNTestimonyWidget(this);
            });
        }
    });

})(jQuery);
