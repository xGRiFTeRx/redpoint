<?php
/**
 * Shared product card (Figma 109:377 and its siblings).
 *
 * FOUR sections render this exact card — Best Sellers, Worth Attention, the product-page
 * upsell and the "you may like" row — so it lives here once rather than in each widget.
 *
 *   card   318x563 (full) / 244x438 (compact)
 *   image  362px, 1px white/10 border, 4px top radius, an optional badge pill top-LEFT
 *   body   #111, 16px padding, 16px gap, 4px bottom radius
 *          row   : name 18 Medium (right) | price group (left) — new white, old red strike
 *          stars : five 11px gold glyphs
 *          desc  : #818181 14/1.4, right
 *          button: full width, 40px, 1px white/40 pill, "הוספה לסל"
 *
 * @package redpoint-widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Query products for a section (Best Sellers, Worth Attention, upsell, "you may like").
 *
 * @param array $q 'source' (best_selling|featured|newest|on_sale|category), 'count',
 *                 'category' (array of slugs), 'exclude' (product id to skip).
 * @return \WC_Product[]
 */
function redpoint_query_products( $q ) {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}

	$source = isset( $q['source'] ) ? $q['source'] : 'newest';
	$count  = isset( $q['count'] ) ? max( 1, (int) $q['count'] ) : 8;

	$args = array(
		'status'  => 'publish',
		'limit'   => $count,
		'return'  => 'objects',
		'orderby' => 'date',
		'order'   => 'DESC',
	);

	if ( ! empty( $q['exclude'] ) ) {
		$args['exclude'] = array( (int) $q['exclude'] );
	}

	switch ( $source ) {
		case 'best_selling':
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery
			$args['order']    = 'DESC';
			break;
		case 'featured':
			$args['featured'] = true;
			break;
		case 'on_sale':
			$ids = wc_get_product_ids_on_sale();
			if ( empty( $ids ) ) {
				return array();
			}
			$args['include'] = $ids;
			break;
		case 'category':
			if ( ! empty( $q['category'] ) ) {
				$args['category'] = (array) $q['category'];
			}
			break;
	}

	$products = wc_get_products( $args );

	// best_selling / featured on a store with no sales or no featured flags yet would come
	// back empty; fall back to newest so a section never renders blank.
	if ( empty( $products ) && in_array( $source, array( 'best_selling', 'featured' ), true ) ) {
		unset( $args['meta_key'], $args['featured'] );
		$args['orderby'] = 'date';
		$products = wc_get_products( $args );
	}

	return $products;
}

/**
 * Render one product card from a WooCommerce product.
 *
 * @param \WC_Product $product The product.
 * @param array       $args    'size' => 'full'|'compact', 'badges' => bool.
 */
function redpoint_product_card( $product, $args = array() ) {
	if ( ! $product instanceof \WC_Product ) {
		return;
	}

	$size    = isset( $args['size'] ) && 'compact' === $args['size'] ? 'compact' : 'full';
	$badges  = ! isset( $args['badges'] ) || $args['badges'];
	$compact = 'compact' === $size;

	$link  = get_permalink( $product->get_id() );
	$name  = $product->get_name();
	$rating = (float) $product->get_average_rating();

	// Badge, auto-derived: on sale -> "Discount"; published in the last 30 days -> "New".
	// The design shows both; a real store decides from the product, not a hand-set field.
	$badge = '';
	if ( $badges ) {
		if ( $product->is_on_sale() ) {
			$badge = 'Discount';
		} elseif ( ( time() - get_post_time( 'U', false, $product->get_id() ) ) < 30 * DAY_IN_SECONDS ) {
			$badge = 'New';
		}
	}

	// Prices, symbol before the number and no decimals (set in setup-woo.sh). New price
	// FIRST in the DOM so under RTL it paints on the right, with the struck old price to its
	// left — matching the design and the pixel-verified Next.js build.
	$reg  = (float) $product->get_regular_price();
	$sale = (float) $product->get_sale_price();
	$on_sale = $product->is_on_sale() && $sale > 0;

	$size_class = $compact ? ' rp-pcard--compact' : '';
	$label      = $compact ? 'Add to Cart' : 'הוספה לסל';
	?>
	<div class="rp-pcard<?php echo esc_attr( $size_class ); ?>">
		<a class="rp-pcard__media" href="<?php echo esc_url( $link ); ?>">
			<?php
			echo $product->get_image( 'woocommerce_single', array( 'class' => 'rp-pcard__img', 'aria-hidden' => 'true' ) ); // phpcs:ignore
			?>
			<?php if ( $badge ) : ?>
				<span class="rp-pcard__badge"><?php echo esc_html( $badge ); ?></span>
			<?php endif; ?>
		</a>

		<div class="rp-pcard__body">
			<div class="rp-pcard__group">
				<div class="rp-pcard__row">
					<a class="rp-pcard__name" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $name ); ?></a>

					<div class="rp-pcard__prices">
						<span class="rp-pcard__price"><?php echo wp_kses_post( wc_price( $on_sale ? $sale : $reg ) ); ?></span>
						<?php if ( $on_sale ) : ?>
							<span class="rp-pcard__price rp-pcard__price--old"><?php echo wp_kses_post( wc_price( $reg ) ); ?></span>
						<?php endif; ?>
					</div>
				</div>

				<div class="rp-pcard__stars" aria-label="<?php echo esc_attr( sprintf( '%s / 5', number_format_i18n( $rating, 1 ) ) ); ?>">
					<?php for ( $i = 0; $i < 5; $i++ ) : ?>
						<svg viewBox="0 0 11 11" class="rp-pcard__star<?php echo $i < round( $rating ) ? ' is-on' : ''; ?>" fill="currentColor" aria-hidden="true"><path d="M5.5 0.75 6.97 3.73 10.26 4.21 7.88 6.53 8.44 9.81 5.5 8.26 2.56 9.81 3.12 6.53 0.74 4.21 4.03 3.73Z"/></svg>
					<?php endfor; ?>
				</div>

				<?php $desc = $product->get_short_description(); ?>
				<?php if ( $desc ) : ?>
					<p class="rp-pcard__desc" dir="ltr"><?php echo esc_html( wp_strip_all_tags( $desc ) ); ?></p>
				<?php endif; ?>
			</div>

			<?php
			// The real WooCommerce add-to-cart link, so the button works — not a dummy.
			// esc_url keeps the query args intact.
			$add_url = $product->add_to_cart_url();
			?>
			<a class="rp-pcard__btn" href="<?php echo esc_url( $add_url ); ?>"
				data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
				rel="nofollow"><?php echo esc_html( $label ); ?></a>
		</div>
	</div>
	<?php
}
