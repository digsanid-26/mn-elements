/**
 * MN Social Reviews Widget JavaScript
 * Handles carousel functionality for review items
 */

class MNSocialReviewsWidget {
    constructor(element) {
        this.element = element;
        this.container = element.querySelector('.mn-social-reviews-container');
        this.isCarousel = element.classList.contains('mn-social-reviews-carousel');
        
        if (this.isCarousel) {
            this.init();
        }
    }

    init() {
        this.setupCarousel();
        this.bindEvents();
    }

    setupCarousel() {
        // Clone items for infinite loop
        this.cloneItems();
    }

    cloneItems() {
        if (!this.container) return;

        const originalItems = this.container.querySelectorAll('.mn-review-item:not(.mn-review-clone)');
        const itemsArray = Array.from(originalItems);
        
        // Clone items to create seamless loop
        itemsArray.forEach(item => {
            const clone = item.cloneNode(true);
            clone.classList.add('mn-review-clone');
            this.container.appendChild(clone);
        });

        console.log('MN Social Reviews Carousel: Cloned', itemsArray.length, 'items');
    }

    bindEvents() {
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
        // Recalculate on resize if needed
        setTimeout(() => {
            // Remove existing clones
            const clones = this.container.querySelectorAll('.mn-review-clone');
            clones.forEach(clone => clone.remove());
            
            // Recreate clones
            this.cloneItems();
        }, 100);
    }

    destroy() {
        // Remove cloned items
        const clones = this.container.querySelectorAll('.mn-review-clone');
        clones.forEach(clone => clone.remove());

        // Remove event listeners
        window.removeEventListener('resize', this.handleResize);
        document.removeEventListener('visibilitychange', this.handleVisibilityChange);
    }
}

// Initialize widgets when DOM is ready
jQuery(document).ready(function($) {
    // Initialize all social reviews widgets
    $('.mn-social-reviews-wrapper').each(function() {
        const instance = new MNSocialReviewsWidget(this);
        
        // Add instance reference to element for easy access
        this.mnSocialReviewsInstance = instance;
    });
    
    // Initialize widgets when Elementor frontend is ready
    if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-social-reviews.default', function(scope) {
            const element = scope.find('.mn-social-reviews-wrapper')[0];
            if (element && !element.mnSocialReviewsInstance) {
                const instance = new MNSocialReviewsWidget(element);
                element.mnSocialReviewsInstance = instance;
            }
        });
    }
});

if (typeof module !== 'undefined' && module.exports) {
    module.exports = MNSocialReviewsWidget;
}
