/**
 * MN Heading Text Animation Handler
 * 
 * Handles character and word-based text animations for MN Heading widget
 * 
 * @package mn-elements
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // MN Heading Text Animation Handler
    class MNHeadingTextAnimationHandler {
        
        constructor() {
            this.animatedElements = new Set();
            this.observers = new Map();
            this.isInitialized = false;
            this.scrollOutTimeouts = new Map(); // Track scroll out timeouts per element
            this.init();
        }

        init() {
            if (this.isInitialized) {
                return;
            }
            
            this.isInitialized = true;
            this.setupAnimations();
            this.bindEvents();
        }

        setupAnimations() {
            // Find all elements with text animation enabled
            const animatedElements = document.querySelectorAll('.mn-text-animation-enabled');
            console.log('Found animated elements:', animatedElements.length);
            
            animatedElements.forEach((element, index) => {
                console.log(`Processing element ${index}:`, element);
                if (!this.animatedElements.has(element)) {
                    this.setupElementAnimation(element);
                    this.animatedElements.add(element);
                } else {
                    console.log('Element already processed:', element);
                }
            });
        }

        setupElementAnimation(element) {
            // Get animation settings from data attribute
            const animationData = element.getAttribute('data-mn-text-animation');
            if (!animationData) {
                return;
            }

            try {
                const settings = JSON.parse(animationData);
                const uniqueId = settings.uniqueId || 'unknown';
                console.log('Animation settings loaded [' + uniqueId + ']:', settings);
                
                // Store settings on element
                element.mnAnimationSettings = settings;
                element.mnUniqueId = uniqueId;
                
                // Process text into units (characters or words)
                this.processTextUnits(element, settings);
                
                // Set up trigger based on settings
                this.setupTrigger(element, settings);
                
            } catch (error) {
                console.warn('MN Heading: Invalid animation data', error);
            }
        }

        processTextUnits(element, settings) {
            const text = element.textContent;
            const unit = settings.unit || 'character';
            
            // Clear existing content
            element.innerHTML = '';
            
            // Create wrapper for animation with unique namespace
            const wrapper = document.createElement('span');
            wrapper.className = 'mn-heading-text-animation';
            wrapper.setAttribute('data-animation-type', settings.type);
            wrapper.setAttribute('data-animation-trigger', settings.trigger);
            
            // Split text into units and wrap them
            if (unit === 'character') {
                const chars = text.split('');
                chars.forEach((char, index) => {
                    const span = document.createElement('span');
                    span.className = 'mn-heading-char';
                    span.textContent = char === ' ' ? '\u00A0' : char; // Use non-breaking space
                    span.style.transitionDelay = `${index * settings.stagger}ms`;
                    wrapper.appendChild(span);
                });
            } else if (unit === 'word') {
                const words = text.split(' ');
                words.forEach((word, index) => {
                    const span = document.createElement('span');
                    span.className = 'mn-heading-word';
                    span.textContent = word;
                    span.style.transitionDelay = `${index * settings.stagger}ms`;
                    wrapper.appendChild(span);
                    
                    // Add space after word (except last)
                    if (index < words.length - 1) {
                        const space = document.createElement('span');
                        space.className = 'mn-heading-char';
                        space.textContent = ' ';
                        space.style.transitionDelay = `${(index + 0.5) * settings.stagger}ms`;
                        wrapper.appendChild(space);
                    }
                });
            }
            
            element.appendChild(wrapper);
            element.mnAnimationWrapper = wrapper;
        }

        setupTrigger(element, settings) {
            const trigger = settings.trigger || 'on_scroll';
            
            switch (trigger) {
                case 'on_load':
                    this.setupLoadTrigger(element, settings);
                    break;
                case 'on_scroll':
                    this.setupScrollTrigger(element, settings);
                    break;
                case 'on_hover':
                    this.setupHoverTrigger(element, settings);
                    break;
                case 'manual':
                    // Manual trigger - do nothing until called
                    break;
            }
        }

        setupLoadTrigger(element, settings) {
            // Start animation after initial delay
            setTimeout(() => {
                this.animateElement(element, settings);
            }, settings.initialDelay || 0);
        }

        setupScrollTrigger(element, settings) {
            // Use IntersectionObserver for scroll trigger
            if (!('IntersectionObserver' in window)) {
                // Fallback for older browsers
                setTimeout(() => {
                    this.animateElement(element, settings);
                }, settings.initialDelay || 0);
                return;
            }

            const scrollSettings = settings.scroll || {};
            const threshold = scrollSettings.threshold || 0.05;
            const offset = scrollSettings.offset || 50;
            const playOnce = scrollSettings.playOnce === 'yes'; // Explicit check for 'yes'
            const reverseOnExit = scrollSettings.reverseOnExit === 'yes';
            const outBehavior = scrollSettings.outBehavior || 'none';
            const uniqueId = element.mnUniqueId || 'unknown';

            // Find the parent MN Heading widget (elementor-widget-mn-heading)
            const widgetElement = element.closest('.elementor-widget-mn-heading');
            const targetElement = widgetElement || element; // Fallback to element if widget not found
            
            console.log('Setting up scroll observer for [' + uniqueId + ']:', {
                targetElement: targetElement,
                isWidget: !!widgetElement,
                element: element
            });

            // Create root margin based on offset - make it more sensitive
            const rootMargin = `${offset}px 0px ${offset}px 0px`;

            // Track element state
            let hasEnteredViewport = false;
            let hasExitedViewport = false;

            // Use multiple thresholds for better detection
            const thresholds = Array.from({length: 21}, (_, i) => i * 0.05).filter(t => t >= threshold - 0.1 && t <= threshold + 0.1);
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    console.log('IntersectionObserver [' + uniqueId + ']:', {
                        isIntersecting: entry.isIntersecting,
                        intersectionRatio: entry.intersectionRatio,
                        boundingClientRect: entry.boundingClientRect,
                        hasEnteredViewport,
                        hasExitedViewport,
                        playOnce: playOnce,
                        outBehavior: outBehavior,
                        reverseOnExit: reverseOnExit,
                        targetElement: targetElement.className,
                        element: element.textContent.substring(0, 20)
                    });
                    
                    // Scroll IN behavior - trigger when widget enters viewport
                    if (entry.isIntersecting && entry.intersectionRatio >= threshold && !hasEnteredViewport) {
                        console.log('Widget entering viewport - triggering animation [' + uniqueId + ']');
                        this.animateElement(element, settings);
                        hasEnteredViewport = true;
                        hasExitedViewport = false; // Reset exit state
                        
                        // Reset scroll out classes when entering
                        this.removeScrollOutClasses(element);
                        
                        if (playOnce) {
                            observer.unobserve(targetElement);
                            this.observers.set(element, null); // Mark as disconnected
                        }
                    }
                    
                    // Scroll OUT behavior - trigger on first detection of leaving viewport
                    else if (!entry.isIntersecting && hasEnteredViewport && !hasExitedViewport && !playOnce) {
                        console.log('Widget first detected leaving viewport - triggering scroll out [' + uniqueId + ']');
                        hasExitedViewport = true;
                        
                        if (outBehavior !== 'none') {
                            // Handle scroll out behavior
                            this.handleScrollOut(element, settings);
                        } else if (reverseOnExit) {
                            // Legacy reverse animation support
                            this.reverseAnimation(element, settings);
                        }
                    }
                    
                    // Handle re-entering viewport after exit (for non-playOnce)
                    else if (entry.isIntersecting && entry.intersectionRatio >= threshold && hasEnteredViewport && hasExitedViewport && !playOnce) {
                        console.log('Widget re-entering viewport - triggering animation again [' + uniqueId + ']');
                        this.animateElement(element, settings);
                        hasExitedViewport = false; // Reset exit state
                        
                        // Reset scroll out classes when entering
                        this.removeScrollOutClasses(element);
                    }
                });
            }, {
                threshold: thresholds.length > 0 ? thresholds : [threshold],
                rootMargin: rootMargin
            });

            observer.observe(targetElement);
            this.observers.set(element, observer);
        }

        handleScrollOut(element, settings) {
            const scrollSettings = settings.scroll || {};
            const outBehavior = scrollSettings.outBehavior || 'none';
            const outDirection = scrollSettings.outDirection || 'down';
            const outDuration = scrollSettings.outDuration || 400;
            const uniqueId = element.mnUniqueId || 'unknown';

            console.log('handleScrollOut called [' + uniqueId + ']:', {
                outBehavior,
                outDirection,
                outDuration,
                element: element,
                wrapper: element.mnAnimationWrapper
            });

            if (outBehavior === 'none') {
                console.log('Scroll out behavior is none, skipping [' + uniqueId + ']');
                return;
            }

            if (!element.mnAnimationWrapper) {
                console.log('No animation wrapper found [' + uniqueId + ']');
                return;
            }

            // Clear existing timeout for this element (debouncing)
            if (this.scrollOutTimeouts.has(element)) {
                clearTimeout(this.scrollOutTimeouts.get(element));
            }

            // Debounce scroll out animation to avoid too frequent triggers
            const timeoutId = setTimeout(() => {
                console.log('Executing scroll out animation [' + uniqueId + ']');
                
                // Add scroll out class and data attributes
            this.scrollOutTimeouts.set(element, timeoutId);
        }

        removeScrollOutClasses(element) {
            if (!element.mnAnimationWrapper) {
                return;
            }

            element.mnAnimationWrapper.classList.remove('mn-text-animation-scroll-out');
            element.mnAnimationWrapper.removeAttribute('data-out-behavior');
            element.mnAnimationWrapper.removeAttribute('data-out-direction');

            const units = element.mnAnimationWrapper.querySelectorAll('.mn-char, .mn-word');
            units.forEach(unit => {
                unit.classList.remove('mn-scroll-out-animated');
                unit.style.transitionDuration = '';
            });
        }

        setupHoverTrigger(element, settings) {
            element.addEventListener('mouseenter', () => {
                this.animateElement(element, settings);
            }, { once: true }); // Only trigger once
        }

        animateElement(element, settings, isReverse = false) {
            if (!element.mnAnimationWrapper) {
                return;
            }

            const units = element.mnAnimationWrapper.querySelectorAll('.mn-heading-char, .mn-heading-word');
            const duration = settings.duration || 600;
            const loopSettings = settings.loop || {};
            
            // Clear any existing loop timeouts
            if (element.mnLoopTimeout) {
                clearTimeout(element.mnLoopTimeout);
                element.mnLoopTimeout = null;
            }
            
            // Add animation class to wrapper
            element.mnAnimationWrapper.classList.add('mn-animating');
            
            // Animate each unit with stagger
            units.forEach((unit, index) => {
                const delay = isReverse ? 
                    (units.length - 1 - index) * (settings.stagger || 100) :
                    index * (settings.stagger || 100);
                
                setTimeout(() => {
                    if (isReverse) {
                        unit.classList.remove('mn-animated');
                    } else {
                        unit.classList.add('mn-animated');
                    }
                }, delay);
            });

            // Clean up after animation completes
            const totalAnimationTime = duration + (units.length * (settings.stagger || 100));
            setTimeout(() => {
                element.mnAnimationWrapper.classList.remove('mn-animating');
                
                if (!isReverse) {
                    this.onAnimationComplete(element, settings);
                    
                    // Handle looping
                    this.handleLooping(element, settings);
                }
            }, totalAnimationTime);
        }

        reverseAnimation(element, settings) {
            this.animateElement(element, settings, true);
        }

        handleLooping(element, settings) {
            const loopSettings = settings.loop || {};
            const loopType = loopSettings.type || 'none';
            
            if (loopType === 'none') {
                return;
            }

            // Initialize loop counter if not exists
            if (!element.mnLoopCount) {
                element.mnLoopCount = 0;
            }

            element.mnLoopCount++;

            // Check if we should continue looping
            if (loopType === 'count' && element.mnLoopCount >= (loopSettings.count || 3)) {
                // Reset counter for future use
                element.mnLoopCount = 0;
                return;
            }

            // Schedule next loop
            const loopDelay = loopSettings.delay || 2000;
            const shouldReverse = loopSettings.reverse === 'yes';

            element.mnLoopTimeout = setTimeout(() => {
                if (shouldReverse) {
                    // Reverse animation first, then forward again
                    this.reverseAnimation(element, settings);
                    
                    // Schedule forward animation after reverse completes
                    const totalAnimationTime = settings.duration + (element.mnAnimationWrapper.querySelectorAll('.mn-char, .mn-word').length * (settings.stagger || 100));
                    setTimeout(() => {
                        this.animateElement(element, settings, false);
                    }, totalAnimationTime + 200); // Small pause between reverse and forward
                } else {
                    // Just play forward again
                    this.animateElement(element, settings, false);
                }
            }, loopDelay);
        }

        onAnimationComplete(element, settings) {
            // Trigger custom event if needed
            const event = new CustomEvent('mnTextAnimationComplete', {
                detail: { element, settings }
            });
            element.dispatchEvent(event);
        }

        // Public method to manually trigger animation
        triggerAnimation(element) {
            if (element && element.mnAnimationSettings) {
                this.animateElement(element, element.mnAnimationSettings);
            }
        }

        // Public method to reset animation
        resetAnimation(element) {
            if (!element.mnAnimationWrapper) {
                return;
            }

            // Clear any loop timeouts
            if (element.mnLoopTimeout) {
                clearTimeout(element.mnLoopTimeout);
                element.mnLoopTimeout = null;
            }

            // Clear any scroll out timeouts
            if (this.scrollOutTimeouts.has(element)) {
                clearTimeout(this.scrollOutTimeouts.get(element));
                this.scrollOutTimeouts.delete(element);
            }

            // Remove scroll out classes
            this.removeScrollOutClasses(element);

            // Reset loop counter
            element.mnLoopCount = 0;

            const units = element.mnAnimationWrapper.querySelectorAll('.mn-char, .mn-word');
            units.forEach(unit => {
                unit.classList.remove('mn-animated', 'mn-scroll-out-animated');
                unit.style.transitionDuration = '';
            });
            element.mnAnimationWrapper.classList.remove('mn-animating');
        }

        // Re-initialize for dynamic content
        reinit() {
            this.animatedElements.clear();
            
            // Disconnect existing observers
            this.observers.forEach((observer, element) => {
                if (observer) {
                    observer.disconnect();
                }
            });
            this.observers.clear();
            
            // Clear scroll out timeouts
            this.scrollOutTimeouts.forEach(timeoutId => {
                clearTimeout(timeoutId);
            });
            this.scrollOutTimeouts.clear();
            
            // Re-setup animations
            setTimeout(() => {
                this.setupAnimations();
            }, 100);
        }

        bindEvents() {
            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    this.reinit();
                }, 250);
            });
        }

        destroy() {
            // Clean up observers
            this.observers.forEach((observer, element) => {
                if (observer) {
                    observer.disconnect();
                }
            });
            this.observers.clear();
            this.animatedElements.clear();
            
            // Clear scroll out timeouts
            this.scrollOutTimeouts.forEach(timeoutId => {
                clearTimeout(timeoutId);
            });
            this.scrollOutTimeouts.clear();
            
            this.isInitialized = false;
        }
    }

    // Initialize when DOM is ready
    let mnHeadingTextAnimation = null;

    function initMNHeadingTextAnimation() {
        if (mnHeadingTextAnimation) {
            mnHeadingTextAnimation.destroy();
        }
        mnHeadingTextAnimation = new MNHeadingTextAnimationHandler();
        
        // Make it globally accessible
        window.MNHeadingTextAnimation = mnHeadingTextAnimation;
    }

    // Multiple initialization points for reliability
    $(document).ready(initMNHeadingTextAnimation);
    
    // Elementor specific initialization
    $(document).on('elementor/frontend/init', function() {
        setTimeout(initMNHeadingTextAnimation, 100);
    });

    // Handle AJAX navigation
    $(document).on('elementor/ajax/load', function() {
        setTimeout(() => {
            if (mnHeadingTextAnimation) {
                mnHeadingTextAnimation.reinit();
            }
        }, 100);
    });

    // Global reset function
    window.MNHeadingAnimationReset = function() {
        if (mnHeadingTextAnimation) {
            mnHeadingTextAnimation.reinit();
        }
    };

})(jQuery);
