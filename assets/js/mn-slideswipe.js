/**
 * MN SlideSwipe JavaScript
 * Template slider with swipe functionality
 */

(function($) {
    'use strict';

    // Global MN SlideSwipe object
    var MNSlideSwipe = {
        instances: {},

        init: function(widgetId) {
            var self = this;
            var $wrapper = $('.elementor-element-' + widgetId);
            
            console.log('MN SlideSwipe init called for widget:', widgetId);
            console.log('Wrapper found:', $wrapper.length);
            
            if ($wrapper.length === 0) {
                console.log('Wrapper not found for widget:', widgetId);
                return;
            }

            var $container = $wrapper.find('.mn-slideswipe-container');
            if ($container.length === 0) {
                console.log('Container not found for widget:', widgetId);
                return;
            }

            // Get settings from data attribute
            var settings = $container.data('swiper-settings');
            if (!settings) {
                console.log('No swiper settings found for widget:', widgetId);
                return;
            }

            console.log('Swiper settings:', settings);

            // Wait for Swiper to be available
            this.waitForSwiper(function() {
                self.initializeSwiper(widgetId, $container, settings);
            });
        },

        waitForSwiper: function(callback) {
            if (typeof Swiper !== 'undefined') {
                callback();
            } else {
                console.log('Waiting for Swiper...');
                setTimeout(function() {
                    if (typeof Swiper !== 'undefined') {
                        callback();
                    } else {
                        console.log('Swiper not available, loading fallback');
                        // Load Swiper dynamically if not available
                        this.loadSwiper(callback);
                    }
                }.bind(this), 100);
            }
        },

        loadSwiper: function(callback) {
            // Load Swiper CSS
            if (!document.querySelector('link[href*="swiper"]')) {
                var css = document.createElement('link');
                css.rel = 'stylesheet';
                css.href = 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css';
                document.head.appendChild(css);
            }

            // Load Swiper JS
            if (!window.Swiper) {
                var script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js';
                script.onload = function() {
                    console.log('Swiper loaded dynamically');
                    callback();
                };
                script.onerror = function() {
                    console.error('Failed to load Swiper');
                    // Fallback to basic functionality
                    this.initBasicSlider();
                }.bind(this);
                document.head.appendChild(script);
            }
        },

        initializeSwiper: function(widgetId, $container, settings) {
            var self = this;
            
            try {
                // Destroy existing instance if any
                if (this.instances[widgetId]) {
                    this.instances[widgetId].destroy(true, true);
                    delete this.instances[widgetId];
                }

                console.log('Initializing Swiper for widget:', widgetId);

                // Initialize Swiper
                var swiper = new Swiper($container[0], settings);
                
                // Store instance
                this.instances[widgetId] = swiper;

                // Setup autoplay progress animation
                var $wrapper = $container.closest('.mn-slideswipe-wrapper');
                if (settings.autoplay && settings.autoplay.delay) {
                    var duration = settings.autoplay.delay / 1000; // Convert to seconds
                    $wrapper.css('--autoplay-duration', duration + 's');
                    
                    // Only add active class if wrapper doesn't have mn-progress-disabled class
                    // (This allows users to disable progress via show_progress setting)
                    if (!$wrapper.hasClass('mn-progress-disabled')) {
                        $wrapper.addClass('mn-autoplay-active');
                        console.log('Autoplay progress enabled with duration:', duration + 's');
                    }
                }

                // Bind events
                this.bindEvents(widgetId, swiper, $wrapper);

                console.log('Swiper initialized successfully for widget:', widgetId);

            } catch (error) {
                console.error('Error initializing Swiper:', error);
                this.initBasicSlider(widgetId, $container);
            }
        },

        bindEvents: function(widgetId, swiper, $wrapper) {
            var self = this;

            // Swiper events
            swiper.on('slideChange', function() {
                console.log('Slide changed:', this.activeIndex);
                self.onSlideChange(widgetId, this.activeIndex);
                
                // Reset progress animation on slide change
                self.resetProgressAnimation($wrapper);
            });

            swiper.on('reachEnd', function() {
                console.log('Reached end');
                self.onReachEnd(widgetId);
            });

            swiper.on('reachBeginning', function() {
                console.log('Reached beginning');
                self.onReachBeginning(widgetId);
            });

            // Touch events for better mobile experience
            swiper.on('touchStart', function() {
                self.onTouchStart(widgetId);
            });

            swiper.on('touchEnd', function() {
                self.onTouchEnd(widgetId);
            });

            // Autoplay events
            swiper.on('autoplayStart', function() {
                console.log('Autoplay started');
                $wrapper.addClass('mn-autoplay-active');
            });

            swiper.on('autoplayStop', function() {
                console.log('Autoplay stopped');
                $wrapper.removeClass('mn-autoplay-active');
            });

            swiper.on('autoplayPause', function() {
                console.log('Autoplay paused');
                $wrapper.removeClass('mn-autoplay-active');
            });

            swiper.on('autoplayResume', function() {
                console.log('Autoplay resumed');
                $wrapper.addClass('mn-autoplay-active');
                self.resetProgressAnimation($wrapper);
            });

            // Keyboard navigation
            $(document).on('keydown.mn-slideswipe-' + widgetId, function(e) {
                if (!self.isWidgetInView(widgetId)) {
                    return;
                }

                switch(e.which) {
                    case 37: // Left arrow
                        e.preventDefault();
                        swiper.slidePrev();
                        break;
                    case 39: // Right arrow
                        e.preventDefault();
                        swiper.slideNext();
                        break;
                }
            });

            // Window resize
            $(window).on('resize.mn-slideswipe-' + widgetId, function() {
                setTimeout(function() {
                    if (swiper && !swiper.destroyed) {
                        swiper.update();
                    }
                }, 100);
            });
        },

        onSlideChange: function(widgetId, activeIndex) {
            // Custom slide change logic
            var $wrapper = $('.elementor-element-' + widgetId);
            $wrapper.trigger('mn-slideswipe:slideChange', [activeIndex]);
            
            // Update slide number display
            var $numberDisplay = $wrapper.find('.mn-slideswipe-number');
            if ($numberDisplay.length > 0) {
                var currentSlide = activeIndex + 1; // Convert to 1-based index
                $numberDisplay.find('.mn-number-current').text(currentSlide);
            }
        },

        onReachEnd: function(widgetId) {
            var $wrapper = $('.elementor-element-' + widgetId);
            $wrapper.trigger('mn-slideswipe:reachEnd');
        },

        onReachBeginning: function(widgetId) {
            var $wrapper = $('.elementor-element-' + widgetId);
            $wrapper.trigger('mn-slideswipe:reachBeginning');
        },

        onTouchStart: function(widgetId) {
            var $wrapper = $('.elementor-element-' + widgetId);
            $wrapper.addClass('mn-slideswipe-touching');
        },

        onTouchEnd: function(widgetId) {
            var $wrapper = $('.elementor-element-' + widgetId);
            setTimeout(function() {
                $wrapper.removeClass('mn-slideswipe-touching');
            }, 100);
        },

        isWidgetInView: function(widgetId) {
            var $wrapper = $('.elementor-element-' + widgetId);
            if ($wrapper.length === 0) {
                return false;
            }

            var rect = $wrapper[0].getBoundingClientRect();
            return rect.top >= 0 && rect.bottom <= window.innerHeight;
        },

        initBasicSlider: function(widgetId, $container) {
            console.log('Initializing basic slider for widget:', widgetId);
            
            var $wrapper = $container || $('.elementor-element-' + widgetId + ' .mn-slideswipe-container');
            var $slides = $wrapper.find('.swiper-slide');
            var $prevBtn = $wrapper.siblings().find('.mn-slideswipe-arrow-prev');
            var $nextBtn = $wrapper.siblings().find('.mn-slideswipe-arrow-next');
            var $dots = $wrapper.siblings().find('.swiper-pagination-bullet');
            
            if ($slides.length === 0) {
                return;
            }

            var currentSlide = 0;
            var totalSlides = $slides.length;

            // Hide all slides except first
            $slides.hide().eq(0).show();

            // Update dots
            function updateDots() {
                $dots.removeClass('swiper-pagination-bullet-active')
                     .eq(currentSlide).addClass('swiper-pagination-bullet-active');
            }

            // Show slide
            function showSlide(index) {
                if (index < 0) index = totalSlides - 1;
                if (index >= totalSlides) index = 0;
                
                $slides.hide().eq(index).fadeIn(300);
                currentSlide = index;
                updateDots();
            }

            // Previous button
            $prevBtn.on('click', function() {
                showSlide(currentSlide - 1);
            });

            // Next button
            $nextBtn.on('click', function() {
                showSlide(currentSlide + 1);
            });

            // Dots navigation
            $dots.on('click', function() {
                var index = $(this).index();
                showSlide(index);
            });

            // Initialize
            updateDots();

            console.log('Basic slider initialized for widget:', widgetId);
        },

        destroy: function(widgetId) {
            if (this.instances[widgetId]) {
                try {
                    this.instances[widgetId].destroy(true, true);
                    delete this.instances[widgetId];
                } catch (error) {
                    console.error('Error destroying Swiper:', error);
                }
            }

            // Remove event listeners
            $(document).off('keydown.mn-slideswipe-' + widgetId);
            $(window).off('resize.mn-slideswipe-' + widgetId);

            console.log('Destroyed slider for widget:', widgetId);
        },

        refresh: function(widgetId) {
            if (this.instances[widgetId]) {
                try {
                    this.instances[widgetId].update();
                    console.log('Refreshed slider for widget:', widgetId);
                } catch (error) {
                    console.error('Error refreshing Swiper:', error);
                    // Reinitialize if refresh fails
                    this.init(widgetId);
                }
            }
        },

        resetProgressAnimation: function($wrapper) {
            // Force reflow to restart animation
            var $activeBullet = $wrapper.find('.swiper-pagination-bullet-active');
            if ($activeBullet.length) {
                // Remove and re-add class to restart animation
                $wrapper.removeClass('mn-autoplay-active');
                
                // Force reflow
                void $wrapper[0].offsetWidth;
                
                // Re-add class
                setTimeout(function() {
                    $wrapper.addClass('mn-autoplay-active');
                }, 10);
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        // Auto-initialize all sliders on page
        $('.mn-slideswipe-container').each(function() {
            var $container = $(this);
            var $wrapper = $container.closest('.elementor-element');
            var widgetId = $wrapper.data('id');
            
            if (widgetId) {
                MNSlideSwipe.init(widgetId);
            }
        });
    });

    // Elementor frontend init
    $(window).on('elementor/frontend/init', function() {
        console.log('Elementor frontend init - MN SlideSwipe');
        
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-slideswipe.default', function($scope) {
            var widgetId = $scope.data('id');
            if (widgetId) {
                console.log('Elementor ready for widget:', widgetId);
                
                // Destroy existing instance if any
                if (MNSlideSwipe.instances[widgetId]) {
                    MNSlideSwipe.destroy(widgetId);
                }
                
                // Initialize new instance
                setTimeout(function() {
                    MNSlideSwipe.init(widgetId);
                }, 100);
            }
        });
    });

    // Expose to global scope for external access
    window.MNSlideSwipe = MNSlideSwipe;

})(jQuery);
