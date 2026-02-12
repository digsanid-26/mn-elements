/**
 * MN Mobile Menu JavaScript
 * Optimized for mobile devices and Safari/iOS browsers
 */

(function($) {
	'use strict';

	class MNMobileMenu {
		constructor($scope) {
			this.$scope = $scope;
			this.$wrapper = $scope.find('.mn-mbmenu-wrapper');
			this.$toggle = $scope.find('.mn-mbmenu-toggle');
			this.$panel = $scope.find('.mn-mbmenu-panel');
			this.$overlay = $scope.find('.mn-mbmenu-overlay');
			this.$close = $scope.find('.mn-mbmenu-close');
			this.$body = $('body');
			this.$menuItems = $scope.find('.menu-item-has-children');
			
			this.isOpen = false;
			this.animationSpeed = parseInt(this.$panel.data('animation-speed')) || 300;
			this.touchStartX = 0;
			this.touchStartY = 0;
			this.touchEndX = 0;
			this.touchEndY = 0;
			
			this.init();
		}

		init() {
			this.bindEvents();
			this.setupSubmenuToggles();
			this.preventBodyScroll();
			this.setupSwipeGestures();
			this.setupAccessibility();

			// Signal that widget is fully initialized (anti-FOUC)
			this.$wrapper.addClass('mn-mbmenu-ready');
		}

		bindEvents() {
			this.$toggle.on('click', (e) => {
				e.preventDefault();
				e.stopPropagation();
				this.toggleMenu();
			});

			this.$toggle.on('keydown', (e) => {
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					this.toggleMenu();
				}
			});

			this.$close.on('click', (e) => {
				e.preventDefault();
				e.stopPropagation();
				this.closeMenu();
			});

			this.$overlay.on('click', (e) => {
				e.preventDefault();
				this.closeMenu();
			});

			$(document).on('keydown', (e) => {
				if (e.key === 'Escape' && this.isOpen) {
					this.closeMenu();
				}
			});

			$(window).on('resize', this.debounce(() => {
				if (this.isOpen) {
					this.adjustPanelHeight();
				}
			}, 250));
		}

		toggleMenu() {
			if (this.isOpen) {
				this.closeMenu();
			} else {
				this.openMenu();
			}
		}

		openMenu() {
			this.isOpen = true;
			
			this.$body.addClass('mn-mbmenu-open');
			
			this.$overlay.addClass('active');
			
			this.$toggle.addClass('active').attr('aria-expanded', 'true');
			
			// Make panel visible first, then animate in
			this.$panel.css({
				'visibility': 'visible',
				'transition': `transform ${this.animationSpeed}ms cubic-bezier(0.4, 0, 0.2, 1), opacity ${this.animationSpeed}ms cubic-bezier(0.4, 0, 0.2, 1)`
			});
			
			requestAnimationFrame(() => {
				this.$panel.addClass('active');
			});
			
			this.adjustPanelHeight();
			
			setTimeout(() => {
				this.$close.focus();
			}, this.animationSpeed);
			
			this.trapFocus();
		}

		closeMenu() {
			this.isOpen = false;
			
			this.$panel.removeClass('active');
			this.$overlay.removeClass('active');
			this.$toggle.removeClass('active').attr('aria-expanded', 'false');
			
			// Remove body scroll lock
			this.$body.css({
				'overflow': '',
				'position': '',
				'top': '',
				'width': ''
			});
			
			// Restore scroll position
			if (this.scrollPosition) {
				window.scrollTo(0, this.scrollPosition);
				this.scrollPosition = 0;
			}
			
			// Hide panel after close animation completes
			setTimeout(() => {
				this.$body.removeClass('mn-mbmenu-open');
				if (!this.isOpen) {
					this.$panel.css('visibility', 'hidden');
				}
			}, this.animationSpeed);
			
			this.removeFocusTrap();
		}

		setupSubmenuToggles() {
			const self = this;
			
			// Handle menu items WITH submenu
			this.$menuItems.each((index, item) => {
				const $item = $(item);
				const $link = $item.children('a').first();
				const $submenu = $item.children('.sub-menu').first();
				
				if ($submenu.length) {
					$link.on('click', (e) => {
						// If already active and has a valid href, close menu and navigate
						if ($item.hasClass('active')) {
							const href = $link.attr('href');
							if (href && href !== '#' && href !== '') {
								self.handleMenuLinkClick(e, href);
								return false;
							}
							return true;
						}
						
						e.preventDefault();
						e.stopPropagation();
						
						this.$menuItems.not($item).removeClass('active');
						this.$menuItems.not($item).children('.sub-menu').slideUp(200);
						
						$item.toggleClass('active');
						
						if ($item.hasClass('active')) {
							$submenu.slideDown(200);
						} else {
							$submenu.slideUp(200);
						}
					});
					
					$link.attr('aria-haspopup', 'true');
					$link.attr('aria-expanded', 'false');
					
					$item.on('toggleSubmenu', function() {
						const isActive = $item.hasClass('active');
						$link.attr('aria-expanded', isActive ? 'true' : 'false');
					});
				}
			});
			
			// Handle ALL menu link clicks (including submenu items and items without submenu)
			this.$scope.find('.mn-mbmenu-nav a').on('click', (e) => {
				const $link = $(e.currentTarget);
				const $parentItem = $link.parent('.menu-item');
				const href = $link.attr('href');
				
				// Skip if this is a parent item with submenu that's not yet active
				if ($parentItem.hasClass('menu-item-has-children') && !$parentItem.hasClass('active')) {
					return; // Let the submenu toggle handler deal with it
				}
				
				// Handle the link click
				if (href && href !== '#' && href !== '') {
					self.handleMenuLinkClick(e, href);
				}
			});
		}
		
		/**
		 * Handle menu link click - supports anchor links for onepage websites
		 */
		handleMenuLinkClick(e, href) {
			// Check if it's an anchor link (starts with # or contains # for same page)
			const isAnchorLink = href.startsWith('#') || 
				(href.includes('#') && (href.startsWith(window.location.origin) || href.startsWith('/')));
			
			if (isAnchorLink) {
				e.preventDefault();
				e.stopPropagation();
				
				// Extract anchor from href
				let anchor = href;
				if (href.includes('#')) {
					anchor = '#' + href.split('#')[1];
				}
				
				// Close menu for anchor navigation (don't restore scroll position)
				this.closeMenuForAnchor();
				
				// Wait for menu close animation, then scroll to anchor
				setTimeout(() => {
					const $target = $(anchor);
					if ($target.length) {
						// Smooth scroll to target
						$('html, body').animate({
							scrollTop: $target.offset().top - 50 // 50px offset for fixed headers
						}, 500, 'swing');
					}
				}, this.animationSpeed + 50);
			} else {
				// Regular link - just close menu and let browser navigate
				this.closeMenu();
			}
		}
		
		/**
		 * Close menu specifically for anchor navigation
		 * Does not restore scroll position since we're scrolling to anchor
		 */
		closeMenuForAnchor() {
			this.isOpen = false;
			
			// Remove all active classes
			this.$panel.removeClass('active');
			this.$overlay.removeClass('active');
			this.$toggle.removeClass('active').attr('aria-expanded', 'false');
			
			// Remove body scroll lock immediately
			this.$body.css({
				'overflow': '',
				'position': '',
				'top': '',
				'width': ''
			});
			
			// Reset scroll position tracker (don't restore - we're scrolling to anchor)
			this.scrollPosition = 0;
			
			// Hide panel after close animation completes
			setTimeout(() => {
				this.$body.removeClass('mn-mbmenu-open');
				if (!this.isOpen) {
					this.$panel.css('visibility', 'hidden');
				}
			}, this.animationSpeed);
			
			this.removeFocusTrap();
		}

		preventBodyScroll() {
			// Store scroll position as class property for use in closeMenu
			this.scrollPosition = 0;
			
			this.$toggle.on('click', () => {
				if (!this.isOpen) {
					this.scrollPosition = window.pageYOffset;
					this.$body.css({
						'overflow': 'hidden',
						'position': 'fixed',
						'top': `-${this.scrollPosition}px`,
						'width': '100%'
					});
				}
			});
			
			// Note: Body scroll unlock is now handled in closeMenu() method
			// to ensure it works for all close triggers (close button, overlay, menu links, escape key)
		}

		setupSwipeGestures() {
			const minSwipeDistance = 50;
			
			this.$panel.on('touchstart', (e) => {
				this.touchStartX = e.changedTouches[0].screenX;
				this.touchStartY = e.changedTouches[0].screenY;
			}, { passive: true });
			
			this.$panel.on('touchend', (e) => {
				this.touchEndX = e.changedTouches[0].screenX;
				this.touchEndY = e.changedTouches[0].screenY;
				this.handleSwipe();
			}, { passive: true });
		}

		handleSwipe() {
			const deltaX = this.touchEndX - this.touchStartX;
			const deltaY = this.touchEndY - this.touchStartY;
			const minSwipeDistance = 50;
			
			if (Math.abs(deltaX) < minSwipeDistance) {
				return;
			}
			
			if (Math.abs(deltaY) > Math.abs(deltaX)) {
				return;
			}
			
			const position = this.$panel.hasClass('mn-mbmenu-position-left') ? 'left' : 
			                 this.$panel.hasClass('mn-mbmenu-position-right') ? 'right' : 'full';
			
			if (position === 'left' && deltaX < 0) {
				this.closeMenu();
			} else if (position === 'right' && deltaX > 0) {
				this.closeMenu();
			} else if (position === 'full' && deltaX < 0) {
				this.closeMenu();
			}
		}

		adjustPanelHeight() {
			if (this.isIOS()) {
				const vh = window.innerHeight * 0.01;
				this.$panel.css('height', `${window.innerHeight}px`);
			}
		}

		isIOS() {
			return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
		}

		isSafari() {
			return /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
		}

		trapFocus() {
			const focusableElements = this.$panel.find('a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])');
			const firstFocusable = focusableElements.first();
			const lastFocusable = focusableElements.last();
			
			this.$panel.on('keydown.focustrap', (e) => {
				if (e.key !== 'Tab') return;
				
				if (e.shiftKey) {
					if (document.activeElement === firstFocusable[0]) {
						e.preventDefault();
						lastFocusable.focus();
					}
				} else {
					if (document.activeElement === lastFocusable[0]) {
						e.preventDefault();
						firstFocusable.focus();
					}
				}
			});
		}

		removeFocusTrap() {
			this.$panel.off('keydown.focustrap');
		}

		setupAccessibility() {
			this.$toggle.attr('aria-label', 'Toggle mobile menu');
			this.$toggle.attr('aria-expanded', 'false');
			this.$toggle.attr('aria-controls', 'mn-mobile-menu-panel');
			
			this.$panel.attr('id', 'mn-mobile-menu-panel');
			this.$panel.attr('role', 'navigation');
			this.$panel.attr('aria-label', 'Mobile navigation menu');
			
			this.$close.attr('aria-label', 'Close mobile menu');
			
			this.$overlay.attr('role', 'button');
			this.$overlay.attr('aria-label', 'Close menu overlay');
			this.$overlay.attr('tabindex', '-1');
		}

		debounce(func, wait) {
			let timeout;
			return function executedFunction(...args) {
				const later = () => {
					clearTimeout(timeout);
					func(...args);
				};
				clearTimeout(timeout);
				timeout = setTimeout(later, wait);
			};
		}

		destroy() {
			this.$toggle.off('click keydown');
			this.$close.off('click');
			this.$overlay.off('click');
			$(document).off('keydown');
			$(window).off('resize');
			this.$menuItems.each((index, item) => {
				$(item).children('a').first().off('click');
			});
			this.$panel.off('touchstart touchend keydown.focustrap');
			this.$body.removeClass('mn-mbmenu-open').css({
				'overflow': '',
				'position': '',
				'top': '',
				'width': ''
			});
		}
	}

	$(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction('frontend/element_ready/mn-mbmenu.default', function($scope) {
			new MNMobileMenu($scope);
		});
	});

	if (typeof jQuery !== 'undefined') {
		$(document).ready(function() {
			$('.elementor-widget-mn-mbmenu').each(function() {
				if (!$(this).hasClass('mn-mbmenu-initialized')) {
					new MNMobileMenu($(this));
					$(this).addClass('mn-mbmenu-initialized');
				}
			});
		});
	}

})(jQuery);
