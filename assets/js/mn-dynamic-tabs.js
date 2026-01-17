/**
 * MN Dynamic Tabs - Enhanced Version 3.1
 * Features: Default & Hero Templates, Hover/Click Triggers, Background Animations, Smart Navigation
 */
class MNDynamicTabs {
    constructor(element) {
        this.container = element;
        this.tabsNav = element.querySelector('.mn-tabs-nav');
        this.tabItems = element.querySelectorAll('.mn-tab-item');
        this.tabContents = element.querySelectorAll('.mn-tab-content');
        this.heroBackgrounds = element.querySelectorAll('.mn-hero-bg');
        
        // Detect template type
        this.isHeroTemplate = element.classList.contains('mn-template-hero');
        this.heroTrigger = this.isHeroTemplate && element.classList.contains('mn-hero-trigger-hover') ? 'hover' : 'click';
        
        this.init();
    }
    
    init() {
        // Setup navigation behavior
        this.setupNavigation();
        
        // Add event listeners based on template type
        this.tabItems.forEach((tabItem, index) => {
            // Click event (always enabled)
            tabItem.addEventListener('click', () => {
                this.switchTab(index);
            });
            
            // Hover event (for hero template with hover trigger)
            if (this.isHeroTemplate && this.heroTrigger === 'hover') {
                tabItem.addEventListener('mouseenter', () => {
                    this.switchTab(index);
                });
            }
            
            // Keyboard navigation
            tabItem.addEventListener('keydown', (e) => {
                this.handleKeyboard(e, index);
            });
        });
        
        // Handle window resize for responsive behavior
        window.addEventListener('resize', () => this.handleResize());
        this.handleResize();
    }
    
    setupNavigation() {
        if (!this.tabsNav) return;
        
        // Check if navigation needs scroll
        this.checkScrollNeeded();
        
        // Add scroll indicators if needed
        if (this.isScrollable()) {
            this.addScrollIndicators();
        }
    }
    
    checkScrollNeeded() {
        if (!this.tabsNav) return;
        
        const navWidth = this.tabsNav.scrollWidth;
        const containerWidth = this.tabsNav.clientWidth;
        
        if (navWidth > containerWidth) {
            this.tabsNav.classList.add('mn-scroll-enabled');
        } else {
            this.tabsNav.classList.remove('mn-scroll-enabled');
        }
    }
    
    isScrollable() {
        if (!this.tabsNav) return false;
        return this.tabsNav.scrollWidth > this.tabsNav.clientWidth;
    }
    
    addScrollIndicators() {
        // Add visual indicators for scrollable navigation
        if (this.tabsNav.scrollLeft > 0) {
            this.tabsNav.classList.add('mn-scroll-left');
        } else {
            this.tabsNav.classList.remove('mn-scroll-left');
        }
        
        const maxScroll = this.tabsNav.scrollWidth - this.tabsNav.clientWidth;
        if (this.tabsNav.scrollLeft < maxScroll - 1) {
            this.tabsNav.classList.add('mn-scroll-right');
        } else {
            this.tabsNav.classList.remove('mn-scroll-right');
        }
    }
    
    handleResize() {
        this.checkScrollNeeded();
        if (this.isScrollable()) {
            this.addScrollIndicators();
        }
    }
    
    switchTab(activeIndex) {
        // Remove active class from all tabs
        this.tabItems.forEach((item, index) => {
            item.classList.remove('active');
            item.setAttribute('aria-selected', 'false');
            item.setAttribute('tabindex', '-1');
            
            // Handle tab contents (for default template)
            if (this.tabContents[index]) {
                this.tabContents[index].classList.remove('active');
                this.tabContents[index].setAttribute('aria-hidden', 'true');
            }
            
            // Handle hero backgrounds (for hero template)
            if (this.heroBackgrounds[index]) {
                this.heroBackgrounds[index].classList.remove('active');
            }
        });
        
        // Add active class to selected tab
        if (this.tabItems[activeIndex]) {
            this.tabItems[activeIndex].classList.add('active');
            this.tabItems[activeIndex].setAttribute('aria-selected', 'true');
            this.tabItems[activeIndex].setAttribute('tabindex', '0');
            
            // Scroll active tab into view if needed
            this.scrollTabIntoView(activeIndex);
        }
        
        // Activate content (for default template)
        if (this.tabContents[activeIndex]) {
            this.tabContents[activeIndex].classList.add('active');
            this.tabContents[activeIndex].setAttribute('aria-hidden', 'false');
        }
        
        // Activate background (for hero template)
        if (this.heroBackgrounds[activeIndex]) {
            this.heroBackgrounds[activeIndex].classList.add('active');
        }
    }
    
    scrollTabIntoView(index) {
        if (!this.tabsNav || !this.tabItems[index]) return;
        
        const tabItem = this.tabItems[index];
        const navRect = this.tabsNav.getBoundingClientRect();
        const tabRect = tabItem.getBoundingClientRect();
        
        // Check if tab is out of view
        if (tabRect.left < navRect.left) {
            // Scroll left
            this.tabsNav.scrollLeft -= (navRect.left - tabRect.left) + 20;
        } else if (tabRect.right > navRect.right) {
            // Scroll right
            this.tabsNav.scrollLeft += (tabRect.right - navRect.right) + 20;
        }
        
        // Update scroll indicators
        setTimeout(() => this.addScrollIndicators(), 100);
    }
    
    handleKeyboard(e, currentIndex) {
        let newIndex = currentIndex;
        
        switch (e.key) {
            case 'ArrowLeft':
            case 'ArrowUp':
                e.preventDefault();
                newIndex = currentIndex > 0 ? currentIndex - 1 : this.tabItems.length - 1;
                break;
            case 'ArrowRight':
            case 'ArrowDown':
                e.preventDefault();
                newIndex = currentIndex < this.tabItems.length - 1 ? currentIndex + 1 : 0;
                break;
            case 'Home':
                e.preventDefault();
                newIndex = 0;
                break;
            case 'End':
                e.preventDefault();
                newIndex = this.tabItems.length - 1;
                break;
            case 'Enter':
            case ' ':
                e.preventDefault();
                this.switchTab(currentIndex);
                return;
            default:
                return;
        }
        
        this.switchTab(newIndex);
        this.tabItems[newIndex].focus();
    }
}

// Initialize tabs when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const tabContainers = document.querySelectorAll('.mn-dynamic-tabs');
    tabContainers.forEach(container => {
        new MNDynamicTabs(container);
    });
});

// Initialize tabs for Elementor editor
jQuery(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/mn-dynamic-tabs.default', function($scope) {
        const container = $scope.find('.mn-dynamic-tabs')[0];
        if (container) {
            new MNDynamicTabs(container);
        }
    });
});
