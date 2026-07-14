<?php
/**
 * Trust strip — Figma 109:257.
 *
 *   section  1440x154, bg #0C0C0C, padding 30/60, four items 90px apart, centred
 *   item     131 wide — a 20px stroke icon inside a 44x44 circle on a 10%-red ground
 *            (109:259), then 16px down the title, then 8px down the subline
 *   title    Google Sans Medium 14, #fff, line-height 1
 *   subline  Google Sans Regular 12, rgba(255,255,255,0.35), line-height 1
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

class Trust_Strip_Widget extends Widget_Base {

	public function get_name() {
		return 'redpoint_trust_strip';
	}

	public function get_title() {
		return 'Trust Strip';
	}

	public function get_icon() {
		return 'eicon-info-box';
	}

	public function get_categories() {
		return array( 'redpoint' );
	}

	public function get_keywords() {
		return array( 'trust', 'badges', 'strip', 'redpoint' );
	}

	protected function register_controls() {

		/* ════════════════════════════════════════
		   CONTENT TAB
		════════════════════════════════════════ */

		$this->start_controls_section(
			'section_items',
			array(
				'label' => 'Items',
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		/*
		 * A SELECT of icons shipped with the plugin, not Elementor's ICONS control.
		 * Elementor forces `fill` onto an SVG's paths, which turns the design's stroke
		 * icons into solid blobs. Inlining our own file keeps the outline. To add an icon,
		 * drop the SVG in assets/icons/ and add it to this list.
		 */
		$repeater->add_control(
			'icon',
			array(
				'label'   => 'Icon',
				'type'    => Controls_Manager::SELECT,
				'default' => 'trust-1',
				'options' => array(
					'trust-1' => 'Package',
					'trust-2' => 'Lock',
					'trust-3' => 'Truck',
					'trust-4' => 'Award',
				),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => 'Title',
				'type'    => Controls_Manager::TEXT,
				'default' => 'אריזה פשוטה',
			)
		);

		$repeater->add_control(
			'subtitle',
			array(
				'label'   => 'Subtitle',
				'type'    => Controls_Manager::TEXT,
				'default' => 'חבילה דיסקרטית 100%',
			)
		);

		/*
		 * Listed LEFT TO RIGHT as the design draws them (109:258 sits at x=323, the award
		 * at x=986) — see the direction note in the CSS for why the row is not simply
		 * reversed here.
		 */
		$this->add_control(
			'items',
			array(
				'label'       => 'Items',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'icon'     => 'trust-1',
						'title'    => 'אריזה פשוטה',
						'subtitle' => 'חבילה דיסקרטית 100%',
					),
					array(
						'icon'     => 'trust-2',
						'title'    => 'תשלום מאובטח',
						'subtitle' => 'צ׳קאאוט מוצפן, תמיד',
					),
					array(
						'icon'     => 'trust-3',
						'title'    => 'משלוח דיסקרטי',
						'subtitle' => 'ללא מותג על הקופסה',
					),
					array(
						'icon'     => 'trust-4',
						'title'    => '+40 שנות אמון',
						'subtitle' => 'מאז 1984',
					),
				),
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

		$this->add_control(
			'bg_color',
			array(
				'label'     => 'Background Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0C0C0C',
				'selectors' => array( '{{WRAPPER}} .rp-trust' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'item_gap',
			array(
				'label'      => 'Gap Between Items',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 200 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 90 ),
				'selectors'  => array( '{{WRAPPER}} .rp-trust__items' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		/* ════════════════════════════════════════
		   STYLE TAB
		════════════════════════════════════════ */

		/* ── Style: Icon ── */
		$this->start_controls_section(
			'style_icon',
			array(
				'label' => 'Icon',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => 'Icon Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF3B3B',
				// The SVGs use stroke="currentColor", so `color` drives the stroke. Setting
				// `stroke` directly would not survive Elementor's own icon rules.
				'selectors' => array( '{{WRAPPER}} .rp-trust__icon' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'icon_bg_color',
			array(
				'label'     => 'Icon Background',
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 59, 59, 0.1)',
				'selectors' => array( '{{WRAPPER}} .rp-trust__icon' => 'background-color: {{VALUE}};' ),
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
				'selector'       => '{{WRAPPER}} .rp-trust__title',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Google Sans' ),
					'font_weight' => array( 'default' => '500' ),
					'font_size'   => array( 'default' => array( 'size' => 14, 'unit' => 'px' ) ),
					// line-height 1: the design's text block is exactly 14 + 8 + 12 = 34px
					// tall (109:265). Any leading above 1 and the strip runs tall.
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
				'selectors' => array( '{{WRAPPER}} .rp-trust__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		/* ── Style: Subtitle ── */
		$this->start_controls_section(
			'style_subtitle',
			array(
				'label' => 'Subtitle',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'subtitle_typography',
				'selector'       => '{{WRAPPER}} .rp-trust__subtitle',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Google Sans' ),
					'font_weight' => array( 'default' => '400' ),
					'font_size'   => array( 'default' => array( 'size' => 12, 'unit' => 'px' ) ),
					'line_height' => array( 'default' => array( 'size' => 1, 'unit' => 'em' ) ),
				),
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label' => 'Color',
				'type'  => Controls_Manager::COLOR,
				// rgba(255,255,255,0.35), NOT the #818181 used elsewhere in the design.
				// They look alike on black and are not the same value (109:267).
				'default'   => 'rgba(255, 255, 255, 0.35)',
				'selectors' => array( '{{WRAPPER}} .rp-trust__subtitle' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = ! empty( $settings['items'] ) ? $settings['items'] : array();

		if ( empty( $items ) ) {
			return;
		}
		?>
		<div class="rp-trust" dir="rtl">
			<div class="rp-trust__items">
				<?php foreach ( $items as $item ) : ?>
					<div class="rp-trust__item">
						<span class="rp-trust__icon" aria-hidden="true">
							<?php
							// Inline SVG from the plugin's own assets — see redpoint_icon().
							echo redpoint_icon( $item['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</span>
						<div class="rp-trust__text">
							<p class="rp-trust__title"><?php echo esc_html( $item['title'] ); ?></p>
							<p class="rp-trust__subtitle"><?php echo esc_html( $item['subtitle'] ); ?></p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
