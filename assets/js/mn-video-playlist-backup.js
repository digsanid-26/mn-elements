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
            
            var $wrapper = $('.elementor-element-' + widgetId);
            
            console.log('MN Video Playlist init called for widget:', widgetId);
            console.log('Wrapper found:', $wrapper.length);
            console.log('YouTube API Ready:', this.youtubeAPIReady);
            
            if ($wrapper.length === 0) {
                console.log('Wrapper not found, adding to pending playlists');
                // Store playlist data for later initialization
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
            this.players[widgetId] = {
                $wrapper: $wrapper,
                $playlistItems: $wrapper.find('.mn-playlist-item'),
                videos: [],
                currentIndex: 0,
                autoplayNext: autoplayNext || false,
                autoplayFirst: autoplayFirst || false,
                player: null
            };
            
            // Rest of initialization code will continue here
            this.setupPlaylist(widgetId);
        },
        
        setupPlaylist: function(widgetId) {
            }

            var playlistData = {
                widgetId: widgetId,
                autoplayNext: autoplayNext,
                autoplayFirst: autoplayFirst,
                currentIndex: 0,
                videos: [],
                $wrapper: $wrapper,
                $iframe: $wrapper.find('#mn-video-iframe-' + widgetId),
                $playlistItems: $wrapper.find('.mn-playlist-item'),
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
                console.log('Video data collected:', videoData);
                playlistData.videos.push(videoData);
            });

            console.log('Total videos collected:', playlistData.videos.length);

            // Store playlist data
            this.players[widgetId] = playlistData;

            // Initialize YouTube API if not already loaded
            if (typeof YT === 'undefined' || typeof YT.Player === 'undefined') {
                this.loadYouTubeAPI(function() {
                    self.initializePlayer(widgetId);
                });
            } else {
                this.initializePlayer(widgetId);
            }

            // Bind playlist item click events
            this.bindEvents(widgetId);
            
            console.log('MN Video Playlist initialization completed for widget:', widgetId);
        },

        loadYouTubeAPI: function(callback) {
            if (window.youtubeAPIReady) {
                callback();
                return;
            }

            // Check if script is already loading
            if (window.youtubeAPILoading) {
                $(document).on('youtubeAPIReady', callback);
                return;
            }

            window.youtubeAPILoading = true;
            
            // Load YouTube IFrame API
            var tag = document.createElement('script');
            tag.src = 'https://www.youtube.com/iframe_api';
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            // Set up API ready callback
            window.onYouTubeIframeAPIReady = function() {
                window.youtubeAPIReady = true;
                window.youtubeAPILoading = false;
                $(document).trigger('youtubeAPIReady');
                callback();
            };
        },

        initializePlayer: function(widgetId) {
            var self = this;
            var playlist = this.players[widgetId];
            
            if (!playlist || playlist.videos.length === 0) {
                return;
            }

            var firstVideo = playlist.videos[0];
            
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
                            // Fallback to simple iframe method on error
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
                self.playVideo(widgetId, index);
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
        },

        playVideo: function(widgetId, index, retryCount) {
            var self = this;
            var playlist = this.players[widgetId];
            retryCount = retryCount || 0;
            
            console.log('playVideo called:', widgetId, index, 'retry:', retryCount, playlist);
            
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
                    } catch (error) {
                        console.log('Error loading video, trying fallback:', error);
                        this.fallbackVideoLoad(widgetId, video.id);
                    }
                } else {
                    console.log('Player method not available, trying fallback');
                    this.fallbackVideoLoad(widgetId, video.id);
                }
            } else if (playlist.player && !playlist.playerReady && retryCount < 3) {
                console.log('Player exists but not ready, waiting and retrying (attempt ' + (retryCount + 1) + ')');
                // Wait a bit and retry with shorter delay
                var self = this;
                setTimeout(function() {
                    self.playVideo(widgetId, index, retryCount + 1);
                }, 300);
                return;
            } else {
                console.log('No player found or retry limit reached, using simple video load');
                // Use simple method instead of complex fallback
                this.simpleVideoLoad(widgetId, video.id);
            }

            // Update current index
            playlist.currentIndex = index;

            // Update UI
            this.updatePlaylistUI(widgetId);

            // Scroll to active item if needed
            this.scrollToActiveItem(widgetId);
        },

        fallbackVideoLoad: function(widgetId, videoId) {
            var self = this;
            var playlist = this.players[widgetId];
            
            console.log('Fallback video load for:', widgetId, videoId);
            
            if (!playlist || !playlist.$iframe.length) {
                console.log('No playlist or iframe found for fallback');
                return;
            }

            var iframe = playlist.$iframe[0];
            
            // Method 1: Simple iframe src change (most reliable)
            var baseUrl = 'https://www.youtube.com/embed/';
            var params = '?enablejsapi=1&rel=0&origin=' + encodeURIComponent(window.location.origin);
            
            // Add autoplay if needed
            if (playlist.autoplayFirst || playlist.currentIndex > 0) {
                params += '&autoplay=1&mute=1';
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

        simpleVideoLoad: function(widgetId, videoId) {
            var playlist = this.players[widgetId];
            
            if (!playlist || !playlist.$iframe.length) {
                return;
            }
            
            console.log('Using simple video load for:', widgetId, videoId);
            
            var iframe = playlist.$iframe[0];
            var baseUrl = 'https://www.youtube.com/embed/';
            var params = '?rel=0&modestbranding=1';
            
            // Add autoplay if not first video
            if (playlist.currentIndex > 0) {
                params += '&autoplay=1&mute=1';
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

            this.playVideo(widgetId, nextIndex);
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

            this.playVideo(widgetId, prevIndex);
        },

        togglePlayPause: function(widgetId) {
            var playlist = this.players[widgetId];
            
            if (!playlist || !playlist.player) {
                return;
            }

            if (typeof playlist.player.getPlayerState === 'function') {
                var state = playlist.player.getPlayerState();
                
                if (state === YT.PlayerState.PLAYING) {
                    playlist.player.pauseVideo();
                } else {
                    playlist.player.playVideo();
                }
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
            var currentVideo = playlist.videos[playlist.currentIndex];
            if (currentVideo && currentVideo.$element) {
                currentVideo.$element.addClass('active');
            }
        },

        scrollToActiveItem: function(widgetId) {
            var playlist = this.players[widgetId];
            
            if (!playlist) {
                return;
            }

            var currentVideo = playlist.videos[playlist.currentIndex];
            if (!currentVideo || !currentVideo.$element) {
                return;
            }

            var $playlistContainer = playlist.$wrapper.find('.mn-playlist-items');
            var $activeItem = currentVideo.$element;
            
            if ($playlistContainer.length && $activeItem.length) {
                var containerHeight = $playlistContainer.height();
                var containerScrollTop = $playlistContainer.scrollTop();
                var itemTop = $activeItem.position().top;
                var itemHeight = $activeItem.outerHeight();

                // Check if item is visible
                if (itemTop < 0 || itemTop + itemHeight > containerHeight) {
                    var scrollTo = containerScrollTop + itemTop - (containerHeight / 2) + (itemHeight / 2);
                    
                    $playlistContainer.animate({
                        scrollTop: scrollTo
                    }, 300);
                }
            }
        },

        // Utility function to get video ID from YouTube URL
        getYouTubeVideoId: function(url) {
            var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            var match = url.match(regExp);
            
            if (match && match[2].length === 11) {
                return match[2];
            }
            
            return null;
        },

        // Destroy player instance
        destroy: function(widgetId) {
            var playlist = this.players[widgetId];
            
            if (playlist) {
                // Destroy YouTube player
                if (playlist.player && typeof playlist.player.destroy === 'function') {
                    playlist.player.destroy();
                }

                // Remove event listeners
                if (playlist.$playlistItems) {
                    playlist.$playlistItems.off('click');
                }

                if (playlist.$wrapper) {
                    playlist.$wrapper.off('keydown');
                }

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
            }
        });
    });

})(jQuery);
