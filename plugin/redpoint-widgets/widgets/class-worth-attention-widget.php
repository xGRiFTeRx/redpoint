<?php
/**
 * Worth Attention — "מוצרים ששווים תשומת הלב" (Figma 109:478).
 *
 *   section  1440x1662, padding 90/60/68, 50px gaps
 *   heading  two-tone Futurism 80
 *   row 1    three product cards (429 wide)
 *   strip    value badges (109:555) — four icon chips, title only, no dots
 *   row 2    three product cards
 *   button   solid "לכל המוצרים" pill, centred
 *
 * Unlike Best Sellers this has NO carousel — it is two static rows with the value strip
 * between them, closed by a button instead of dots.
 *
 * @package redpoint-widgets
 */

namespace RedPoint\Widgets;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Worth_Attention_Widget extends Widget_Base {

	public function get_name() {
		return 'redpoint_worth_attention';
	}

	public function get_title() {
		return 'Worth Attention';
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_categories() {
		return array( 'redpoint' );
	}

	public function get_keywords() {
		return array( 'products', 'worth attention', 'value', 'woocommerce', 'redpoint' );
	}

	protected function register_controls() {

		/* ── Heading ── */
		$this->start_controls_section(
			'section_heading',
			array( 'label' => 'Heading', 'tab' => Controls_Manager::TAB_CONTENT )
		);
		$this->add_control( 'lead', array( 'label' => 'Lead (white)', 'type' => Controls_Manager::TEXT, 'default' => 'מוצרים ששווים' ) );
		$this->add_control( 'accent', array( 'label' => 'Accent (red)', 'type' => Controls_Manager::TEXT, 'default' => 'תשומת הלב' ) );
		$this->end_controls_section();

		/* ── Products ── */
		$this->start_controls_section(
			'section_products',
			array( 'label' => 'Products', 'tab' => Controls_Manager::TAB_CONTENT )
		);

		$this->add_control(
			'source',
			array(
				'label'   => 'Source',
				'type'    => Controls_Manager::SELECT,
				'default' => 'featured',
				'options' => array(
					'featured'     => 'Featured',
					'newest'       => 'Newest',
					'best_selling' => 'Best selling',
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
				'label'       => 'How many',
				'type'        => Controls_Manager::NUMBER,
				'default'     => 6,
				'min'         => 3,
				'max'         => 12,
				'description' => 'Two rows of three — six is the design.',
			)
		);

		$this->add_control(
			'badges',
			array(
				'label'        => 'Show Badges',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		/* ── Value strip ── */
		$this->start_controls_section(
			'section_values',
			array( 'label' => 'Value Strip', 'tab' => Controls_Manager::TAB_CONTENT )
		);

		$rep = new Repeater();
		$rep->add_control(
			'icon',
			array(
				'label'   => 'Icon',
				'type'    => Controls_Manager::SELECT,
				'default' => 'value-1',
				'options' => array(
					'value-1' => 'Eye-slash (discretion)',
					'value-2' => 'Seal-check (quality)',
					'value-3' => 'Magnifier (transparency)',
					'value-4' => 'Handshake (service)',
				),
			)
		);
		$rep->add_control( 'label', array( 'label' => 'Label', 'type' => Controls_Manager::TEXT, 'default' => '' ) );

		/*
		 * Listed LEFT to RIGHT as the design draws them (109:556 דיסקרטיות at x=263 … 109:571
		 * שירות אישי at x=926). The strip is forced to direction:ltr in the CSS so this order
		 * paints unchanged — see the note there, same handling as the trust strip.
		 */
		$this->add_control(
			'values',
			array(
				'label'       => 'Badges',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => array(
					array( 'icon' => 'value-1', 'label' => 'דיסקרטיות' ),
					array( 'icon' => 'value-2', 'label' => 'איכות' ),
					array( 'icon' => 'value-3', 'label' => 'שקיפות' ),
					array( 'icon' => 'value-4', 'label' => 'שירות אישי' ),
				),
			)
		);

		$this->end_controls_section();

		/* ── Button ── */
		$this->start_controls_section(
			'section_button',
			array( 'label' => 'Button', 'tab' => Controls_Manager::TAB_CONTENT )
		);
		$this->add_control( 'button_text', array( 'label' => 'Text', 'type' => Controls_Manager::TEXT, 'default' => 'לכל המוצרים' ) );
		$this->add_control( 'button_url', array( 'label' => 'Link', 'type' => Controls_Manager::URL, 'default' => array( 'url' => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '#' ) ) );
		$this->end_controls_section();
	}

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

	/** Render one row of product cards, reversed for RTL. */
	private function render_row( $products, $badges ) {
		if ( empty( $products ) ) {
			return;
		}
		// Reverse for RTL — the grid fills right-to-left, so without this the row mirrors.
		$row = array_reverse( $products );
		?>
		<div class="rp-worth__row">
			<?php foreach ( $row as $product ) : ?>
				<?php redpoint_product_card( $product, array( 'size' => 'full', 'badges' => $badges ) ); ?>
			<?php endforeach; ?>
		</div>
		<?php
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$products = redpoint_query_products(
			array(
				'source'   => $s['source'],
				'count'    => (int) $s['count'],
				'category' => isset( $s['category'] ) ? $s['category'] : array(),
			)
		);

		if ( empty( $products ) ) {
			return;
		}

		$half   = (int) ceil( count( $products ) / 2 );
		$row1   = array_slice( $products, 0, $half );
		$row2   = array_slice( $products, $half );
		$badges = 'yes' === $s['badges'];
		$values = ! empty( $s['values'] ) ? $s['values'] : array();

		$btn_href = ! empty( $s['button_url']['url'] ) ? $s['button_url']['url'] : '#';
		$btn_ext  = ! empty( $s['button_url']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
		?>
		<section class="rp-worth" dir="rtl">

			<div class="rp-heading">
				<h2 class="rp-heading__title">
					<span class="rp-heading__lead"><?php echo esc_html( $s['lead'] ); ?></span>
					<?php if ( $s['accent'] ) : ?>
						<span class="rp-heading__accent"> <?php echo esc_html( $s['accent'] ); ?></span>
					<?php endif; ?>
				</h2>
			</div>

			<?php $this->render_row( $row1, $badges ); ?>

			<?php if ( $values ) : ?>
				<div class="rp-worth__values">
					<?php foreach ( $values as $v ) : ?>
						<div class="rp-worth__value">
							<span class="rp-worth__value-icon" aria-hidden="true"><?php echo redpoint_icon( $v['icon'] ); // phpcs:ignore ?></span>
							<span class="rp-worth__value-label"><?php echo esc_html( $v['label'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $row2 ) : ?>
				<?php $this->render_row( $row2, $badges ); ?>
			<?php endif; ?>

			<?php if ( $s['button_text'] ) : ?>
				<div class="rp-worth__more-row">
					<a class="rp-worth__more" href="<?php echo esc_url( $btn_href ); ?>"<?php echo $btn_ext; // phpcs:ignore ?>>
						<?php echo esc_html( $s['button_text'] ); ?>
					</a>
				</div>
			<?php endif; ?>

		</section>
		<?php
	}
}
