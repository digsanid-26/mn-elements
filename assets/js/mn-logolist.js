/**
 * MN Logolist Widget JavaScript
 * Handles carousel functionality with smooth infinite loop and hover pause
 */

class MNLogolistWidget {
    constructor(element) {
        this.element = element;
        this.container = element.querySelector('.mn-logolist-container');
        this.isCarousel = element.classList.contains('mn-logolist-carousel');
        
        // Initialize lightbox
        this.initLightbox();
        
        if (this.isCarousel) {
            this.init();
        }
    }

    init() {
        this.setupCarousel();
        this.bindEvents();
    }

    setupCarousel() {
        // Get settings from data attributes with validation
        const speedAttr = this.element.dataset.carouselSpeed;
        const slidesAttr = this.element.dataset.carouselSlides;
        const pauseAttr = this.element.dataset.pauseHover;
        const directionAttr = this.element.dataset.carouselDirection;
        
        this.speed = parseInt(speedAttr) || 3000;
        this.slidesToShow = parseInt(slidesAttr) || 4;
        this.pauseOnHover = pauseAttr === 'yes';
        this.direction = directionAttr || 'ltr'; // 'ltr' or 'rtl'

        // Debug logging with raw attributes
        console.log('MN Logolist Carousel Setup:', {
            rawSpeedAttr: speedAttr,
            rawSlidesAttr: slidesAttr,
            rawPauseAttr: pauseAttr,
            rawDirectionAttr: directionAttr,
            parsedSpeed: this.speed,
            parsedSlides: this.slidesToShow,
            parsedPause: this.pauseOnHover,
            parsedDirection: this.direction,
            containerWidth: this.element.offsetWidth
        });

        // Clone items for infinite loop
        this.cloneItems();
    }

    cloneItems() {
        if (!this.container) return;

        const originalItems = this.container.querySelectorAll('.mn-logo-item:not(.mn-logo-clone)');
        const itemsArray = Array.from(originalItems);
        
        // Clone items multiple times to ensure smooth infinite loop
        // Clone enough items to fill at least 2 full viewport widths
        const viewportWidth = this.element.offsetWidth;
        let totalClonedWidth = 0;
        let cloneCount = 0;
        
        // First, calculate original items width
        let originalWidth = 0;
        itemsArray.forEach(item => {
            originalWidth += item.offsetWidth + 30; // 30px margin
        });
        
        // Clone items until we have enough for smooth loop
        while (totalClonedWidth < viewportWidth * 2 || cloneCount < itemsArray.length) {
            itemsArray.forEach(item => {
                const clone = item.cloneNode(true);
                clone.classList.add('mn-logo-clone');
                this.container.appendChild(clone);
                totalClonedWidth += item.offsetWidth + 30;
                cloneCount++;
            });
        }

        // Debug logging
        console.log('MN Logolist Cloning Complete:', {
            originalItems: itemsArray.length,
            originalWidth: originalWidth,
            totalClonedWidth: totalClonedWidth,
            cloneCount: cloneCount
        });

        // Set container width and animation
        this.setupInfiniteLoop(originalWidth);
    }

    setupInfiniteLoop(originalWidth) {
        // Set animation to move exactly the width of original items
        this.container.style.setProperty('--original-width', originalWidth + 'px');
        
        // Fallback for browsers that don't support CSS custom properties
        if (!CSS.supports('(--original-width: 0px)')) {
            // Create a new keyframe animation dynamically
            const animationName = `mn-carousel-scroll-${Date.now()}`;
            const styleSheet = document.styleSheets[0];
            const keyframes = `
                @keyframes ${animationName} {
                    0% { transform: translateX(0); }
                    100% { transform: translateX(-${originalWidth}px); }
                }
            `;
            styleSheet.insertRule(keyframes, styleSheet.cssRules.length);
            
            // Use the custom animation name
            this.customAnimationName = animationName;
        }
        
        // Calculate total width including clones
        const allItems = this.container.querySelectorAll('.mn-logo-item');
        let totalWidth = 0;
        allItems.forEach(item => {
            totalWidth += item.offsetWidth + 30;
        });
        
        this.container.style.width = totalWidth + 'px';
        
        // Store original width for reference
        this.originalWidth = originalWidth;
        
        // Set animation speed after everything is setup with delay to ensure DOM is ready
        setTimeout(() => {
            this.setAnimationSpeed();
        }, 100);
    }

