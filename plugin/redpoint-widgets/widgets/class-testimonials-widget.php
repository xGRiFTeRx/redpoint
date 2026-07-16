<?php
/**
 * Testimonials — "מדברים עלינו" (Figma 109:698).
 *
 *   section  1440x813, padding 80/60, gap 60, centred over a full-bleed pink gradient
 *   heading  Futurism 80 white, centred
 *   cards    two review cards (109:701, 109:720), 648x448, bg rgba(0,0,0,0.8), 24px radius
 *            each: a 267x400 portrait beside stars + date + quote + name
 *
 * A repeater, not a WooCommerce query — these are site testimonials, not per-product
 * reviews. Shown two at a time; the dots page through more if the client adds them.
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

class Testimonials_Widget extends Widget_Base {

	public function get_name() {
		return 'redpoint_testimonials';
	}

	public function get_title() {
		return 'Testimonials';
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	public function get_categories() {
		return array( 'redpoint' );
	}

	public function get_keywords() {
		return array( 'testimonials', 'reviews', 'quotes', 'redpoint' );
	}

	protected function register_controls() {

		$lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna.';

		/* ── Heading ── */
		$this->start_controls_section(
			'section_heading',
			array( 'label' => 'Heading', 'tab' => Controls_Manager::TAB_CONTENT )
		);
		$this->add_control( 'heading', array( 'label' => 'Heading', 'type' => Controls_Manager::TEXT, 'default' => 'מדברים עלינו' ) );
		$this->add_control(
			'background',
			array(
				'label'   => 'Background',
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => REDPOINT_WIDGETS_URL . 'assets/img/testimonial-bg.webp' ),
			)
		);
		$this->end_controls_section();

		/* ── Testimonials ── */
		$this->start_controls_section(
			'section_items',
			array( 'label' => 'Testimonials', 'tab' => Controls_Manager::TAB_CONTENT )
		);

		$rep = new Repeater();
		$rep->add_control( 'portrait', array( 'label' => 'Portrait', 'type' => Controls_Manager::MEDIA ) );
		$rep->add_control( 'name', array( 'label' => 'Name', 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$rep->add_control( 'date', array( 'label' => 'Date', 'type' => Controls_Manager::TEXT, 'default' => '21/09/2026' ) );
		$rep->add_control( 'quote', array( 'label' => 'Quote', 'type' => Controls_Manager::TEXTAREA, 'default' => '' ) );
		$rep->add_control(
			'rating',
			array( 'label' => 'Stars', 'type' => Controls_Manager::NUMBER, 'default' => 5, 'min' => 0, 'max' => 5 )
		);

		$this->add_control(
			'items',
			array(
				'label'       => 'Testimonials',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'title_field' => '{{{ name }}}',
				'default'     => array(
					array(
						'name'     => 'Sonia Hamilton',
						'date'     => '21/09/2026',
						'quote'    => $lorem . ' ' . $lorem . $lorem,
						'rating'   => 5,
						'portrait' => array( 'url' => REDPOINT_WIDGETS_URL . 'assets/img/portrait-1.webp' ),
					),
					array(
						'name'     => 'Edward Piastri',
						'date'     => '21/09/2026',
						'quote'    => $lorem . ' ' . $lorem . $lorem,
						'rating'   => 5,
						'portrait' => array( 'url' => REDPOINT_WIDGETS_URL . 'assets/img/portrait-2.webp' ),
					),
				),
			)
		);

		$this->add_control(
			'per_page',
			array(
				'label'   => 'Per row',
				'type'    => Controls_Manager::NUMBER,
				'default' => 2,
				'min'     => 1,
				'max'     => 3,
			)
		);

		$this->add_control(
			'dots',
			array(
				'label'       => 'Dots',
				'type'        => Controls_Manager::NUMBER,
				'default'     => 4,
				'min'         => 0,
				'max'         => 8,
				// Decorative, matching the Figma (109:739) and the Next.js reference — four
				// dots over the two cards. 0 hides them.
				'description' => 'The design draws four. Decorative — set 0 to hide.',
			)
		);

		$this->end_controls_section();
	}

	private function stars( $rating ) {
		$out = '<div class="rp-tcard__stars" aria-label="' . esc_attr( $rating ) . ' / 5">';
		for ( $i = 0; $i < 5; $i++ ) {
			$on   = $i < (int) $rating ? ' is-on' : '';
			$out .= '<svg viewBox="0 0 11 11" class="rp-tcard__star' . $on . '" fill="currentColor" aria-hidden="true"><path d="M5.5 0.75 6.97 3.73 10.26 4.21 7.88 6.53 8.44 9.81 5.5 8.26 2.56 9.81 3.12 6.53 0.74 4.21 4.03 3.73Z"/></svg>';
		}
		return $out . '</div>';
	}

	private function render_card( $t ) {
		$portrait = ! empty( $t['portrait']['url'] ) ? $t['portrait']['url'] : '';
		?>
		<div class="rp-tcard">
			<?php /* Copy first so RTL paints it on the RIGHT and the portrait on the LEFT. */ ?>
			<div class="rp-tcard__copy">
				<?php echo $this->stars( $t['rating'] ); // phpcs:ignore ?>
				<span class="rp-tcard__date"><?php echo esc_html( $t['date'] ); ?></span>
				<blockquote class="rp-tcard__quote"><?php echo esc_html( $t['quote'] ); ?></blockquote>
				<span class="rp-tcard__name"><?php echo esc_html( $t['name'] ); ?></span>
			</div>
			<?php if ( $portrait ) : ?>
				<div class="rp-tcard__portrait">
					<img class="rp-tcard__img" src="<?php echo esc_url( $portrait ); ?>" alt="" aria-hidden="true">
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$items = ! empty( $s['items'] ) ? $s['items'] : array();
		if ( empty( $items ) ) {
			return;
		}

		$per_page  = max( 1, (int) $s['per_page'] );
		// One row, reversed for RTL. Dots are decorative (see the control) — the design
		// shows two cards with four static dots beneath.
		$row       = array_reverse( $items );
		$dots      = max( 0, (int) $s['dots'] );
		$bg        = ! empty( $s['background']['url'] ) ? $s['background']['url'] : '';
		?>
		<section class="rp-testimonials" dir="rtl"<?php echo $bg ? ' style="background-image:url(' . esc_url( $bg ) . ');"' : ''; ?>>

			<h2 class="rp-testimonials__heading"><?php echo esc_html( $s['heading'] ); ?></h2>

			<div class="rp-carousel rp-testimonials__carousel" style="--rp-cols: <?php echo (int) $per_page; ?>;">
				<div class="rp-carousel__pages">
					<div class="rp-carousel__page">
						<?php foreach ( $row as $t ) : ?>
							<?php $this->render_card( $t ); ?>
						<?php endforeach; ?>
					</div>
				</div>

				<?php if ( $dots > 0 ) : ?>
					<?php /* Decorative — a single page, so these are purely visual, as in the design. */ ?>
					<div class="rp-carousel__dots" aria-hidden="true">
						<?php for ( $i = 0; $i < $dots; $i++ ) : ?>
							<span class="rp-carousel__dot<?php echo 0 === $i ? ' is-active' : ''; ?>"></span>
						<?php endfor; ?>
					</div>
				<?php endif; ?>
			</div>

		</section>
		<?php
	}
}
