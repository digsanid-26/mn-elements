/**
 * MN Gootesti Widget JavaScript
 * Google Business Reviews & Testimonials
 */

class MNGootestiWidget {
	constructor(element) {
		this.element = element;
		this.slider = element.querySelector('.mn-gootesti-slider');
		
		if (this.slider) {
			this.initSlider();
		}
	}
	
	initSlider() {
		this.slides = this.slider.querySelector('.mn-gootesti-slides');
		this.slideItems = this.slider.querySelectorAll('.mn-gootesti-slide');
		this.prevBtn = this.slider.querySelector('.mn-slider-prev');
		this.nextBtn = this.slider.querySelector('.mn-slider-next');
		this.dotsContainer = this.slider.querySelector('.mn-slider-dots');
		
		this.currentSlide = 0;
		this.totalSlides = this.slideItems.length;
		
		// Get settings from data attributes
		this.autoplay = this.slider.dataset.autoplay === 'yes';
		this.autoplaySpeed = parseInt(this.slider.dataset.autoplaySpeed) || 5000;
		this.showArrows = this.slider.dataset.showArrows === 'yes';
		this.showDots = this.slider.dataset.showDots === 'yes';
		
		// Initialize dots
		if (this.showDots) {
			this.initDots();
		}
		
		// Initialize arrows
		if (this.showArrows && this.prevBtn && this.nextBtn) {
			this.initArrows();
		}
		
		// Initialize autoplay
		if (this.autoplay) {
			this.startAutoplay();
		}
		
		// Touch/swipe support
		this.initTouch();
		
		// Keyboard navigation
		this.initKeyboard();
		
		// Show first slide
		this.goToSlide(0);
	}
	
	initDots() {
		if (!this.dotsContainer) return;
		
		this.dotsContainer.innerHTML = '';
		
		for (let i = 0; i < this.totalSlides; i++) {
			const dot = document.createElement('button');
			dot.classList.add('mn-slider-dot');
			if (i === 0) dot.classList.add('active');
			dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
			dot.addEventListener('click', () => {
				this.goToSlide(i);
				this.stopAutoplay();
				if (this.autoplay) {
					setTimeout(() => this.startAutoplay(), 5000);
				}
			});
			this.dotsContainer.appendChild(dot);
		}
		
		this.dots = this.dotsContainer.querySelectorAll('.mn-slider-dot');
	}
	
	initArrows() {
		this.prevBtn.addEventListener('click', () => {
			this.prevSlide();
			this.stopAutoplay();
			if (this.autoplay) {
				setTimeout(() => this.startAutoplay(), 5000);
			}
		});
		
		this.nextBtn.addEventListener('click', () => {
			this.nextSlide();
			this.stopAutoplay();
			if (this.autoplay) {
				setTimeout(() => this.startAutoplay(), 5000);
			}
		});
	}
	
	initTouch() {
		let startX = 0;
		let startY = 0;
		
		this.slider.addEventListener('touchstart', (e) => {
			startX = e.touches[0].clientX;
			startY = e.touches[0].clientY;
		});
		
		this.slider.addEventListener('touchend', (e) => {
			if (!startX || !startY) return;
			
			const endX = e.changedTouches[0].clientX;
			const endY = e.changedTouches[0].clientY;
			
			const diffX = startX - endX;
			const diffY = startY - endY;
			
			// Only handle horizontal swipes
			if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
				if (diffX > 0) {
					this.nextSlide();
				} else {
					this.prevSlide();
				}
				this.stopAutoplay();
				if (this.autoplay) {
					setTimeout(() => this.startAutoplay(), 5000);
				}
			}
			
			startX = 0;
			startY = 0;
		});
	}
	
	initKeyboard() {
		document.addEventListener('keydown', (e) => {
			if (!this.slider.matches(':hover')) return;
			
			if (e.key === 'ArrowLeft') {
				this.prevSlide();
				this.stopAutoplay();
				if (this.autoplay) {
					setTimeout(() => this.startAutoplay(), 5000);
				}
			} else if (e.key === 'ArrowRight') {
				this.nextSlide();
				this.stopAutoplay();
				if (this.autoplay) {
					setTimeout(() => this.startAutoplay(), 5000);
				}
			}
		});
	}
	
	goToSlide(index) {
		this.currentSlide = index;
		const offset = -index * 100;
		this.slides.style.transform = `translateX(${offset}%)`;
		
		// Update dots
		if (this.dots) {
			this.dots.forEach((dot, i) => {
				dot.classList.toggle('active', i === index);
			});
		}
	}
	
	nextSlide() {
		const next = (this.currentSlide + 1) % this.totalSlides;
		this.goToSlide(next);
	}
	
	prevSlide() {
		const prev = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
		this.goToSlide(prev);
	}
	
	startAutoplay() {
		this.stopAutoplay();
		this.autoplayInterval = setInterval(() => {
			this.nextSlide();
		}, this.autoplaySpeed);
	}
	
	stopAutoplay() {
		if (this.autoplayInterval) {
			clearInterval(this.autoplayInterval);
		}
	}
}

// Initialize widgets on DOM ready
document.addEventListener('DOMContentLoaded', function() {
	const widgets = document.querySelectorAll('.mn-gootesti-wrapper');
	widgets.forEach(widget => {
		if (!widget.dataset.mnGootestiInitialized) {
			new MNGootestiWidget(widget);
			widget.dataset.mnGootestiInitialized = 'true';
		}
	});
});

// Elementor frontend integration
jQuery(document).ready(function($) {
	if (typeof elementorFrontend !== 'undefined') {
		$(document).one('elementor/frontend/init', function() {
			if (elementorFrontend && elementorFrontend.hooks) {
				elementorFrontend.hooks.addAction('frontend/element_ready/mn-gootesti.default', function($scope) {
					const widget = $scope.find('.mn-gootesti-wrapper')[0];
					if (widget && !widget.dataset.mnGootestiInitialized) {
						new MNGootestiWidget(widget);
						widget.dataset.mnGootestiInitialized = 'true';
					}
				});
			}
		});
	}
});

// Handle dynamic content loading
const mnGootestiObserver = new MutationObserver(function(mutations) {
	mutations.forEach(function(mutation) {
		mutation.addedNodes.forEach(function(node) {
			if (node.nodeType === 1) {
				const widgets = node.querySelectorAll ? node.querySelectorAll('.mn-gootesti-wrapper') : [];
				widgets.forEach(widget => {
					if (!widget.dataset.mnGootestiInitialized) {
						new MNGootestiWidget(widget);
						widget.dataset.mnGootestiInitialized = 'true';
					}
				});
			}
		});
	});
});

mnGootestiObserver.observe(document.body, {
	childList: true,
	subtree: true
});
