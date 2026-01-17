# Changelog - MN Elements

All notable changes to the MN Elements plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Changelog file for tracking version changes

### Changed
- **MN SlideSwipe**: Slide backgrounds changed to transparent for better template integration
- **MN SlideSwipe**: Arrow navigation moved outside container to prevent clipping with negative positioning

### Enhanced
- **MN SlideSwipe**: Added separate Position X and Position Y controls for arrow navigation
  - Position X: Horizontal positioning with extended range (-200px to 200px)
  - Position Y: Vertical positioning with extended range (-200px to 200px)
  - Support for both px and % units
  - Arrows can now be positioned outside slider boundaries without clipping
- **MN SlideSwipe**: Enhanced responsive settings with additional breakpoints
  - Added Tablet Landscape control (1024px - 1199px)
  - Added Mobile Landscape control (480px - 767px)
  - Improved breakpoint descriptions for better user understanding
  - Better responsive behavior across all device sizes
- **MN Dynamic Tabs**: Added comprehensive style controls matching Elementor native tabs
  - Complete tab navigation styling (width, border, background, typography, alignment)
  - Separate active tab styling controls
  - Content area styling (background, border, padding, box shadow)
  - Post items styling (gap, background, border, padding, box shadow)
  - Post title and excerpt typography controls
  - Read more button styling with normal/hover states
- **MN Dynamic Tabs**: Enhanced content slider functionality
  - Individual content sliders for each tab with autoplay support
  - Navigation controls (previous/next buttons) for content sliding
  - Pause on hover functionality for better user experience
  - Smooth slide transitions and animations
- **MN Dynamic Tabs**: Improved accessibility and user experience
  - ARIA attributes for screen reader compatibility
  - Keyboard navigation support (Arrow keys, Home, End, Enter, Space)
  - Focus management and visual focus indicators
  - Mobile touch swipe support for tab navigation

### Fixed
- **MN SlideSwipe**: Arrow visibility issue with negative positioning values
- **MN SlideSwipe**: Template content now properly fills slide dimensions
- **MN SlideSwipe**: Mobile view responsive settings now work correctly
- **MN SlideSwipe**: Fixed Swiper breakpoints configuration using mobile-first approach
- **MN SlideSwipe**: Slide width now properly controlled by Swiper instead of CSS conflicts
- **MN Video Playlist**: Mobile view layout improved with full-width thumbnails and proper content stacking
- **MN Video Playlist**: Fixed playlist scroll functionality that was not working properly
- **MN Video Playlist**: Enhanced mobile touch scrolling with momentum scrolling support
- **MN Video Playlist**: Duration overlay now positioned correctly over thumbnails
- **MN Video Playlist**: JavaScript scroll function now targets correct container class
- **MN Dynamic Tabs**: Fixed tab click functionality - tabs were not clickable
- **MN Dynamic Tabs**: Changed auto-slide behavior from tab navigation to post content within tabs
- **MN Dynamic Tabs**: Fixed mobile auto-centering for active tabs
- **MN Dynamic Tabs**: Enhanced JavaScript with proper event handling and accessibility support
- **MN Dynamic Tabs**: Improved responsive design and mobile touch support
- Script initialization conflicts resolved

---

## [1.8.0] - 2024-12-20

### Added
- **MN SlideSwipe Widget**: Complete template-based slider system
  - Swiper.js integration for smooth animations
  - Template selection from Elementor library
  - Responsive slide configuration (desktop/tablet/mobile)
  - Arrow and dot navigation with full styling controls
  - Autoplay and infinite loop options
  - Individual slide background color configuration
  - Separate X/Y arrow positioning with extended ranges (-200px to 200px)
  - Margin and padding controls for slider container
  - Overflow management for arrow visibility with negative positioning

### Enhanced
- **MN Video Playlist Widget**:
  - Fixed autoplay issues for first video and user clicks
  - Redesigned playlist layout with card-based design
  - Full-width thumbnails with duration overlay
  - Enhanced JavaScript with better error handling
  - Improved responsive design and theme integration

### Fixed
- MN SlideSwipe responsive settings (slides to show now works correctly)
- Arrow visibility with negative positioning values
- Mobile-first breakpoint configuration for Swiper

---

## [1.7.0] - 2024-12-15

### Added
- **MN Download Widget**: Comprehensive file download functionality
  - Dual source system (Manual/Dynamic)
  - Dynamic query controls for WordPress posts
  - Advanced custom field support (ACF, JetEngine, WordPress meta)
  - Taxonomy filtering system with term ID support
  - Advanced styling panel with backgrounds, borders, shadows
  - Light/Dark theme system with hover animations
  - Grid and list layout options

