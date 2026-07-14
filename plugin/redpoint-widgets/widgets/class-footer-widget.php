<?php
/**
 * Footer — Figma 109:750 / 109:763.
 *
 *   section    padding 32/60, gap 40, bg #0C0C0C
 *   links row  1320x224 (109:764) — four blocks, space-between
 *   columns    heading Futurism 24 in accent red; links Google Sans 16/24 white, 16px apart
 *   logo       237px block — a 71x100 mark beside "רד" / "פוינט" at Futurism 64,
 *              then the tagline in Futurism 24 white
 *   copyright  14/20 muted, on the LEFT (109:792 sits at x=0)
 *
 * The Figma "Footer Section" frame also contains the newsletter banner (109:751). That is a
 * separate section and gets its own widget — this is the footer proper.
 *
 * Drop into an Elementor Pro Theme Builder FOOTER template.
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

class Footer_Widget extends Widget_Base {

	public function get_name() {
		return 'redpoint_footer';
	}

	public function get_title() {
		return 'Footer';
	}

	public function get_icon() {
		return 'eicon-footer';
	}

	public function get_categories() {
		return array( 'redpoint' );
	}

	public function get_keywords() {
		return array( 'footer', 'links', 'columns', 'redpoint' );
	}

	/**
	 * Build one column's controls.
	 *
	 * Elementor cannot nest a repeater inside a repeater, so the three link columns are
	 * three separate control sections rather than one repeater of columns. Clumsier in the
	 * source, but it is what the panel actually supports.
	 *
	 * @param string $id       Control id prefix, e.g. 'col_1'.
	 * @param string $label    Panel section label.
	 * @param string $heading  Default heading.
	 * @param array  $defaults Default link labels.
	 */
	private function column_controls( $id, $label, $heading, $defaults ) {
		$this->start_controls_section(
			'section_' . $id,
			array(
				'label' => $label,
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			$id . '_heading',
			array(
				'label'   => 'Heading',
				'type'    => Controls_Manager::TEXT,
				'default' => $heading,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'label',
			array(
				'label'   => 'Label',
				'type'    => Controls_Manager::TEXT,
				'default' => '',
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

		$items = array();
		foreach ( $defaults as $d ) {
			$items[] = array( 'label' => $d, 'url' => array( 'url' => '#' ) );
		}

		$this->add_control(
			$id . '_links',
			array(
				'label'       => 'Links',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => $items,
			)
		);

		$this->end_controls_section();
	}

	protected function register_controls() {

		/* ════════════════════════════════════════
		   CONTENT TAB
		════════════════════════════════════════ */

		/*
		 * The columns are declared RIGHT to LEFT, because that is the order they paint in.
		 *
		 * The page is RTL, so the first block in the DOM lands on the RIGHT. On Figma's LTR
		 * canvas the blocks read logo (x=0) → 5 links (x=438) → 4 links (x=760) → 4 links
		 * (x=1145) left-to-right — so the RIGHTMOST block in the design is the LAST one on
		 * the canvas. Column 1 below is therefore the design's x=1145 block, and the logo
		 * comes last so it paints leftmost.
		 */
		$this->column_controls(
			'col_1',
			'Column 1 (rightmost)',
			'Redpoint Sex Shop',
			array( 'עלינו', 'מה זה רד פוינט?', 'משלוחים', 'אבטחה ופרטיות' )
		);

		$this->column_controls(
			'col_2',
			'Column 2 (middle)',
			'Website Categories',
			array( 'לנשים', 'לגברים', 'לזוגות', 'צעצועי סקס' )
		);

		/*
		 * NEEDS CHECKING against the Figma. The design's block at x=438 is 224 tall — a 24px
		 * heading plus a 184px list, which is FIVE links (5x24 + 4x16 gap). These two are
		 * what the Next.js reference carries, and it only ever had three.
		 *
		 * The footer was never part of the 21 sections that were pixel-audited, and the
		 * Figma connector was down when this was written, so the remaining labels could not
		 * be read off the file. The count is a control the client fills in, so nothing is
		 * broken — but confirm the real list before this ships.
		 */
		$this->column_controls(
			'col_3',
			'Column 3 (left of logo)',
			'Contact Info.',
			array( 'טלפון', 'יצירת קשר' )
		);

		/* ── Logo ── */
		$this->start_controls_section(
			'section_logo',
			array(
				'label' => 'Logo (leftmost)',
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
			'tagline',
			array(
				'label'   => 'Tagline',
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'חנות אביזרי המין המובילה והוותיקה בישראל',
			)
		);

		$this->end_controls_section();

		/* ── Copyright ── */
		$this->start_controls_section(
			'section_copyright',
			array(
				'label' => 'Copyright',
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'copyright',
			array(
				'label'   => 'Text',
				'type'    => Controls_Manager::TEXT,
				'default' => '2026 Redpoint All rights reserved.',
			)
		);

		$this->end_controls_section();

		/* ════════════════════════════════════════
		   STYLE TAB
		════════════════════════════════════════ */

		$this->start_controls_section(
			'style_section',
			array(
				'label' => 'Section',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'bg_color',
			array(
				'label'     => 'Background',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#0C0C0C',
				'selectors' => array( '{{WRAPPER}} .rp-footer' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		/* ── Style: Column headings ── */
		$this->start_controls_section(
			'style_heading',
			array(
				'label' => 'Column Headings',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'heading_typography',
				'selector'       => '{{WRAPPER}} .rp-footer__heading',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Futurism' ),
					'font_size'   => array( 'default' => array( 'size' => 24, 'unit' => 'px' ) ),
					'line_height' => array( 'default' => array( 'size' => 1, 'unit' => 'em' ) ),
				),
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => 'Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF3B3B',
				'selectors' => array( '{{WRAPPER}} .rp-footer__heading' => 'color: {{VALUE}};' ),
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
				'selector'       => '{{WRAPPER}} .rp-footer__link',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Google Sans' ),
					'font_size'   => array( 'default' => array( 'size' => 16, 'unit' => 'px' ) ),
					'line_height' => array( 'default' => array( 'size' => 24, 'unit' => 'px' ) ),
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => 'Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array( '{{WRAPPER}} .rp-footer__link' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'     => 'Hover Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF3B3B',
				'selectors' => array( '{{WRAPPER}} .rp-footer__link:hover' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

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
				'selector'       => '{{WRAPPER}} .rp-footer__logo-text',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Futurism' ),
					// 64px here — the footer wordmark is BIGGER than the header's 42px.
					'font_size'   => array( 'default' => array( 'size' => 64, 'unit' => 'px' ) ),
					'line_height' => array( 'default' => array( 'size' => 0.72, 'unit' => 'em' ) ),
				),
			)
		);

		$this->add_control(
			'logo_color_1',
			array(
				'label' => 'First Line Color',
				'type'  => Controls_Manager::COLOR,
				// The wordmark's own red, deeper than the site accent. Not a mistake.
				'default'   => '#D8103A',
				'selectors' => array( '{{WRAPPER}} .rp-footer__logo-line--1' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'logo_color_2',
			array(
				'label'     => 'Second Line Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array( '{{WRAPPER}} .rp-footer__logo-line--2' => 'color: {{VALUE}};' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'tagline_typography',
				'selector'       => '{{WRAPPER}} .rp-footer__tagline',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Futurism' ),
					'font_size'   => array( 'default' => array( 'size' => 24, 'unit' => 'px' ) ),
					'line_height' => array( 'default' => array( 'size' => 1.3, 'unit' => 'em' ) ),
				),
			)
		);

		$this->add_control(
			'tagline_color',
			array(
				'label'     => 'Tagline Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array( '{{WRAPPER}} .rp-footer__tagline' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		/* ── Style: Copyright ── */
		$this->start_controls_section(
			'style_copyright',
			array(
				'label' => 'Copyright',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'copyright_typography',
				'selector'       => '{{WRAPPER}} .rp-footer__copyright',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Google Sans' ),
					'font_size'   => array( 'default' => array( 'size' => 14, 'unit' => 'px' ) ),
					'line_height' => array( 'default' => array( 'size' => 20, 'unit' => 'px' ) ),
				),
			)
		);

		$this->add_control(
			'copyright_color',
			array(
				'label'     => 'Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#818181',
				'selectors' => array( '{{WRAPPER}} .rp-footer__copyright' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * One link column.
	 */
	private function render_column( $heading, $links ) {
		if ( ! $heading && empty( $links ) ) {
			return;
		}
		?>
		<div class="rp-footer__col">
			<?php if ( $heading ) : ?>
				<h2 class="rp-footer__heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>

			<?php if ( ! empty( $links ) ) : ?>
				<ul class="rp-footer__list">
					<?php foreach ( $links as $link ) : ?>
						<?php
						if ( empty( $link['label'] ) ) {
							continue;
						}
						$href     = ! empty( $link['url']['url'] ) ? $link['url']['url'] : '#';
						$external = ! empty( $link['url']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
						?>
						<li>
							<a class="rp-footer__link" href="<?php echo esc_url( $href ); ?>"<?php echo $external; // phpcs:ignore ?>>
								<?php echo esc_html( $link['label'] ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$mark = ! empty( $s['logo_mark']['url'] ) ? $s['logo_mark']['url'] : '';
		?>
		<footer class="rp-footer" dir="rtl">
			<div class="rp-footer__inner">

				<div class="rp-footer__cols">
					<?php
					// Right to left — see the ordering note in register_controls().
					$this->render_column( $s['col_1_heading'], $s['col_1_links'] );
					$this->render_column( $s['col_2_heading'], $s['col_2_links'] );
					$this->render_column( $s['col_3_heading'], $s['col_3_links'] );
					?>

					<?php /* Logo LAST so RTL paints it leftmost, where the design has it (x=0). */ ?>
					<div class="rp-footer__brand">
						<div class="rp-footer__logo">
							<?php if ( $mark ) : ?>
								<img class="rp-footer__logo-mark" src="<?php echo esc_url( $mark ); ?>" alt="" width="71" height="100">
							<?php endif; ?>
							<span class="rp-footer__logo-text">
								<span class="rp-footer__logo-line rp-footer__logo-line--1"><?php echo esc_html( $s['logo_line_1'] ); ?></span>
								<span class="rp-footer__logo-line rp-footer__logo-line--2"><?php echo esc_html( $s['logo_line_2'] ); ?></span>
							</span>
						</div>

						<?php if ( ! empty( $s['tagline'] ) ) : ?>
							<p class="rp-footer__tagline"><?php echo esc_html( $s['tagline'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( ! empty( $s['copyright'] ) ) : ?>
					<p class="rp-footer__copyright"><?php echo esc_html( $s['copyright'] ); ?></p>
				<?php endif; ?>

			</div>
		</footer>
		<?php
	}
}