    setAnimationSpeed() {
        // Convert speed from milliseconds to seconds for CSS animation
        const animationDuration = this.speed / 1000;
        
        // Validate animation duration (minimum 1 second, maximum 30 seconds)
        const validDuration = Math.max(1, Math.min(30, animationDuration));
        
        // Determine animation name based on direction
        const animationName = this.direction === 'rtl' ? 'mn-carousel-scroll-rtl' : 'mn-carousel-scroll';
        
        // Clear any existing animation first
        this.container.style.animation = 'none';
        
        // Force reflow to ensure animation is cleared
        this.container.offsetHeight;
        
        // Set animation properties individually for better control
        if (this.customAnimationName) {
            // Use custom animation name for fallback browsers
            this.container.style.animationName = this.customAnimationName;
        } else {
            // Use animation name based on direction
            this.container.style.animationName = animationName;
        }
        
        // Set other animation properties individually
        this.container.style.animationDuration = validDuration + 's';
        this.container.style.animationTimingFunction = 'linear';
        this.container.style.animationIterationCount = 'infinite';
        this.container.style.animationFillMode = 'none';
        this.container.style.animationPlayState = 'running';
        this.container.style.animationDirection = 'normal'; // Always normal, direction is handled by keyframes
        
        // Force override any CSS that might interfere with multiple methods
        this.container.style.setProperty('animation-duration', validDuration + 's', 'important');
        this.container.style.setProperty('animation-name', this.customAnimationName || animationName, 'important');
        this.container.style.setProperty('animation-direction', 'normal', 'important');
        
        // Debug logging
        console.log('MN Logolist Animation Speed Set:', {
            originalSpeedMs: this.speed,
            calculatedDuration: animationDuration,
            validatedDuration: validDuration,
            direction: this.direction,
            animationName: animationName,
            customAnimation: this.customAnimationName,
            appliedName: this.container.style.animationName,
            appliedDuration: this.container.style.animationDuration,
            appliedDirection: this.container.style.animationDirection,
            finalAnimation: this.container.style.animation
        });
    }

    bindEvents() {
        if (this.pauseOnHover) {
            this.element.addEventListener('mouseenter', () => {
                this.pauseAnimation();
            });

            this.element.addEventListener('mouseleave', () => {
                this.resumeAnimation();
            });
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            this.handleResize();
        });

