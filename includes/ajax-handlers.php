<?php
/**
 * MN Elements AJAX Handlers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build WP_Query args from filter parameters
 */
function mn_woofilter_build_query_args() {
	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => isset( $_REQUEST['per_page'] ) ? intval( $_REQUEST['per_page'] ) : 12,
		'paged'          => isset( $_REQUEST['paged'] ) ? intval( $_REQUEST['paged'] ) : 1,
		'fields'         => 'ids',
	);

	$tax_query  = array();
	$meta_query = array();

	// Category filter
	if ( isset( $_REQUEST['product_cat'] ) && ! empty( $_REQUEST['product_cat'] ) ) {
		$cat_value = $_REQUEST['product_cat'];
		if ( is_array( $cat_value ) ) {
			$cats = array_map( 'sanitize_text_field', $cat_value );
		} else {
			$cats = array_filter( explode( ',', sanitize_text_field( $cat_value ) ) );
		}
		if ( ! empty( $cats ) ) {
			$tax_query[] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $cats,
			);
		}
	}

	// Price filter
	if ( isset( $_REQUEST['min_price'] ) || isset( $_REQUEST['max_price'] ) ) {
		$min_price = isset( $_REQUEST['min_price'] ) ? floatval( $_REQUEST['min_price'] ) : 0;
		$max_price = isset( $_REQUEST['max_price'] ) ? floatval( $_REQUEST['max_price'] ) : PHP_INT_MAX;
		if ( $min_price > 0 || $max_price < PHP_INT_MAX ) {
			$meta_query[] = array(
				'key'     => '_price',
				'value'   => array( $min_price, $max_price ),
				'compare' => 'BETWEEN',
				'type'    => 'DECIMAL(10,2)',
			);
		}
	}

	// Attribute filters (filter_color, filter_size, etc.)
	foreach ( $_REQUEST as $key => $value ) {
		if ( strpos( $key, 'filter_' ) === 0 && ! empty( $value ) ) {
			$attribute = sanitize_text_field( str_replace( 'filter_', '', $key ) );
			if ( is_array( $value ) ) {
				$terms = array_map( 'sanitize_text_field', $value );
			} else {
				$terms = array_filter( explode( ',', sanitize_text_field( $value ) ) );
			}
			if ( ! empty( $terms ) ) {
				$tax_query[] = array(
					'taxonomy' => 'pa_' . $attribute,
					'field'    => 'slug',
					'terms'    => $terms,
				);
			}
		}
	}

	// Rating filter
	if ( isset( $_REQUEST['rating_filter'] ) && ! empty( $_REQUEST['rating_filter'] ) ) {
		$rating = intval( $_REQUEST['rating_filter'] );
		if ( $rating > 0 ) {
			$meta_query[] = array(
				'key'     => '_wc_average_rating',
				'value'   => $rating,
				'compare' => '>=',
				'type'    => 'DECIMAL(10,2)',
			);
		}
	}

	// In stock filter
	if ( isset( $_REQUEST['in_stock'] ) && $_REQUEST['in_stock'] === '1' ) {
		$meta_query[] = array(
			'key'     => '_stock_status',
			'value'   => 'instock',
			'compare' => '=',
		);
	}

	// On sale filter
	if ( isset( $_REQUEST['on_sale'] ) && $_REQUEST['on_sale'] === '1' ) {
		$sale_ids = wc_get_product_ids_on_sale();
		if ( ! empty( $sale_ids ) ) {
			$args['post__in'] = $sale_ids;
		} else {
			$args['post__in'] = array( 0 );
		}
	}

	// Search filter
	if ( isset( $_REQUEST['s'] ) && ! empty( $_REQUEST['s'] ) ) {
		$args['s'] = sanitize_text_field( $_REQUEST['s'] );
	}

	// Apply tax_query
	if ( ! empty( $tax_query ) ) {
		if ( count( $tax_query ) > 1 ) {
			$tax_query['relation'] = 'AND';
		}
		$args['tax_query'] = $tax_query;
	}

	// Apply meta_query
	if ( ! empty( $meta_query ) ) {
		if ( count( $meta_query ) > 1 ) {
			$meta_query['relation'] = 'AND';
		}
		$args['meta_query'] = $meta_query;
	}

	return $args;
}

