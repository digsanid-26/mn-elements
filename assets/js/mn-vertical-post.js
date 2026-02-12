/**
 * MN Vertical Post Widget JavaScript
 * Handles vertical carousel, sticky scroll, and interactions
 */

(function($) {
    'use strict';

    class MNVerticalPost {
        constructor(element) {
            this.$wrapper = $(element);
            this.$container = this.$wrapper.find('.mn-vpost-container');
            this.$track = this.$wrapper.find('.mn-vpost-track');
            this.$items = this.$wrapper.find('.mn-vpost-item');
            this.$arrowUp = this.$wrapper.find('.mn-vpost-arrow-up');
            this.$arrowDown = this.$wrapper.find('.mn-vpost-arrow-down');
            
            // Get settings from data attribute
            this.settings = this.$wrapper.data('settings') || {};
            
            // State
            this.currentIndex = 0;
            this.totalItems = this.$items.length;
            this.isAnimating = false;
            this.autoplayTimer = null;
            this.wheelTimeout = null;
            this.touchStartY = 0;
            this.touchEndY = 0;
            
            // Calculate visible percentage
            this.visiblePercentage = this.settings.visiblePercentage || 20;
            this.infinityLoop = this.settings.infinityLoop !== false;
            
            // Item height settings
            this.itemHeightValue = this.settings.itemHeight || 60;
            this.itemHeightUnit = this.settings.itemHeightUnit || 'vh';
            
            // Item gap settings
            this.itemGapValue = this.settings.itemGap || 0;
            this.itemGapUnit = this.settings.itemGapUnit || 'px';
            
            this.init();
        }
        
        init() {
            if (this.totalItems === 0) return;
            
            // Check if carousel should be disabled on mobile
            this.isMobile = window.innerWidth <= 767;
            this.disableCarouselMobile = this.settings.disableCarouselMobile === true;
            
            if (this.isMobile && this.disableCarouselMobile) {
                // Mobile listing mode - don't initialize carousel
                this.$wrapper.addClass('mn-vpost-mobile-active');
                this.bindMobileListingEvents();
                return;
            }
            
            // Setup initial state
            this.currentIndex = 0; // Ensure we start at first slide
            this.setupItems();
            this.updateActiveItem(0);
            this.updatePosition(false); // Set initial position without animation
            this.applyOverlayGradient();
            
            // Bind events
            this.bindEvents();
            
            // Start autoplay if enabled
            if (this.settings.autoplay) {
                this.startAutoplay();
            }
            
            // Update arrows state
            this.updateArrows();
        }
        
        bindMobileListingEvents() {
            // Only bind resize to check if we need to reinitialize
            $(window).on('resize', () => this.handleMobileResize());
        }
        
        handleMobileResize() {
            const wasMobile = this.isMobile;
            this.isMobile = window.innerWidth <= 767;
            
            // If switched from mobile to desktop, reinitialize carousel
            if (wasMobile && !this.isMobile && this.disableCarouselMobile) {
                this.$wrapper.removeClass('mn-vpost-mobile-active');
                this.currentIndex = 0;
                this.setupItems();
                this.updateActiveItem(0);
                this.updatePosition(false);
                this.applyOverlayGradient();
                this.bindEvents();
                this.updateArrows();
            }
            // If switched from desktop to mobile, disable carousel
            else if (!wasMobile && this.isMobile && this.disableCarouselMobile) {
                this.$wrapper.addClass('mn-vpost-mobile-active');
                this.stopAutoplay();
                if (this.scrollHijackEnabled) {
                    this.disableScrollHijack();
                }
            }
        }
        
        setupItems() {
            const containerHeight = this.$container.height();
            
            // Get actual item height from CSS (set by Elementor controls)
            // Don't override CSS - just read the computed value for positioning calculations
            const $firstItem = this.$items.first();
            let itemHeight = $firstItem.outerHeight();
            
            // Fallback to settings if CSS hasn't applied yet
            if (!itemHeight || itemHeight < 50) {
                if (this.itemHeightUnit === 'vh') {
                    itemHeight = (window.innerHeight * this.itemHeightValue) / 100;
                } else if (this.itemHeightUnit === '%') {
                    itemHeight = (containerHeight * this.itemHeightValue) / 100;
                } else {
                    itemHeight = this.itemHeightValue; // px
                }
            }
            
            // Calculate item gap based on settings
            let itemGap = 0;
            if (this.itemGapUnit === 'vh') {
                itemGap = (window.innerHeight * this.itemGapValue) / 100;
            } else if (this.itemGapUnit === 'em') {
                itemGap = this.itemGapValue * 16; // Approximate em to px
            } else {
                itemGap = this.itemGapValue; // px
            }
            
            // Don't set inline height on items - let CSS from Elementor handle it
            // Only calculate track height for positioning
            
            // Calculate total track height - all items stacked plus gaps
            const totalGaps = (this.totalItems - 1) * itemGap;
            const trackHeight = (itemHeight * this.totalItems) + totalGaps;
            
            this.$track.css({
                'height': trackHeight + 'px'
            });
            
            // Store for positioning calculations
            this.containerHeight = containerHeight;
            this.itemHeight = itemHeight;
            this.itemGap = itemGap;
        }
        
        bindEvents() {
            // Arrow navigation
            this.$arrowUp.on('click', () => this.navigate('up'));
            this.$arrowDown.on('click', () => this.navigate('down'));
            
            // Mouse wheel scroll
            if (this.settings.mouseScroll) {
                this.$container.on('wheel', (e) => this.handleWheel(e));
            }
            
            // Touch events for mobile
            this.$container.on('touchstart', (e) => this.handleTouchStart(e));
            this.$container.on('touchend', (e) => this.handleTouchEnd(e));
            
            // Keyboard navigation
            $(document).on('keydown', (e) => this.handleKeyboard(e));
            
            // Window resize
            $(window).on('resize', () => this.handleResize());
            
            // Pause autoplay on hover
            if (this.settings.autoplay) {
                this.$wrapper.on('mouseenter', () => this.stopAutoplay());
                this.$wrapper.on('mouseleave', () => this.startAutoplay());
            }
            
            // Intersection Observer for sticky scroll
            if (this.settings.stickyScroll) {
                this.setupStickyScroll();
            }
        }
        
        setupStickyScroll() {
            // Track if mouse/touch is inside widget area
            this.isPointerInside = false;
            
            this.$wrapper.on('mouseenter', () => {
                this.isPointerInside = true;
            });
            
            this.$wrapper.on('mouseleave', () => {
                this.isPointerInside = false;
                // Disable scroll hijack when mouse leaves
                if (this.scrollHijackEnabled) {
                    this.disableScrollHijack();
                }
            });
            
            // Also bind wheel event on document to intercept scroll
            this.boundDocumentWheel = (e) => this.handleDocumentWheel(e);
            document.addEventListener('wheel', this.boundDocumentWheel, { passive: false });
            
            // Bind touch events on document for mobile sticky scroll
            this.boundDocumentTouchStart = (e) => this.handleDocumentTouchStart(e);
            this.boundDocumentTouchMove = (e) => this.handleDocumentTouchMove(e);
            this.boundDocumentTouchEnd = (e) => this.handleDocumentTouchEnd(e);
            document.addEventListener('touchstart', this.boundDocumentTouchStart, { passive: true });
            document.addEventListener('touchmove', this.boundDocumentTouchMove, { passive: false });
            document.addEventListener('touchend', this.boundDocumentTouchEnd, { passive: true });
        }
        
        handleDocumentTouchStart(e) {
            if (!this.settings.stickyScroll) return;
            
            // Check if touch is inside widget area
            const touch = e.touches[0];
            const rect = this.$wrapper[0].getBoundingClientRect();
            this.isTouchInsideWidget = (
                touch.clientX >= rect.left &&
                touch.clientX <= rect.right &&
                touch.clientY >= rect.top &&
                touch.clientY <= rect.bottom
            );
            
            this.docTouchStartY = touch.clientY;
        }
        
        handleDocumentTouchMove(e) {
            if (!this.settings.stickyScroll || !this.scrollHijackEnabled) return;
            
            // Only hijack if touch started inside widget
            if (!this.isTouchInsideWidget) return;
            
            // Check if widget is visible in viewport
            const rect = this.$wrapper[0].getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const visibleHeight = Math.min(rect.bottom, viewportHeight) - Math.max(rect.top, 0);
            const isVisible = visibleHeight > 0 && visibleHeight / rect.height >= 0.5;
            
            if (!isVisible) {
                return; // Allow normal touch scroll
            }
            
            // Prevent default scrolling when in sticky mode
            e.preventDefault();
        }
        
        handleDocumentTouchEnd(e) {
            if (!this.settings.stickyScroll) return;
            
            // Only process if touch started inside widget
            if (!this.isTouchInsideWidget) return;
            
            const touchEndY = e.changedTouches[0].clientY;
            const diff = this.docTouchStartY - touchEndY;
            const swipeThreshold = 50;
            
            // Check if widget is visible
            const rect = this.$wrapper[0].getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const visibleHeight = Math.min(rect.bottom, viewportHeight) - Math.max(rect.top, 0);
            const isVisible = visibleHeight > 0 && visibleHeight / rect.height >= 0.5;
            
            if (!isVisible) return;
            
            if (!this.scrollHijackEnabled) {
                this.enableScrollHijack();
                this.boundarySwipeCount = 0;
            }
            
            const lastIndex = this.totalItems - 1;
            const swipingUp = diff > swipeThreshold;
            const swipingDown = diff < -swipeThreshold;
            
            // Check boundary for scroll release
            if (!this.infinityLoop) {
                const atTop = this.currentIndex === 0 && swipingDown;
                const atBottom = this.currentIndex === lastIndex && swipingUp;
                
                if (atTop || atBottom) {
                    this.boundarySwipeCount = (this.boundarySwipeCount || 0) + 1;
                    
                    if (this.boundarySwipeCount >= 2) {
                        this.boundarySwipeCount = 0;
                        this.disableScrollHijack();
                        return;
                    }
                    return;
                } else {
                    this.boundarySwipeCount = 0;
                }
            }
            
            if (this.isAnimating) return;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    this.navigate('down');
                } else {
                    this.navigate('up');
                }
            }
        }
        
        enableScrollHijack() {
            this.scrollHijackEnabled = true;
            // Lock body scroll
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
        }
        
        disableScrollHijack() {
            this.scrollHijackEnabled = false;
            // Unlock body scroll
            document.body.style.overflow = '';
            document.documentElement.style.overflow = '';
        }
        
        handleDocumentWheel(e) {
            if (!this.settings.stickyScroll) {
                return;
            }
            
            // Check if pointer is inside widget area (for multi-column layouts)
            if (!this.isPointerInside) {
                return; // Allow normal scroll in other columns
            }
            
            // Check if widget is visible in viewport
            const rect = this.$wrapper[0].getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const visibleHeight = Math.min(rect.bottom, viewportHeight) - Math.max(rect.top, 0);
            const isVisible = visibleHeight > 0 && visibleHeight / rect.height >= 0.5;
            
            if (!isVisible) {
                // Widget not visible enough, disable hijack and allow normal scroll
                if (this.scrollHijackEnabled) {
                    this.disableScrollHijack();
                }
                return;
            }
            
            // Widget is visible and pointer is inside, enable hijack if not already
            if (!this.scrollHijackEnabled) {
                this.enableScrollHijack();
                this.boundaryScrollCount = 0;
            }
            
            const delta = e.deltaY;
            const lastIndex = this.totalItems - 1;
            
            // Check if at boundary and should release scroll
            if (!this.infinityLoop) {
                const atTop = this.currentIndex === 0 && delta < 0;
                const atBottom = this.currentIndex === lastIndex && delta > 0;
                
                if (atTop || atBottom) {
                    // Increment boundary scroll counter
                    this.boundaryScrollCount = (this.boundaryScrollCount || 0) + 1;
                    
                    // After 2 scroll attempts at boundary, release scroll lock
                    if (this.boundaryScrollCount >= 2) {
                        this.boundaryScrollCount = 0;
                        this.disableScrollHijack();
                        return; // Allow normal scroll
                    }
                    
                    // Still at boundary, prevent scroll but don't navigate
                    e.preventDefault();
                    e.stopPropagation();
                    return;
                } else {
                    // Reset counter when not at boundary
                    this.boundaryScrollCount = 0;
                }
            }
            
            // Prevent default scroll and handle carousel navigation
            e.preventDefault();
            e.stopPropagation();
            
            if (this.isAnimating) {
                return;
            }
            
            // Debounce wheel events
            if (this.wheelTimeout) {
                clearTimeout(this.wheelTimeout);
            }
            
            this.wheelTimeout = setTimeout(() => {
                if (delta > 0) {
                    this.navigate('down');
                } else {
                    this.navigate('up');
                }
            }, 50);
        }
        
        handleWheel(e) {
            // If sticky scroll is enabled, let handleDocumentWheel handle it
            if (this.settings.stickyScroll) {
                return;
            }
            
            if (this.isAnimating) {
                e.preventDefault();
                return;
            }
            
            // Clear previous timeout
            if (this.wheelTimeout) {
                clearTimeout(this.wheelTimeout);
            }
            
            // Debounce wheel events
            this.wheelTimeout = setTimeout(() => {
                const delta = e.originalEvent.deltaY;
                
                if (delta > 0) {
                    // Scroll down
                    if (this.currentIndex < this.totalItems - 1 || this.infinityLoop) {
                        e.preventDefault();
                        this.navigate('down');
                    }
                } else {
                    // Scroll up
                    if (this.currentIndex > 0 || this.infinityLoop) {
                        e.preventDefault();
                        this.navigate('up');
                    }
                }
            }, 50);
        }
        
        handleTouchStart(e) {
            this.touchStartY = e.originalEvent.touches[0].clientY;
            this.touchStartTime = Date.now();
        }
        
        handleTouchEnd(e) {
            this.touchEndY = e.originalEvent.changedTouches[0].clientY;
            this.handleSwipe();
        }
        
        handleSwipe() {
            const swipeThreshold = 50;
            const diff = this.touchStartY - this.touchEndY;
            const lastIndex = this.totalItems - 1;
            
            // Check if at boundary for sticky scroll release on mobile
            if (this.settings.stickyScroll && !this.infinityLoop) {
                const swipingUp = diff > swipeThreshold; // Swipe up = go to next slide
                const swipingDown = diff < -swipeThreshold; // Swipe down = go to prev slide
                
                const atTop = this.currentIndex === 0 && swipingDown;
                const atBottom = this.currentIndex === lastIndex && swipingUp;
                
                if (atTop || atBottom) {
                    // Increment boundary swipe counter
                    this.boundarySwipeCount = (this.boundarySwipeCount || 0) + 1;
                    
                    // After 2 swipe attempts at boundary, release scroll lock
                    if (this.boundarySwipeCount >= 2) {
                        this.boundarySwipeCount = 0;
                        this.disableScrollHijack();
                        return; // Allow normal scroll
                    }
                    return; // Don't navigate, just count
                } else {
                    // Reset counter when not at boundary
                    this.boundarySwipeCount = 0;
                }
            }
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe up - go to next
                    this.navigate('down');
                } else {
                    // Swipe down - go to previous
                    this.navigate('up');
                }
            }
        }
        
        handleKeyboard(e) {
            if (!this.$wrapper.is(':visible')) return;
            
            switch(e.key) {
                case 'ArrowUp':
                case 'PageUp':
                    e.preventDefault();
                    this.navigate('up');
                    break;
                case 'ArrowDown':
                case 'PageDown':
                    e.preventDefault();
                    this.navigate('down');
                    break;
                case 'Home':
                    e.preventDefault();
                    this.goToSlide(0);
                    break;
                case 'End':
                    e.preventDefault();
                    this.goToSlide(this.totalItems - 1);
                    break;
            }
        }
        
        handleResize() {
            clearTimeout(this.resizeTimeout);
            this.resizeTimeout = setTimeout(() => {
                this.setupItems();
                this.updatePosition(false);
            }, 250);
        }
        
        navigate(direction) {
            if (this.isAnimating) return;
            
            let newIndex = this.currentIndex;
            
            if (direction === 'up') {
                if (this.currentIndex > 0) {
                    newIndex = this.currentIndex - 1;
                } else if (this.infinityLoop) {
                    // Loop to last slide
                    newIndex = this.totalItems - 1;
                }
            } else if (direction === 'down') {
                if (this.currentIndex < this.totalItems - 1) {
                    newIndex = this.currentIndex + 1;
                } else if (this.infinityLoop) {
                    // Loop to first slide
                    newIndex = 0;
                }
            }
            
            if (newIndex !== this.currentIndex) {
                this.goToSlide(newIndex);
            }
        }
        
        goToSlide(index) {
            if (index < 0 || index >= this.totalItems || index === this.currentIndex || this.isAnimating) {
                return;
            }
            
            this.isAnimating = true;
            this.currentIndex = index;
            
            this.updateActiveItem(index);
            this.updatePosition(true);
            this.updateArrows();
            
            // Reset animation lock after transition
            setTimeout(() => {
                this.isAnimating = false;
            }, this.settings.transitionSpeed || 600);
        }
        
        updateActiveItem(index) {
            this.$items.removeClass('mn-vpost-active mn-vpost-prev mn-vpost-next');
            
            this.$items.each((i, item) => {
                const $item = $(item);
                
                if (i === index) {
                    $item.addClass('mn-vpost-active');
                } else if (i < index) {
                    $item.addClass('mn-vpost-prev');
                } else {
                    $item.addClass('mn-vpost-next');
                }
            });
        }
        
        updatePosition(animate) {
            const itemHeight = this.itemHeight || this.$items.first().height();
            const containerHeight = this.containerHeight || this.$container.height();
            const itemGap = this.itemGap || 0;
            
            // Calculate center offset - where active slide should start to be centered
            // Center position = (containerHeight - itemHeight) / 2
            const centerOffset = (containerHeight - itemHeight) / 2;
            
            // Calculate translateY to position active slide at center
            // Active slide position in track = currentIndex * (itemHeight + gap)
            // To center it: translateY = centerOffset - (currentIndex * (itemHeight + gap))
            const slideOffset = this.currentIndex * (itemHeight + itemGap);
            const translateY = centerOffset - slideOffset;
            
            // Apply transform
            if (animate) {
                this.$track.css({
                    'transform': `translateY(${translateY}px)`,
                    'transition': `transform ${this.settings.transitionSpeed || 600}ms cubic-bezier(0.4, 0, 0.2, 1)`
                });
            } else {
                this.$track.css({
                    'transform': `translateY(${translateY}px)`,
                    'transition': 'none'
                });
            }
        }
        
        updateArrows() {
            // Update arrow disabled state
            if (this.infinityLoop) {
                // Never disable arrows in infinity loop mode
                this.$arrowUp.prop('disabled', false);
                this.$arrowDown.prop('disabled', false);
            } else {
                if (this.currentIndex === 0) {
                    this.$arrowUp.prop('disabled', true);
                } else {
                    this.$arrowUp.prop('disabled', false);
                }
                
                if (this.currentIndex === this.totalItems - 1) {
                    this.$arrowDown.prop('disabled', true);
                } else {
                    this.$arrowDown.prop('disabled', false);
                }
            }
        }
        
        applyOverlayGradient() {
            const topColor = this.settings.overlayColorTop || '#000000';
            const bottomColor = this.settings.overlayColorBottom || '#000000';
            const opacityDesktop = this.settings.overlayOpacity || 0.8;
            const opacityTablet = this.settings.overlayOpacityTablet !== undefined ? this.settings.overlayOpacityTablet : opacityDesktop;
            const opacityMobile = this.settings.overlayOpacityMobile !== undefined ? this.settings.overlayOpacityMobile : 0;
            const visiblePercentage = this.visiblePercentage || 20;
            
            // Calculate overlay height based on visible percentage
            const overlayHeight = Math.max(visiblePercentage + 5, 25);
            
            // Add styles dynamically to container pseudo-elements
            const widgetId = this.$wrapper.closest('.elementor-widget').data('id') || 'default';
            const styleId = 'mn-vpost-overlay-' + widgetId;
            let $style = $('#' + styleId);
            
            if ($style.length === 0) {
                $style = $('<style id="' + styleId + '"></style>');
                $('head').append($style);
            }
            
            // Generate gradients for each breakpoint
            const generateGradients = (opacity) => {
                const topRgba = this.hexToRgba(topColor, opacity);
                const topRgbaHalf = this.hexToRgba(topColor, opacity * 0.5);
                const bottomRgba = this.hexToRgba(bottomColor, opacity);
                const bottomRgbaHalf = this.hexToRgba(bottomColor, opacity * 0.5);
                
                return {
                    top: `linear-gradient(to bottom, ${topRgba} 0%, ${topRgbaHalf} 50%, transparent 100%)`,
                    bottom: `linear-gradient(to top, ${bottomRgba} 0%, ${bottomRgbaHalf} 50%, transparent 100%)`
                };
            };
            
            const desktopGradients = generateGradients(opacityDesktop);
            const tabletGradients = generateGradients(opacityTablet);
            const mobileGradients = generateGradients(opacityMobile);
            
            const css = `
                /* Desktop */
                [data-id="${widgetId}"] .mn-vpost-container::before {
                    background: ${desktopGradients.top} !important;
                    height: ${overlayHeight}% !important;
                }
                [data-id="${widgetId}"] .mn-vpost-container::after {
                    background: ${desktopGradients.bottom} !important;
                    height: ${overlayHeight}% !important;
                }
                
                /* Tablet */
                @media (max-width: 1024px) {
                    [data-id="${widgetId}"] .mn-vpost-container::before {
                        background: ${tabletGradients.top} !important;
                    }
                    [data-id="${widgetId}"] .mn-vpost-container::after {
                        background: ${tabletGradients.bottom} !important;
                    }
                }
                
                /* Mobile */
                @media (max-width: 767px) {
                    [data-id="${widgetId}"] .mn-vpost-container::before {
                        background: ${mobileGradients.top} !important;
                        ${opacityMobile === 0 ? 'display: none !important; visibility: hidden !important;' : ''}
                    }
                    [data-id="${widgetId}"] .mn-vpost-container::after {
                        background: ${mobileGradients.bottom} !important;
                        ${opacityMobile === 0 ? 'display: none !important; visibility: hidden !important;' : ''}
                    }
                    
                    /* Mobile listing mode - always hide overlay */
                    [data-id="${widgetId}"] .mn-vpost-mobile-listing .mn-vpost-container::before,
                    [data-id="${widgetId}"] .mn-vpost-mobile-listing .mn-vpost-container::after {
                        display: none !important;
                        content: none !important;
                        background: none !important;
                        opacity: 0 !important;
                        visibility: hidden !important;
                    }
                }
            `;
            
            $style.html(css);
        }
        
        hexToRgba(hex, alpha) {
            // Remove # if present
            hex = hex.replace('#', '');
            
            // Parse hex values
            const r = parseInt(hex.substring(0, 2), 16);
            const g = parseInt(hex.substring(2, 4), 16);
            const b = parseInt(hex.substring(4, 6), 16);
            
            return `rgba(${r}, ${g}, ${b}, ${alpha})`;
        }
        
        startAutoplay() {
            if (!this.settings.autoplay) return;
            
            this.stopAutoplay();
            
            this.autoplayTimer = setInterval(() => {
                if (this.currentIndex < this.totalItems - 1) {
                    this.navigate('down');
                } else if (this.infinityLoop) {
                    // Loop back to start in infinity mode
                    this.navigate('down');
                } else {
                    // Loop back to start in normal mode
                    this.goToSlide(0);
                }
            }, this.settings.autoplaySpeed || 5000);
        }
        
        stopAutoplay() {
            if (this.autoplayTimer) {
                clearInterval(this.autoplayTimer);
                this.autoplayTimer = null;
            }
        }
        
        destroy() {
            this.stopAutoplay();
            this.$arrowUp.off('click');
            this.$arrowDown.off('click');
            this.$container.off('wheel touchstart touchend');
            $(document).off('keydown');
            $(window).off('resize');
        }
    }

    // Initialize widget
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-vertical-post.default', function($scope) {
            const $wrapper = $scope.find('.mn-vpost-wrapper');
            
            if ($wrapper.length) {
                // Add unique ID for overlay styles
                const uniqueId = 'vpost-' + Math.random().toString(36).substr(2, 9);
                $wrapper.attr('data-id', uniqueId);
                
                // Initialize widget
                new MNVerticalPost($wrapper[0]);
            }
        });
    });

    // Also initialize for non-Elementor pages
    $(document).ready(function() {
        $('.mn-vpost-wrapper').each(function() {
            if (!$(this).attr('data-id')) {
                const uniqueId = 'vpost-' + Math.random().toString(36).substr(2, 9);
                $(this).attr('data-id', uniqueId);
                new MNVerticalPost(this);
            }
        });
    });

})(jQuery);
