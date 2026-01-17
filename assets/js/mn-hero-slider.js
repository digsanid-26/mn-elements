/**
 * MN Hero Slider Widget
 * Handles hero slider functionality with autoplay, navigation, and animations
 */

class MNHeroSlider {
    constructor(element) {
        this.element = element;
        this.wrapper = element.querySelector('.mn-hero-slider-wrapper');
        this.slider = element.querySelector('.mn-hero-slider');
        this.slides = Array.from(element.querySelectorAll('.mn-hero-slide'));
        this.prevArrow = element.querySelector('.mn-hero-arrow-prev');
        this.nextArrow = element.querySelector('.mn-hero-arrow-next');
        this.dots = Array.from(element.querySelectorAll('.mn-hero-dot'));
        
        this.currentIndex = 0;
        this.isAnimating = false;
        this.autoplayTimer = null;
        this.isPaused = false;
        
        // Get settings from data attribute
        const settingsData = this.wrapper.getAttribute('data-settings');
        this.settings = settingsData ? JSON.parse(settingsData) : {};
        
        // Default settings
        this.settings = {
            autoplay: this.settings.autoplay !== false,
            autoplaySpeed: this.settings.autoplaySpeed || 5000,
            pauseOnHover: this.settings.pauseOnHover !== false,
            animationSpeed: this.settings.animationSpeed || 800,
            infinite: this.settings.infinite !== false,
            transitionEffect: this.settings.transitionEffect || 'slide'
        };
        
        this.init();
    }
    
    init() {
        if (this.slides.length === 0) {
            console.warn('MN Hero Slider: No slides found');
            return;
        }
        
        // Set initial active slide
        this.slides[0].classList.add('active');
        if (this.dots.length > 0) {
            this.dots[0].classList.add('active');
        }
        
        // Bind events
        this.bindEvents();
        
        // Start autoplay if enabled
        if (this.settings.autoplay) {
            this.startAutoplay();
        }
        
        // Remove loading state
        this.wrapper.classList.remove('loading');
        
        console.log('MN Hero Slider initialized:', {
            slides: this.slides.length,
            autoplay: this.settings.autoplay,
            autoplaySpeed: this.settings.autoplaySpeed,
            animationSpeed: this.settings.animationSpeed,
            transitionEffect: this.settings.transitionEffect
        });
    }
    
