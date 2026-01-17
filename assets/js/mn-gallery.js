/**
 * MN Gallery Widget JavaScript
 */

class MNGalleryWidget {
    constructor(element) {
        this.element = element;
        this.lightboxEnabled = element.classList.contains('mn-gallery-lightbox-enabled');
        this.currentLightboxIndex = 0;
        this.images = [];
        
        this.init();
    }
    
    init() {
        this.collectImages();
        this.initializeLayout();
        
        if (this.lightboxEnabled) {
            this.initLightbox();
        }
    }
    
    collectImages() {
        const galleryItems = this.element.querySelectorAll('.mn-gallery-item');
        this.images = Array.from(galleryItems).map(item => {
            const img = item.querySelector('img');
            const lightboxData = item.querySelector('[data-lightbox]');
            return {
                element: item,
                src: lightboxData ? lightboxData.dataset.src : img.src,
                caption: lightboxData ? lightboxData.dataset.caption : '',
                alt: img.alt
            };
        });
    }
    
    initializeLayout() {
        if (this.element.classList.contains('mn-gallery-layout-slideshow')) {
            this.initSlideshow();
        } else if (this.element.classList.contains('mn-gallery-layout-masonry')) {
            this.initMasonry();
        }
    }
    
    initSlideshow() {
        const slideshow = this.element.querySelector('.mn-gallery-slideshow');
        if (!slideshow) return;
        
        const slides = slideshow.querySelectorAll('.mn-slideshow-slide');
        const prevBtn = slideshow.querySelector('.mn-slideshow-prev');
        const nextBtn = slideshow.querySelector('.mn-slideshow-next');
        const dots = slideshow.querySelectorAll('.mn-slideshow-dot');
        
        let currentSlide = 0;
        let autoplayInterval;
        
        const autoplay = slideshow.dataset.autoplay === 'yes';
        const autoplaySpeed = parseInt(slideshow.dataset.autoplaySpeed) || 3000;
        
        // Show slide function
        const showSlide = (index) => {
            slides.forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
            
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
            
            currentSlide = index;
        };
        
        // Next slide
        const nextSlide = () => {
            const next = (currentSlide + 1) % slides.length;
            showSlide(next);
        };
        
        // Previous slide
        const prevSlide = () => {
            const prev = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(prev);
        };
        
        // Start autoplay
        const startAutoplay = () => {
            if (autoplay) {
                autoplayInterval = setInterval(nextSlide, autoplaySpeed);
            }
        };
        
        // Stop autoplay
        const stopAutoplay = () => {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
            }
        };
        