/**
 * Render a single product item HTML (server-side, matching widget output exactly)
 */
function mn_woofilter_render_product_item( $product_id, $settings = array() ) {
	$product = wc_get_product( $product_id );
	if ( ! $product ) return '';

	// Default settings matching widget defaults
	$s = wp_parse_args( $settings, array(
		'show_image'              => 'yes',
		'show_badge'              => 'yes',
		'show_sale_badge'         => 'yes',
		'sale_badge_type'         => 'text',
		'show_featured_badge'     => 'yes',
		'featured_badge_text'     => 'Featured',
		'show_out_of_stock_badge' => 'yes',
		'show_category'           => 'yes',
		'show_title'              => 'yes',
		'show_price'              => 'yes',
		'show_variations'         => 'no',
		'show_counter'            => 'no',
		'show_rating'             => 'no',
		'show_review_count'       => 'no',
		'show_sold_count'         => 'no',
		'show_add_to_cart'        => 'yes',
		'add_to_cart_text'        => 'Add to Cart',
		'ajax_add_to_cart'        => 'yes',
		'show_checkout'           => 'no',
		'checkout_text'           => 'Buy Now',
		'show_read_more'          => 'yes',
		'read_more_text'          => 'View Details',
		'image_type'              => 'single',
	) );

	$is_variable = $product->is_type( 'variable' );
	$is_on_sale  = $product->is_on_sale();
	$is_featured = $product->is_featured();
	$is_in_stock = $product->is_in_stock();
	$permalink   = get_permalink( $product_id );

	ob_start();
	?>
	<article class="mn-wooproduct-item" data-product-id="<?php echo esc_attr( $product_id ); ?>">
		<?php if ( $s['show_image'] === 'yes' ) : ?>
		<div class="mn-wooproduct-image-wrapper">
			<?php if ( $s['show_badge'] === 'yes' ) : ?>
			<div class="mn-wooproduct-badges">
				<?php if ( $s['show_sale_badge'] === 'yes' && $is_on_sale ) : ?>
					<span class="mn-wooproduct-badge mn-wooproduct-badge-sale">
						<?php
						if ( $s['sale_badge_type'] === 'percentage' && $product->is_type( 'simple' ) ) {
							$regular = (float) $product->get_regular_price();
							$sale    = (float) $product->get_sale_price();
							if ( $regular > 0 ) {
								echo '-' . round( ( ( $regular - $sale ) / $regular ) * 100 ) . '%';
							} else {
								esc_html_e( 'Sale!', 'mn-elements' );
							}
						} else {
							esc_html_e( 'Sale!', 'mn-elements' );
						}
						?>
					</span>
				<?php endif; ?>
				<?php if ( $s['show_featured_badge'] === 'yes' && $is_featured ) : ?>
					<span class="mn-wooproduct-badge mn-wooproduct-badge-featured"><?php echo esc_html( $s['featured_badge_text'] ); ?></span>
				<?php endif; ?>
				<?php if ( $s['show_out_of_stock_badge'] === 'yes' && ! $is_in_stock ) : ?>
					<span class="mn-wooproduct-badge mn-wooproduct-badge-outofstock"><?php esc_html_e( 'Out of Stock', 'mn-elements' ); ?></span>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<?php if ( $s['show_category'] === 'yes' ) : ?>
				<div class="mn-wooproduct-category-overlay"><?php mn_woofilter_render_category( $product ); ?></div>
			<?php endif; ?>
			<div class="mn-wooproduct-image" data-product-id="<?php echo esc_attr( $product_id ); ?>">
				<a href="<?php echo esc_url( $permalink ); ?>"><?php echo $product->get_image( 'woocommerce_thumbnail' ); ?></a>
			</div>
		</div>
		<?php endif; ?>
		<div class="mn-wooproduct-content">
			<?php if ( $s['show_category'] === 'yes' ) mn_woofilter_render_category( $product ); ?>
			<?php if ( $s['show_title'] === 'yes' ) : ?>
				<h3 class="mn-wooproduct-title"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3>
			<?php endif; ?>
			<?php if ( $s['show_price'] === 'yes' ) : ?>
				<div class="mn-wooproduct-price"><?php echo $product->get_price_html(); ?></div>
			<?php endif; ?>
			<?php if ( $s['show_counter'] === 'yes' ) : ?>
				<div class="mn-wooproduct-counter">
					<?php if ( $s['show_rating'] === 'yes' ) : ?>
						<div class="mn-wooproduct-rating">
							<?php
							$rating = $product->get_average_rating();
							for ( $i = 1; $i <= 5; $i++ ) {
								echo '<span class="' . ( $i <= round( $rating ) ? 'star-filled' : 'star-empty' ) . '">★</span>';
							}
							?>
						</div>
					<?php endif; ?>
					<?php if ( $s['show_review_count'] === 'yes' ) : ?>
						<span class="mn-wooproduct-review-count">(<?php echo esc_html( $product->get_review_count() ); ?>)</span>
					<?php endif; ?>
					<?php if ( $s['show_sold_count'] === 'yes' && $product->get_total_sales() > 0 ) : ?>
						<span class="mn-wooproduct-sold-count"><i class="fas fa-shopping-bag"></i> <?php printf( esc_html__( '%s sold', 'mn-elements' ), $product->get_total_sales() ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="mn-wooproduct-buttons">
				<div class="mn-wooproduct-buttons-left">
					<?php if ( $s['show_add_to_cart'] === 'yes' && $is_in_stock ) :
						$ajax_class = $s['ajax_add_to_cart'] === 'yes' ? 'mn-ajax-add-to-cart' : '';
					?>
						<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="mn-wooproduct-btn mn-wooproduct-btn-cart <?php echo esc_attr( $ajax_class ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>" data-quantity="1">
							<?php echo esc_html( $s['add_to_cart_text'] ); ?>
						</a>
					<?php endif; ?>
					<?php if ( $s['show_checkout'] === 'yes' && $is_in_stock ) : ?>
						<a href="<?php echo esc_url( wc_get_checkout_url() . '?add-to-cart=' . $product_id ); ?>" class="mn-wooproduct-btn mn-wooproduct-btn-checkout">
							<?php echo esc_html( $s['checkout_text'] ); ?>
						</a>
					<?php endif; ?>
				</div>
				<?php if ( $s['show_read_more'] === 'yes' ) : ?>
					<a href="<?php echo esc_url( $permalink ); ?>" class="mn-wooproduct-btn mn-wooproduct-btn-readmore">
						<?php echo esc_html( $s['read_more_text'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</article>
	<?php
	return ob_get_clean();
}

/**
 * Render product category link
 */
function mn_woofilter_render_category( $product ) {
	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		$term = $terms[0];
		echo '<div class="mn-wooproduct-category"><a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a></div>';
	}
}

/**
 * Handle MN WooFilter AJAX request - returns rendered HTML
 */
function mn_woofilter_ajax_handler() {
	// Verify nonce
	if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'mn_woofilter_nonce' ) ) {
		wp_send_json_error( array( 'message' => 'Invalid nonce' ) );
	}

	if ( ! class_exists( 'WooCommerce' ) ) {
		wp_send_json_error( array( 'message' => 'WooCommerce is not active' ) );
	}

	// Parse widget settings from request
	$widget_settings = array();
	if ( isset( $_REQUEST['widget_settings'] ) ) {
		$decoded = json_decode( stripslashes( $_REQUEST['widget_settings'] ), true );
		if ( is_array( $decoded ) ) {
			$widget_settings = array_map( 'sanitize_text_field', $decoded );
		}
	}

	$args  = mn_woofilter_build_query_args();
	$query = new WP_Query( $args );

	// Render product items HTML server-side using widget settings
	$html = '';
	if ( ! empty( $query->posts ) ) {
		foreach ( $query->posts as $product_id ) {
			$html .= mn_woofilter_render_product_item( $product_id, $widget_settings );
		}
	}

	wp_send_json_success( array(
		'html'         => $html,
		'total'        => $query->found_posts,
		'max_pages'    => $query->max_num_pages,
		'current_page' => $args['paged'],
	) );
}
add_action( 'wp_ajax_mn_woofilter_ajax', 'mn_woofilter_ajax_handler' );
add_action( 'wp_ajax_nopriv_mn_woofilter_ajax', 'mn_woofilter_ajax_handler' );

