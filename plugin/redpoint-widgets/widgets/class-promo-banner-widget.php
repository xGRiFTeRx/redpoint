<?php
/**
 * Promo banner — Figma 109:295.
 *
 *   section   1440x450, bg #0C0C0C, padding 50/60
 *   card      1320x350, 24px radius, 24px padding, photo fill
 *   scrim     715px on the copy side, fading to #0C0C0C
 *   copy      349px card, 24px padding, 32px gap — Futurism 40 title, 16/1.4 body at
 *             white/80, and a white pill CTA (146x40) with a #0C0C0C label
 *   chevrons  32px circles at both edges, black/30, white glyph
 *
 * On the category page the same section is 470 tall (109:1245) — the padding is 60, not 50.
 * That is a control.
 *
 * @package redpoint-widgets
 */

namespace RedPoint\Widgets;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Promo_Banner_Widget extends Widget_Base {

	public function get_name() {
		return 'redpoint_promo_banner';
	}

	public function get_title() {
		return 'Promo Banner';
	}

	public function get_icon() {
		return 'eicon-image-rollover';
	}

	public function get_categories() {
		return array( 'redpoint' );
	}

	public function get_keywords() {
		return array( 'promo', 'banner', 'offer', 'redpoint' );
	}

	protected function register_controls() {

		/* ════════════════════════════════════════
		   CONTENT TAB
		════════════════════════════════════════ */

		$this->start_controls_section(
			'section_content',
			array(
				'label' => 'Content',
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => 'Image',
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => REDPOINT_WIDGETS_URL . 'assets/img/promo.webp' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => 'Title',
				'type'    => Controls_Manager::TEXT,
				'default' => 'לרגעים מיוחדים',
			)
		);

		$this->add_control(
			'body',
			array(
				'label'       => 'Body',
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => "עד 25% הנחה לאביזרים לזוגות\nלרגל חג האהבה",
				'description' => 'One line per row.',
			)
		);

		$this->add_control(
			'cta_text',
			array(
				'label'   => 'Button Text',
				'type'    => Controls_Manager::TEXT,
				'default' => 'קנו עכשיו',
			)
		);

		$this->add_control(
			'cta_url',
			array(
				'label'   => 'Button Link',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '#' ),
			)
		);

		$this->add_control(
			'show_chevrons',
			array(
				'label'        => 'Show Chevrons',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => 'The design draws carousel arrows at both edges. They are decoration — the banner is a single slide in the Figma.',
			)
		);

		$this->end_controls_section();

