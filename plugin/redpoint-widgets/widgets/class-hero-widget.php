<?php
/**
 * Hero — Figma 109:212.
 *
 *   section   1440x800, flex column, space-between
 *   media     the fill is a VIDEO in the file (6037377_Woman_Sexy_1280x720), not a still
 *   gradient  linear-gradient(90.16deg, rgba(0,0,0,0.2) 0.14%, rgba(102,102,102,0.2) 107.68%)
 *   fade      109:213 — 280px fade to #0C0C0C at the foot, so the hero dissolves into the page
 *   title     Futurism 100/80 — first line Light, second Regular (109:237)
 *   subtitle  20/1.3 in #E6E6E6
 *   pills     eight 130x40 chips, bg rgba(37,37,37,0.6), 1px border in the category's colour
 *
 * The design uses this section three ways, so `layout` covers all three:
 *   full     1440x800 — media, title, pills   (home and category)
 *   compact  1440x220 — pills only, no media  (product page)
 *
 * The pill strip can read the eight categories straight out of WooCommerce, colour and all
 * (setup-woo.sh stores each category's design colour as `rp_colour` term meta). Add a
 * category in Woo and it appears here — no need to touch the widget.
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

class Hero_Widget extends Widget_Base {

	public function get_name() {
		return 'redpoint_hero';
	}

	public function get_title() {
		return 'Hero';
	}

	public function get_icon() {
		return 'eicon-banner';
	}

	public function get_categories() {
		return array( 'redpoint' );
	}

	public function get_keywords() {
		return array( 'hero', 'banner', 'categories', 'pills', 'redpoint' );
	}

	/** The design's eight categories, in the order they paint right-to-left. */
	private function default_pills() {
		return array(
			array( 'label' => 'לנשים', 'colour' => '#FF4FA8' ),
			array( 'label' => 'לגברים', 'colour' => '#25D9F5' ),
			array( 'label' => 'לזוגות', 'colour' => '#FF3DD1' ),
			array( 'label' => 'צעצועי סקס', 'colour' => '#FF8A2B' ),
			array( 'label' => 'חוויה אנאלית', 'colour' => '#A45CFF' ),
			array( 'label' => "ביגוד ולונז'ארי", 'colour' => '#FFD13B' ),
			array( 'label' => 'פטיש ו-BDSM', 'colour' => '#36E07A' ),
			array( 'label' => 'חומרי סיכוך', 'colour' => '#FF3B3B' ),
		);
	}

	protected function register_controls() {

		/* ════════════════════════════════════════
		   CONTENT TAB
		════════════════════════════════════════ */

		/* ── Layout ── */
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => 'Layout',
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => 'Layout',
				'type'    => Controls_Manager::SELECT,
				'default' => 'full',
				'options' => array(
					'full'    => 'Full (800px — media, title, pills)',
					'compact' => 'Compact (220px — pills only)',
				),
				'description' => 'Compact is the product page: no media, no title, just the pill strip.',
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => 'Height',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array( 'px' => array( 'min' => 200, 'max' => 1000 ) ),
				'default'    => array( 'unit' => 'px', 'size' => 800 ),
				'selectors'  => array( '{{WRAPPER}} .rp-hero' => 'min-height: {{SIZE}}{{UNIT}};' ),
				'condition'  => array( 'layout' => 'full' ),
			)
		);

		$this->end_controls_section();

		/* ── Media ── */
		$this->start_controls_section(
			'section_media',
			array(
				'label'     => 'Media',
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array( 'layout' => 'full' ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => 'Image',
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => REDPOINT_WIDGETS_URL . 'assets/img/hero.webp' ),
			)
		);

		$this->add_control(
			'video_url',
			array(
				'label'       => 'Video URL (mp4)',
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://…/hero.mp4',
				/*
				 * The hero fill is a VIDEO in the Figma (6037377_Woman_Sexy_1280x720), not a
				 * still. The image above is the exported poster frame and stands in until the
				 * client supplies the clip. Point this at the mp4 and it plays, with the
				 * image as its poster.
				 */
				'description' => 'The design’s hero is a VIDEO. The image above is only the poster frame — set this when the client supplies the clip.',
				'options'     => false,
			)
		);

		$this->end_controls_section();

		/* ── Copy ── */
		$this->start_controls_section(
			'section_copy',
			array(
				'label'     => 'Copy',
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array( 'layout' => 'full' ),
			)
		);

		$this->add_control(
			'title_line_1',
			array(
				'label'   => 'Title — First Line',
				'type'    => Controls_Manager::TEXT,
				'default' => 'לעורר את',
				// Light in the design; the second line is Regular (109:237).
				'description' => 'Rendered in Futurism Light.',
			)
		);

		$this->add_control(
			'title_line_2',
			array(
				'label'       => 'Title — Second Line',
				'type'        => Controls_Manager::TEXT,
				'default'     => 'כל החושים.',
				'description' => 'Rendered in Futurism Regular.',
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label'       => 'Subtitle',
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => "הנאה מחודשת ומעוצבת, לסקרנים, למנוסים\nולמי שביניהם",
				'description' => 'One line per row.',
			)
		);

		$this->add_control(
			'subtitle_italic',
			array(
				'label'        => 'Italic Subtitle',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'description'  => 'The category page sets its strapline in italic; the home page does not.',
			)
		);

		$this->end_controls_section();

		/* ── Pills ── */
		$this->start_controls_section(
			'section_pills',
			array(
				'label' => 'Category Pills',
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'pills_source',
			array(
				'label'   => 'Source',
				'type'    => Controls_Manager::SELECT,
				'default' => 'woocommerce',
				'options' => array(
					'woocommerce' => 'WooCommerce categories',
					'manual'      => 'Manual list',
				),
				'description' => 'WooCommerce reads the product categories live, colour included — add a category there and it appears here.',
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

		$repeater->add_control(
			'colour',
			array(
				'label'     => 'Colour',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FF3B3B',
				'selectors' => array( '{{WRAPPER}} {{CURRENT_ITEM}}' => '--rp-pill: {{VALUE}};' ),
			)
		);

		$repeater->add_control(
			'is_active',
			array(
				'label'        => 'Active (filled)',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'pills',
			array(
				'label'       => 'Pills',
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => $this->default_pills(),
				'condition'   => array( 'pills_source' => 'manual' ),
			)
		);

		$this->end_controls_section();

		/* ════════════════════════════════════════
		   STYLE TAB
		════════════════════════════════════════ */

		/* ── Style: Title ── */
		$this->start_controls_section(
			'style_title',
			array(
				'label'     => 'Title',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'layout' => 'full' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'title_typography',
				'selector'       => '{{WRAPPER}} .rp-hero__title',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Futurism' ),
					'font_size'   => array( 'default' => array( 'size' => 100, 'unit' => 'px' ) ),
					// 80px against a 100px type size — the lines overlap slightly, which is
					// what gives the headline its stacked look. Not a mistake.
					'line_height' => array( 'default' => array( 'size' => 80, 'unit' => 'px' ) ),
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => 'Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array( '{{WRAPPER}} .rp-hero__title' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		/* ── Style: Subtitle ── */
		$this->start_controls_section(
			'style_subtitle',
			array(
				'label'     => 'Subtitle',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'layout' => 'full' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'subtitle_typography',
				'selector'       => '{{WRAPPER}} .rp-hero__subtitle',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Google Sans' ),
					'font_size'   => array( 'default' => array( 'size' => 20, 'unit' => 'px' ) ),
					'line_height' => array( 'default' => array( 'size' => 1.3, 'unit' => 'em' ) ),
				),
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => 'Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#E6E6E6',
				'selectors' => array( '{{WRAPPER}} .rp-hero__subtitle' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		/* ── Style: Pills ── */
		$this->start_controls_section(
			'style_pills',
			array(
				'label' => 'Pills',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'pill_typography',
				'selector'       => '{{WRAPPER}} .rp-hero__pill',
				'fields_options' => array(
					'typography'  => array( 'default' => 'yes' ),
					'font_family' => array( 'default' => 'Google Sans' ),
					'font_weight' => array( 'default' => '500' ),
					'font_size'   => array( 'default' => array( 'size' => 16, 'unit' => 'px' ) ),
				),
			)
		);

		$this->add_control(
			'pill_bg',
			array(
				'label'     => 'Background',
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(37, 37, 37, 0.6)',
				'selectors' => array( '{{WRAPPER}} .rp-hero__pill' => 'background-color: {{VALUE}};' ),
			)
		);

		$this->add_control(
			'pill_color',
			array(
				'label'     => 'Text Color',
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array( '{{WRAPPER}} .rp-hero__pill' => 'color: {{VALUE}};' ),
			)
		);

		$this->end_controls_section();

		/* ── Style: Scrims ── */
		$this->start_controls_section(
			'style_scrims',
			array(
				'label'     => 'Scrims',
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'layout' => 'full' ),
			)
		);

		$this->add_control(
			'bottom_fade',
			array(
				'label'        => 'Bottom Fade',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => 'Node 109:213 — 280px fade to the page background, so the hero dissolves into the section below. Part of the design.',
			)
		);

		$this->add_control(
			'top_scrim',
			array(
				'label'        => 'Top Scrim',
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				/*
				 * NOT IN THE DESIGN — added deliberately.
				 *
				 * Figma's single hero photo is dark, so the white nav reads fine over it. But
				 * the category heroes vary, and on the lighter ones the nav vanished. This
				 * scrim is invisible over a dark image and rescues a light one. Turn it off to
				 * match the Figma exactly.
				 */
				'description'  => 'NOT in the design. Rescues the nav over light hero images — invisible over dark ones. Turn off to match the Figma exactly.',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * The pills, either from WooCommerce or from the repeater.
	 *
	 * @return array of [ label, url, colour, active ]
	 */
	private function get_pills( $s ) {
		if ( 'manual' === $s['pills_source'] ) {
			$out = array();
			foreach ( (array) $s['pills'] as $p ) {
				if ( empty( $p['label'] ) ) {
					continue;
				}
				$out[] = array(
					'label'  => $p['label'],
					'url'    => ! empty( $p['url']['url'] ) ? $p['url']['url'] : '#',
					'colour' => ! empty( $p['colour'] ) ? $p['colour'] : '#FF3B3B',
					'active' => 'yes' === $p['is_active'],
					'key'    => 'elementor-repeater-item-' . $p['_id'],
				);
			}
			return $out;
		}

		if ( ! taxonomy_exists( 'product_cat' ) ) {
			return array();
		}

		/*
		 * Ordered by the `order` term meta that setup-woo.sh writes, so the pills come out in
		 * the design's sequence (לנשים first) rather than alphabetically. Terms without it
		 * fall to the end.
		 */
		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'meta_key'   => 'order', // phpcs:ignore WordPress.DB.SlowDBQuery
				'orderby'    => 'meta_value_num',
				'order'      => 'ASC',
			)
		);

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		// Fill the pill for the archive we are currently looking at.
		$current = is_tax( 'product_cat' ) ? get_queried_object_id() : 0;

		$out = array();
		foreach ( $terms as $t ) {
			$colour = get_term_meta( $t->term_id, 'rp_colour', true );
			if ( ! $colour ) {
				continue; // Not one of the design's categories (e.g. "Uncategorized").
			}
			$out[] = array(
				'label'  => $t->name,
				'url'    => get_term_link( $t ),
				'colour' => $colour,
				'active' => ( $current === $t->term_id ),
				'key'    => 'rp-cat-' . $t->term_id,
			);
		}
		return $out;
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$compact = 'compact' === $s['layout'];
		$pills   = $this->get_pills( $s );

		$image = ! empty( $s['image']['url'] ) ? $s['image']['url'] : '';
		$video = ! empty( $s['video_url']['url'] ) ? $s['video_url']['url'] : '';
		$subs  = array_filter( array_map( 'trim', explode( "\n", (string) $s['subtitle'] ) ) );

		$classes = 'rp-hero' . ( $compact ? ' rp-hero--compact' : '' );
		?>
		<section class="<?php echo esc_attr( $classes ); ?>" dir="rtl">

			<?php if ( ! $compact ) : ?>
				<?php if ( $video ) : ?>
					<video class="rp-hero__media" poster="<?php echo esc_url( $image ); ?>"
						autoplay muted loop playsinline aria-hidden="true">
						<source src="<?php echo esc_url( $video ); ?>" type="video/mp4">
					</video>
				<?php elseif ( $image ) : ?>
					<img class="rp-hero__media" src="<?php echo esc_url( $image ); ?>" alt="" aria-hidden="true">
				<?php endif; ?>

				<?php /* The hero's own gradient, straight from the design. */ ?>
				<span class="rp-hero__tint" aria-hidden="true"></span>

				<?php if ( 'yes' === $s['top_scrim'] ) : ?>
					<span class="rp-hero__scrim-top" aria-hidden="true"></span>
				<?php endif; ?>

				<?php if ( 'yes' === $s['bottom_fade'] ) : ?>
					<span class="rp-hero__fade" aria-hidden="true"></span>
				<?php endif; ?>
			<?php endif; ?>

			<div class="rp-hero__inner">

				<?php if ( ! $compact && ( $s['title_line_1'] || $s['title_line_2'] || $subs ) ) : ?>
					<div class="rp-hero__copy">
						<h1 class="rp-hero__title">
							<?php if ( $s['title_line_1'] ) : ?>
								<span class="rp-hero__title-line rp-hero__title-line--light"><?php echo esc_html( $s['title_line_1'] ); ?></span>
							<?php endif; ?>
							<?php if ( $s['title_line_2'] ) : ?>
								<span class="rp-hero__title-line"><?php echo esc_html( $s['title_line_2'] ); ?></span>
							<?php endif; ?>
						</h1>

						<?php if ( $subs ) : ?>
							<p class="rp-hero__subtitle<?php echo 'yes' === $s['subtitle_italic'] ? ' rp-hero__subtitle--italic' : ''; ?>">
								<?php foreach ( $subs as $line ) : ?>
									<span><?php echo esc_html( $line ); ?></span>
								<?php endforeach; ?>
							</p>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php
				/*
				 * Pills. NO direction override, and that is correct.
				 *
				 * They are listed לנשים first, and under RTL the first child paints on the
				 * RIGHT — which is where the design starts them, at the 60px right gutter.
				 * (The header's icon row DID need a flip. The answer is not the same for every
				 * row; check each.)
				 */
				?>
				<?php if ( $pills ) : ?>
					<nav class="rp-hero__pills" aria-label="<?php esc_attr_e( 'Categories', 'redpoint-widgets' ); ?>">
						<?php foreach ( $pills as $p ) : ?>
							<a class="rp-hero__pill<?php echo $p['active'] ? ' rp-hero__pill--active' : ''; ?> <?php echo esc_attr( $p['key'] ); ?>"
								href="<?php echo esc_url( $p['url'] ); ?>"
								style="--rp-pill: <?php echo esc_attr( $p['colour'] ); ?>;">
								<?php echo esc_html( $p['label'] ); ?>
							</a>
						<?php endforeach; ?>
					</nav>
				<?php endif; ?>

			</div>
		</section>
		<?php
	}
}
