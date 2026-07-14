<?php
/**
 * Header / navigation bar — Figma 109:214.
 *
 *   bar    1440x100, padding 20/60, space-between, transparent (it sits OVER the hero)
 *   logo   176x60 (109:231) — the mark, then "רד" / "פוינט" stacked in Futurism 44.4
 *   links  16px Google Sans Medium #D7D7D7, 24px apart; the active one is Bold white
 *   icons  four 32x32 chips (109:215) — bag / account / wishlist / search, each a 20px
 *          stroke glyph on rgba(37,37,37,0.2), fully rounded
 *
 * Drop this into an Elementor Pro Theme Builder HEADER template. The bar draws no
 * background of its own by default, because in the design it floats over the hero image —
 * set a background on the Elementor container if a solid header is wanted elsewhere.
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

class Header_Widget extends Widget_Base {

	public function get_name() {
		return 'redpoint_header';
	}

	public function get_title() {
		return 'Header';
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return array( 'redpoint' );
	}

	public function get_keywords() {
		return array( 'header', 'nav', 'navigation', 'menu', 'logo', 'redpoint' );
	}

	protected function register_controls() {

		/* ════════════════════════════════════════
		   CONTENT TAB
		════════════════════════════════════════ */

		/* ── Logo ── */
		$this->start_controls_section(
			'section_logo',
			array(
				'label' => 'Logo',
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'logo_mark',
			array(
				'label'   => 'Mark',
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => REDPOINT_WIDGETS_URL . 'assets/img/logomark.webp' ),
			)
		);

		$this->add_control(
			'logo_line_1',
			array(
				'label'   => 'First Line',
				'type'    => Controls_Manager::TEXT,
				'default' => 'רד',
			)
		);

		$this->add_control(
			'logo_line_2',
			array(
				'label'   => 'Second Line',
				'type'    => Controls_Manager::TEXT,
				'default' => 'פוינט',
			)
		);

		$this->add_control(
			'logo_url',
			array(
				'label'   => 'Link',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => home_url( '/' ) ),
			)
		);

		$this->end_controls_section();

		/* ── Links ── */
		$this->start_controls_section(
			'section_links',
			array(
				'label' => 'Links',
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'label',
			array(
				'label'   => 'Label',
				'type'    => Controls_Manager::TEXT,
				'default' => 'בית',
			)
		);

		$repeater->add_control(
			'url',
			array(
				'label'   => 'Link',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '#' ),
			)
		);

		$repeater->add_control(
			'is_active',
			array(
				'label'        => 'Active (bold, white)',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		/*
		 * Listed in HEBREW READING ORDER — בית first. Under dir=rtl the first item paints
		 * on the RIGHT, which is exactly where the design puts בית (it is the last child on
		 * Figma's LTR canvas, so it lands rightmost there too). No direction override is
		 * needed here: RTL and the design agree.
		 *
		 * That is NOT true of the icon row below — see the note there.
		 */
		$this->add_control(
			'links',
			array(
				'label'       => 'Links',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => array(
					array( 'label' => 'בית', 'is_active' => 'yes', 'url' => array( 'url' => home_url( '/' ) ) ),
					array( 'label' => 'עלינו', 'url' => array( 'url' => '#' ) ),
					array( 'label' => 'מה זה רד פוינט?', 'url' => array( 'url' => '#' ) ),
					array( 'label' => 'משלוחים', 'url' => array( 'url' => '#' ) ),
					array( 'label' => 'אבטחה ופרטיות', 'url' => array( 'url' => '#' ) ),
					array( 'label' => 'יצירת קשר', 'url' => array( 'url' => '#' ) ),
				),
			)
		);

		$this->end_controls_section();

		/* ── Icons ── */
		$this->start_controls_section(
			'section_icons',
			array(
				'label' => 'Icons',
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'cart_url',
			array(
				'label'   => 'Cart Link',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#' ),
			)
		);

		$this->add_control(
			'account_url',
			array(
				'label'   => 'Account Link',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : '#' ),
			)
		);

		$this->add_control(
			'wishlist_url',
			array(
				'label'   => 'Wishlist Link',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '#' ),
			)
		);

		$this->add_control(
			'search_url',
			array(
				'label'   => 'Search Link',
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '?s=' ),
			)
		);

		$this->add_control(
			'show_cart_count',
			array(
				'label'        => 'Show Cart Count',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => 'Live count from WooCommerce. The design draws no badge — it is off in the Figma because the mock cart is empty.',
			)
		);

		$this->end_controls_section();

		/* ════════════════════════════════════════
		   STYLE TAB
		════════════════════════════════════════ */

		/* ── Style: Logo ── */
		$this->start_controls_section(
			'style_logo',
			array(
				'label' => 'Logo',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'logo_typography',
				'selector'       => '{{WRAPPER}} .rp-header__logo-text',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Futurism' ),
					'font_weight' => array( 'default' => '400' ),
					'font_size'   => array( 'default' => array( 'size' => 42, 'unit' => 'px' ) ),
					/*
					 * DEVIATION FROM FIGMA, and a deliberate one.
					 *
					 * The file sets 44.44px/0.56 (a 25px line box), which stacks "רד" and
					 * "פוינט" almost touching. The client saw it and said the wordmark was
					 * too dense, so the leading is opened to 0.72. Restore 0.56 to match the
					 * Figma exactly — and tell the designer, or the next person to rebuild
					 * this from the file will reintroduce the density the client rejected.
					 */
					'line_height' => array( 'default' => array( 'size' => 0.72, 'unit' => 'em' ) ),
				),
			)
		);

		$this->add_control(
			'logo_color_1',
			array(
				'label'   => 'First Line Color',
				'type'    => Controls_Manager::COLOR,
				// #D8103A — a DEEPER red than the site accent (#FF3B3B). The wordmark has
				// its own red; do not "correct" it to the accent.
				'default'   => '#D8103A',
				'selectors' => array( '{{WRAPPER}} .rp-header__logo-line--1' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'logo_color_2',
			array(
				'label'     => 'Second Line Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array( '{{WRAPPER}} .rp-header__logo-line--2' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		/* ── Style: Links ── */
		$this->start_controls_section(
			'style_links',
			array(
				'label' => 'Links',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'link_typography',
				'selector'       => '{{WRAPPER}} .rp-header__link',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Google Sans' ),
					'font_weight' => array( 'default' => '500' ),
					'font_size'   => array( 'default' => array( 'size' => 16, 'unit' => 'px' ) ),
					'line_height' => array( 'default' => array( 'size' => 1, 'unit' => 'em' ) ),
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => 'Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#D7D7D7',
				'selectors' => array( '{{WRAPPER}} .rp-header__link' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'link_color_active',
			array(
				'label'     => 'Active Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array( '{{WRAPPER}} .rp-header__link--active' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_responsive_control(
			'link_gap',
			array(
				'label'      => 'Gap',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 0, 'max' => 80 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 24 ),
				'selectors'  => array( '{{WRAPPER}} .rp-header__links' => 'gap: {{SIZE}}{{UNIT}};' ),
			)
		);

		$this->end_controls_section();

		/* ── Style: Icons ── */
		$this->start_controls_section(
			'style_icons',
			array(
				'label' => 'Icons',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'   => 'Glyph Color',
				'type'    => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				// The glyphs are stroke="currentColor", so `color` drives them.
				'selectors' => array( '{{WRAPPER}} .rp-header__icon' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'icon_bg_color',
			array(
				'label'     => 'Chip Background',
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(37, 37, 37, 0.2)',
				'selectors' => array( '{{WRAPPER}} .rp-header__icon' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * One 32x32 icon chip.
	 *
	 * @param string $slug  Icon file in assets/icons (bag|user|heart|search).
	 * @param array  $url   Elementor URL control value.
	 * @param string $label Accessible label.
	 * @param int    $count Cart count; a badge is drawn when > 0.
	 */
	private function render_icon( $slug, $url, $label, $count = 0 ) {
		$href     = ! empty( $url['url'] ) ? $url['url'] : '#';
		$external = ! empty( $url['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
		?>
		<a class="rp-header__icon" href="<?php echo esc_url( $href ); ?>"<?php echo $external; // phpcs:ignore ?>
			aria-label="<?php echo esc_attr( $label ); ?>">
			<?php
			echo redpoint_icon( $slug ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			if ( $count > 0 ) :
				?>
				<span class="rp-header__badge"><?php echo esc_html( $count ); ?></span>
			<?php endif; ?>
		</a>
		<?php
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$links = ! empty( $s['links'] ) ? $s['links'] : array();

		$count = 0;
		if ( 'yes' === $s['show_cart_count'] && function_exists( 'WC' ) && WC()->cart ) {
			$count = WC()->cart->get_cart_contents_count();
		}

		$logo_href = ! empty( $s['logo_url']['url'] ) ? $s['logo_url']['url'] : home_url( '/' );
		$mark      = ! empty( $s['logo_mark']['url'] ) ? $s['logo_mark']['url'] : '';
		?>
		<header class="rp-header" dir="rtl">
			<div class="rp-header__bar">

				<?php /* Logo FIRST: under dir=rtl the first child paints on the RIGHT, which is
				         where the design puts it (109:231 sits at the far right of the bar). */ ?>
				<a class="rp-header__logo" href="<?php echo esc_url( $logo_href ); ?>">
					<?php if ( $mark ) : ?>
						<img class="rp-header__logo-mark" src="<?php echo esc_url( $mark ); ?>" alt="" width="42" height="60">
					<?php endif; ?>
					<span class="rp-header__logo-text">
						<span class="rp-header__logo-line rp-header__logo-line--1"><?php echo esc_html( $s['logo_line_1'] ); ?></span>
						<span class="rp-header__logo-line rp-header__logo-line--2"><?php echo esc_html( $s['logo_line_2'] ); ?></span>
					</span>
				</a>

				<nav class="rp-header__links" aria-label="<?php esc_attr_e( 'Main', 'redpoint-widgets' ); ?>">
					<?php
					foreach ( $links as $link ) :
						$href     = ! empty( $link['url']['url'] ) ? $link['url']['url'] : '#';
						$external = ! empty( $link['url']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
						$classes  = 'rp-header__link' . ( 'yes' === $link['is_active'] ? ' rp-header__link--active' : '' );
						?>
						<a class="<?php echo esc_attr( $classes ); ?>" href="<?php echo esc_url( $href ); ?>"<?php echo $external; // phpcs:ignore ?>>
							<?php echo esc_html( $link['label'] ); ?>
						</a>
					<?php endforeach; ?>
				</nav>

				<?php
				/*
				 * Icons, LEFTMOST in the design (109:215) and reading bag → account →
				 * wishlist → search from the left.
				 *
				 * The row is forced to direction:ltr in the CSS, so they are listed here in
				 * that same left-to-right order. Left as RTL, the row would fill from the
				 * right and the bag would end up on the far right of the group — mirrored
				 * against the design.
				 */
				?>
				<div class="rp-header__icons">
					<?php
					$this->render_icon( 'bag', $s['cart_url'], __( 'עגלה', 'redpoint-widgets' ), $count );
					$this->render_icon( 'user', $s['account_url'], __( 'חשבון', 'redpoint-widgets' ) );
					$this->render_icon( 'heart', $s['wishlist_url'], __( 'מועדפים', 'redpoint-widgets' ) );
					$this->render_icon( 'search', $s['search_url'], __( 'חיפוש', 'redpoint-widgets' ) );
					?>
				</div>

			</div>
		</header>
		<?php
	}
}
