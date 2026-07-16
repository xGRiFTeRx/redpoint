<?php
/**
 * Best Sellers — "הנמכרים ביותר" (Figma 109:372).
 *
 *   section  1440x838, padding 90/60/0 (no bottom padding — the dots sit flush)
 *   heading  two-tone Futurism 80, no kicker
 *   row      four product cards (109:377), 16px gap, dots beneath
 *
 * Binds to WooCommerce: it queries products (best-selling / featured / newest / on-sale /
 * a category) and renders the shared product card. Add products in Woo and they appear.
 *
 * @package redpoint-widgets
 */

namespace RedPoint\Widgets;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Best_Sellers_Widget extends Widget_Base {

	public function get_name() {
		return 'redpoint_best_sellers';
	}

	public function get_title() {
		return 'Best Sellers';
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_categories() {
		return array( 'redpoint' );
	}

	public function get_keywords() {
		return array( 'products', 'best sellers', 'carousel', 'woocommerce', 'redpoint' );
	}

	public function get_script_depends() {
		return array( 'redpoint-carousel' );
	}

	protected function register_controls() {

		/* ── Heading ── */
		$this->start_controls_section(
			'section_heading',
			array(
				'label' => 'Heading',
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'lead',
			array( 'label' => 'Lead (white)', 'type' => Controls_Manager::TEXT, 'default' => 'הנמכרים' )
		);

		$this->add_control(
			'accent',
			array( 'label' => 'Accent (red)', 'type' => Controls_Manager::TEXT, 'default' => 'ביותר' )
		);

		$this->end_controls_section();

		/* ── Products ── */
		$this->start_controls_section(
			'section_products',
			array(
				'label' => 'Products',
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'source',
			array(
				'label'   => 'Source',
				'type'    => Controls_Manager::SELECT,
				'default' => 'best_selling',
				'options' => array(
					'best_selling' => 'Best selling',
					'featured'     => 'Featured',
					'newest'       => 'Newest',
					'on_sale'      => 'On sale',
					'category'     => 'By category',
				),
			)
		);

		$this->add_control(
			'category',
			array(
				'label'       => 'Category',
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->category_options(),
				'multiple'    => true,
				'label_block' => true,
				'condition'   => array( 'source' => 'category' ),
			)
		);

		$this->add_control(
			'count',
			array(
				'label'   => 'How many',
				'type'    => Controls_Manager::NUMBER,
				// 12 = three pages of four, matching the Next.js reference. The dot count is
				// count / columns, so this is what sets it: 12/4 = 3 dots, 8/4 = 2, etc.
				'default' => 12,
				'min'     => 1,
				'max'     => 24,
				'description' => 'Shown four at a time; the dots page through the rest. 12 = 3 pages.',
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'   => 'Columns',
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 4,
			)
		);

		$this->add_control(
			'badges',
			array(
				'label'        => 'Show Badges',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => 'On-sale → "Discount", published in the last 30 days → "New".',
			)
		);

		$this->add_control(
			'dots',
			array(
				'label'        => 'Show Dots',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/** Product categories for the SELECT2, skipping the ones with no design colour. */
	private function category_options() {
		$out = array();
		if ( ! taxonomy_exists( 'product_cat' ) ) {
			return $out;
		}
		$terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) );
		if ( is_wp_error( $terms ) ) {
			return $out;
		}
		foreach ( $terms as $t ) {
			$out[ $t->slug ] = $t->name;
		}
		return $out;
	}

	/** Query the products for the chosen source. @return \WC_Product[] */
	private function get_products( $s ) {
		if ( ! function_exists( 'wc_get_products' ) ) {
			return array();
		}

		$count = max( 1, (int) $s['count'] );
		$args  = array(
			'status'  => 'publish',
			'limit'   => $count,
			'return'  => 'objects',
			'orderby' => 'date',
			'order'   => 'DESC',
		);

		switch ( $s['source'] ) {
			case 'best_selling':
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery
				$args['order']    = 'DESC';
				break;
			case 'featured':
				$args['featured'] = true;
				break;
			case 'on_sale':
				$args['include'] = wc_get_product_ids_on_sale();
				if ( empty( $args['include'] ) ) {
					return array();
				}
				break;
			case 'category':
				if ( ! empty( $s['category'] ) ) {
					$args['category'] = (array) $s['category'];
				}
				break;
			case 'newest':
			default:
				break;
		}

		$products = wc_get_products( $args );

		// "Best selling" with no sales data yet falls back to newest, so an empty demo store
		// still shows a full row instead of nothing.
		if ( 'best_selling' === $s['source'] && empty( $products ) ) {
			unset( $args['meta_key'], $args['orderby'] );
			$args['orderby'] = 'date';
			$products = wc_get_products( $args );
		}

		return $products;
	}

	protected function render() {
		$s        = $this->get_settings_for_display();
		$products = $this->get_products( $s );

		if ( empty( $products ) ) {
			return;
		}

		$columns = max( 1, (int) $s['columns'] );
		$pages   = array_chunk( $products, $columns );
		$badges  = 'yes' === $s['badges'];
		$show_dots = 'yes' === $s['dots'] && count( $pages ) > 1;
		?>
		<section class="rp-bestsellers" dir="rtl">

			<div class="rp-heading">
				<h2 class="rp-heading__title">
					<span class="rp-heading__lead"><?php echo esc_html( $s['lead'] ); ?></span>
					<?php if ( $s['accent'] ) : ?>
						<span class="rp-heading__accent"> <?php echo esc_html( $s['accent'] ); ?></span>
					<?php endif; ?>
				</h2>
			</div>

			<div class="rp-carousel" style="--rp-cols: <?php echo (int) $columns; ?>;">
				<div class="rp-carousel__pages">
					<?php foreach ( $pages as $page_i => $page ) : ?>
						<?php
						/*
						 * Reverse each page for RTL. The grid fills right-to-left, so the first
						 * card would land on the right; reversing the DOM order puts card 1 back
						 * on the right-to-left reading start. Same handedness fix as every card
						 * row in this design.
						 */
						$row = array_reverse( $page );
						?>
						<div class="rp-carousel__page"<?php echo $page_i > 0 ? ' hidden' : ''; ?>>
							<?php foreach ( $row as $product ) : ?>
								<?php redpoint_product_card( $product, array( 'size' => 'full', 'badges' => $badges ) ); ?>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( $show_dots ) : ?>
					<div class="rp-carousel__dots" role="tablist">
						<?php foreach ( $pages as $page_i => $page ) : ?>
							<button type="button" class="rp-carousel__dot<?php echo 0 === $page_i ? ' is-active' : ''; ?>"
								role="tab" aria-selected="<?php echo 0 === $page_i ? 'true' : 'false'; ?>"
								aria-label="<?php echo esc_attr( sprintf( '%d', $page_i + 1 ) ); ?>"></button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

		</section>
		<?php
	}
}
