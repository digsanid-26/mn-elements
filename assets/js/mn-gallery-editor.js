/**
 * MN Gallery Widget - Elementor Editor Script
 * Handles dynamic taxonomy dropdown population based on selected post type
 */

(function($) {
    'use strict';

    // Wait for Elementor editor to be ready
    $(window).on('elementor:init', function() {
        
        // Listen for post type changes
        elementor.channels.editor.on('change', function(controlView, elementView) {
            const controlName = controlView.model.get('name');
            
            // Check if featured_post_type control changed
            if (controlName === 'featured_post_type') {
                const postType = controlView.getControlValue();
                const widgetId = elementView.model.get('id');
                
                // Update taxonomy dropdown options
                updateTaxonomyOptions(widgetId, postType);
            }
        });
        
        /**
         * Update taxonomy dropdown options based on selected post type
         */
        function updateTaxonomyOptions(widgetId, postType) {
            // Get the widget view
            const widgetView = elementor.getPanelView().getCurrentPageView().children.findByModel(
                elementor.getPanelView().getCurrentPageView().collection.findWhere({ id: widgetId })
            );
            
            if (!widgetView) return;
            
            // Make AJAX request to get taxonomies for the post type
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'mn_gallery_get_taxonomies',
                    post_type: postType,
                    nonce: mnGalleryEditor.nonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        // Update the taxonomy control options
                        const taxonomyControl = widgetView.children.findByModel(
                            widgetView.collection.findWhere({ name: 'featured_taxonomy' })
                        );
                        
                        if (taxonomyControl) {
                            // Update control options
                            taxonomyControl.model.set('options', response.data);
                            
                            // Re-render the control
                            taxonomyControl.render();
                            
                            // Reset the value to empty
                            taxonomyControl.setValue('');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('MN Gallery: Failed to load taxonomies', error);
                }
            });
        }
        
        // Initialize taxonomy options when widget is first added
        elementor.hooks.addAction('panel/open_editor/widget/mn-gallery', function(panel, model, view) {
            const settings = model.get('settings');
            const postType = settings.get('featured_post_type');
            
            if (postType && settings.get('gallery_source') === 'featured') {
                updateTaxonomyOptions(model.get('id'), postType);
            }
        });
    });
    
})(jQuery);
