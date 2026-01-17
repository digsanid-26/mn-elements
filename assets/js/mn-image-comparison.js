/**
 * MN Image Comparison Widget JavaScript
 * 
 * Before/After image comparison widget with slider functionality
 * @since 1.2.7
 */

(function($) {
    'use strict';

    /**
     * MN Image Comparison Handler Class
     */
    class MNImageComparison {
        constructor(element) {
            this.element = element;
            this.container = element.querySelector('.mn-image-comparison-container');
            this.beforeImage = element.querySelector('.mn-comparison-before');
            this.afterImage = element.querySelector('.mn-comparison-after');
            this.divider = element.querySelector('.mn-comparison-divider');
            this.handle = element.querySelector('.mn-comparison-handle');
            
            // Content elements for dynamic positioning
            this.contentRow = element.querySelector('.mn-comparison-content-row');
            this.labelPosition = this.element.classList.contains('mn-label-position-below') ? 'below' : 
                               this.element.classList.contains('mn-label-position-overlay') ? 'overlay' : 'none';
            
            // Zoom elements
            this.zoomIcon = element.querySelector('.mn-comparison-zoom-icon');
            this.zoomPopup = element.querySelector('.mn-comparison-zoom-popup');
            this.zoomOverlay = element.querySelector('.mn-comparison-zoom-overlay');
            this.zoomClose = element.querySelector('.mn-comparison-zoom-close');
            
            // Get settings from data attribute
            this.settings = JSON.parse(this.container.getAttribute('data-comparison-settings') || '{}');
            this.orientation = this.settings.orientation || 'horizontal';
            this.initialPosition = this.settings.initialPosition || 50;
            
            // State variables
            this.isDragging = false;
            this.currentPosition = this.initialPosition;
            
            this.init();
        }

        init() {
            if (!this.container || !this.divider || !this.handle) {
                console.warn('MN Image Comparison: Required elements not found');
                return;
            }

            this.setupInitialPosition();
            this.bindEvents();
            this.bindZoomEvents();
            this.updateComparison(this.currentPosition);
            
            // Add ARIA attributes for accessibility
            this.setupAccessibility();
        }

        setupInitialPosition() {
            // Set initial position based on settings
            this.currentPosition = Math.max(0, Math.min(100, this.initialPosition));
        }

        setupAccessibility() {
            // Add ARIA attributes
            this.container.setAttribute('role', 'img');
            this.container.setAttribute('aria-label', 'Image comparison slider');
            this.container.setAttribute('tabindex', '0');
            
            this.handle.setAttribute('role', 'slider');
            this.handle.setAttribute('aria-label', 'Comparison slider handle');
            this.handle.setAttribute('aria-valuemin', '0');
            this.handle.setAttribute('aria-valuemax', '100');
            this.handle.setAttribute('aria-valuenow', this.currentPosition);
        }

        bindEvents() {
            // Mouse events
            this.handle.addEventListener('mousedown', this.onDragStart.bind(this));
            document.addEventListener('mousemove', this.onDragMove.bind(this));
            document.addEventListener('mouseup', this.onDragEnd.bind(this));

            // Touch events for mobile
            this.handle.addEventListener('touchstart', this.onDragStart.bind(this), { passive: false });
            document.addEventListener('touchmove', this.onDragMove.bind(this), { passive: false });
            document.addEventListener('touchend', this.onDragEnd.bind(this));

            // Click on container to move slider
            this.container.addEventListener('click', this.onContainerClick.bind(this));

            // Keyboard navigation
            this.container.addEventListener('keydown', this.onKeyDown.bind(this));

            // Prevent image dragging
            this.beforeImage.addEventListener('dragstart', this.preventDefault);
            this.afterImage.addEventListener('dragstart', this.preventDefault);

            // Window resize
            window.addEventListener('resize', this.onResize.bind(this));
        }

        onDragStart(event) {
            event.preventDefault();
            this.isDragging = true;
            this.container.classList.add('mn-comparison-dragging');
            
            // Change cursor
            document.body.style.cursor = this.orientation === 'horizontal' ? 'ew-resize' : 'ns-resize';
        }

        onDragMove(event) {
            if (!this.isDragging) return;

            event.preventDefault();
            
            const rect = this.container.getBoundingClientRect();
            let position;

            if (this.orientation === 'horizontal') {
                const clientX = event.type.startsWith('touch') ? event.touches[0].clientX : event.clientX;
                position = ((clientX - rect.left) / rect.width) * 100;
            } else {
                const clientY = event.type.startsWith('touch') ? event.touches[0].clientY : event.clientY;
                position = ((clientY - rect.top) / rect.height) * 100;
            }

            // Clamp position between 0 and 100
            position = Math.max(0, Math.min(100, position));
            
            this.updateComparison(position);
        }

        onDragEnd(event) {
            if (!this.isDragging) return;

            this.isDragging = false;
            this.container.classList.remove('mn-comparison-dragging');
            
            // Reset cursor
            document.body.style.cursor = '';
        }

        onContainerClick(event) {
            // Don't trigger if clicking on handle
            if (event.target.closest('.mn-comparison-handle')) return;

            const rect = this.container.getBoundingClientRect();
            let position;

            if (this.orientation === 'horizontal') {
                position = ((event.clientX - rect.left) / rect.width) * 100;
            } else {
                position = ((event.clientY - rect.top) / rect.height) * 100;
            }

            // Clamp position between 0 and 100
            position = Math.max(0, Math.min(100, position));
            
            // Animate to new position
            this.animateToPosition(position);
        }

        onKeyDown(event) {
            const step = event.shiftKey ? 10 : 1; // Larger steps with Shift key
            let newPosition = this.currentPosition;

            switch (event.key) {
                case 'ArrowLeft':
                case 'ArrowUp':
                    newPosition = Math.max(0, this.currentPosition - step);
                    event.preventDefault();
                    break;
                case 'ArrowRight':
                case 'ArrowDown':
                    newPosition = Math.min(100, this.currentPosition + step);
                    event.preventDefault();
                    break;
                case 'Home':
                    newPosition = 0;
                    event.preventDefault();
                    break;
                case 'End':
                    newPosition = 100;
                    event.preventDefault();
                    break;
                case ' ':
                case 'Enter':
                    newPosition = 50; // Reset to center
                    event.preventDefault();
                    break;
            }

            if (newPosition !== this.currentPosition) {
                this.animateToPosition(newPosition);
            }
        }

        onResize() {
            // Recalculate position on window resize
            this.updateComparison(this.currentPosition);
        }

        preventDefault(event) {
            event.preventDefault();
        }

        updateComparison(position) {
            this.currentPosition = position;

            if (this.orientation === 'horizontal') {
                // Horizontal comparison - After image reveals from right side
                this.divider.style.left = position + '%';
                this.afterImage.style.clipPath = `inset(0 0 0 ${position}%)`;
            } else {
                // Vertical comparison - After image reveals from bottom side
                this.divider.style.top = position + '%';
                this.afterImage.style.clipPath = `inset(${position}% 0 0 0)`;
            }

            // Update dynamic label positioning
            this.updateDynamicLabels(position);

            // Update ARIA value
            this.handle.setAttribute('aria-valuenow', Math.round(position));

            // Trigger custom event
            this.container.dispatchEvent(new CustomEvent('mn-comparison-change', {
                detail: { position: position, orientation: this.orientation }
            }));
        }

        updateDynamicLabels(position) {
            // Only update for below label position
            if (this.labelPosition !== 'below' || !this.contentRow) {
                return;
            }

            const beforeLabel = this.element.querySelector('.mn-comparison-content-before');
            const afterLabel = this.element.querySelector('.mn-comparison-content-after');
            
            if (!beforeLabel || !afterLabel) return;

            if (this.orientation === 'horizontal') {
                // Horizontal: Labels follow the visible area of each image
                // Before image is visible from 0% to position%
                // After image is visible from position% to 100%
                
                // Before label: move from initial 25% to center of visible before area
                const beforeVisibleCenter = position / 2; // Center of visible before area (0% to position%)
                const beforeOffset = beforeVisibleCenter - 25; // Offset from initial 25% position
                beforeLabel.style.transform = `translateX(calc(-50% + ${beforeOffset}%))`;
                beforeLabel.style.opacity = position > 10 ? '1' : '0'; // Hide when area too small
                
                // After label: move from initial 75% to center of visible after area
                const afterVisibleWidth = 100 - position;
                const afterVisibleCenter = position + (afterVisibleWidth / 2); // Center of visible after area
                const afterOffset = afterVisibleCenter - 75; // Offset from initial 75% position
                afterLabel.style.transform = `translateX(calc(-50% + ${afterOffset}%))`;
                afterLabel.style.opacity = afterVisibleWidth > 10 ? '1' : '0'; // Hide when area too small
            } else {
                // Vertical: Labels follow the visible area of each image
                // Before image is visible from 0% to position%
                // After image is visible from position% to 100%
                
                // Before label: move from initial 25% to center of visible before area
                const beforeVisibleCenter = position / 2; // Center of visible before area (0% to position%)
                const beforeOffset = beforeVisibleCenter - 25; // Offset from initial 25% position
                beforeLabel.style.transform = `translateY(calc(-50% + ${beforeOffset}%))`;
                beforeLabel.style.opacity = position > 10 ? '1' : '0'; // Hide when area too small
                
                // After label: move from initial 75% to center of visible after area
                const afterVisibleHeight = 100 - position;
                const afterVisibleCenter = position + (afterVisibleHeight / 2); // Center of visible after area
                const afterOffset = afterVisibleCenter - 75; // Offset from initial 75% position
                afterLabel.style.transform = `translateY(calc(-50% + ${afterOffset}%))`;
                afterLabel.style.opacity = afterVisibleHeight > 10 ? '1' : '0'; // Hide when area too small
            }
        }

        animateToPosition(targetPosition) {
            const startPosition = this.currentPosition;
            const distance = targetPosition - startPosition;
            const duration = 300; // Animation duration in ms
            const startTime = performance.now();

            const animate = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function (ease-out)
                const easeOut = 1 - Math.pow(1 - progress, 3);
                
                const currentPosition = startPosition + (distance * easeOut);
                this.updateComparison(currentPosition);

                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            };

            requestAnimationFrame(animate);
        }

        bindZoomEvents() {
            if (!this.zoomIcon || !this.zoomPopup) return;

            // Zoom icon click
            this.zoomIcon.addEventListener('click', this.openZoomPopup.bind(this));

            // Close popup events
            if (this.zoomClose) {
                this.zoomClose.addEventListener('click', this.closeZoomPopup.bind(this));
            }
            if (this.zoomOverlay) {
                this.zoomOverlay.addEventListener('click', this.closeZoomPopup.bind(this));
            }

            // ESC key to close popup
            document.addEventListener('keydown', this.onZoomKeyDown.bind(this));
        }

        openZoomPopup() {
            if (!this.zoomPopup) return;

            this.zoomPopup.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
            
            // Focus trap for accessibility
            this.zoomPopup.setAttribute('tabindex', '-1');
            this.zoomPopup.focus();

            // Trigger custom event
            this.element.dispatchEvent(new CustomEvent('mn-comparison-zoom-open', {
                detail: { widget: this }
            }));
        }

        closeZoomPopup() {
            if (!this.zoomPopup) return;

            this.zoomPopup.style.display = 'none';
            document.body.style.overflow = ''; // Restore scrolling

            // Trigger custom event
            this.element.dispatchEvent(new CustomEvent('mn-comparison-zoom-close', {
                detail: { widget: this }
            }));
        }

        onZoomKeyDown(event) {
            // Close popup on ESC key
            if (event.key === 'Escape' && this.zoomPopup && this.zoomPopup.style.display === 'flex') {
                this.closeZoomPopup();
                event.preventDefault();
            }
        }

        unbindEvents() {
            this.handle.removeEventListener('mousedown', this.onDragStart);
            this.handle.removeEventListener('touchstart', this.onDragStart);
            document.removeEventListener('mousemove', this.onDragMove);
            document.removeEventListener('touchmove', this.onDragMove);
            document.removeEventListener('mouseup', this.onDragEnd);
            document.removeEventListener('touchend', this.onDragEnd);
            this.container.removeEventListener('click', this.onContainerClick);
            this.container.removeEventListener('keydown', this.onKeyDown);
            this.beforeImage.removeEventListener('dragstart', this.preventDefault);
            this.afterImage.removeEventListener('dragstart', this.preventDefault);
            window.removeEventListener('resize', this.onResize);

            // Reset cursor
            document.body.style.cursor = '';
        }

        unbindZoomEvents() {
            if (this.zoomIcon) {
                this.zoomIcon.removeEventListener('click', this.openZoomPopup.bind(this));
            }
            if (this.zoomClose) {
                this.zoomClose.removeEventListener('click', this.closeZoomPopup.bind(this));
            }
            if (this.zoomOverlay) {
                this.zoomOverlay.removeEventListener('click', this.closeZoomPopup.bind(this));
            }
            document.removeEventListener('keydown', this.onZoomKeyDown.bind(this));
        }

        // Public method to set position programmatically
        setPosition(position) {
            position = Math.max(0, Math.min(100, position));
            this.animateToPosition(position);
        }

        // Public method to get current position
        getPosition() {
            return this.currentPosition;
        }

        // Cleanup method
        destroy() {
            this.unbindEvents();
            this.unbindZoomEvents();
        }
    }

    /**
     * Initialize Image Comparison widgets
     */
    function initImageComparison() {
        const elements = document.querySelectorAll('.mn-image-comparison-wrapper');
        
        elements.forEach(element => {
            // Check if already initialized
            if (element.mnImageComparison) {
                return;
            }

            // Initialize new instance
            element.mnImageComparison = new MNImageComparison(element);
        });
    }

    /**
     * jQuery plugin wrapper
     */
    $.fn.mnImageComparison = function(options) {
        return this.each(function() {
            if (!this.mnImageComparison) {
                this.mnImageComparison = new MNImageComparison(this);
            }
        });
    };

    /**
     * Auto-initialize on DOM ready and Elementor frontend init
     */
    $(document).ready(function() {
        initImageComparison();
    });

    // Elementor frontend compatibility
    $(window).on('elementor/frontend/init', function() {
        // Initialize for Elementor frontend
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-image-comparison.default', function($scope) {
            initImageComparison();
        });
    });

    // Re-initialize on AJAX content load
    $(document).on('mn-elements:refresh', initImageComparison);

    // Global function for manual initialization
    window.MNImageComparison = {
        init: initImageComparison,
        create: function(element) {
            return new MNImageComparison(element);
        }
    };

})(jQuery);
