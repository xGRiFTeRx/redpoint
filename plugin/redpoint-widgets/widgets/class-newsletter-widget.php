<?php
/**
 * Newsletter — "הצטרפו לרשימת התפוצה הדיסקרטית" (Figma 109:751).
 *
 *   section  padding 32/60, on #0C0C0C
 *   card     1320x400, 24px radius, photo + a flat black/20 wash
 *   panel    486px, black/40, 32px radius, 24px padding, 32px gap
 *   heading  Futurism 50, "הצטרפו לרשימת" light + "התפוצה הדיסקרטית", centred in a 407px box
 *   subtitle 16/1.4 white/80, centred
 *   form     email field (right) + a white pill submit (left)
 *
 * The form works out of the box: it validates and shows a confirmation. Point "Form action
 * URL" at an email provider's POST endpoint to wire real submission, or place an Elementor
 * Pro Form inside a styled container instead — either way the styling here is the design.
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

class Newsletter_Widget extends Widget_Base {

	public function get_name() {
		return 'redpoint_newsletter';
	}

	public function get_title() {
		return 'Newsletter';
	}

	public function get_icon() {
		return 'eicon-email-field';
	}

	public function get_categories() {
		return array( 'redpoint' );
	}

	public function get_keywords() {
		return array( 'newsletter', 'signup', 'email', 'subscribe', 'redpoint' );
	}

	public function get_script_depends() {
		return array( 'redpoint-newsletter' );
	}

	protected function register_controls() {

		/* ── Content ── */
		$this->start_controls_section(
			'section_content',
			array( 'label' => 'Content', 'tab' => Controls_Manager::TAB_CONTENT )
		);

		$this->add_control(
			'image',
			array(
				'label'   => 'Background Image',
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => REDPOINT_WIDGETS_URL . 'assets/img/newsletter.webp' ),
			)
		);

		$this->add_control(
			'title_lead',
			array( 'label' => 'Title — Line 1 (light)', 'type' => Controls_Manager::TEXT, 'default' => 'הצטרפו לרשימת' )
		);

		$this->add_control(
			'title_rest',
			array( 'label' => 'Title — Line 2', 'type' => Controls_Manager::TEXT, 'default' => 'התפוצה הדיסקרטית' )
		);

		$this->add_control(
			'subtitle',
			array( 'label' => 'Subtitle', 'type' => Controls_Manager::TEXT, 'default' => 'הישארו מעודכנים באמצעות הדוא״ל. ללא ספאם.' )
		);

		$this->add_control(
			'placeholder',
			array( 'label' => 'Field Placeholder', 'type' => Controls_Manager::TEXT, 'default' => 'כתובת אימייל' )
		);

		$this->add_control(
			'cta',
			array( 'label' => 'Button Text', 'type' => Controls_Manager::TEXT, 'default' => 'הירשמו' )
		);

		$this->add_control(
			'success',
			array( 'label' => 'Success Message', 'type' => Controls_Manager::TEXT, 'default' => 'תודה! נרשמתם בהצלחה.' )
		);

		$this->add_control(
			'action_url',
			array(
				'label'       => 'Form action URL',
				'type'        => Controls_Manager::URL,
				'options'     => false,
				'description' => 'Leave empty to just validate and confirm (demo). Set it to an email provider’s POST endpoint to wire real submission.',
			)
		);

		$this->add_control(
			'email_name',
			array(
				'label'       => 'Email field name',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'email',
				'description' => 'The field name the provider expects (e.g. EMAIL, email, merge0).',
			)
		);

		$this->end_controls_section();

		/* ── Style ── */
		$this->start_controls_section(
			'style',
			array( 'label' => 'Style', 'tab' => Controls_Manager::TAB_STYLE )
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'title_typography',
				'label'          => 'Title',
				'selector'       => '{{WRAPPER}} .rp-news__title',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Futurism' ),
					'font_size'   => array( 'default' => array( 'size' => 50, 'unit' => 'px' ) ),
					'line_height' => array( 'default' => array( 'size' => 1, 'unit' => 'em' ) ),
				),
			)
		);

		$this->add_control(
			'cta_bg',
			array(
				'label'     => 'Button Background',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array( '{{WRAPPER}} .rp-news__submit' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'cta_color',
			array(
				'label'     => 'Button Text Colour',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0C0C0C',
				'selectors' => array( '{{WRAPPER}} .rp-news__submit' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s      = $this->get_settings_for_display();
		$image  = ! empty( $s['image']['url'] ) ? $s['image']['url'] : '';
		$action = ! empty( $s['action_url']['url'] ) ? $s['action_url']['url'] : '';
		$name   = ! empty( $s['email_name'] ) ? $s['email_name'] : 'email';
		?>
		<section class="rp-news" dir="rtl">
			<div class="rp-news__card">
				<?php if ( $image ) : ?>
					<img class="rp-news__media" src="<?php echo esc_url( $image ); ?>" alt="" aria-hidden="true">
				<?php endif; ?>
				<span class="rp-news__wash" aria-hidden="true"></span>

				<div class="rp-news__panel">
					<div class="rp-news__text">
						<h3 class="rp-news__title">
							<span class="rp-news__title-lead"><?php echo esc_html( $s['title_lead'] ); ?></span>
							<span class="rp-news__title-rest"> <?php echo esc_html( $s['title_rest'] ); ?></span>
						</h3>
						<p class="rp-news__subtitle"><?php echo esc_html( $s['subtitle'] ); ?></p>
					</div>

					<?php
					/*
					 * data-success carries the confirmation text; the script shows it on a
					 * valid submit. With an action URL set the form posts there normally (no
					 * JS interception); empty, the script validates and confirms in place.
					 * The FIELD comes first so under RTL it paints on the right, button left.
					 */
					?>
					<form class="rp-news__form"
						<?php echo $action ? 'action="' . esc_url( $action ) . '" method="post"' : ''; ?>
						data-success="<?php echo esc_attr( $s['success'] ); ?>"
						data-demo="<?php echo $action ? '0' : '1'; ?>">
						<input class="rp-news__input" type="email" name="<?php echo esc_attr( $name ); ?>"
							required placeholder="<?php echo esc_attr( $s['placeholder'] ); ?>">
						<button class="rp-news__submit" type="submit"><?php echo esc_html( $s['cta'] ); ?></button>
					</form>
				</div>
			</div>
		</section>
		<?php
	}
}
