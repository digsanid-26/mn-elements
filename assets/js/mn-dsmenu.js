/**
 * MN Desktop Menu JavaScript
 * Handles submenu indicator injection, keyboard navigation, and accessibility
 */

(function($) {
	'use strict';

	class MNDesktopMenu {
		constructor($scope) {
			this.$scope = $scope;
			this.$nav = $scope.find('.mn-dsmenu-nav');
			this.indicatorType = this.$nav.data('indicator') || 'none';
			this.indicatorSvg = this.$nav.data('indicator-svg') || '';

			this.init();
		}

		init() {
			this.injectIndicators();
			this.setupKeyboardNav();
			this.setupSubmenuPosition();
			this.setupAccessibility();

			$(window).on('resize.mnDsMenu', this.debounce(() => {
				this.setupSubmenuPosition();
			}, 250));
		}

		injectIndicators() {
			if (this.indicatorType === 'none' || !this.indicatorSvg) {
				return;
			}

			var svg = this.indicatorSvg;

			this.$nav.find('.menu-item-has-children > a').each(function() {
				if ($(this).find('.mn-dsmenu-indicator').length === 0) {
					$(this).append('<span class="mn-dsmenu-indicator">' + svg + '</span>');
				}
			});
		}

		setupKeyboardNav() {
			var self = this;

			// Handle Escape key to close submenus
			this.$nav.on('keydown', 'a', function(e) {
				var $item = $(this).parent('li');

				if (e.key === 'Escape') {
					var $parentMenu = $item.parent('.sub-menu');
					if ($parentMenu.length) {
						$parentMenu.parent('li').children('a').first().focus();
					}
					e.preventDefault();
				}

				// Arrow down opens submenu
				if (e.key === 'ArrowDown') {
					var $submenu = $item.children('.sub-menu');
					if ($submenu.length) {
						e.preventDefault();
						$submenu.find('a').first().focus();
					}
				}

				// Arrow right for horizontal nav moves to next sibling
				if (e.key === 'ArrowRight') {
					var $next = $item.next('li');
					if ($next.length) {
						e.preventDefault();
						$next.children('a').first().focus();
					}
				}

				// Arrow left for horizontal nav moves to prev sibling
				if (e.key === 'ArrowLeft') {
					var $prev = $item.prev('li');
					if ($prev.length) {
						e.preventDefault();
						$prev.children('a').first().focus();
					}
				}
			});
		}

		setupSubmenuPosition() {
			// Prevent sub-menus from going off-screen on the right
			this.$nav.find('.sub-menu').each(function() {
				var $submenu = $(this);
				$submenu.removeClass('mn-dsmenu-submenu-left');

				var rect = this.getBoundingClientRect();
				var windowWidth = $(window).width();

				if (rect.right > windowWidth) {
					$submenu.addClass('mn-dsmenu-submenu-left');
				}
			});
		}

		setupAccessibility() {
			// Add aria attributes to menu items with children
			this.$nav.find('.menu-item-has-children').each(function() {
				var $item = $(this);
				var $link = $item.children('a').first();

				$link.attr('aria-haspopup', 'true');
				$link.attr('aria-expanded', 'false');

				$item.on('mouseenter focusin', function() {
					$link.attr('aria-expanded', 'true');
				});

				$item.on('mouseleave focusout', function() {
					// Delay to allow focus to move to submenu
					setTimeout(function() {
						if (!$item.find(':focus').length && !$item.is(':hover')) {
							$link.attr('aria-expanded', 'false');
						}
					}, 100);
				});
			});

			// Add role attributes
			this.$nav.find('ul').attr('role', 'menubar');
			this.$nav.find('.sub-menu').attr('role', 'menu');
			this.$nav.find('li').attr('role', 'none');
			this.$nav.find('a').attr('role', 'menuitem');
		}

		debounce(func, wait) {
			var timeout;
			return function() {
				var args = arguments;
				clearTimeout(timeout);
				timeout = setTimeout(function() {
					func.apply(this, args);
				}, wait);
			};
		}
	}

	// Elementor frontend init
	$(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction('frontend/element_ready/mn-dsmenu.default', function($scope) {
			new MNDesktopMenu($scope);
		});
	});

	// Non-Elementor fallback
	$(document).ready(function() {
		$('.elementor-widget-mn-dsmenu').each(function() {
			if (!$(this).hasClass('mn-dsmenu-initialized')) {
				new MNDesktopMenu($(this));
				$(this).addClass('mn-dsmenu-initialized');
			}
		});
	});

})(jQuery);
