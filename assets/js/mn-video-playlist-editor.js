(function($) {
    'use strict';

    // Wait for Elementor editor to be ready
    $(window).on('elementor:init', function() {
        
        // Listen for panel open events
        elementor.hooks.addAction('panel/open_editor/widget/mn-video-playlist', function(panel, model, view) {
            
            // Wait for panel to render
            setTimeout(function() {
                initClearCacheButton(panel, model);
            }, 100);
            
        });

    });

    /**
     * Initialize Clear Cache button functionality
     */
    function initClearCacheButton(panel, model) {
        var $panel = panel.$el;
        
        // Find the clear cache button control
        var $clearCacheControl = $panel.find('[data-setting="youtube_clear_cache"]').closest('.elementor-control');
        
        if ($clearCacheControl.length === 0) {
            // Try alternative selector for button type control
            $clearCacheControl = $panel.find('.elementor-control-youtube_clear_cache');
        }

        if ($clearCacheControl.length === 0) {
            return;
        }

        // Find or create the button
        var $button = $clearCacheControl.find('button.elementor-button');
        
        if ($button.length === 0) {
            // Create button if not exists
            $clearCacheControl.find('.elementor-control-content').append(
                '<button type="button" class="elementor-button elementor-button-default mn-clear-cache-btn">' +
                '<i class="eicon-sync"></i> Clear YouTube Cache' +
                '</button>'
            );
            $button = $clearCacheControl.find('.mn-clear-cache-btn');
        }

        // Remove existing click handlers and add new one
        $button.off('click.mnClearCache').on('click.mnClearCache', function(e) {
            e.preventDefault();
            
            var settings = model.get('settings').attributes;
            var sourceType = settings.source_type;
            var id = '';

            // Get the appropriate ID based on source type
            if (sourceType === 'youtube_playlist') {
                id = settings.youtube_playlist_id;
            } else if (sourceType === 'youtube_channel') {
                id = settings.youtube_channel_id;
            }

            if (!id) {
                alert('Please enter a YouTube Playlist ID or Channel ID first.');
                return;
            }

            // Show loading state
            var $btn = $(this);
            var originalText = $btn.html();
            $btn.html('<i class="eicon-loading eicon-animation-spin"></i> Clearing...');
            $btn.prop('disabled', true);

            // Make AJAX request to clear cache
            $.ajax({
                url: mnVideoPlaylistEditor.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'mn_video_playlist_clear_cache',
                    nonce: mnVideoPlaylistEditor.nonce,
                    source_type: sourceType,
                    id: id
                },
                success: function(response) {
                    if (response.success) {
                        $btn.html('<i class="eicon-check"></i> Cache Cleared!');
                        
                        // Refresh the widget preview
                        setTimeout(function() {
                            $btn.html(originalText);
                            $btn.prop('disabled', false);
                            
                            // Trigger widget re-render
                            model.renderRemoteServer();
                        }, 1500);
                    } else {
                        $btn.html('<i class="eicon-warning"></i> Error');
                        setTimeout(function() {
                            $btn.html(originalText);
                            $btn.prop('disabled', false);
                        }, 2000);
                    }
                },
                error: function() {
                    $btn.html('<i class="eicon-warning"></i> Error');
                    setTimeout(function() {
                        $btn.html(originalText);
                        $btn.prop('disabled', false);
                    }, 2000);
                }
            });
        });
    }

})(jQuery);