        // Event listeners
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                prevSlide();
                stopAutoplay();
                setTimeout(startAutoplay, 5000); // Restart after 5 seconds
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                nextSlide();
                stopAutoplay();
                setTimeout(startAutoplay, 5000); // Restart after 5 seconds
            });
        }
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                showSlide(index);
                stopAutoplay();
                setTimeout(startAutoplay, 5000); // Restart after 5 seconds
            });
        });
        
        // Pause on hover
        slideshow.addEventListener('mouseenter', stopAutoplay);
        slideshow.addEventListener('mouseleave', startAutoplay);
        
        // Touch/swipe support
        let startX = 0;
        let startY = 0;
        
        slideshow.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });
        
        slideshow.addEventListener('touchend', (e) => {
            if (!startX || !startY) return;
            
            const endX = e.changedTouches[0].clientX;
            const endY = e.changedTouches[0].clientY;
            
            const diffX = startX - endX;
            const diffY = startY - endY;
            
            // Only handle horizontal swipes
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    nextSlide(); // Swipe left - next slide
                } else {
                    prevSlide(); // Swipe right - previous slide
                }
                stopAutoplay();
                setTimeout(startAutoplay, 5000);
            }
            
            startX = 0;
            startY = 0;
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!slideshow.matches(':hover')) return;
            
            if (e.key === 'ArrowLeft') {
                prevSlide();
                stopAutoplay();
                setTimeout(startAutoplay, 5000);
            } else if (e.key === 'ArrowRight') {
                nextSlide();
                stopAutoplay();
                setTimeout(startAutoplay, 5000);
            }
        });
        
        // Start autoplay
        startAutoplay();
    }
    
    initMasonry() {
        // Simple masonry-like layout adjustment
        const container = this.element.querySelector('.mn-gallery-masonry');
        if (!container) return;
        
        const resizeObserver = new ResizeObserver(() => {
            this.adjustMasonryLayout(container);
        });
        
        resizeObserver.observe(container);
        
        // Initial layout
        setTimeout(() => this.adjustMasonryLayout(container), 100);
    }
    
    adjustMasonryLayout(container) {
        // This is a simple implementation
        // For more advanced masonry, consider using a library like Masonry.js
        const items = container.querySelectorAll('.mn-gallery-item');
        items.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
        });
    }
    
    initLightbox() {
        // Create lightbox HTML
        this.createLightboxHTML();
        
        // Add click listeners to gallery items
        this.images.forEach((image, index) => {
            image.element.addEventListener('click', (e) => {
                e.preventDefault();
                this.openLightbox(index);
            });
        });
    }
    
    createLightboxHTML() {
        if (document.querySelector('.mn-lightbox-overlay')) return;
        
        const lightboxHTML = `
            <div class="mn-lightbox-overlay">
                <div class="mn-lightbox-content">
                    <button class="mn-lightbox-close" aria-label="Close">
                        <i class="eicon-close"></i>
                    </button>
                    <button class="mn-lightbox-nav mn-lightbox-prev" aria-label="Previous">
                        <i class="eicon-chevron-left"></i>
                    </button>
                    <button class="mn-lightbox-nav mn-lightbox-next" aria-label="Next">
                        <i class="eicon-chevron-right"></i>
                    </button>
                    <img class="mn-lightbox-image" src="" alt="">
                    <div class="mn-lightbox-caption"></div>
                    <div class="mn-lightbox-counter"></div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', lightboxHTML);
        
        // Add event listeners
        const overlay = document.querySelector('.mn-lightbox-overlay');
        const closeBtn = overlay.querySelector('.mn-lightbox-close');
        const prevBtn = overlay.querySelector('.mn-lightbox-prev');
        const nextBtn = overlay.querySelector('.mn-lightbox-next');
        const image = overlay.querySelector('.mn-lightbox-image');
        
        closeBtn.addEventListener('click', () => this.closeLightbox());
        prevBtn.addEventListener('click', () => this.prevLightboxImage());
        nextBtn.addEventListener('click', () => this.nextLightboxImage());
        
        // Close on overlay click
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                this.closeLightbox();
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!overlay.classList.contains('active')) return;
            
            switch (e.key) {
                case 'Escape':
                    this.closeLightbox();
                    break;
                case 'ArrowLeft':
                    this.prevLightboxImage();
                    break;
                case 'ArrowRight':
                    this.nextLightboxImage();
                    break;
            }
        });
        
        // Prevent body scroll when lightbox is open
        overlay.addEventListener('wheel', (e) => {
            e.preventDefault();
        });
    }
    
    openLightbox(index) {
        this.currentLightboxIndex = index;
        const overlay = document.querySelector('.mn-lightbox-overlay');
        const image = overlay.querySelector('.mn-lightbox-image');
        const caption = overlay.querySelector('.mn-lightbox-caption');
        const counter = overlay.querySelector('.mn-lightbox-counter');
        
        // Show loading state
        image.style.opacity = '0';
        
        // Load image
        const img = new Image();
        img.onload = () => {
            image.src = this.images[index].src;
            image.alt = this.images[index].alt;
            image.style.opacity = '1';
        };
        img.src = this.images[index].src;
        
        // Set caption
        caption.textContent = this.images[index].caption || '';
        caption.style.display = this.images[index].caption ? 'block' : 'none';
        
        // Set counter
        counter.textContent = `${index + 1} / ${this.images.length}`;
        
        // Show lightbox
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Hide navigation if only one image
        const navButtons = overlay.querySelectorAll('.mn-lightbox-nav');
        navButtons.forEach(btn => {
            btn.style.display = this.images.length > 1 ? 'flex' : 'none';
        });
    }
    
    closeLightbox() {
        const overlay = document.querySelector('.mn-lightbox-overlay');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    nextLightboxImage() {
        const nextIndex = (this.currentLightboxIndex + 1) % this.images.length;
        this.openLightbox(nextIndex);
    }
    
    prevLightboxImage() {
        const prevIndex = (this.currentLightboxIndex - 1 + this.images.length) % this.images.length;
        this.openLightbox(prevIndex);
    }
}

// Initialize MN Gallery widgets
document.addEventListener('DOMContentLoaded', function() {
    const galleries = document.querySelectorAll('.mn-gallery-wrapper');
    galleries.forEach(gallery => {
        if (!gallery.dataset.mnGalleryInitialized) {
            new MNGalleryWidget(gallery);
            gallery.dataset.mnGalleryInitialized = 'true';
        }
    });
});

// Elementor frontend integration with proper timing
jQuery(document).ready(function($) {
    // Wait for Elementor frontend to be ready
    if (typeof elementorFrontend !== 'undefined') {
        $(document).one('elementor/frontend/init', function() {
            if (elementorFrontend && elementorFrontend.hooks) {
                elementorFrontend.hooks.addAction('frontend/element_ready/mn-gallery.default', function($scope) {
                    const gallery = $scope.find('.mn-gallery-wrapper')[0];
                    if (gallery && !gallery.dataset.mnGalleryInitialized) {
                        new MNGalleryWidget(gallery);
                        gallery.dataset.mnGalleryInitialized = 'true';
                    }
                });
            }
        });
    }
});

// Handle dynamic content loading (AJAX, infinite scroll, etc.)
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        mutation.addedNodes.forEach(function(node) {
            if (node.nodeType === 1) { // Element node
                const galleries = node.querySelectorAll ? node.querySelectorAll('.mn-gallery-wrapper') : [];
                galleries.forEach(gallery => {
                    if (!gallery.dataset.mnGalleryInitialized) {
                        new MNGalleryWidget(gallery);
                        gallery.dataset.mnGalleryInitialized = 'true';
                    }
                });
            }
        });
    });
});

observer.observe(document.body, {
    childList: true,
    subtree: true
});