/**
 * Custom Thank You Page Redirect
 * Redirects WooCommerce order received URL to custom page if configured
 */
function mn_custom_thankyou_redirect( $url, $order ) {
	$custom_page_id = get_option( 'mn_custom_thankyou_page_id' );

	if ( ! $custom_page_id || ! is_numeric( $custom_page_id ) ) {
		return $url;
	}

	$custom_page_url = get_permalink( absint( $custom_page_id ) );
	if ( ! $custom_page_url ) {
		return $url;
	}

	// Build custom URL with order key for security
	$custom_url = add_query_arg( array(
		'order_key' => $order->get_order_key(),
		'order_id'  => $order->get_id(),
	), $custom_page_url );

	return $custom_url;
}
add_filter( 'woocommerce_get_checkout_order_received_url', 'mn_custom_thankyou_redirect', 10, 2 );

/**
 * Shortcode [mn_order_details] - Display order details on custom Thank You page
 */
function mn_order_details_shortcode( $atts ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return '';
	}

	// Enqueue styles for the order details
	if ( function_exists( 'mn_elements' ) ) {
		wp_enqueue_style(
			'mn-woocart-checkout-style',
			mn_elements()->plugin_url( 'assets/css/mn-woocart-checkout.css' ),
			array(),
			mn_elements()->get_version()
		);
	}

	$order_id  = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : 0;
	$order_key = isset( $_GET['order_key'] ) ? sanitize_text_field( $_GET['order_key'] ) : '';

	if ( ! $order_id || ! $order_key ) {
		return '<p class="mn-order-notice">' . esc_html__( 'No order information found.', 'mn-elements' ) . '</p>';
	}

	$order = wc_get_order( $order_id );
	if ( ! $order || $order->get_order_key() !== $order_key ) {
		return '<p class="mn-order-notice">' . esc_html__( 'Invalid order.', 'mn-elements' ) . '</p>';
	}

	ob_start();
	?>
	<div class="mn-order-details-wrapper">
		<div class="mn-order-confirmation">
			<div class="mn-order-confirmation-icon">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
			</div>
			<h2 class="mn-order-confirmation-title"><?php esc_html_e( 'Thank you for your order!', 'mn-elements' ); ?></h2>
			<p class="mn-order-confirmation-message"><?php esc_html_e( 'Your order has been received and is being processed.', 'mn-elements' ); ?></p>
		</div>

		<div class="mn-order-overview">
			<div class="mn-order-overview-item">
				<span class="mn-order-overview-label"><?php esc_html_e( 'Order Number', 'mn-elements' ); ?></span>
				<span class="mn-order-overview-value"><?php echo esc_html( $order->get_order_number() ); ?></span>
			</div>
			<div class="mn-order-overview-item">
				<span class="mn-order-overview-label"><?php esc_html_e( 'Date', 'mn-elements' ); ?></span>
				<span class="mn-order-overview-value"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span>
			</div>
			<div class="mn-order-overview-item">
				<span class="mn-order-overview-label"><?php esc_html_e( 'Total', 'mn-elements' ); ?></span>
				<span class="mn-order-overview-value"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
			</div>
			<div class="mn-order-overview-item">
				<span class="mn-order-overview-label"><?php esc_html_e( 'Payment Method', 'mn-elements' ); ?></span>
				<span class="mn-order-overview-value"><?php echo esc_html( $order->get_payment_method_title() ); ?></span>
			</div>
			<div class="mn-order-overview-item">
				<span class="mn-order-overview-label"><?php esc_html_e( 'Status', 'mn-elements' ); ?></span>
				<span class="mn-order-overview-value mn-order-status mn-order-status-<?php echo esc_attr( $order->get_status() ); ?>"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></span>
			</div>
		</div>

		<div class="mn-order-items">
			<h3 class="mn-order-section-title"><?php esc_html_e( 'Order Items', 'mn-elements' ); ?></h3>
			<table class="mn-order-items-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Product', 'mn-elements' ); ?></th>
						<th><?php esc_html_e( 'Quantity', 'mn-elements' ); ?></th>
						<th><?php esc_html_e( 'Total', 'mn-elements' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $order->get_items() as $item_id => $item ) :
						$product = $item->get_product();
					?>
						<tr>
							<td class="mn-order-item-name">
								<?php echo esc_html( $item->get_name() ); ?>
								<?php
								$meta_data = $item->get_formatted_meta_data( '' );
								if ( $meta_data ) {
									echo '<div class="mn-order-item-meta">';
									foreach ( $meta_data as $meta ) {
										echo '<span class="mn-order-meta-item"><strong>' . wp_kses_post( $meta->display_key ) . ':</strong> ' . wp_kses_post( $meta->display_value ) . '</span>';
									}
									echo '</div>';
								}
								?>
							</td>
							<td class="mn-order-item-qty"><?php echo esc_html( $item->get_quantity() ); ?></td>
							<td class="mn-order-item-total"><?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
						<tr>
							<td colspan="2" class="mn-order-totals-label"><?php echo wp_kses_post( $total['label'] ); ?></td>
							<td class="mn-order-totals-value"><?php echo wp_kses_post( $total['value'] ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tfoot>
			</table>
		</div>

		<?php if ( $order->get_billing_first_name() ) : ?>
		<div class="mn-order-addresses">
			<div class="mn-order-address mn-order-billing">
				<h3 class="mn-order-section-title"><?php esc_html_e( 'Billing Address', 'mn-elements' ); ?></h3>
				<address><?php echo wp_kses_post( $order->get_formatted_billing_address() ); ?></address>
				<?php if ( $order->get_billing_email() ) : ?>
					<p class="mn-order-email"><?php echo esc_html( $order->get_billing_email() ); ?></p>
				<?php endif; ?>
				<?php if ( $order->get_billing_phone() ) : ?>
					<p class="mn-order-phone"><?php echo esc_html( $order->get_billing_phone() ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $order->needs_shipping_address() && $order->get_formatted_shipping_address() ) : ?>
			<div class="mn-order-address mn-order-shipping">
				<h3 class="mn-order-section-title"><?php esc_html_e( 'Shipping Address', 'mn-elements' ); ?></h3>
				<address><?php echo wp_kses_post( $order->get_formatted_shipping_address() ); ?></address>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if ( $order->get_customer_note() ) : ?>
		<div class="mn-order-notes">
			<h3 class="mn-order-section-title"><?php esc_html_e( 'Order Notes', 'mn-elements' ); ?></h3>
			<p><?php echo wp_kses_post( nl2br( $order->get_customer_note() ) ); ?></p>
		</div>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'mn_order_details', 'mn_order_details_shortcode' );
