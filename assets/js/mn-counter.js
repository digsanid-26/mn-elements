/**
 * MN Counter Animation Script
 * 
 * Custom counter animation functionality for MN Counter widget
 */

(function($) {
    'use strict';

    /**
     * Counter Animation Class
     */
    class MNCounter {
        constructor(element) {
            this.element = element;
            this.counter = this.element.find('.mn-counter-number');
            this.init();
        }

        init() {
            if (this.counter.length) {
                this.setupIntersectionObserver();
            }
        }

        setupIntersectionObserver() {
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.startAnimation();
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.5
                });

                observer.observe(this.element[0]);
            } else {
                // Fallback for older browsers
                this.startAnimation();
            }
        }

        startAnimation() {
            const counter = this.counter;
            const fromValue = parseInt(counter.data('from-value')) || 0;
            const toValue = parseInt(counter.data('to-value')) || 100;
            const duration = parseInt(counter.data('duration')) || 2000;
            const delimiter = counter.data('delimiter') || '';

            this.animateCounter(counter, fromValue, toValue, duration, delimiter);
        }

        animateCounter(element, from, to, duration, delimiter) {
            const startTime = performance.now();
            const difference = to - from;

            const animate = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function (ease-out)
                const easeOut = 1 - Math.pow(1 - progress, 3);
                const currentValue = Math.floor(from + (difference * easeOut));
                
                // Format number with delimiter if specified
                let formattedValue = currentValue.toString();
                if (delimiter && currentValue >= 1000) {
                    formattedValue = this.addDelimiter(currentValue, delimiter);
                }
                
                element.text(formattedValue);

                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    // Ensure final value is exact
                    let finalValue = to.toString();
                    if (delimiter && to >= 1000) {
                        finalValue = this.addDelimiter(to, delimiter);
                    }
                    element.text(finalValue);
                }
            };

            requestAnimationFrame(animate);
        }

        addDelimiter(number, delimiter) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, delimiter);
        }
    }

    /**
     * Initialize counters when DOM is ready
     */
    const initCounters = () => {
        $('.mn-counter').each(function() {
            if (!$(this).data('mn-counter-initialized')) {
                new MNCounter($(this));
                $(this).data('mn-counter-initialized', true);
            }
        });
    };

    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        initCounters();
    });

    /**
     * Initialize for Elementor frontend
     */
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-counter.default', function($scope) {
            if (!$scope.find('.mn-counter').data('mn-counter-initialized')) {
                new MNCounter($scope.find('.mn-counter'));
                $scope.find('.mn-counter').data('mn-counter-initialized', true);
            }
        });
    });

    /**
     * Reinitialize on AJAX load or dynamic content
     */
    $(document).on('mn-elements:reinit', function() {
        initCounters();
    });

    /**
     * Global reset function
     */
    window.MNCounterReset = function() {
        $('.mn-counter').removeData('mn-counter-initialized');
        initCounters();
    };

})(jQuery);