		/* ── Section ── */
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => 'Section',
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'section_padding',
			array(
				'label'      => 'Vertical Padding',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 120 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 50 ),
				'selectors'  => array(
					'{{WRAPPER}} .rp-promo' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				),
				// 50 on the home page (109:295, section 450 tall); 60 on the category page
				// (109:1245, 470). Same 1320x350 card either way.
				'description' => '50px on the home page, 60px on the category page.',
			)
		);

		$this->add_responsive_control(
			'card_height',
			array(
				'label'      => 'Card Height',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 200, 'max' => 600 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 350 ),
				'selectors'  => array( '{{WRAPPER}} .rp-promo__card' => 'height: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		/* ════════════════════════════════════════
		   STYLE TAB
		════════════════════════════════════════ */

		/* ── Style: Scrim ── */
		$this->start_controls_section(
			'style_scrim',
			array(
				'label' => 'Scrim',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'scrim_width',
			array(
				'label'      => 'Width',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 1320 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 715 ),
				'selectors'  => array( '{{WRAPPER}} .rp-promo__scrim' => 'width: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->add_control(
			'scrim_end',
			array(
				'label'      => 'Fade End',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array( '%' => array( 'min' => 100, 'max' => 250 ) ),
				'default'    => array( 'unit' => '%', 'size' => 170 ),
				'selectors'  => array(
					'{{WRAPPER}} .rp-promo__scrim' => 'background-image: linear-gradient(to right, rgba(12,12,12,0) 15%, rgba(12,12,12,1) {{SIZE}}{{UNIT}});',
				),
				/*
				 * DEVIATION FROM FIGMA — deliberate, and client-requested.
				 *
				 * The file's stops are 15% -> 117%, which lands the card's right edge at ~83%
				 * black. Against the #0C0C0C page that edge is almost indistinguishable from
				 * the background, and the rounded corner reads as a faint outlined box — the
				 * "border" the client kept reporting. It was measured: there is no rim or
				 * seam, at DPR 1 / 1.25 / 1.5. The fade simply goes too dark.
				 *
				 * 170% lands the edge at ~57%, so the banner stays visibly solid to its
				 * corner. Set this back to 117 to match the Figma exactly.
				 */
				'description' => 'The Figma says 117%, which fades the card’s edge into the page and reads as a phantom border. 170% keeps it solid. Set 117 to match the file exactly.',
			)
		);

		$this->end_controls_section();

		/* ── Style: Title ── */
		$this->start_controls_section(
			'style_title',
			array(
				'label' => 'Title',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'title_typography',
				'selector'       => '{{WRAPPER}} .rp-promo__title',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Futurism' ),
					'font_size'   => array( 'default' => array( 'size' => 40, 'unit' => 'px' ) ),
					'line_height' => array( 'default' => array( 'size' => 1, 'unit' => 'em' ) ),
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => 'Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array( '{{WRAPPER}} .rp-promo__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		/* ── Style: Body ── */
		$this->start_controls_section(
			'style_body',
			array(
				'label' => 'Body',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'body_typography',
				'selector'       => '{{WRAPPER}} .rp-promo__body',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Google Sans' ),
					'font_size'   => array( 'default' => array( 'size' => 16, 'unit' => 'px' ) ),
					'line_height' => array( 'default' => array( 'size' => 1.4, 'unit' => 'em' ) ),
				),
			)
		);

		$this->add_control(
			'body_color',
			array(
				'label'     => 'Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 0.8)',
				'selectors' => array( '{{WRAPPER}} .rp-promo__body' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		/* ── Style: Button ── */
		$this->start_controls_section(
			'style_cta',
			array(
				'label' => 'Button',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'cta_bg',
			array(
				'label'     => 'Background',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array( '{{WRAPPER}} .rp-promo__cta' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'cta_color',
			array(
				'label'     => 'Text Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0C0C0C',
				'selectors' => array( '{{WRAPPER}} .rp-promo__cta' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$image = ! empty( $s['image']['url'] ) ? $s['image']['url'] : '';
		$lines = array_filter( array_map( 'trim', explode( "\n", (string) $s['body'] ) ) );
		$href  = ! empty( $s['cta_url']['url'] ) ? $s['cta_url']['url'] : '#';
		$ext   = ! empty( $s['cta_url']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
		?>
		<section class="rp-promo" dir="rtl">
			<div class="rp-promo__card">

				<?php if ( $image ) : ?>
					<?php
					/*
					 * The image is deliberately NOT given the card's radius. The card already
					 * clips it (overflow:hidden + border-radius); giving the image the same
					 * radius nests a second rounded clip inside the first, and the two
					 * antialiased curves compound into a visible light rim on the corner.
					 * One clip only.
					 */
					?>
					<img class="rp-promo__media" src="<?php echo esc_url( $image ); ?>" alt="" aria-hidden="true">
				<?php endif; ?>

				<?php
				/*
				 * The scrim carries NO backdrop-filter, although the Figma puts a 3px blur on
				 * it. backdrop-filter promotes the element to its own compositing layer, which
				 * Chrome does not antialias against the parent's border-radius — leaving a
				 * hairline tracing the card's rounded corners. Verified by toggling it: blur
				 * on = seam, blur off = clean. A 3px blur over a photograph is invisible; the
				 * seam is not, so the blur loses.
				 */
				?>
				<span class="rp-promo__scrim" aria-hidden="true"></span>

				<?php /* Copy group first so RTL paints it on the RIGHT, as the design has it. */ ?>
				<div class="rp-promo__group">
					<?php if ( 'yes' === $s['show_chevrons'] ) : ?>
						<span class="rp-promo__chevron" aria-hidden="true">
							<?php echo redpoint_icon( 'chevron-right' ); // phpcs:ignore ?>
						</span>
					<?php endif; ?>

					<div class="rp-promo__copy">
						<div class="rp-promo__text">
							<?php if ( $s['title'] ) : ?>
								<h3 class="rp-promo__title"><?php echo esc_html( $s['title'] ); ?></h3>
							<?php endif; ?>

							<?php if ( $lines ) : ?>
								<p class="rp-promo__body">
									<?php foreach ( $lines as $line ) : ?>
										<span><?php echo esc_html( $line ); ?></span>
									<?php endforeach; ?>
								</p>
							<?php endif; ?>
						</div>

						<?php if ( $s['cta_text'] ) : ?>
							<?php /* justify-start = the RIGHT edge under RTL, matching the design. */ ?>
							<div class="rp-promo__cta-row">
								<a class="rp-promo__cta" href="<?php echo esc_url( $href ); ?>"<?php echo $ext; // phpcs:ignore ?>>
									<?php echo esc_html( $s['cta_text'] ); ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( 'yes' === $s['show_chevrons'] ) : ?>
					<span class="rp-promo__chevron" aria-hidden="true">
						<?php echo redpoint_icon( 'chevron-left' ); // phpcs:ignore ?>
					</span>
				<?php endif; ?>

			</div>
		</section>
		<?php
	}
}
