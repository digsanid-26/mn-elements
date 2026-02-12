(function ($) {
    'use strict';

    var MNVideoPlayer = {

        init: function () {
            this.bindEvents();
        },

        bindEvents: function () {
            // Handle lazy-load inline triggers
            $(document).on('click', '.mn-vp-wrapper[data-display="inline"] .mn-vp-trigger', function (e) {
                e.preventDefault();
                var $wrapper = $(this).closest('.mn-vp-wrapper');
                if ($wrapper.hasClass('mn-vp-loaded')) return;
                MNVideoPlayer.loadInlineVideo($wrapper);
            });

            // Handle modal triggers
            $(document).on('click', '.mn-vp-modal-trigger', function (e) {
                e.preventDefault();
                var $wrapper = $(this).closest('.mn-vp-wrapper');
                MNVideoPlayer.openModal($wrapper);
            });

            // Handle modal close button
            $(document).on('click', '.mn-vp-modal-close', function (e) {
                e.preventDefault();
                var $wrapper = $(this).closest('.mn-vp-wrapper');
                MNVideoPlayer.closeModal($wrapper);
            });

            // Handle modal backdrop click
            $(document).on('click', '.mn-vp-modal-overlay', function (e) {
                if ($(e.target).hasClass('mn-vp-modal-overlay')) {
                    var $wrapper = $(this).closest('.mn-vp-wrapper');
                    var closeBg = $wrapper.data('modal-close-bg');
                    if (closeBg === 'yes' || closeBg === undefined) {
                        MNVideoPlayer.closeModal($wrapper);
                    }
                }
            });

            // Handle ESC key
            $(document).on('keydown', function (e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    $('.mn-vp-modal-overlay:visible').each(function () {
                        var $wrapper = $(this).closest('.mn-vp-wrapper');
                        var closeEsc = $wrapper.data('modal-close-esc');
                        if (closeEsc === 'yes' || closeEsc === undefined) {
                            MNVideoPlayer.closeModal($wrapper);
                        }
                    });
                }
            });

            // Handle self-hosted video start time
            $(document).on('loadedmetadata', '.mn-vp-video', function () {
                var startTime = $(this).data('start');
                if (startTime) {
                    this.currentTime = parseInt(startTime, 10);
                }
            });

            // Init on Elementor frontend
            $(window).on('elementor/frontend/init', function () {
                if (typeof elementorFrontend !== 'undefined') {
                    elementorFrontend.hooks.addAction('frontend/element_ready/mn-videoplayer.default', function ($scope) {
                        MNVideoPlayer.initWidget($scope);
                    });
                }
            });
        },

        initWidget: function ($scope) {
            // Nothing special needed on init for now; events are delegated
        },

        /**
         * Load video into inline lazy-load container
         */
        loadInlineVideo: function ($wrapper) {
            var $trigger = $wrapper.find('.mn-vp-trigger');
            var $container = $wrapper.find('.mn-vp-embed-container');
            var embedHtml = this.buildEmbed($wrapper, true);

            $container.html(embedHtml);
            $trigger.addClass('mn-vp-hidden');
            $wrapper.addClass('mn-vp-loaded');

            // Handle self-hosted start time
            var $video = $container.find('video');
            if ($video.length) {
                var start = $wrapper.data('start');
                if (start) {
                    $video.on('loadedmetadata', function () {
                        this.currentTime = parseInt(start, 10);
                    });
                }
                $video[0].play().catch(function () {});
            }
        },

        /**
         * Open modal and load video
         */
        openModal: function ($wrapper) {
            var $overlay = $wrapper.find('.mn-vp-modal-overlay');
            var $container = $overlay.find('.mn-vp-embed-container');
            var embedHtml = this.buildEmbed($wrapper, true);

            $container.html(embedHtml);
            $overlay.fadeIn(250);
            $('body').addClass('mn-vp-modal-open');

            // Handle self-hosted start time
            var $video = $container.find('video');
            if ($video.length) {
                var start = $wrapper.data('start');
                if (start) {
                    $video.on('loadedmetadata', function () {
                        this.currentTime = parseInt(start, 10);
                    });
                }
                $video[0].play().catch(function () {});
            }
        },

        /**
         * Close modal and destroy video
         */
        closeModal: function ($wrapper) {
            var $overlay = $wrapper.find('.mn-vp-modal-overlay');
            var $container = $overlay.find('.mn-vp-embed-container');

            $overlay.fadeOut(250, function () {
                $container.empty();
                $('body').removeClass('mn-vp-modal-open');
            });
        },

        /**
         * Build embed HTML from wrapper data attributes
         */
        buildEmbed: function ($wrapper, forceAutoplay) {
            var source   = $wrapper.data('source');
            var videoUrl = $wrapper.data('video-url');
            var autoplay = forceAutoplay ? 'yes' : $wrapper.data('autoplay');
            var mute     = $wrapper.data('mute') || '';
            var loop     = $wrapper.data('loop') || '';
            var controls = $wrapper.data('controls') || 'yes';
            var start    = $wrapper.data('start') || '';
            var end      = $wrapper.data('end') || '';

            if (source === 'youtube') {
                return this.buildYouTubeEmbed(videoUrl, autoplay, mute, loop, controls, start, end);
            } else if (source === 'vimeo') {
                return this.buildVimeoEmbed(videoUrl, autoplay, mute, loop, start);
            } else if (source === 'self_hosted') {
                return this.buildHTML5Embed(videoUrl, autoplay, mute, loop, controls, start);
            }

            return '';
        },

        /**
         * Build YouTube iframe
         */
        buildYouTubeEmbed: function (url, autoplay, mute, loop, controls, start, end) {
            var videoId = this.extractYouTubeId(url);
            if (!videoId) return '';

            var params = [];
            if (autoplay === 'yes') params.push('autoplay=1');
            if (mute === 'yes') params.push('mute=1');
            if (loop === 'yes') {
                params.push('loop=1');
                params.push('playlist=' + videoId);
            }
            if (controls !== 'yes') params.push('controls=0');
            if (start) params.push('start=' + parseInt(start, 10));
            if (end) params.push('end=' + parseInt(end, 10));
            params.push('rel=0');

            var query = params.length ? '?' + params.join('&') : '';
            var src = 'https://www.youtube.com/embed/' + videoId + query;

            return '<iframe class="mn-vp-iframe" src="' + src + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        },

        /**
         * Build Vimeo iframe
         */
        buildVimeoEmbed: function (url, autoplay, mute, loop, start) {
            var videoId = this.extractVimeoId(url);
            if (!videoId) return '';

            var params = [];
            if (autoplay === 'yes') params.push('autoplay=1');
            if (mute === 'yes') params.push('muted=1');
            if (loop === 'yes') params.push('loop=1');

            var query = params.length ? '?' + params.join('&') : '';
            var hash = start ? '#t=' + parseInt(start, 10) + 's' : '';
            var src = 'https://player.vimeo.com/video/' + videoId + query + hash;

            return '<iframe class="mn-vp-iframe" src="' + src + '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
        },

        /**
         * Build HTML5 video element
         */
        buildHTML5Embed: function (url, autoplay, mute, loop, controls, start) {
            if (!url) return '';

            var attrs = 'playsinline';
            if (controls === 'yes') attrs += ' controls';
            if (autoplay === 'yes') attrs += ' autoplay';
            if (mute === 'yes') attrs += ' muted';
            if (loop === 'yes') attrs += ' loop';
            if (start) attrs += ' data-start="' + parseInt(start, 10) + '"';

            return '<video class="mn-vp-video" ' + attrs + '><source src="' + url + '" type="video/mp4"></video>';
        },

        /**
         * Extract YouTube video ID
         */
        extractYouTubeId: function (url) {
            var match = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i);
            return match ? match[1] : '';
        },

        /**
         * Extract Vimeo video ID
         */
        extractVimeoId: function (url) {
            var match = url.match(/vimeo\.com\/(?:video\/)?(\d+)/i);
            return match ? match[1] : '';
        }
    };

    $(function () {
        MNVideoPlayer.init();
    });

})(jQuery);
