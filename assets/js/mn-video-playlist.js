/**
 * MN Video Playlist JavaScript
 * Handles video player and playlist functionality
 */

(function($) {
    'use strict';

    // Global MN Video Playlist object
    var MNVideoPlaylist = {
        players: {},
        youtubeAPIReady: false,
        pendingPlaylists: [],
        
        init: function(widgetId, autoplayNext, autoplayFirst) {
            var self = this;
            
            // If no widgetId provided, scan for all playlists
            if (!widgetId) {
                $('.mn-video-playlist-wrapper').each(function() {
                    var $wrapper = $(this);
                    var elementorWrapper = $wrapper.closest('.elementor-element');
                    var id = elementorWrapper.data('id');
                    var autoNext = $wrapper.data('autoplay-next') === 'true';
                    var autoFirst = $wrapper.data('autoplay-first') === 'true';
                    
                    if (id && !self.players[id]) {
                        console.log('Auto-initializing playlist for widget:', id);
                        self.init(id, autoNext, autoFirst);
                    }
                });
                return;
            }
            
            var $wrapper = $('.elementor-element-' + widgetId + ' .mn-video-playlist-wrapper');
            
            console.log('MN Video Playlist init called for widget:', widgetId);
            console.log('Wrapper found:', $wrapper.length);
            console.log('YouTube API Ready:', this.youtubeAPIReady);
            
            if ($wrapper.length === 0) {
                console.log('Wrapper not found, adding to pending playlists');
                this.pendingPlaylists.push({
                    widgetId: widgetId,
                    autoplayNext: autoplayNext,
                    autoplayFirst: autoplayFirst
                });
                return;
            }
            
            // If YouTube API is not ready, add to pending
            if (!this.youtubeAPIReady && typeof YT === 'undefined') {
                console.log('YouTube API not ready, adding to pending playlists');
                this.pendingPlaylists.push({
                    widgetId: widgetId,
                    autoplayNext: autoplayNext,
                    autoplayFirst: autoplayFirst
                });
                return;
            }
            
            // Continue with normal initialization
            var modalMode = $wrapper.data('modal-mode') === 'true';
            
            var playlistData = {
                widgetId: widgetId,
                autoplayNext: autoplayNext || false,
                autoplayFirst: autoplayFirst || false,
                modalMode: modalMode,
                currentIndex: 0,
                videos: [],
                $wrapper: $wrapper,
                $iframe: modalMode ? null : $wrapper.find('#mn-video-iframe-' + widgetId),
                $playlistItems: $wrapper.find('.mn-playlist-item'),
                $modal: modalMode ? $wrapper.find('.mn-video-modal') : null,
                $modalIframe: modalMode ? $wrapper.find('#mn-modal-iframe-' + widgetId) : null,
                player: null,
                playerReady: false
            };

            console.log('Playlist items found:', playlistData.$playlistItems.length);

            // Collect video data
            playlistData.$playlistItems.each(function(index) {
                var $item = $(this);
                var videoData = {
                    id: $item.data('video-id'),
                    index: index,
                    $element: $item
                };
                playlistData.videos.push(videoData);
            });

            console.log('Videos collected:', playlistData.videos.length);

            // Store playlist data
            this.players[widgetId] = playlistData;

            // Initialize player if we have videos
            if (playlistData.videos.length > 0) {
                this.initializePlayer(widgetId);
                this.bindEvents(widgetId);
                this.updatePlaylistUI(widgetId);
            }
        },

        initializePlayer: function(widgetId) {
            var self = this;
            var playlist = this.players[widgetId];
            
            if (!playlist || playlist.videos.length === 0) {
                console.log('No playlist or videos found for widget:', widgetId);
                return;
            }

            // Skip player initialization if modal mode is enabled
            if (playlist.modalMode) {
                console.log('Modal mode enabled, skipping player initialization');
                return;
            }

            var firstVideo = playlist.videos[0];
            console.log('Initializing player for widget:', widgetId, 'with first video:', firstVideo.id);

            // Check if YouTube API is available
            if (typeof YT === 'undefined' || !YT.Player) {
                console.log('YouTube API not available, using simple method');
                this.simpleVideoLoad(widgetId, firstVideo.id);
                return;
            }

            // Set autoplay and mute based on settings
            var playerVars = {
                autoplay: playlist.autoplayFirst ? 1 : 0,
                mute: playlist.autoplayFirst ? 1 : 0,
                controls: 1,
                rel: 0,
                showinfo: 0,
                modestbranding: 1,
                fs: 1,
                cc_load_policy: 0,
                iv_load_policy: 3,
                autohide: 0,
                origin: window.location.origin
            };
            
            console.log('Player vars for widget ' + widgetId + ':', playerVars);
            
            // Create YouTube player with error handling
            try {
                playlist.player = new YT.Player('mn-video-iframe-' + widgetId, {
                    videoId: firstVideo.id,
                    playerVars: playerVars,
                    events: {
                        'onReady': function(event) {
                            self.onPlayerReady(event, widgetId);
                        },
                        'onStateChange': function(event) {
                            self.onPlayerStateChange(event, widgetId);
                        },
                        'onError': function(event) {
                            console.log('YouTube Player Error:', event.data);
                            self.simpleVideoLoad(widgetId, firstVideo.id);
                        }
                    }
                });
            } catch (error) {
                console.log('Error creating YouTube player, using simple method:', error);
                this.simpleVideoLoad(widgetId, firstVideo.id);
            }
        },

        onPlayerReady: function(event, widgetId) {
            console.log('MN Video Playlist: Player ready for widget ' + widgetId);
            var playlist = this.players[widgetId];
            if (playlist) {
                playlist.playerReady = true;
                console.log('Player marked as ready for widget:', widgetId);
                
                // Auto-play first video if autoplayFirst is enabled
                if (playlist.autoplayFirst) {
                    setTimeout(function() {
                        try {
                            console.log('Auto-playing first video');
                            playlist.player.playVideo();
                        } catch (error) {
                            console.log('Error auto-playing first video:', error);
                        }
                    }, 1000); // Wait 1 second to ensure everything is loaded
                }
            }
        },

        onPlayerStateChange: function(event, widgetId) {
            var playlist = this.players[widgetId];
            
            if (!playlist) {
                return;
            }

            // Video ended
            if (event.data === YT.PlayerState.ENDED) {
                if (playlist.autoplayNext) {
                    this.playNext(widgetId);
                }
            }
        },

        bindEvents: function(widgetId) {
            var self = this;
            var playlist = this.players[widgetId];
            
            console.log('Binding events for widget:', widgetId, playlist);
            
            if (!playlist) {
                console.log('No playlist found for bindEvents:', widgetId);
                return;
            }

            // Playlist item click - use event delegation for better reliability
            playlist.$wrapper.on('click', '.mn-playlist-item', function(e) {
                e.preventDefault();
                var index = parseInt($(this).data('index'));
                console.log('Playlist item clicked, index:', index, 'widgetId:', widgetId);
                
                // If modal mode, open modal instead of playing in player
                if (playlist.modalMode) {
                    var videoId = $(this).data('video-id');
                    self.openPlaylistModal(widgetId, videoId, index);
                } else {
                    self.playVideo(widgetId, index);
                }
            });
            
            console.log('Click events bound for playlist items in widget:', widgetId);

            // Keyboard navigation
            playlist.$wrapper.on('keydown', function(e) {
                if (e.target.tagName.toLowerCase() === 'input' || e.target.tagName.toLowerCase() === 'textarea') {
                    return;
                }

                switch(e.which) {
                    case 38: // Up arrow
                        e.preventDefault();
                        self.playPrevious(widgetId);
                        break;
                    case 40: // Down arrow
                        e.preventDefault();
                        self.playNext(widgetId);
                        break;
                    case 32: // Space
                        e.preventDefault();
                        self.togglePlayPause(widgetId);
                        break;
                }
            });

            // Enhanced mobile scroll support
            self.enhanceMobileScroll(widgetId);
            
            // Modal event handlers (if modal mode)
            if (playlist.modalMode && playlist.$modal) {
                // Close modal on close button click
                playlist.$modal.on('click', '.mn-modal-close', function(e) {
                    e.preventDefault();
                    self.closePlaylistModal(widgetId);
                });
                
                // Close modal on overlay click
                playlist.$modal.on('click', '.mn-modal-overlay', function(e) {
                    e.preventDefault();
                    self.closePlaylistModal(widgetId);
                });
                
                // Prevent modal content click from closing
                playlist.$modal.on('click', '.mn-modal-content', function(e) {
                    e.stopPropagation();
                });
                
                // ESC key to close modal
                $(document).on('keydown.mn-playlist-' + widgetId, function(e) {
                    if (e.keyCode === 27) { // ESC key
                        self.closePlaylistModal(widgetId);
                    }
                });
            }
        },

        playVideo: function(widgetId, index, retryCount, isUserClick) {
            var self = this;
            var playlist = this.players[widgetId];
            retryCount = retryCount || 0;
            isUserClick = isUserClick !== undefined ? isUserClick : true; // Default to true for user clicks
            
            console.log('playVideo called:', widgetId, index, 'retry:', retryCount, 'userClick:', isUserClick, playlist);
            
            if (!playlist || !playlist.videos[index]) {
                console.log('Playlist or video not found:', playlist, playlist ? playlist.videos : 'no playlist');
                return;
            }

            var video = playlist.videos[index];
            console.log('Playing video:', video);
            
            // Update player with retry mechanism
            if (playlist.player && playlist.playerReady) {
                // Check if player is ready and has the method
                if (typeof playlist.player.loadVideoById === 'function') {
                    try {
                        console.log('Loading video ID:', video.id);
                        playlist.player.loadVideoById(video.id);
                        
                        // Unmute if this is a user click (not autoplay first video)
                        if (isUserClick && index > 0) {
                            setTimeout(function() {
                                try {
                                    console.log('Unmuting video for user click');
                                    playlist.player.unMute();
                                    playlist.player.setVolume(100);
                                } catch (error) {
                                    console.log('Error unmuting video:', error);
                                }
                            }, 500);
                        }
                    } catch (error) {
                        console.log('Error loading video, trying fallback:', error);
                        this.fallbackVideoLoad(widgetId, video.id, isUserClick);
                    }
                } else {
                    console.log('Player method not available, trying fallback');
                    this.fallbackVideoLoad(widgetId, video.id, isUserClick);
                }
            } else if (playlist.player && !playlist.playerReady && retryCount < 3) {
                console.log('Player exists but not ready, waiting and retrying (attempt ' + (retryCount + 1) + ')');
                // Wait a bit and retry with shorter delay
                var self = this;
                setTimeout(function() {
                    self.playVideo(widgetId, index, retryCount + 1, isUserClick);
                }, 300);
                return;
            } else {
                console.log('No player found or retry limit reached, using simple video load');
                // Use simple method instead of complex fallback
                this.simpleVideoLoad(widgetId, video.id, isUserClick);
            }

            // Update current index
            playlist.currentIndex = index;

            // Update UI
            this.updatePlaylistUI(widgetId);

            // Scroll to active item if needed
            this.scrollToActiveItem(widgetId);
        },

        fallbackVideoLoad: function(widgetId, videoId, isUserClick) {
            var self = this;
            var playlist = this.players[widgetId];
            isUserClick = isUserClick !== undefined ? isUserClick : true;
            
            console.log('Fallback video load for:', widgetId, videoId, 'userClick:', isUserClick);
            
            if (!playlist || !playlist.$iframe.length) {
                console.log('No playlist or iframe found for fallback');
                return;
            }

            var iframe = playlist.$iframe[0];
            
            // Method 1: Simple iframe src change (most reliable)
            var baseUrl = 'https://www.youtube.com/embed/';
            var params = '?enablejsapi=1&rel=0&origin=' + encodeURIComponent(window.location.origin);
            
            // Add autoplay and mute logic
            if (playlist.autoplayFirst || playlist.currentIndex > 0) {
                params += '&autoplay=1';
                // Only mute if it's the first video or not a user click
                if (playlist.currentIndex === 0 || !isUserClick) {
                    params += '&mute=1';
                }
            }
            
            var newSrc = baseUrl + videoId + params;
            console.log('Setting new iframe src:', newSrc);
            
            // Destroy existing player first
            if (playlist.player && typeof playlist.player.destroy === 'function') {
                try {
                    playlist.player.destroy();
                } catch (error) {
                    console.log('Error destroying player:', error);
                }
            }
            
            // Reset player state
            playlist.player = null;
            playlist.playerReady = false;
            
            // Update iframe src
            iframe.src = newSrc;
            
            // Wait for YouTube API to be ready and reinitialize
            this.waitForYouTubeAPI(function() {
                setTimeout(function() {
                    try {
                        console.log('Reinitializing player for fallback');
                        playlist.player = new YT.Player(iframe, {
                            events: {
                                'onReady': function(event) {
                                    console.log('Fallback player ready');
                                    self.onPlayerReady(event, widgetId);
                                },
                                'onStateChange': function(event) {
                                    self.onPlayerStateChange(event, widgetId);
                                },
                                'onError': function(event) {
                                    console.log('Player error:', event.data);
                                    // Try simple iframe approach on error
                                    self.simpleVideoLoad(widgetId, videoId);
                                }
                            }
                        });
                    } catch (error) {
                        console.log('Error reinitializing player, using simple method:', error);
                        self.simpleVideoLoad(widgetId, videoId);
                    }
                }, 1000);
            });
        },

        simpleVideoLoad: function(widgetId, videoId, isUserClick) {
            var playlist = this.players[widgetId];
            isUserClick = isUserClick !== undefined ? isUserClick : true;
            
            if (!playlist || !playlist.$iframe.length) {
                return;
            }
            
            console.log('Using simple video load for:', widgetId, videoId, 'userClick:', isUserClick);
            
            var iframe = playlist.$iframe[0];
            var baseUrl = 'https://www.youtube.com/embed/';
            var params = '?rel=0&modestbranding=1';
            
            // Add autoplay logic
            if (playlist.currentIndex > 0) {
                params += '&autoplay=1';
                // Only mute if it's not a user click
                if (!isUserClick) {
                    params += '&mute=1';
                }
            }
            
            var newSrc = baseUrl + videoId + params;
            iframe.src = newSrc;
            
            // Clear player reference since we're not using API
            playlist.player = null;
            playlist.playerReady = false;
            
            console.log('Simple video load completed:', newSrc);
        },

        waitForYouTubeAPI: function(callback) {
            if (typeof YT !== 'undefined' && YT.Player) {
                callback();
            } else {
                console.log('Waiting for YouTube API...');
                setTimeout(function() {
                    if (typeof YT !== 'undefined' && YT.Player) {
                        callback();
                    } else {
                        console.log('YouTube API not available, using simple method');
                        // Don't callback if API is not available
                    }
                }, 2000);
            }
        },

        playNext: function(widgetId) {
            var playlist = this.players[widgetId];
            
            if (!playlist) {
                return;
            }

            var nextIndex = playlist.currentIndex + 1;
            
            if (nextIndex >= playlist.videos.length) {
                nextIndex = 0; // Loop to first video
            }

            // This is auto-navigation, not user click
            this.playVideo(widgetId, nextIndex, 0, false);
        },

        playPrevious: function(widgetId) {
            var playlist = this.players[widgetId];
            
            if (!playlist) {
                return;
            }

            var prevIndex = playlist.currentIndex - 1;
            
            if (prevIndex < 0) {
                prevIndex = playlist.videos.length - 1; // Loop to last video
            }

            // This is auto-navigation, not user click
            this.playVideo(widgetId, prevIndex, 0, false);
        },

        togglePlayPause: function(widgetId) {
            var playlist = this.players[widgetId];
            
            if (!playlist || !playlist.player || !playlist.playerReady) {
                return;
            }

            try {
                var state = playlist.player.getPlayerState();
                
                if (state === YT.PlayerState.PLAYING) {
                    playlist.player.pauseVideo();
                } else {
                    playlist.player.playVideo();
                }
            } catch (error) {
                console.log('Error toggling play/pause:', error);
            }
        },

        updatePlaylistUI: function(widgetId) {
            var playlist = this.players[widgetId];
            
            if (!playlist) {
                return;
            }

            // Remove active class from all items
            playlist.$playlistItems.removeClass('active');
            
            // Add active class to current item
            var currentItem = playlist.$playlistItems.eq(playlist.currentIndex);
            currentItem.addClass('active');
        },

        enhanceMobileScroll: function(widgetId) {
            var playlist = this.players[widgetId];
            
            if (!playlist) {
                return;
            }

            var $playlistContainer = playlist.$wrapper.find('.mn-playlist-items');
            
            if ($playlistContainer.length) {
                // Add momentum scrolling for iOS
                $playlistContainer.css({
                    '-webkit-overflow-scrolling': 'touch',
                    'overflow-scrolling': 'touch'
                });

                // Prevent scroll issues on mobile
                $playlistContainer.on('touchstart', function() {
                    $(this).data('scrolling', false);
                });

                $playlistContainer.on('touchmove', function() {
                    $(this).data('scrolling', true);
                });

                // Prevent click events during scroll
                $playlistContainer.on('touchend', function(e) {
                    var isScrolling = $(this).data('scrolling');
                    if (isScrolling) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    $(this).data('scrolling', false);
                });

                console.log('Enhanced mobile scroll enabled for widget:', widgetId);
            }
        },

        scrollToActiveItem: function(widgetId) {
            var playlist = this.players[widgetId];
            
            if (!playlist) {
                return;
            }

            var activeItem = playlist.$playlistItems.eq(playlist.currentIndex);
            var container = playlist.$wrapper.find('.mn-playlist-items');
            
            if (activeItem.length && container.length) {
                var itemTop = activeItem.position().top;
                var containerHeight = container.height();
                var itemHeight = activeItem.outerHeight();
                
                if (itemTop < 0 || itemTop + itemHeight > containerHeight) {
                    container.animate({
                        scrollTop: container.scrollTop() + itemTop - (containerHeight / 2) + (itemHeight / 2)
                    }, 300);
                }
            }
        },

        openPlaylistModal: function(widgetId, videoId, index) {
            var playlist = this.players[widgetId];
            if (!playlist || !playlist.modalMode || !playlist.$modal) {
                return;
            }
            
            console.log('Opening playlist modal for video:', videoId);
            
            // Build YouTube embed URL with autoplay
            var embedUrl = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
            
            // Set modal iframe src
            if (playlist.$modalIframe && playlist.$modalIframe.length) {
                playlist.$modalIframe.attr('src', embedUrl);
            }
            
            // Update current index
            playlist.currentIndex = index;
            
            // Update UI
            this.updatePlaylistUI(widgetId);
            
            // Show modal
            playlist.$modal.addClass('active');
            $('body').css('overflow', 'hidden');
        },
        
        closePlaylistModal: function(widgetId) {
            var playlist = this.players[widgetId];
            if (!playlist || !playlist.modalMode || !playlist.$modal) {
                return;
            }
            
            console.log('Closing playlist modal for widget:', widgetId);
            
            // Hide modal
            playlist.$modal.removeClass('active');
            $('body').css('overflow', '');
            
            // Stop video
            if (playlist.$modalIframe && playlist.$modalIframe.length) {
                playlist.$modalIframe.attr('src', '');
            }
        },

        destroy: function(widgetId) {
            var playlist = this.players[widgetId];
            
            if (playlist) {
                // Destroy YouTube player
                if (playlist.player && typeof playlist.player.destroy === 'function') {
                    try {
                        playlist.player.destroy();
                    } catch (error) {
                        console.log('Error destroying player:', error);
                    }
                }

                // Remove event listeners
                if (playlist.$playlistItems) {
                    playlist.$playlistItems.off('click');
                }

                if (playlist.$wrapper) {
                    playlist.$wrapper.off('keydown');
                }
                
                // Remove modal event listeners
                if (playlist.$modal) {
                    playlist.$modal.off('click');
                }
                
                $(document).off('keydown.mn-playlist-' + widgetId);

                // Remove from players object
                delete this.players[widgetId];
            }
        },

        initPendingPlaylists: function() {
            var self = this;
            console.log('Initializing pending playlists:', this.pendingPlaylists.length);
            
            this.pendingPlaylists.forEach(function(playlistData) {
                console.log('Initializing pending playlist:', playlistData.widgetId);
                self.init(playlistData.widgetId, playlistData.autoplayNext, playlistData.autoplayFirst);
            });
            
            // Clear pending playlists
            this.pendingPlaylists = [];
        }
    };

    // YouTube API Ready callback
    window.onYouTubeIframeAPIReady = function() {
        console.log('YouTube API Ready');
        MNVideoPlaylist.youtubeAPIReady = true;
        MNVideoPlaylist.initPendingPlaylists();
    };

    // Initialize when document is ready
    $(document).ready(function() {
        MNVideoPlaylist.init();
    });

    // Elementor frontend init
    $(window).on('elementor/frontend/init', function() {
        console.log('Elementor frontend init');
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-video-playlist.default', function($scope) {
            var widgetId = $scope.data('id');
            if (widgetId) {
                // Destroy existing instance if any
                if (MNVideoPlaylist.players[widgetId]) {
                    MNVideoPlaylist.destroy(widgetId);
                }
                
                // Initialize new instance
                var $wrapper = $scope.find('.mn-video-playlist-wrapper');
                var autoplayNext = $wrapper.data('autoplay-next') === 'true';
                var autoplayFirst = $wrapper.data('autoplay-first') === 'true';
                MNVideoPlaylist.init(widgetId, autoplayNext, autoplayFirst);
                
                // Initialize carousel if present
                var $carouselWrapper = $scope.find('.mn-video-carousel-wrapper');
                if ($carouselWrapper.length) {
                    MNVideoCarousel.init($carouselWrapper);
                }
            }
        });
    });

    // ========================================
    // MN VIDEO CAROUSEL
    // ========================================
    
    var MNVideoCarousel = {
        instances: {},
        
        init: function($wrapper) {
            var self = this;
            var widgetId = $wrapper.data('widget-id');
            
            if (!widgetId) {
                console.log('No widget ID found for carousel');
                return;
            }
            
            console.log('Initializing carousel for widget:', widgetId);
            
            var carouselData = {
                widgetId: widgetId,
                $wrapper: $wrapper,
                $container: $wrapper.find('.mn-carousel-items'),
                $items: $wrapper.find('.mn-carousel-item'),
                $modal: $wrapper.find('.mn-video-modal'),
                $modalIframe: $wrapper.find('.mn-modal-video-container iframe'),
                $modalTitle: $wrapper.find('.mn-modal-title'),
                $modalDescription: $wrapper.find('.mn-modal-description'),
                speed: parseInt($wrapper.data('carousel-speed')) || 30000,
                pauseHover: $wrapper.data('pause-hover') === 'true'
            };
            
            // Store instance
            this.instances[widgetId] = carouselData;
            
            // Setup infinite loop
            this.setupInfiniteLoop(widgetId);
            
            // Bind events
            this.bindEvents(widgetId);
            
            console.log('Carousel initialized:', carouselData);
        },
        
        setupInfiniteLoop: function(widgetId) {
            var carousel = this.instances[widgetId];
            if (!carousel) return;
            
            var $container = carousel.$container;
            var $items = carousel.$items;
            
            // Clone items for seamless loop
            var itemsArray = $items.toArray();
            var containerWidth = $container.width();
            var totalWidth = 0;
            
            // Calculate total width of original items
            itemsArray.forEach(function(item) {
                totalWidth += $(item).outerWidth(true);
            });
            
            // Clone items until we have at least 2x viewport width
            var cloneCount = 0;
            while (totalWidth < containerWidth * 2 || cloneCount < itemsArray.length) {
                itemsArray.forEach(function(item) {
                    var $clone = $(item).clone(true);
                    $clone.addClass('mn-carousel-clone');
                    $container.append($clone);
                    cloneCount++;
                });
                totalWidth *= 2;
            }
            
            // Set animation duration
            var duration = carousel.speed / 1000;
            $container.css('animation-duration', duration + 's');
            
            console.log('Infinite loop setup complete. Cloned items:', cloneCount, 'Duration:', duration + 's');
        },
        
        bindEvents: function(widgetId) {
            var self = this;
            var carousel = this.instances[widgetId];
            if (!carousel) return;
            
            // Click on carousel item to open modal
            carousel.$wrapper.on('click', '.mn-carousel-item', function(e) {
                e.preventDefault();
                var $item = $(this);
                var videoId = $item.data('video-id');
                var videoTitle = $item.data('video-title');
                var videoDescription = $item.data('video-description');
                
                console.log('Carousel item clicked:', videoId, videoTitle);
                
                self.openModal(widgetId, videoId, videoTitle, videoDescription);
            });
            
            // Close modal
            carousel.$modal.on('click', '.mn-modal-close, .mn-modal-overlay', function(e) {
                e.preventDefault();
                self.closeModal(widgetId);
            });
            
            // Prevent modal content click from closing
            carousel.$modal.on('click', '.mn-modal-content', function(e) {
                e.stopPropagation();
            });
            
            // ESC key to close modal
            $(document).on('keydown.mn-carousel-' + widgetId, function(e) {
                if (e.keyCode === 27) { // ESC key
                    self.closeModal(widgetId);
                }
            });
            
            // Handle window resize
            $(window).on('resize.mn-carousel-' + widgetId, function() {
                self.handleResize(widgetId);
            });
            
            console.log('Carousel events bound for widget:', widgetId);
        },
        
        openModal: function(widgetId, videoId, title, description) {
            var carousel = this.instances[widgetId];
            if (!carousel) return;
            
            console.log('Opening modal for video:', videoId);
            
            // Build YouTube embed URL with autoplay
            var embedUrl = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
            
            // Set modal content
            carousel.$modalIframe.attr('src', embedUrl);
            carousel.$modalTitle.text(title);
            carousel.$modalDescription.text(description);
            
            // Show modal
            carousel.$modal.addClass('active');
            $('body').css('overflow', 'hidden');
            
            // Pause carousel animation
            carousel.$container.css('animation-play-state', 'paused');
        },
        
        closeModal: function(widgetId) {
            var carousel = this.instances[widgetId];
            if (!carousel) return;
            
            console.log('Closing modal for widget:', widgetId);
            
            // Hide modal
            carousel.$modal.removeClass('active');
            $('body').css('overflow', '');
            
            // Stop video
            carousel.$modalIframe.attr('src', '');
            
            // Resume carousel animation if not hover-paused
            if (!carousel.$container.is(':hover') || !carousel.pauseHover) {
                carousel.$container.css('animation-play-state', 'running');
            }
        },
        
        handleResize: function(widgetId) {
            var carousel = this.instances[widgetId];
            if (!carousel) return;
            
            // Recalculate if needed
            console.log('Carousel resize handled for widget:', widgetId);
        },
        
        destroy: function(widgetId) {
            var carousel = this.instances[widgetId];
            if (!carousel) return;
            
            console.log('Destroying carousel for widget:', widgetId);
            
            // Remove event listeners
            carousel.$wrapper.off('click');
            carousel.$modal.off('click');
            $(document).off('keydown.mn-carousel-' + widgetId);
            $(window).off('resize.mn-carousel-' + widgetId);
            
            // Remove clones
            carousel.$wrapper.find('.mn-carousel-clone').remove();
            
            // Close modal if open
            this.closeModal(widgetId);
            
            // Remove instance
            delete this.instances[widgetId];
        }
    };
    
    // Initialize carousels on document ready
    $(document).ready(function() {
        $('.mn-video-carousel-wrapper').each(function() {
            MNVideoCarousel.init($(this));
        });
    });

})(jQuery);