    bindEvents() {
        // Arrow navigation
        if (this.prevArrow) {
            this.prevArrow.addEventListener('click', () => this.prev());
        }
        
        if (this.nextArrow) {
            this.nextArrow.addEventListener('click', () => this.next());
        }
        
        // Dot navigation
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToSlide(index));
        });
        
        // Pause on hover
        if (this.settings.pauseOnHover && this.settings.autoplay) {
            this.wrapper.addEventListener('mouseenter', () => this.pauseAutoplay());
            this.wrapper.addEventListener('mouseleave', () => this.resumeAutoplay());
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!this.isElementInViewport(this.wrapper)) return;
            
            if (e.key === 'ArrowLeft') {
                this.prev();
            } else if (e.key === 'ArrowRight') {
                this.next();
            }
        });
        
        // Handle visibility change (pause when tab is hidden)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.pauseAutoplay();
            } else if (!this.isPaused) {
                this.resumeAutoplay();
            }
        });
    }
    
    goToSlide(index, direction = 'next') {
        if (this.isAnimating || index === this.currentIndex) {
            return;
        }
        
        // Check infinite loop setting
        if (!this.settings.infinite) {
            if (index < 0 || index >= this.slides.length) {
                return;
            }
        }
        
        // Handle infinite loop
        if (index < 0) {
            index = this.slides.length - 1;
        } else if (index >= this.slides.length) {
            index = 0;
        }
        
        this.isAnimating = true;
        
        const currentSlide = this.slides[this.currentIndex];
        const nextSlide = this.slides[index];
        
        // Remove all animation classes from all slides first
        this.slides.forEach(slide => {
            slide.classList.remove(
                'slide-enter-right', 'slide-enter-left', 'slide-exit-right', 'slide-exit-left',
                'fade-enter', 'fade-exit',
                'carousel-enter-right', 'carousel-enter-left', 'carousel-exit-right', 'carousel-exit-left',
                'zoom-enter', 'zoom-exit',
                'flip-enter', 'flip-exit'
            );
        });
        
        // Apply transition effect based on settings
        const effect = this.settings.transitionEffect;
        
        console.log('MN Hero Slider: Applying transition effect:', effect, 'Direction:', direction);
        console.log('Current slide classes before:', currentSlide.className);
        console.log('Next slide classes before:', nextSlide.className);
        
        if (effect === 'fade') {
            // Fade effect
            nextSlide.classList.add('fade-enter');
            currentSlide.classList.add('fade-exit');
        } else if (effect === 'slide-fade') {
            // Slide + Fade effect
            if (direction === 'next') {
                nextSlide.classList.add('slide-enter-right', 'fade-enter');
                currentSlide.classList.add('slide-exit-left', 'fade-exit');
            } else {
                nextSlide.classList.add('slide-enter-left', 'fade-enter');
                currentSlide.classList.add('slide-exit-right', 'fade-exit');
            }
        } else if (effect === 'carousel') {
            // Carousel effect with 3D perspective
            if (direction === 'next') {
                nextSlide.classList.add('carousel-enter-right');
                currentSlide.classList.add('carousel-exit-left');
            } else {
                nextSlide.classList.add('carousel-enter-left');
                currentSlide.classList.add('carousel-exit-right');
            }
        } else if (effect === 'zoom') {
            // Zoom effect
            nextSlide.classList.add('zoom-enter');
            currentSlide.classList.add('zoom-exit');
        } else if (effect === 'flip') {
            // Flip effect
            if (direction === 'next') {
                nextSlide.classList.add('flip-enter');
                currentSlide.classList.add('flip-exit');
            } else {
                nextSlide.classList.add('flip-enter');
                currentSlide.classList.add('flip-exit');
            }
        } else {
            // Default: Slide effect
            if (direction === 'next') {
                nextSlide.classList.add('slide-enter-right');
                currentSlide.classList.add('slide-exit-left');
            } else {
                nextSlide.classList.add('slide-enter-left');
                currentSlide.classList.add('slide-exit-right');
            }
        }
        
        // Force reflow to ensure classes are applied
        nextSlide.offsetHeight;
        
        // Use requestAnimationFrame to ensure proper timing
        requestAnimationFrame(() => {
            // Add active class to trigger animation
            nextSlide.classList.add('active');
            
            // Remove active from current slide
            currentSlide.classList.remove('active');
            
            console.log('Current slide classes after:', currentSlide.className);
            console.log('Next slide classes after:', nextSlide.className);
        });
        
        // Update dots
        if (this.dots.length > 0) {
            this.dots[this.currentIndex].classList.remove('active');
            this.dots[index].classList.add('active');
        }
        
        // Update current index
        this.currentIndex = index;
        
        // Clean up after animation completes
        setTimeout(() => {
            // Remove all animation classes
            this.slides.forEach(slide => {
                slide.classList.remove(
                    'slide-enter-right', 'slide-enter-left', 'slide-exit-right', 'slide-exit-left',
                    'fade-enter', 'fade-exit',
                    'carousel-enter-right', 'carousel-enter-left', 'carousel-exit-right', 'carousel-exit-left',
                    'zoom-enter', 'zoom-exit',
                    'flip-enter', 'flip-exit'
                );
            });
            
            this.isAnimating = false;
        }, this.settings.animationSpeed);
        
        // Reset autoplay timer
        if (this.settings.autoplay && !this.isPaused) {
            this.resetAutoplay();
        }
    }
    
    next() {
        this.goToSlide(this.currentIndex + 1, 'next');
    }
    
    prev() {
        this.goToSlide(this.currentIndex - 1, 'prev');
    }
    
    startAutoplay() {
        if (!this.settings.autoplay || this.autoplayTimer) {
            return;
        }
        
        this.autoplayTimer = setInterval(() => {
            if (!this.isPaused && !this.isAnimating) {
                this.next();
            }
        }, this.settings.autoplaySpeed);
        
        console.log('MN Hero Slider: Autoplay started');
    }
    
    stopAutoplay() {
        if (this.autoplayTimer) {
            clearInterval(this.autoplayTimer);
            this.autoplayTimer = null;
            console.log('MN Hero Slider: Autoplay stopped');
        }
    }
    
    pauseAutoplay() {
        this.isPaused = true;
        console.log('MN Hero Slider: Autoplay paused');
    }
    
    resumeAutoplay() {
        this.isPaused = false;
        console.log('MN Hero Slider: Autoplay resumed');
    }
    
    resetAutoplay() {
        this.stopAutoplay();
        this.startAutoplay();
    }
    
    isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
    
    destroy() {
        this.stopAutoplay();
        
        // Remove event listeners
        if (this.prevArrow) {
            this.prevArrow.removeEventListener('click', () => this.prev());
        }
        
        if (this.nextArrow) {
            this.nextArrow.removeEventListener('click', () => this.next());
        }
        
        this.dots.forEach((dot, index) => {
            dot.removeEventListener('click', () => this.goToSlide(index));
        });
        
        console.log('MN Hero Slider: Destroyed');
    }
}

// Initialize sliders
function initMNHeroSliders() {
    const sliders = document.querySelectorAll('.elementor-widget-mn-hero-slider');
    
    sliders.forEach(slider => {
        if (!slider.mnHeroSliderInstance) {
            slider.mnHeroSliderInstance = new MNHeroSlider(slider);
        }
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMNHeroSliders);
} else {
    initMNHeroSliders();
}

// Initialize for Elementor frontend
if (typeof elementorFrontend !== 'undefined') {
    jQuery(window).on('elementor/frontend/init', function() {
        if (elementorFrontend && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/mn-hero-slider.default', function($scope) {
                const element = $scope[0];
                if (!element.mnHeroSliderInstance) {
                    element.mnHeroSliderInstance = new MNHeroSlider(element);
                }
            });
        }
    });
}

// Reinitialize on window load (for cached pages)
window.addEventListener('load', function() {
    initMNHeroSliders();
});

// Export for global access
window.MNHeroSlider = MNHeroSlider;