### Enhanced
- **MN Posts Widget**:
  - Added comprehensive typography controls (Title, Meta, Excerpt)
  - Added color customization controls (Background, Title, Meta, Excerpt, Read More)
  - Added border and hover state controls for Read More button
  - Enhanced read more button styling with transitions

---

## [1.6.0] - 2024-12-10

### Added
- **MN Infolist Widget**: Manual list management system
  - Elementor Repeater control for list items
  - Image, title, description, and read more fields per item
  - Same styling system as MN Posts (light/dark themes)
  - Individual read more settings per list item
  - Responsive grid layout with hover animations

### Enhanced
- **MN Counter Widget**:
  - Fixed alignment functionality for grid layout
  - Enhanced CSS alignment support for all elements
  - Proper flexbox to inline-block conversion for number wrapper

---

## [1.5.0] - 2024-12-05

### Added
- **MN Running Post Widget**: Continuous scrolling text animation
  - Right-to-left running animation with seamless loop
  - Hover pause functionality
  - Click redirect to post details
  - Animation speed control (10-100 seconds)
  - Customizable separator text
  - Complete styling controls with theme variants

### Enhanced
- **Animation System**:
  - Added "push" animation effect to MN Button and MN Posts
  - Enhanced entrance animation system with queue management
  - Fixed animation loading on page refresh
  - Prevented conflicts between multiple containers
  - Browser navigation support with proper state management

---

## [1.4.0] - 2024-11-30

### Added
- **MN Video Playlist Widget**: YouTube playlist functionality
  - Manual video list management with Elementor Repeater
  - Dynamic source system pulling from WordPress posts
  - Custom field integration (ACF, JetEngine, WordPress meta)
  - Advanced playlist layouts (top, left, right, bottom)
  - Player controls and autoplay functionality
  - Responsive design with mobile optimizations

### Enhanced
- **MN Posts Widget**:
  - Dynamic source functionality added
  - Query controls for post types and ordering
  - Custom field mapping for dynamic content
  - Enhanced theme system with hover animations

---

## [1.3.0] - 2024-11-25

### Added
- **MN Posts Widget**: Enhanced posts display system
  - Dark/Light theme version switcher
  - Hover animations with theme transformations
  - Read More button with icon controls and animations
  - Responsive grid layout with customizable columns
  - Complete query controls and layout options

### Enhanced
- **MN Button Widget**:
  - Added loop animations (pulse, bounce, shake, rotate, swing, flash)
  - Added hover animations (grow, shrink, wobble, buzz)
  - Individual icon resize controls with responsive sizing
  - Enhanced animation system with CSS keyframes

---

## [1.2.0] - 2024-11-20

### Added
- **MN Counter Widget**: Animated counter functionality
  - Number animation with customizable speed and easing
  - Icon support with positioning options
  - Grid and list layout options
  - Typography and color controls
  - Responsive design with mobile optimizations

### Enhanced
- **MNTriks System**:
  - Rewrote entrance animation system to follow Elementor's Motion Effects pattern
  - Class-based handler extending elementorModules.frontend.handlers.Base
  - Proper Elementor integration with scroll observer
  - Fixed preview functionality in Elementor editor
  - Enhanced animation reliability on page reload

---

## [1.1.0] - 2024-11-15

### Added
- **MN Button Widget**: Enhanced button functionality
  - Individual icon resize controls
  - Loop and hover animation effects
  - Complete Elementor integration
  - Custom CSS animations and styling

### Enhanced
- **Plugin Architecture**:
  - Custom widget category "MN Elements"
  - Proper widget registration system
  - Assets management for CSS and JavaScript
  - Elementor compatibility checks

---

## [1.0.0] - 2024-11-10

### Added
- **Initial Plugin Release**
- **MNTriks System**: Container entrance animations
  - Zoom out, zoom in, fade in animations
  - Slide animations (up, down, left, right)
  - Integration with Elementor's Advanced tab
  - Viewport detection and scroll-based triggering
  - Editor preview functionality

### Technical Foundation
- Plugin architecture with proper WordPress hooks
- Elementor integration and compatibility
- Frontend and editor JavaScript systems
- CSS animation framework
- Widget registration and management system

---

## Version Numbering

- **Major versions (x.0.0)**: Breaking changes, major new features
- **Minor versions (x.y.0)**: New features, enhancements, new widgets
- **Patch versions (x.y.z)**: Bug fixes, small improvements

## Support

For support and feature requests, please contact the development team.

## License

This plugin is proprietary software. All rights reserved.
