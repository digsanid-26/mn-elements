/**
 * MN Elements Frontend JavaScript
 * 
 * @package mn-elements
 * @version 1.0.6
 */

(function($) {
    'use strict';

    // MNTriks Animation Handler - Following Elementor's Global Handler Pattern
    class MNTriksAnimationHandler extends elementorModules.frontend.handlers.Base {
        getWidgetType() {
            return 'global';
        }

        animate() {
            const $element = this.$element;
            const animation = this.getAnimation();
            
            if ('none' === animation || !animation) {
                $element.removeClass('mn-entrance-invisible');
                return;
            }

            const elementSettings = this.getElementSettings();
            const animationDelay = elementSettings.mn_entrance_delay || 0;
            const animationDuration = elementSettings.mn_entrance_duration || 1000;
            const animationEasing = elementSettings.mn_entrance_easing || 'easeOutQuart';

            // Remove any existing animation classes
            $element.removeClass(animation);
            if (this.currentAnimation) {
                $element.removeClass(this.currentAnimation);
            }
            
            this.currentAnimation = animation;

            // Apply animation with proper timing like Elementor
            setTimeout(() => {
                $element.removeClass('mn-entrance-invisible').addClass('mn-animated ' + animation);
                
                // Use anime.js for enhanced animations if available
                if (typeof anime !== 'undefined') {
                    const animationProps = this.getAnimationProperties(animation);
                    
                    anime({
                        targets: $element[0],
                        opacity: [animationProps.from.opacity, 1],
                        scale: animationProps.scale ? [animationProps.from.scale, 1] : undefined,
                        translateX: animationProps.translateX ? [animationProps.from.translateX, 0] : undefined,
                        translateY: animationProps.translateY ? [animationProps.from.translateY, 0] : undefined,
                        duration: animationDuration,
                        easing: this.convertEasing(animationEasing),
                        complete: () => {
                            $element.removeClass('mn-animating');
                            $element[0].style.transform = '';
                            $element[0].style.opacity = '';
                        }
                    });
                }
            }, animationDelay);
        }

        getAnimation() {
            return this.getCurrentDeviceSetting('mn_entrance_animation_type') || 
                   this.getElementSettings('mn_entrance_animation_type');
        }

        getAnimationProperties(type) {
            const properties = {
                from: { opacity: 0 }
            };

            switch (type) {
                case 'zoom-out':
                    properties.scale = true;
                    properties.from.scale = 1.08;
                    break;
                case 'zoom-in':
                    properties.scale = true;
                    properties.from.scale = 0.8;
                    break;
                case 'fade-in':
                    break;
                case 'slide-up':
                    properties.translateY = true;
                    properties.from.translateY = 50;
                    break;
                case 'slide-down':
                    properties.translateY = true;
                    properties.from.translateY = -50;
                    break;
                case 'slide-left':
                    properties.translateX = true;
                    properties.from.translateX = 50;
                    break;
                case 'slide-right':
                    properties.translateX = true;
                    properties.from.translateX = -50;
                    break;
            }

            return properties;
        }

        convertEasing(easing) {
            const easingMap = {
                'linear': 'linear',
                'easeInQuad': 'easeInQuad',
                'easeOutQuad': 'easeOutQuad',
                'easeInOutQuad': 'easeInOutQuad',
                'easeInCubic': 'easeInCubic',
                'easeOutCubic': 'easeOutCubic',
                'easeInOutCubic': 'easeInOutCubic',
                'easeInQuart': 'easeInQuart',
                'easeOutQuart': 'easeOutQuart',
                'easeInOutQuart': 'easeInOutQuart'
            };
            
            return easingMap[easing] || 'easeOutCubic';
        }

        onInit() {
            super.onInit(...arguments);
            
            // Only initialize if element has MNTriks animation
            if (this.getAnimation() && this.$element.hasClass('mn-entrance-animation')) {
                // Set initial invisible state like Elementor
                this.$element.addClass('mn-entrance-invisible');
                
                // Use Elementor's scroll observer pattern
                const observer = elementorModules.utils.Scroll.scrollObserver({
                    callback: (event) => {
                        if (event.isInViewport) {
                            this.animate();
                            observer.unobserve(this.$element[0]);
                        }
                    }
                });
                
                observer.observe(this.$element[0]);
            }
        }

        onElementChange(propertyName) {
            // Re-animate when MNTriks settings change
            if (/^mn_entrance/.test(propertyName)) {
                this.animate();
            }
        }
    }

    // Initialize MNTriks Handler following Elementor's pattern
    function initializeMNTriksHandler() {
        // Register the MNTriks handler with Elementor's frontend system
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.elementsHandler) {
            // Add handler for all global elements (containers, sections, columns)
            elementorFrontend.hooks.addAction('frontend/element_ready/global', function($scope) {
                // Register MNTriks handler for this element
                elementorFrontend.elementsHandler.addHandler(MNTriksAnimationHandler, {
                    $element: $scope
                });
            });
        }
    }

    // Legacy MNElements object for backward compatibility
    var MNElements = {
        
        animationQueue: [],
        isInitialized: false,
        uniqueId: 0,

        init: function() {
            if (this.isInitialized) {
                return;
            }
            this.isInitialized = true;
            this.initEntranceAnimations();
        },

        initEntranceAnimations: function() {
            // Clear any existing animations
            this.clearExistingAnimations();
            
            if (typeof MNElementsSettings === 'undefined' || !MNElementsSettings.elements_data.containers) {
                // Show all elements if no animation data
                this.showFallbackElements();
                return;
            }

            var containers = MNElementsSettings.elements_data.containers;
            var self = this;

            // Reset and prepare all animation elements
            this.prepareAnimationElements(containers);

            // Create intersection observer for better performance
            if ('IntersectionObserver' in window) {
                // Destroy existing observer if exists
                if (this.observer) {
                    this.observer.disconnect();
                }
                
                this.observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var elementId = entry.target.getAttribute('data-id');
                            if (containers[elementId] && !entry.target.classList.contains('mn-animated')) {
                                self.queueAnimation(entry.target, containers[elementId]);
                                self.observer.unobserve(entry.target);
                            }
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                });

                // Observe all elements with entrance animations
                Object.keys(containers).forEach(function(elementId) {
                    var element = document.querySelector('[data-id="' + elementId + '"]');
                    if (element && element.classList.contains('mn-entrance-animation')) {
                        self.observer.observe(element);
                    }
                });
            } else {
                // Fallback for browsers without IntersectionObserver
                this.initScrollAnimations(containers);
            }
        },

        clearExistingAnimations: function() {
            // Clear animation queue
            this.animationQueue = [];
            
            // Reset all animated elements
            var animatedElements = document.querySelectorAll('.mn-entrance-animation');
            animatedElements.forEach(function(element) {
                element.classList.remove('mn-animated', 'mn-animation-initialized', 'mn-animating');
                element.style.opacity = '0';
                element.style.transform = '';
                element.style.transition = '';
                element.removeAttribute('data-mn-anim-id');
                
                // Stop any running anime.js animations
                if (typeof anime !== 'undefined') {
                    anime.remove(element);
                }
            });
            
            // Disconnect existing observer
            if (this.observer) {
                this.observer.disconnect();
                this.observer = null;
            }
        },

        prepareAnimationElements: function(containers) {
            var self = this;
            // Prepare elements for animation and assign unique IDs to prevent conflicts
            Object.keys(containers).forEach(function(elementId) {
                var element = document.querySelector('[data-id="' + elementId + '"]');
                if (element && element.classList.contains('mn-entrance-animation')) {
                    // Assign unique animation ID to prevent conflicts
                    var uniqueAnimationId = 'mn-anim-' + (++self.uniqueId);
                    element.setAttribute('data-mn-anim-id', uniqueAnimationId);
                    
                    // Set initial state based on animation type
                    var animationType = containers[elementId].animation_type || 'zoom-out';
                    var initialProps = self.getInitialAnimationState(animationType);
                    
                    element.style.opacity = initialProps.opacity;
                    if (initialProps.transform) {
                        element.style.transform = initialProps.transform;
                    }
                    
                    element.classList.add('mn-animation-initialized');
                }
            });
        },

        getInitialAnimationState: function(type) {
            var state = { opacity: '0' };
            
            switch (type) {
                case 'zoom-out':
                    state.transform = 'scale(1.08)';
                    break;
                case 'zoom-in':
                    state.transform = 'scale(0.8)';
                    break;
                case 'fade-in':
                    // Only opacity
                    break;
                case 'slide-up':
                    state.transform = 'translateY(50px)';
                    break;
                case 'slide-down':
                    state.transform = 'translateY(-50px)';
                    break;
                case 'slide-left':
                    state.transform = 'translateX(50px)';
                    break;
                case 'slide-right':
                    state.transform = 'translateX(-50px)';
                    break;
            }
            
            return state;
        },

        queueAnimation: function(element, settings) {
            var animationId = element.getAttribute('data-mn-anim-id');
            
            // Check if animation is already queued or completed
            var existingAnimation = this.animationQueue.find(function(anim) {
                return anim.id === animationId;
            });
            
            if (existingAnimation || element.classList.contains('mn-animated')) {
                return;
            }
            
            // Add to queue
            this.animationQueue.push({
                id: animationId,
                element: element,
                settings: settings,
                timestamp: Date.now()
            });
            
            // Process queue
            this.processAnimationQueue();
        },

        processAnimationQueue: function() {
            var self = this;
            
            // Process animations with proper delays
            this.animationQueue.forEach(function(animation, index) {
                if (!animation.processed) {
                    animation.processed = true;
                    
                    setTimeout(function() {
                        if (animation.element && !animation.element.classList.contains('mn-animated')) {
                            self.animateElement(animation.element, animation.settings);
                        }
                    }, animation.settings.delay || 0);
                }
            });
        },

        markAnimationElements: function(containers) {
            // Mark elements as initialized to prevent fallback from showing
            Object.keys(containers).forEach(function(elementId) {
                var element = document.querySelector('[data-id="' + elementId + '"]');
                if (element && element.classList.contains('mn-entrance-animation')) {
                    element.classList.add('mn-animation-initialized');
                }
            });
        },

        showFallbackElements: function() {
            // Show all animation elements if no data is available
            var elements = document.querySelectorAll('.mn-entrance-animation');
            elements.forEach(function(element) {
                element.style.opacity = '1';
                element.style.transform = 'none';
            });
        },

        animateElement: function(element, settings) {
            var animationType = settings.animation_type || 'zoom-out';
            var delay = settings.delay || 0;
            var duration = settings.duration || 1000;
            var easing = settings.easing || 'easeOutQuart';
            var animationId = element.getAttribute('data-mn-anim-id');

            // Prevent duplicate animations
            if (element.classList.contains('mn-animated') || element.classList.contains('mn-animating')) {
                return;
            }

            // Mark as animating to prevent conflicts
            element.classList.add('mn-animating');

            // Set initial animation properties based on type
            var animationProps = this.getAnimationProperties(animationType);

            // Use anime.js for smooth animations
            if (typeof anime !== 'undefined') {
                // Convert easing to anime.js format for smoother animations
                var animeEasing = this.convertEasing(easing);
                
                // Stop any existing animations on this element
                anime.remove(element);
                
                anime({
                    targets: element,
                    opacity: [animationProps.from.opacity, 1],
                    scale: animationProps.scale ? [animationProps.from.scale, 1] : undefined,
                    translateX: animationProps.translateX ? [animationProps.from.translateX, 0] : undefined,
                    translateY: animationProps.translateY ? [animationProps.from.translateY, 0] : undefined,
                    duration: duration,
                    delay: 0, // Delay is handled in queue
                    easing: animeEasing,
                    begin: function() {
                        element.classList.add('mn-animating');
                    },
                    complete: function() {
                        element.classList.remove('mn-animating');
                        element.classList.add('mn-animated');
                        element.style.opacity = '1';
                        element.style.transform = 'none';
                    }
                });
            } else {
                // Fallback CSS animation with safety timeout
                element.style.transition = 'all ' + (duration / 1000) + 's ease-out';
                element.style.opacity = '1';
                element.style.transform = 'none';
                element.classList.remove('mn-animating');
                element.classList.add('mn-animated');
                
                // Safety timeout to ensure element is visible
                setTimeout(function() {
                    if (!element.classList.contains('mn-animated')) {
                        element.style.opacity = '1';
                        element.style.transform = 'none';
                        element.classList.add('mn-animated');
                    }
                    element.classList.remove('mn-animating');
                }, duration + 100);
            }
        },

        getAnimationProperties: function(type) {
            var properties = {
                from: { opacity: 0 }
            };

            switch (type) {
                case 'zoom-out':
                    properties.scale = true;
                    properties.from.scale = 1.08;
                    break;
                case 'zoom-in':
                    properties.scale = true;
                    properties.from.scale = 0.8;
                    break;
                case 'fade-in':
                    // Only opacity change
                    break;
                case 'slide-up':
                    properties.translateY = true;
                    properties.from.translateY = 50;
                    break;
                case 'slide-down':
                    properties.translateY = true;
                    properties.from.translateY = -50;
                    break;
                case 'slide-left':
                    properties.translateX = true;
                    properties.from.translateX = 50;
                    break;
                case 'slide-right':
                    properties.translateX = true;
                    properties.from.translateX = -50;
                    break;
            }

            return properties;
        },

        convertEasing: function(easing) {
            // Convert custom easing names to anime.js easing for smoother animations
            var easingMap = {
                'linear': 'linear',
                'easeInQuad': 'easeInQuad',
                'easeOutQuad': 'easeOutQuad',
                'easeInOutQuad': 'easeInOutQuad',
                'easeInCubic': 'easeInCubic',
                'easeOutCubic': 'easeOutCubic',
                'easeInOutCubic': 'easeInOutCubic',
                'easeInQuart': 'easeInQuart',
                'easeOutQuart': 'easeOutQuart',
                'easeInOutQuart': 'easeInOutQuart'
            };
            
            return easingMap[easing] || 'easeOutCubic';
        },

        initScrollAnimations: function(containers) {
            var self = this;
            var $window = $(window);
            
            function checkAnimations() {
                Object.keys(containers).forEach(function(elementId) {
                    var $element = $('[data-id="' + elementId + '"]');
                    if ($element.length && $element.hasClass('mn-entrance-animation') && !$element.hasClass('mn-animated') && !$element.hasClass('mn-animating')) {
                        var elementTop = $element.offset().top;
                        var elementBottom = elementTop + $element.outerHeight();
                        var viewportTop = $window.scrollTop();
                        var viewportBottom = viewportTop + $window.height();

                        if (elementBottom > viewportTop && elementTop < viewportBottom) {
                            self.queueAnimation($element[0], containers[elementId]);
                        }
                    }
                });
            }

            $window.on('scroll.mnElements resize.mnElements', checkAnimations);
            checkAnimations(); // Check on load
        },

        // Reset animations for page refresh/reload
        resetAnimations: function() {
            this.isInitialized = false;
            this.uniqueId = 0;
            this.clearExistingAnimations();
            
            // Remove event listeners
            $(window).off('.mnElements');
            
            // Force a small delay to ensure DOM is ready
            var self = this;
            setTimeout(function() {
                self.init();
            }, 100);
        }
    };

    // Single initialization point to prevent conflicts
    var initializationHandled = false;

    // Initialize when DOM is ready
    $(document).ready(function() {
        if (initializationHandled) return;
        initializationHandled = true;
        
        // Force reset on DOM ready to ensure fresh state
        MNElements.isInitialized = false;
        MNElements.uniqueId = 0;
        MNElements.animationQueue = [];
        
        // Add a small delay to ensure all elements are rendered
        setTimeout(function() {
            MNElements.init();
        }, 50);
    });

    // Initialize when Elementor frontend is ready
    var initializationHandled = false;

    function initializeMNElements() {
        if (initializationHandled) {
            return;
        }
        initializationHandled = true;
        
        // Initialize new MNTriks handler system
        initializeMNTriksHandler();
        
        // Initialize legacy MN Elements for backward compatibility
        MNElements.init();
        
        // Reset animations for any elements that might be already visible
        MNElements.resetAnimations();
    }

    // Primary initialization - DOM ready
    $(document).ready(function() {
        // Check if Elementor frontend is available
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            // Also hook into components init for better timing
            elementorFrontend.on('components:init', function() {
                initializeMNElements();
            });
        }
        
        // Fallback initialization
        setTimeout(initializeMNElements, 100);
    });

    // Additional event listeners for various loading scenarios
    $(window).on('load', function() {
        // Reset animations on window load
        if (typeof MNElements !== 'undefined') {
            MNElements.resetAnimations();
        }
    });

    // Handle page navigation and AJAX loads
    $(document).one('elementor/frontend/init', function() {
        initializeMNElements();
    });

    // Global reset function for manual control
    window.MNElementsReset = function() {
        if (typeof MNElements !== 'undefined') {
            MNElements.resetAnimations();
        }
    };

})(jQuery);