        // Handle visibility change (pause when tab is not active)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.pauseAnimation();
            } else {
                this.resumeAnimation();
            }
        });

        // Listen for Elementor editor changes
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/widget', (scope) => {
                if (scope[0] === this.element) {
                    // Re-read settings and update animation
                    setTimeout(() => {
                        this.updateSettings();
                        this.setAnimationSpeed();
                    }, 200);
                }
            });
        }
    }

    updateSettings() {
        // Re-read data attributes in case they changed
        const speedAttr = this.element.dataset.carouselSpeed;
        const slidesAttr = this.element.dataset.carouselSlides;
        const pauseAttr = this.element.dataset.pauseHover;
        const directionAttr = this.element.dataset.carouselDirection;
        
        this.speed = parseInt(speedAttr) || 3000;
        this.slidesToShow = parseInt(slidesAttr) || 4;
        this.pauseOnHover = pauseAttr === 'yes';
        this.direction = directionAttr || 'ltr';

        console.log('MN Logolist Settings Updated:', {
            newSpeed: this.speed,
            newSlides: this.slidesToShow,
            newPause: this.pauseOnHover,
            newDirection: this.direction
        });
    }

    pauseAnimation() {
        if (this.container) {
            this.container.style.animationPlayState = 'paused';
        }
    }

    resumeAnimation() {
        if (this.container) {
            this.container.style.animationPlayState = 'running';
        }
    }

    handleResize() {
        // Recalculate width on resize
        setTimeout(() => {
            // Remove existing clones
            const clones = this.container.querySelectorAll('.mn-logo-clone');
            clones.forEach(clone => clone.remove());
            
            // Recreate clones with new viewport size
            this.cloneItems();
            
            // Ensure animation speed is maintained after resize
            this.setAnimationSpeed();
        }, 100);
    }

    // Public method to force refresh animation (for debugging)
    forceRefreshAnimation() {
        console.log('Force refreshing animation...');
        this.updateSettings();
        this.setAnimationSpeed();
    }

    initLightbox() {
        // Create lightbox modal if it doesn't exist
        if (!document.querySelector('.mn-logolist-lightbox')) {
            const lightbox = document.createElement('div');
            lightbox.className = 'mn-logolist-lightbox';
            lightbox.innerHTML = `
                <div class="mn-logolist-lightbox-content">
                    <div class="mn-logolist-lightbox-header">
                        <h3 class="mn-logolist-lightbox-title"></h3>
                        <button class="mn-logolist-lightbox-close" aria-label="Close">&times;</button>
                    </div>
                    <div class="mn-logolist-lightbox-body">
                        <div class="mn-logolist-lightbox-loading">
                            <div class="mn-logolist-lightbox-spinner"></div>
                            <div>Loading...</div>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(lightbox);
            
            // Bind lightbox events
            this.bindLightboxEvents();
        }
        
        // Bind trigger events
        this.bindLightboxTriggers();
    }
    
    bindLightboxTriggers() {
        // Use event delegation on parent element to handle both original and cloned items
        this.element.addEventListener('click', (e) => {
            const trigger = e.target.closest('.mn-lightbox-trigger');
            if (trigger) {
                e.preventDefault();
                this.openLightbox(trigger);
            }
        });
    }
    
    bindLightboxEvents() {
        const lightbox = document.querySelector('.mn-logolist-lightbox');
        const closeBtn = lightbox.querySelector('.mn-logolist-lightbox-close');
        
        // Close button
        closeBtn.addEventListener('click', () => {
            this.closeLightbox();
        });
        
        // Click outside to close
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                this.closeLightbox();
            }
        });
        
        // ESC key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && lightbox.classList.contains('active')) {
                this.closeLightbox();
            }
        });
    }
    
    openLightbox(trigger) {
        const lightbox = document.querySelector('.mn-logolist-lightbox');
        const title = lightbox.querySelector('.mn-logolist-lightbox-title');
        const body = lightbox.querySelector('.mn-logolist-lightbox-body');
        const loading = lightbox.querySelector('.mn-logolist-lightbox-loading');
        
        const mediaUrl = trigger.dataset.mediaUrl;
        const mediaType = trigger.dataset.mediaType;
        const mediaTitle = trigger.dataset.mediaTitle;
        
        // Set title
        title.textContent = mediaTitle || '';
        
        // Show loading
        loading.style.display = 'block';
        
        // Clear previous content
        const existingMedia = body.querySelector('img, video');
        if (existingMedia) {
            existingMedia.remove();
        }
        
        // Open lightbox
        lightbox.classList.add('active');
        document.body.classList.add('mn-lightbox-open');
        
        // Load media
        if (mediaType === 'video') {
            const video = document.createElement('video');
            video.src = mediaUrl;
            video.controls = true;
            video.autoplay = true;
            
            video.addEventListener('loadeddata', () => {
                loading.style.display = 'none';
            });
            
            video.addEventListener('error', () => {
                loading.innerHTML = '<div style="color: #ff0000;">Error loading video</div>';
            });
            
            body.appendChild(video);
        } else {
            const img = document.createElement('img');
            img.src = mediaUrl;
            img.alt = mediaTitle || '';
            
            img.addEventListener('load', () => {
                loading.style.display = 'none';
            });
            
            img.addEventListener('error', () => {
                loading.innerHTML = '<div style="color: #ff0000;">Error loading image</div>';
            });
            
            body.appendChild(img);
        }
    }
    
    closeLightbox() {
        const lightbox = document.querySelector('.mn-logolist-lightbox');
        const body = lightbox.querySelector('.mn-logolist-lightbox-body');
        const loading = lightbox.querySelector('.mn-logolist-lightbox-loading');
        
        // Stop video if playing
        const video = body.querySelector('video');
        if (video) {
            video.pause();
            video.currentTime = 0;
        }
        
        // Close lightbox
        lightbox.classList.remove('active');
        document.body.classList.remove('mn-lightbox-open');
        
        // Reset loading state
        setTimeout(() => {
            loading.style.display = 'block';
            loading.innerHTML = `
                <div class="mn-logolist-lightbox-spinner"></div>
                <div>Loading...</div>
            `;
        }, 300);
    }

    destroy() {
        // Remove cloned items
        const clones = this.container.querySelectorAll('.mn-logo-clone');
        clones.forEach(clone => clone.remove());

        // Remove event listeners
        this.element.removeEventListener('mouseenter', this.pauseAnimation);
        this.element.removeEventListener('mouseleave', this.resumeAnimation);
        window.removeEventListener('resize', this.handleResize);
        document.removeEventListener('visibilitychange', this.handleVisibilityChange);
    }
}

// Global storage for widget instances (for debugging)
window.MNLogolistInstances = window.MNLogolistInstances || [];

// Initialize widgets when DOM is ready
jQuery(document).ready(function($) {
    // Initialize all logolist widgets
    $('.mn-logolist-wrapper').each(function() {
        const instance = new MNLogolistWidget(this);
        window.MNLogolistInstances.push(instance);
        
        // Add instance reference to element for easy access
        this.mnLogolistInstance = instance;
    });
    
    // Initialize widgets when Elementor frontend is ready
    if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-logolist.default', function(scope) {
            const element = scope.find('.mn-logolist-wrapper')[0];
            if (element && !element.mnLogolistInstance) {
                const instance = new MNLogolistWidget(element);
                window.MNLogolistInstances.push(instance);
                element.mnLogolistInstance = instance;
            }
        });
    }
});

// Global helper function for debugging
window.refreshAllLogolistAnimations = function() {
    console.log('Refreshing all MN Logolist animations...');
    window.MNLogolistInstances.forEach(instance => {
        if (instance && instance.forceRefreshAnimation) {
            instance.forceRefreshAnimation();
        }
    });
};

// Global function to fix speed animation issues
window.fixLogolistSpeed = function(speedMs = null) {
    console.log('Fixing MN Logolist speed animation...');
    
    document.querySelectorAll('.mn-logolist-carousel .mn-logolist-container').forEach(container => {
        const wrapper = container.closest('.mn-logolist-wrapper');
        if (!wrapper) return;
        
        // Get speed and direction from data attributes or use provided value
        const currentSpeed = speedMs || parseInt(wrapper.dataset.carouselSpeed) || 3000;
        const direction = wrapper.dataset.carouselDirection || 'ltr';
        const duration = currentSpeed / 1000;
        const animationName = direction === 'rtl' ? 'mn-carousel-scroll-rtl' : 'mn-carousel-scroll';
        
        console.log(`Setting speed: ${currentSpeed}ms (${duration}s), direction: ${direction} for container:`, container);
        
        // Force clear and reset animation
        container.style.animation = 'none';
        container.offsetHeight; // Force reflow
        
        // Set individual properties with !important
        container.style.setProperty('animation-name', animationName, 'important');
        container.style.setProperty('animation-duration', duration + 's', 'important');
        container.style.setProperty('animation-timing-function', 'linear', 'important');
        container.style.setProperty('animation-iteration-count', 'infinite', 'important');
        container.style.setProperty('animation-fill-mode', 'none', 'important');
        container.style.setProperty('animation-play-state', 'running', 'important');
        container.style.setProperty('animation-direction', 'normal', 'important');
        
        console.log('Applied animation:', container.style.animation);
    });
};
if (typeof module !== 'undefined' && module.exports) {
    module.exports = MNLogolistWidget;
}
