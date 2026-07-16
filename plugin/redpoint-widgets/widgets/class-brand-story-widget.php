<?php
/**
 * Brand Story — "40 שנים בחושך" (Figma 109:744).
 *
 *   section  1440x650, padding 140/60, centred
 *   headline two lines at 90/80, tracking -2.2 — "40 שנים בחושך" white, "עכשיו הדלקנו את
 *            האורות." in accent red
 *   body     a single 573px paragraph, 16/1.4 in white/55
 *
 * The red comes from the BACKGROUND, in two layers (this section reads as flat black
 * without them):
 *   1. a radial gradient anchored bottom-centre — rgba(255,59,59,0.14) fading out by 60%
 *   2. a soft blurred red block behind the copy (109:745)
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

class Brand_Story_Widget extends Widget_Base {

	public function get_name() {
		return 'redpoint_brand_story';
	}

	public function get_title() {
		return 'Brand Story';
	}

	public function get_icon() {
		return 'eicon-text';
	}

	public function get_categories() {
		return array( 'redpoint' );
	}

	public function get_keywords() {
		return array( 'brand', 'story', 'about', 'redpoint' );
	}

	protected function register_controls() {

		/* ── Content ── */
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'Content', 'tab' => Controls_Manager::TAB_CONTENT )
		);

		$this->add_control(
			'lead',
			array( 'label' => 'Headline — Line 1 (white)', 'type' => Controls_Manager::TEXT, 'default' => '40 שנים בחושך' )
		);

		$this->add_control(
			'accent',
			array( 'label' => 'Headline — Line 2 (red)', 'type' => Controls_Manager::TEXT, 'default' => 'עכשיו הדלקנו את האורות.' )
		);

		$this->add_control(
			'body',
			array(
				'label'   => 'Paragraph',
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 5,
				'default' => 'רד פוינט מנחה עונג כבר ארבעה עשורים, וסיימנו ללחוש על זה. עונג הוא טבעי, חושני, ולכל גוף. בלי בושה. בלי פשרות זולות. רק אוצרות יוצאת דופן, ליווי מקצועי ודיסקרטיות מלאה בכל הזמנה.',
			)
		);

		$this->end_controls_section();

		/* ── Style ── */
		$this->start_controls_section(
			'style',
			array( 'label' => 'Style', 'tab' => Controls_Manager::TAB_STYLE )
		);

		$this->add_control(
			'bg_color',
			array(
				'label'     => 'Background',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0C0C0C',
				'selectors' => array( '{{WRAPPER}} .rp-brand' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'glow',
			array(
				'label'        => 'Red Glow',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => 'The radial glow + blurred block behind the copy. Without it the section reads as flat black — it is where the red comes from.',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'headline_typography',
				'label'          => 'Headline',
				'selector'       => '{{WRAPPER}} .rp-brand__headline',
				'fields_options' => array(
					'typography'     => array( 'default' => 'yes' ),
					'font_family'    => array( 'default' => 'Futurism' ),
					'font_size'      => array( 'default' => array( 'size' => 90, 'unit' => 'px' ) ),
					'line_height'    => array( 'default' => array( 'size' => 80, 'unit' => 'px' ) ),
					'letter_spacing' => array( 'default' => array( 'size' => -2.2, 'unit' => 'px' ) ),
				),
			)
		);

		$this->add_control(
			'lead_color',
			array(
				'label'     => 'Line 1 Colour',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array( '{{WRAPPER}} .rp-brand__lead' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'accent_color',
			array(
				'label'     => 'Line 2 Colour',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF3B3B',
				'selectors' => array( '{{WRAPPER}} .rp-brand__accent' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'body_color',
			array(
				'label'     => 'Paragraph Colour',
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 0.55)',
				'selectors' => array( '{{WRAPPER}} .rp-brand__body' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$glow = 'yes' === $s['glow'];
		?>
		<section class="rp-brand<?php echo $glow ? ' rp-brand--glow' : ''; ?>" dir="rtl">
			<?php if ( $glow ) : ?>
				<span class="rp-brand__block" aria-hidden="true"></span>
			<?php endif; ?>

			<div class="rp-brand__inner">
				<h2 class="rp-brand__headline">
					<span class="rp-brand__lead"><?php echo esc_html( $s['lead'] ); ?></span>
					<span class="rp-brand__accent"><?php echo esc_html( $s['accent'] ); ?></span>
				</h2>
				<?php if ( $s['body'] ) : ?>
					<p class="rp-brand__body"><?php echo esc_html( $s['body'] ); ?></p>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
