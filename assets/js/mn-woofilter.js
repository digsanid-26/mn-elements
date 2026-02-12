/**
 * MN WooFilter Widget JavaScript
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        initWooFilter();
    });

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/mn-woofilter.default', function($scope) {
            initWooFilter($scope);
        });
    });

    function initWooFilter($scope) {
        var $container = $scope ? $scope : $(document);

        $container.find('.mn-woofilter-wrapper').each(function() {
            var $wrapper = $(this);
            var $form = $wrapper.find('.mn-woofilter-form');
            var isAjax = $wrapper.hasClass('mn-woofilter-ajax');
            var targetId = $wrapper.data('target');

            // Initialize mobile sidebar
            initMobileSidebar($wrapper);

            // Initialize price slider
            initPriceSlider($wrapper);

            // Initialize price presets
            initPricePresets($wrapper);

            // Initialize category buttons
            initCategoryButtons($wrapper);

            // Initialize collapsible groups in sidebar
            initCollapsibleGroups($wrapper);

            // Reset button
            $wrapper.find('.mn-woofilter-reset').on('click', function(e) {
                e.preventDefault();
                resetFilters($wrapper);
            });

            // AJAX filtering
            if (isAjax) {
                // Debounce for input changes - only desktop form (sidebar uses Apply button)
                var filterTimeout;
                var $desktopForm = $wrapper.find('.mn-woofilter-form').not('.mn-woofilter-sidebar .mn-woofilter-form').first();
                if (!$desktopForm.length) {
                    $desktopForm = $wrapper.find('.mn-woofilter-form').first();
                }

                $desktopForm.find('input, select').not('input[type="range"]').on('change', function() {
                    clearTimeout(filterTimeout);
                    filterTimeout = setTimeout(function() {
                        applyAjaxFilter($wrapper, targetId);
                    }, 500);
                });

                // Prevent form submission
                $wrapper.find('.mn-woofilter-form').on('submit', function(e) {
                    e.preventDefault();
                    applyAjaxFilter($wrapper, targetId);
                });
            }
        });
    }

    /**
     * Initialize Mobile Sidebar
     */
    function initMobileSidebar($wrapper) {
        var $trigger = $wrapper.find('.mn-woofilter-mobile-trigger');
        var $sidebar = $wrapper.find('.mn-woofilter-sidebar');
        var $overlay = $wrapper.find('.mn-woofilter-overlay');
        var $closeBtn = $wrapper.find('.mn-woofilter-sidebar-close');
        var $applyBtn = $wrapper.find('.mn-woofilter-sidebar-footer .mn-woofilter-apply');

        // Open sidebar
        $trigger.on('click', function(e) {
            e.preventDefault();
            // Sync desktop form values to sidebar before opening
            var $desktopForm = $wrapper.find('.mn-woofilter-form').not('.mn-woofilter-sidebar .mn-woofilter-form').first();
            var $sidebarForm = $sidebar.find('.mn-woofilter-form');
            if ($desktopForm.length && $sidebarForm.length) {
                syncForms($desktopForm, $sidebarForm);
            }
            openSidebar($sidebar, $overlay);
            // Re-initialize price slider inside sidebar after it's visible
            initPriceSlider($sidebar);
            initPricePresets($sidebar);
            initCategoryButtons($sidebar);
        });

        // Close sidebar - close button
        $closeBtn.on('click', function(e) {
            e.preventDefault();
            closeSidebar($sidebar, $overlay);
        });

        // Close sidebar - overlay click
        $overlay.on('click', function() {
            closeSidebar($sidebar, $overlay);
        });

        // Close sidebar - ESC key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $sidebar.hasClass('active')) {
                closeSidebar($sidebar, $overlay);
            }
        });

        // Apply filters and close sidebar
        $applyBtn.on('click', function(e) {
            e.preventDefault();
            
            // Sync sidebar form values to desktop form
            var $sidebarForm = $wrapper.find('.mn-woofilter-sidebar .mn-woofilter-form');
            var $desktopForm = $wrapper.find('.mn-woofilter-form').not('.mn-woofilter-sidebar .mn-woofilter-form').first();
            if ($sidebarForm.length && $desktopForm.length) {
                syncForms($sidebarForm, $desktopForm);
            }
            
            // If AJAX filtering, trigger the filter
            if ($wrapper.hasClass('mn-woofilter-ajax')) {
                var targetId = $wrapper.data('target');
                applyAjaxFilter($wrapper, targetId);
            } else {
                // Submit the desktop form
                $desktopForm.length ? $desktopForm.submit() : $sidebarForm.submit();
            }
            
            closeSidebar($sidebar, $overlay);
        });

        // Update filter count badge
        updateFilterCountBadge($wrapper);

        // Update badge on filter change
        $wrapper.find('.mn-woofilter-form input, .mn-woofilter-form select').on('change', function() {
            updateFilterCountBadge($wrapper);
        });
    }

    function openSidebar($sidebar, $overlay) {
        $sidebar.addClass('active');
        $overlay.addClass('active');
        $('body').addClass('mn-woofilter-sidebar-open');
    }

    function closeSidebar($sidebar, $overlay) {
        $sidebar.removeClass('active');
        $overlay.removeClass('active');
        $('body').removeClass('mn-woofilter-sidebar-open');
    }

    function updateFilterCountBadge($wrapper) {
        var count = 0;
        var $form = $wrapper.find('.mn-woofilter-form').first();

        // Count checked checkboxes
        count += $form.find('input[type="checkbox"]:checked').length;

        // Count selected radios (excluding default)
        count += $form.find('input[type="radio"]:checked').length;

        // Count filled text/number inputs
        $form.find('input[type="text"], input[type="number"]').each(function() {
            if ($(this).val() && $(this).val().trim() !== '') {
                count++;
            }
        });

        // Count selected dropdowns (not default)
        $form.find('select').each(function() {
            if ($(this).val() && $(this).val() !== '') {
                count++;
            }
        });

        // Update badge
        var $badge = $wrapper.find('.mn-woofilter-count-badge');
        if (count > 0) {
            $badge.text(count);
        } else {
            $badge.text('');
        }
    }

    function syncForms($source, $dest) {
        // Sync checkboxes
        $source.find('input[type="checkbox"]').each(function() {
            var name = $(this).attr('name');
            var value = $(this).val();
            var isChecked = $(this).is(':checked');
            $dest.find('input[type="checkbox"][name="' + name + '"][value="' + value + '"]').prop('checked', isChecked);
        });

        // Sync radios
        $source.find('input[type="radio"]:checked').each(function() {
            var name = $(this).attr('name');
            var value = $(this).val();
            $dest.find('input[type="radio"][name="' + name + '"][value="' + value + '"]').prop('checked', true);
        });

        // Sync text/number/hidden inputs
        $source.find('input[type="text"], input[type="number"], input[type="hidden"]').each(function() {
            var name = $(this).attr('name');
            if (name) {
                $dest.find('input[name="' + name + '"]').val($(this).val());
            }
        });

        // Sync selects
        $source.find('select').each(function() {
            var name = $(this).attr('name');
            if (name) {
                $dest.find('select[name="' + name + '"]').val($(this).val());
            }
        });

        // Sync range inputs
        $source.find('input[type="range"]').each(function() {
            var $this = $(this);
            var className = $this.attr('class');
            if (className) {
                $dest.find('input[type="range"].' + className.split(' ').join('.')).val($this.val());
            }
        });
    }

    function initPriceSlider($wrapper) {
        var $sliderWrapper = $wrapper.find('.mn-woofilter-price-slider-wrapper');
        if (!$sliderWrapper.length) return;

        var $minHandle = $sliderWrapper.find('.mn-price-min');
        var $maxHandle = $sliderWrapper.find('.mn-price-max');
        var $range = $sliderWrapper.find('.mn-woofilter-price-range');
        var $minDisplay = $sliderWrapper.find('.mn-price-min-display');
        var $maxDisplay = $sliderWrapper.find('.mn-price-max-display');
        var $minInput = $sliderWrapper.find('input[name="min_price"]');
        var $maxInput = $sliderWrapper.find('input[name="max_price"]');

        var min = parseFloat($sliderWrapper.data('min'));
        var max = parseFloat($sliderWrapper.data('max'));

        // Get currency symbol directly from data attribute (set by PHP)
        var currencySymbol = $sliderWrapper.attr('data-currency') || '';

        function updateSlider() {
            var minVal = parseFloat($minHandle.val());
            var maxVal = parseFloat($maxHandle.val());

            // Ensure min doesn't exceed max
            if (minVal > maxVal) {
                var temp = minVal;
                minVal = maxVal;
                maxVal = temp;
            }

            // Update range bar position
            var minPercent = ((minVal - min) / (max - min)) * 100;
            var maxPercent = ((maxVal - min) / (max - min)) * 100;

            $range.css({
                'left': minPercent + '%',
                'width': (maxPercent - minPercent) + '%'
            });

            // Update display using stored currency symbol
            $minDisplay.text(currencySymbol + numberFormat(minVal));
            $maxDisplay.text(currencySymbol + numberFormat(maxVal));

            // Update hidden inputs
            $minInput.val(minVal);
            $maxInput.val(maxVal);
        }

        // Unbind previous events to prevent duplicates on re-init
        $minHandle.off('.mnslider');
        $maxHandle.off('.mnslider');

        // Update display dynamically while dragging (visual only, no AJAX)
        $minHandle.on('input.mnslider', function() {
            updateSlider();
        });
        $maxHandle.on('input.mnslider', function() {
            updateSlider();
        });

        // Trigger AJAX filter only when user releases the slider handle
        var sliderChangeTriggered = false;
        $minHandle.add($maxHandle).on('change.mnslider', function() {
            updateSlider();
            sliderChangeTriggered = true;
            $minInput.trigger('change');
            setTimeout(function() { sliderChangeTriggered = false; }, 100);
        });
        $minHandle.add($maxHandle).on('mouseup.mnslider touchend.mnslider', function() {
            setTimeout(function() {
                if (!sliderChangeTriggered) {
                    updateSlider();
                    $minInput.trigger('change');
                }
            }, 50);
        });

        // Initial update
        updateSlider();
    }

    function initPricePresets($wrapper) {
        var $presets = $wrapper.find('.mn-woofilter-price-presets');
        if (!$presets.length) return;

        $presets.find('input[name="price_preset"]').off('change.mnpreset').on('change.mnpreset', function() {
            var val = $(this).val();
            var parts = val.split('-');
            var minPrice = parts[0] || '';
            var maxPrice = parts[1] || '';

            // Find or create hidden min_price/max_price inputs in the same form
            var $form = $(this).closest('form');
            var $minInput = $form.find('input[name="min_price"]');
            var $maxInput = $form.find('input[name="max_price"]');

            if (!$minInput.length) {
                $minInput = $('<input type="hidden" name="min_price">').appendTo($form);
            }
            if (!$maxInput.length) {
                $maxInput = $('<input type="hidden" name="max_price">').appendTo($form);
            }

            $minInput.val(minPrice);
            $maxInput.val(maxPrice).trigger('change');
        });
    }

    function initCategoryButtons($wrapper) {
        $wrapper.find('.mn-woofilter-cat-btn').off('click.mncatbtn').on('click.mncatbtn', function() {
            var $btn = $(this);
            var value = $btn.data('value');
            var $input = $btn.siblings('input[name="product_cat"]');

            $btn.siblings('.mn-woofilter-cat-btn').removeClass('active');
            $btn.addClass('active');
            $input.val(value).trigger('change');
        });
    }

    function initCollapsibleGroups($wrapper) {
        var $sidebar = $wrapper.find('.mn-woofilter-sidebar');
        if (!$sidebar.length) return;

        $sidebar.find('.mn-woofilter-group').each(function(index) {
            var $group = $(this);
            var $title = $group.find('.mn-woofilter-title');
            if (!$title.length) return;

            // Open first group by default
            if (index === 0) {
                $group.addClass('open');
            }

            $title.off('click.collapsible').on('click.collapsible', function(e) {
                e.preventDefault();
                $group.toggleClass('open');
            });
        });
    }

    function resetFilters($wrapper) {
        var $allForms = $wrapper.find('.mn-woofilter-form');

        $allForms.each(function() {
            var $form = $(this);

            // Reset all inputs
            $form.find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
            $form.find('input[type="text"], input[type="number"]').val('');
            $form.find('input[type="hidden"]').each(function() {
                var name = $(this).attr('name');
                if (name === 'post_type') return; // Keep post_type
                $(this).val('');
            });
            $form.find('select').prop('selectedIndex', 0);

            // Reset price slider
            var $sliderWrapper = $form.find('.mn-woofilter-price-slider-wrapper');
            if ($sliderWrapper.length) {
                var min = $sliderWrapper.data('min');
                var max = $sliderWrapper.data('max');
                $sliderWrapper.find('.mn-price-min').val(min);
                $sliderWrapper.find('.mn-price-max').val(max);
                $sliderWrapper.find('input[name="min_price"]').val(min);
                $sliderWrapper.find('input[name="max_price"]').val(max);
            }

            // Reset category buttons
            $form.find('.mn-woofilter-cat-btn').removeClass('active');
            $form.find('input[name="product_cat"]').val('');
        });

        // Re-init price sliders to update visual range bar
        initPriceSlider($wrapper);
        var $sidebar = $wrapper.find('.mn-woofilter-sidebar');
        if ($sidebar.length) {
            initPriceSlider($sidebar);
        }

        // Trigger AJAX filter
        if ($wrapper.hasClass('mn-woofilter-ajax')) {
            var targetId = $wrapper.data('target');
            applyAjaxFilter($wrapper, targetId);
        }
    }

    function applyAjaxFilter($wrapper, targetId) {
        // Use only the desktop form (not the sidebar duplicate)
        var $form = $wrapper.find('.mn-woofilter-form').not('.mn-woofilter-sidebar .mn-woofilter-form').first();
        if (!$form.length) {
            $form = $wrapper.find('.mn-woofilter-form').first();
        }
        var formData = $form.serialize();

        // Find target product widget
        var $target = targetId ? $('#' + targetId) : $('.mn-wooproduct-wrapper').first();

        if (!$target.length) {
            // Fallback to form submission
            $form.submit();
            return;
        }

        // Check if AJAX params are available
        if (typeof mn_woofilter_params === 'undefined') {
            $form.submit();
            return;
        }

        $wrapper.addClass('mn-woofilter-loading');
        $target.addClass('mn-woofilter-loading');

        // Get default price range from slider (if exists) to skip unchanged values
        var $sliderWrapper = $form.find('.mn-woofilter-price-slider-wrapper');
        var defaultMin = $sliderWrapper.length ? String($sliderWrapper.data('min')) : null;
        var defaultMax = $sliderWrapper.length ? String($sliderWrapper.data('max')) : null;

        // Remove empty params, post_type, price_preset, and unchanged price defaults
        var cleanData = formData.split('&').filter(function(param) {
            var parts = param.split('=');
            var key = parts[0];
            var val = decodeURIComponent(parts[1] || '');
            // Skip empty values and internal params
            if (val === '' || val === undefined) return false;
            if (key === 'post_type') return false;
            if (key === 'price_preset') return false;
            // Skip default price range values (user hasn't changed slider)
            if (defaultMin !== null && key === 'min_price' && val === defaultMin) return false;
            if (defaultMax !== null && key === 'max_price' && val === defaultMax) return false;
            return true;
        }).join('&');

        // Build URL for browser history (human-readable)
        var pageBaseUrl = $form.attr('data-ajax-url') || window.location.href.split('?')[0];
        var browserUrl = pageBaseUrl + (cleanData ? '?' + cleanData : '');

        // Build AJAX data object
        var ajaxData = {
            action: 'mn_woofilter_ajax',
            nonce: mn_woofilter_params.nonce,
            per_page: $target.data('per-page') || 12,
            widget_settings: JSON.stringify($target.data('widget-settings') || {})
        };

        // Parse cleanData string into the ajaxData object
        if (cleanData) {
            cleanData.split('&').forEach(function(pair) {
                var parts = pair.split('=');
                if (parts[0] && parts[1]) {
                    var key = decodeURIComponent(parts[0]);
                    var val = decodeURIComponent(parts[1]);
                    // Handle array params like product_cat[]
                    if (key.indexOf('[]') > -1) {
                        var baseKey = key.replace('[]', '');
                        if (!ajaxData[key]) ajaxData[key] = [];
                        ajaxData[key].push(val);
                    } else {
                        ajaxData[key] = val;
                    }
                }
            });
        }

        $.ajax({
            url: mn_woofilter_params.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: ajaxData,
            success: function(response) {
                if (response && response.success && response.data && response.data.html) {
                    // Use server-rendered HTML to preserve all styles and structure
                    $target.find('.mn-wooproduct-grid').html(response.data.html);
                    
                    // Re-initialize product widget JS if available
                    if (typeof window.initMNWooProduct === 'function') {
                        window.initMNWooProduct();
                    }
                    
                    // Trigger custom event for other scripts
                    $(document).trigger('mn_woofilter_updated', [$target]);
                } else {
                    console.log('MN WooFilter response:', response);
                    var noResultsText = mn_woofilter_params.no_products_text || 'No products found.';
                    $target.find('.mn-wooproduct-grid').html('<p class="mn-wooproduct-no-results">' + noResultsText + '</p>');
                }

                // Update URL without reload
                if (history.pushState) {
                    history.pushState(null, null, browserUrl);
                }

                $wrapper.removeClass('mn-woofilter-loading');
                $target.removeClass('mn-woofilter-loading');
            },
            error: function(xhr, status, error) {
                console.log('MN WooFilter AJAX Error:', status, error);
                $wrapper.removeClass('mn-woofilter-loading');
                $target.removeClass('mn-woofilter-loading');
            }
        });
    }

    function numberFormat(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    // Handle browser back/forward
    $(window).on('popstate', function() {
        location.reload();
    });

})(jQuery);
