/**
 * MN Dual Slider
 * Synchronized dual slider with video support
 */

(function($) {
    'use strict';

    var MNDualSlider = {
        instances: {},

        init: function($wrapper) {
            var self = this;
            var widgetId = $wrapper.data('widget-id');

            if (!widgetId) {
                console.log('MN Dual Slider: No widget ID found');
                return;
            }

            console.log('MN Dual Slider: Initializing widget', widgetId);

            // Get settings
            var settings = {
                widgetId: widgetId,
                $wrapper: $wrapper,
                $mainSlider: $wrapper.find('.mn-dual-slider-main'),
                $thumbSlider: $wrapper.find('.mn-dual-slider-thumb'),
                $mainSlides: $wrapper.find('.mn-dual-slider-main .mn-slide'),
                $thumbSlides: $wrapper.find('.mn-dual-slider-thumb .mn-slide'),
                $ctaSlides: $wrapper.find('.mn-cta-slide'),
                $prevBtn: $wrapper.find('.mn-nav-prev'),
                $nextBtn: $wrapper.find('.mn-nav-next'),
                autoplay: $wrapper.data('autoplay') === 'true',
                autoplaySpeed: parseInt($wrapper.data('autoplay-speed')) || 5000,
                transitionSpeed: parseInt($wrapper.data('transition-speed')) || 600,
                loop: $wrapper.data('loop') === 'true',
                videoMuted: $wrapper.data('video-muted') === '1',
                currentIndex: 0,
                totalSlides: $wrapper.find('.mn-dual-slider-main .mn-slide').length,
                autoplayTimer: null,
                isTransitioning: false
            };

            // Store instance
            this.instances[widgetId] = settings;

            // Set transition speed
            settings.$mainSlides.css('transition-duration', settings.transitionSpeed + 'ms');
            settings.$thumbSlides.css('transition-duration', settings.transitionSpeed + 'ms');
            settings.$ctaSlides.css('transition-duration', (settings.transitionSpeed - 200) + 'ms');

            // Initialize video handling
            this.initVideoHandling(widgetId);

            // Bind events
            this.bindEvents(widgetId);

            // Start autoplay if enabled
            if (settings.autoplay) {
                this.startAutoplay(widgetId);
            }

            // Update caption for first slide
            this.updateCaption(widgetId, 0);

            console.log('MN Dual Slider: Initialized', settings);
        },

        bindEvents: function(widgetId) {
            var self = this;
            var slider = this.instances[widgetId];

            if (!slider) return;

            // Previous button
            slider.$prevBtn.on('click', function(e) {
                e.preventDefault();
                self.prevSlide(widgetId);
            });

            // Next button
            slider.$nextBtn.on('click', function(e) {
                e.preventDefault();
                self.nextSlide(widgetId);
            });

            // Stop autoplay on user interaction
            slider.$wrapper.on('mouseenter', function() {
                if (slider.autoplay) {
                    self.stopAutoplay(widgetId);
                }
            });

            slider.$wrapper.on('mouseleave', function() {
                if (slider.autoplay) {
                    self.startAutoplay(widgetId);
                }
            });

            // Audio toggle buttons
            slider.$wrapper.find('.mn-audio-toggle').on('click', function(e) {
                e.preventDefault();
                self.toggleAudio($(this));
            });

            console.log('MN Dual Slider: Events bound for widget', widgetId);
        },

        nextSlide: function(widgetId) {
            var slider = this.instances[widgetId];
            if (!slider || slider.isTransitioning) return;

            var nextIndex = slider.currentIndex + 1;

            if (nextIndex >= slider.totalSlides) {
                if (slider.loop) {
                    nextIndex = 0;
                } else {
                    return;
                }
            }

            this.goToSlide(widgetId, nextIndex);
        },

        prevSlide: function(widgetId) {
            var slider = this.instances[widgetId];
            if (!slider || slider.isTransitioning) return;

            var prevIndex = slider.currentIndex - 1;

            if (prevIndex < 0) {
                if (slider.loop) {
                    prevIndex = slider.totalSlides - 1;
                } else {
                    return;
                }
            }

            this.goToSlide(widgetId, prevIndex);
        },

        goToSlide: function(widgetId, index) {
            var self = this;
            var slider = this.instances[widgetId];

            if (!slider || slider.isTransitioning) return;

            console.log('MN Dual Slider: Going to slide', index);

            slider.isTransitioning = true;

            // Update main slider
            slider.$mainSlides.removeClass('active');
            slider.$mainSlides.eq(index).addClass('active');

            // Update thumbnail slider (offset by 1)
            var thumbIndex = (index + 1) % slider.totalSlides;
            slider.$thumbSlides.removeClass('active');
            slider.$thumbSlides.eq(thumbIndex).addClass('active');

            // Update CTA section
            slider.$ctaSlides.removeClass('active');
            slider.$ctaSlides.eq(index).addClass('active');

            // Update caption
            this.updateCaption(widgetId, index);

            // Handle video playback
            this.handleVideoPlayback(widgetId, index);

            // Update current index
            slider.currentIndex = index;

            // Reset transitioning flag
            setTimeout(function() {
                slider.isTransitioning = false;
            }, slider.transitionSpeed);
        },

        updateCaption: function(widgetId, index) {
            var slider = this.instances[widgetId];
            if (!slider) return;

            var $slide = slider.$mainSlides.eq(index);
            var title = $slide.data('title');
            var subtitle = $slide.data('subtitle');

            // Check if slide has video
            var hasVideo = $slide.find('video, iframe.mn-slide-youtube').length > 0;

            if (hasVideo) {
                // Hide caption for video slides
                slider.$mainSlider.addClass('has-video');
            } else {
                // Show caption for image slides
                slider.$mainSlider.removeClass('has-video');
                
                // Update caption text with fade effect
                var $caption = slider.$mainSlider.find('.mn-slide-caption');
                var $titleEl = $caption.find('.mn-slide-title');
                var $subtitleEl = $caption.find('.mn-slide-subtitle');

                $titleEl.css('opacity', 0);
                $subtitleEl.css('opacity', 0);

                setTimeout(function() {
                    $titleEl.text(title).css('opacity', 1);
                    $subtitleEl.text(subtitle).css('opacity', 1);
                }, 200);
            }
        },

        initVideoHandling: function(widgetId) {
            var slider = this.instances[widgetId];
            if (!slider) return;

            // Initialize video states
            slider.$wrapper.find('.mn-slide-video').each(function() {
                var $video = $(this);
                var video = this;

                // Set initial muted state
                if (slider.videoMuted) {
                    video.muted = true;
                    $video.siblings('.mn-audio-toggle').find('i').removeClass('eicon-volume-high').addClass('eicon-volume-mute');
                } else {
                    video.muted = false;
                    $video.siblings('.mn-audio-toggle').find('i').removeClass('eicon-volume-mute').addClass('eicon-volume-high');
                }

                // Pause video initially if not active
                if (!$video.closest('.mn-slide').hasClass('active')) {
                    video.pause();
                }
            });

            console.log('MN Dual Slider: Video handling initialized');
        },

        handleVideoPlayback: function(widgetId, index) {
            var slider = this.instances[widgetId];
            if (!slider) return;

            // Pause all videos
            slider.$wrapper.find('.mn-slide-video').each(function() {
                this.pause();
            });

            // Play video in active slide
            var $activeSlide = slider.$mainSlides.eq(index);
            var $video = $activeSlide.find('.mn-slide-video');

            if ($video.length) {
                setTimeout(function() {
                    $video[0].play().catch(function(error) {
                        console.log('MN Dual Slider: Video autoplay prevented', error);
                    });
                }, 300);
            }
        },

        toggleAudio: function($button) {
            var $video = $button.siblings('.mn-slide-video');
            
            if ($video.length) {
                var video = $video[0];
                var $icon = $button.find('i');

                if (video.muted) {
                    video.muted = false;
                    video.volume = 1;
                    $icon.removeClass('eicon-volume-mute').addClass('eicon-volume-high');
                } else {
                    video.muted = true;
                    $icon.removeClass('eicon-volume-high').addClass('eicon-volume-mute');
                }
            }
        },

        startAutoplay: function(widgetId) {
            var self = this;
            var slider = this.instances[widgetId];

            if (!slider || !slider.autoplay) return;

            this.stopAutoplay(widgetId);

            slider.autoplayTimer = setInterval(function() {
                self.nextSlide(widgetId);
            }, slider.autoplaySpeed);

            console.log('MN Dual Slider: Autoplay started');
        },

        stopAutoplay: function(widgetId) {
            var slider = this.instances[widgetId];

            if (!slider) return;

            if (slider.autoplayTimer) {
                clearInterval(slider.autoplayTimer);
                slider.autoplayTimer = null;
                console.log('MN Dual Slider: Autoplay stopped');
            }
        },

        destroy: function(widgetId) {
            var slider = this.instances[widgetId];

            if (!slider) return;

            console.log('MN Dual Slider: Destroying widget', widgetId);

            // Stop autoplay
            this.stopAutoplay(widgetId);

            // Remove event listeners
            slider.$prevBtn.off('click');
            slider.$nextBtn.off('click');
            slider.$wrapper.off('mouseenter mouseleave');
            slider.$wrapper.find('.mn-audio-toggle').off('click');

            // Pause all videos
            slider.$wrapper.find('.mn-slide-video').each(function() {
                this.pause();
            });

            // Remove instance
            delete this.instances[widgetId];
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        $('.mn-dual-slider-wrapper').each(function() {
            MNDualSlider.init($(this));
        });
    });

    // Elementor frontend integration
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend === 'undefined') return;

        elementorFrontend.hooks.addAction('frontend/element_ready/mn-dual-slider.default', function($scope) {
            var $wrapper = $scope.find('.mn-dual-slider-wrapper');
            
            if ($wrapper.length) {
                var widgetId = $wrapper.data('widget-id');
                
                // Destroy existing instance
                if (widgetId && MNDualSlider.instances[widgetId]) {
                    MNDualSlider.destroy(widgetId);
                }
                
                // Initialize new instance
                MNDualSlider.init($wrapper);
            }
        });
    });

    // Expose to global scope for debugging
    window.MNDualSlider = MNDualSlider;

})(jQuery);
