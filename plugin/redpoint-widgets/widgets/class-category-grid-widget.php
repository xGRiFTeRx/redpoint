<?php
/**
 * Category grid — "למצוא את ההנאה שלך" (Figma 109:311).
 *
 *   section  1440x842, padding 60/60, heading then a two-column grid
 *   grid     1320x600, two equal columns, 16px gap
 *   left     one tall card, 652x600 (109:316)
 *   right    a 2x2 of 318x292 cards (109:328/339/350/361)
 *   card     photo fill, a 162px scrim fading to #0C0C0C, title Futurism 30, desc 12 in
 *            white/55, and a "קנו עכשיו" link in the card's own colour
 *
 * @package redpoint-widgets
 */

namespace RedPoint\Widgets;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Repeater;
use \Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Category_Grid_Widget extends Widget_Base {

	public function get_name() {
		return 'redpoint_category_grid';
	}

	public function get_title() {
		return 'Category Grid';
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return array( 'redpoint' );
	}

	public function get_keywords() {
		return array( 'category', 'grid', 'pleasure', 'cards', 'redpoint' );
	}

	protected function register_controls() {

		/* ════════════════════════════════════════
		   CONTENT TAB
		════════════════════════════════════════ */

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
			array(
				'label'       => 'Lead (white)',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'למצוא את',
			)
		);

		$this->add_control(
			'accent',
			array(
				'label'       => 'Accent (red)',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'ההנאה שלך',
			)
		);

		$this->add_control(
			'kicker',
			array(
				'label'       => 'Kicker',
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => "בלי תוויות. בלי שיפוטיות.\nרק מה שמרגיש לך נכון.",
				'description' => 'One line per row. Sits opposite the heading.',
			)
		);

		$this->add_control(
			'kicker_width',
			array(
				'label'      => 'Kicker Width',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 120, 'max' => 400 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 211 ),
				'selectors'  => array( '{{WRAPPER}} .rp-heading__kicker' => 'width: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		/* ── Cards ── */
		$this->start_controls_section(
			'section_cards',
			array(
				'label' => 'Cards',
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			array(
				'label' => 'Image',
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => 'Title',
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$repeater->add_control(
			'desc',
			array(
				'label'   => 'Description',
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
			)
		);

		$repeater->add_control(
			'cta',
			array(
				'label'   => 'CTA Text',
				'type'    => Controls_Manager::TEXT,
				'default' => 'קנו עכשיו',
			)
		);

		$repeater->add_control(
			'color',
			array(
				'label'     => 'CTA Colour',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF3DD1',
				'selectors' => array( '{{WRAPPER}} {{CURRENT_ITEM}} .rp-cat-card__cta' => 'color: {{VALUE}};' ),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'   => 'Link',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '#' ),
			)
		);

		/*
		 * FIVE cards. The FIRST is the tall one on the left; the other four fill the 2x2 on
		 * the right. The default order below is the design's own reading order — the render
		 * method reorders the 2x2 for RTL (see the note there), so this list stays intuitive
		 * to edit.
		 */
		$this->add_control(
			'cards',
			array(
				'label'       => 'Cards (first = tall)',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'title' => 'התחילו בעדינות',
						'desc'  => 'היכרות עדינה עבור הסקרנים השקטים',
						'color' => '#FF3DD1',
						'cta'   => 'קנו עכשיו',
						'image' => array( 'url' => REDPOINT_WIDGETS_URL . 'assets/img/cat-gentle.webp' ),
						'link'  => array( 'url' => '#' ),
					),
					array(
						'title' => 'לזוגות',
						'desc'  => 'נועד לחוויה משותפת',
						'color' => '#FF3DD1',
						'cta'   => 'קנו עכשיו',
						'image' => array( 'url' => REDPOINT_WIDGETS_URL . 'assets/img/cat-couples.webp' ),
						'link'  => array( 'url' => '#' ),
					),
					array(
						'title' => 'הגבירו את העוצמה',
						'desc'  => 'למי שיודעים בדיוק מה הם רוצים',
						'color' => '#FF8A2B',
						'cta'   => 'קנו עכשיו',
						'image' => array( 'url' => REDPOINT_WIDGETS_URL . 'assets/img/cat-intensity.webp' ),
						'link'  => array( 'url' => '#' ),
					),
					array(
						'title' => 'גלו עוד',
						'desc'  => 'BDSM, משחק אנאלי והדברים הייחודיים והיוצאי דופן',
						'color' => '#FF3B3B',
						'cta'   => 'קנו עכשיו',
						'image' => array( 'url' => REDPOINT_WIDGETS_URL . 'assets/img/cat-explore.webp' ),
						'link'  => array( 'url' => '#' ),
					),
					array(
						'title' => 'לבשו את זה',
						'desc'  => 'הלבשה תחתונה וביגוד שמושכים את כל תשומת הלב',
						'color' => '#FFD13B',
						'cta'   => 'קנו עכשיו',
						'image' => array( 'url' => REDPOINT_WIDGETS_URL . 'assets/img/cat-wear.webp' ),
						'link'  => array( 'url' => '#' ),
					),
				),
			)
		);

		$this->end_controls_section();

		/* ════════════════════════════════════════
		   STYLE TAB
		════════════════════════════════════════ */

		$this->start_controls_section(
			'style_card',
			array(
				'label' => 'Card',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'card_title_typography',
				'label'          => 'Title',
				'selector'       => '{{WRAPPER}} .rp-cat-card__title',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Futurism' ),
					'font_size'   => array( 'default' => array( 'size' => 30, 'unit' => 'px' ) ),
					'line_height' => array( 'default' => array( 'size' => 30, 'unit' => 'px' ) ),
				),
			)
		);

		$this->add_control(
			'card_desc_color',
			array(
				'label'     => 'Description Colour',
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 0.55)',
				'selectors' => array( '{{WRAPPER}} .rp-cat-card__desc' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * One card.
	 *
	 * @param array  $c    Card settings.
	 * @param bool   $tall Tall (652x600) or small (318x292).
	 */
	private function render_card( $c, $tall ) {
		$img   = ! empty( $c['image']['url'] ) ? $c['image']['url'] : '';
		$href  = ! empty( $c['link']['url'] ) ? $c['link']['url'] : '#';
		$ext   = ! empty( $c['link']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
		$klass = 'rp-cat-card' . ( $tall ? ' rp-cat-card--tall' : '' ) . ' elementor-repeater-item-' . $c['_id'];
		?>
		<a class="<?php echo esc_attr( $klass ); ?>" href="<?php echo esc_url( $href ); ?>"<?php echo $ext; // phpcs:ignore ?>>
			<?php if ( $img ) : ?>
				<img class="rp-cat-card__media" src="<?php echo esc_url( $img ); ?>" alt="" aria-hidden="true">
			<?php endif; ?>

			<?php /* 162px scrim fading to #0C0C0C — see the CSS for the backdrop-blur note. */ ?>
			<span class="rp-cat-card__scrim" aria-hidden="true"></span>

			<span class="rp-cat-card__body">
				<span class="rp-cat-card__text">
					<span class="rp-cat-card__title"><?php echo esc_html( $c['title'] ); ?></span>
					<span class="rp-cat-card__desc"><?php echo esc_html( $c['desc'] ); ?></span>
				</span>
				<?php if ( $c['cta'] ) : ?>
					<span class="rp-cat-card__cta" style="color: <?php echo esc_attr( $c['color'] ); ?>;">
						<?php echo esc_html( $c['cta'] ); ?>
						<svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.17" stroke-linecap="round" aria-hidden="true"><path d="M11.08 7H2.92M6.42 3.5 2.92 7l3.5 3.5"/></svg>
					</span>
				<?php endif; ?>
			</span>
		</a>
		<?php
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$cards = ! empty( $s['cards'] ) ? $s['cards'] : array();

		if ( empty( $cards ) ) {
			return;
		}

		$tall  = $cards[0];
		$small = array_slice( $cards, 1 );

		/*
		 * Reorder the 2x2 for RTL. A two-column grid under dir=rtl fills each row RIGHT to
		 * LEFT, so the first card of a row lands on the right. The design's rows read
		 * left-to-right —
		 *   row 1:  לזוגות (left)  |  הגבירו את העוצמה (right)
		 *   row 2:  גלו עוד (left) |  לבשו את זה (right)
		 * — so each pair is swapped here to paint in that arrangement. Left un-swapped the
		 * whole grid comes out mirrored: correct photo, wrong side. This was THE recurring
		 * bug on the Next.js build of this design.
		 *
		 * The repeater itself stays in reading order so it is intuitive to edit; the swap is
		 * only for rendering.
		 */
		$ordered = array();
		for ( $i = 0; $i < count( $small ); $i += 2 ) {
			if ( isset( $small[ $i + 1 ] ) ) {
				$ordered[] = $small[ $i + 1 ];
			}
			$ordered[] = $small[ $i ];
		}

		$kicker = array_filter( array_map( 'trim', explode( "\n", (string) $s['kicker'] ) ) );
		?>
		<section class="rp-cat-grid" dir="rtl">

			<?php /* Title first so RTL paints it on the right; kicker opposite. */ ?>
			<div class="rp-heading">
				<h2 class="rp-heading__title">
					<span class="rp-heading__lead"><?php echo esc_html( $s['lead'] ); ?></span>
					<?php if ( $s['accent'] ) : ?>
						<span class="rp-heading__accent"> <?php echo esc_html( $s['accent'] ); ?></span>
					<?php endif; ?>
				</h2>
				<?php if ( $kicker ) : ?>
					<p class="rp-heading__kicker">
						<?php foreach ( $kicker as $line ) : ?>
							<span><?php echo esc_html( $line ); ?></span>
						<?php endforeach; ?>
					</p>
				<?php endif; ?>
			</div>

			<?php /* The 2x2 comes FIRST in the DOM so RTL paints it on the RIGHT; the tall
			         card is second and lands on the LEFT, where the design has it. */ ?>
			<div class="rp-cat-grid__layout">
				<div class="rp-cat-grid__quad">
					<?php foreach ( $ordered as $c ) : ?>
						<?php $this->render_card( $c, false ); ?>
					<?php endforeach; ?>
				</div>

				<?php $this->render_card( $tall, true ); ?>
			</div>

		</section>
		<?php
	}
}
