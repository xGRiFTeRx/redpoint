<?php
/**
 * Blog Teaser — "שווה לדעת" (Figma 109:652).
 *
 *   section  1440x877, padding 90/60/68, heading + row of 3 + dots
 *   card     429x534 (109:657) — image 305, then #111 body
 *            date 12/#818181 · title 18 Medium 1.3 · excerpt 14/#C5C5C5 1.4 · "Read more"
 *
 * Binds to WordPress posts: it queries the latest posts and renders them. Publish a post
 * and it appears.
 *
 * @package redpoint-widgets
 */

namespace RedPoint\Widgets;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Blog_Teaser_Widget extends Widget_Base {

	public function get_name() {
		return 'redpoint_blog_teaser';
	}

	public function get_title() {
		return 'Blog Teaser';
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return array( 'redpoint' );
	}

	public function get_keywords() {
		return array( 'blog', 'posts', 'articles', 'redpoint' );
	}

	public function get_script_depends() {
		return array( 'redpoint-carousel' );
	}

	protected function register_controls() {

		/* ── Heading ── */
		$this->start_controls_section(
			'section_heading',
			array( 'label' => 'Heading', 'tab' => Controls_Manager::TAB_CONTENT )
		);
		$this->add_control( 'lead', array( 'label' => 'Lead (white)', 'type' => Controls_Manager::TEXT, 'default' => 'שווה' ) );
		$this->add_control( 'accent', array( 'label' => 'Accent (red)', 'type' => Controls_Manager::TEXT, 'default' => 'לדעת' ) );
		$this->add_control(
			'kicker',
			array(
				'label'   => 'Kicker',
				'type'    => Controls_Manager::TEXTAREA,
				'default' => "תשובות לשאלות שכולם\nביישנים מדי כדי לשאול",
			)
		);
		$this->add_control(
			'kicker_width',
			array(
				'label'      => 'Kicker Width',
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array( 'px' => array( 'min' => 120, 'max' => 400 ) ),
				// 236 here, NOT the pleasure grid's 211 — the two kickers are different widths
				// and hardcoding one re-wraps the other.
				'default'    => array( 'unit' => 'px', 'size' => 236 ),
				'selectors'  => array( '{{WRAPPER}} .rp-heading__kicker' => 'width: {{SIZE}}{{UNIT}};' ),
			)
		);
		$this->end_controls_section();

		/* ── Posts ── */
		$this->start_controls_section(
			'section_posts',
			array( 'label' => 'Posts', 'tab' => Controls_Manager::TAB_CONTENT )
		);

		$this->add_control(
			'category',
			array(
				'label'       => 'Category',
				'type'        => Controls_Manager::SELECT2,
				'options'     => $this->category_options(),
				'multiple'    => true,
				'label_block' => true,
				'description' => 'Leave empty for the latest across all categories.',
			)
		);

		$this->add_control(
			'count',
			array(
				'label'       => 'How many',
				'type'        => Controls_Manager::NUMBER,
				'default'     => 6,
				'min'         => 1,
				'max'         => 12,
				'description' => 'Shown three at a time; the dots page through the rest.',
			)
		);

		$this->add_control(
			'columns',
			array( 'label' => 'Columns', 'type' => Controls_Manager::NUMBER, 'default' => 3, 'min' => 1, 'max' => 4 )
		);

		$this->add_control(
			'read_more',
			array( 'label' => '"Read more" text', 'type' => Controls_Manager::TEXT, 'default' => 'Read more' )
		);

		$this->add_control(
			'dots',
			array( 'label' => 'Show Dots', 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' )
		);

		$this->end_controls_section();
	}

	private function category_options() {
		$out   = array();
		$terms = get_terms( array( 'taxonomy' => 'category', 'hide_empty' => false ) );
		if ( is_wp_error( $terms ) ) {
			return $out;
		}
		foreach ( $terms as $t ) {
			$out[ $t->slug ] = $t->name;
		}
		return $out;
	}

	/** One blog card. */
	private function render_card( $post_id, $read_more ) {
		$link  = get_permalink( $post_id );
		$title = get_the_title( $post_id );
		$date  = get_the_date( 'M j, Y', $post_id );
		$excerpt = has_excerpt( $post_id ) ? get_the_excerpt( $post_id ) : wp_trim_words( get_post_field( 'post_content', $post_id ), 40 );
		$img   = get_the_post_thumbnail( $post_id, 'large', array( 'class' => 'rp-blog-card__img', 'aria-hidden' => 'true' ) );
		?>
		<article class="rp-blog-card">
			<a class="rp-blog-card__media" href="<?php echo esc_url( $link ); ?>">
				<?php echo $img; // phpcs:ignore ?>
			</a>
			<div class="rp-blog-card__body">
				<?php /* dir=ltr keeps the Latin date/title's punctuation at the right end. */ ?>
				<div class="rp-blog-card__head" dir="ltr">
					<span class="rp-blog-card__date"><?php echo esc_html( $date ); ?></span>
					<a class="rp-blog-card__title" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a>
				</div>
				<p class="rp-blog-card__excerpt" dir="ltr"><?php echo esc_html( $excerpt ); ?></p>
				<a class="rp-blog-card__more" href="<?php echo esc_url( $link ); ?>">
					<?php echo esc_html( $read_more ); ?>
					<svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.17" stroke-linecap="round" aria-hidden="true"><path d="M11.08 7H2.92M6.42 3.5 2.92 7l3.5 3.5"/></svg>
				</a>
			</div>
		</article>
		<?php
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => max( 1, (int) $s['count'] ),
			'fields'         => 'ids',
			'ignore_sticky_posts' => true,
		);
		if ( ! empty( $s['category'] ) ) {
			$args['category_name'] = implode( ',', (array) $s['category'] );
		}

		$post_ids = get_posts( $args );
		if ( empty( $post_ids ) ) {
			return;
		}

		$columns   = max( 1, (int) $s['columns'] );
		$pages     = array_chunk( $post_ids, $columns );
		$show_dots = 'yes' === $s['dots'] && count( $pages ) > 1;
		$kicker    = array_filter( array_map( 'trim', explode( "\n", (string) $s['kicker'] ) ) );
		?>
		<section class="rp-blog" dir="rtl">

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

			<div class="rp-carousel" style="--rp-cols: <?php echo (int) $columns; ?>;">
				<div class="rp-carousel__pages">
					<?php foreach ( $pages as $page_i => $page ) : ?>
						<?php $row = array_reverse( $page ); // RTL: reverse each row ?>
						<div class="rp-carousel__page"<?php echo $page_i > 0 ? ' hidden' : ''; ?>>
							<?php foreach ( $row as $post_id ) : ?>
								<?php $this->render_card( $post_id, $s['read_more'] ); ?>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( $show_dots ) : ?>
					<div class="rp-carousel__dots" role="tablist">
						<?php foreach ( $pages as $page_i => $page ) : ?>
							<button type="button" class="rp-carousel__dot<?php echo 0 === $page_i ? ' is-active' : ''; ?>"
								role="tab" aria-selected="<?php echo 0 === $page_i ? 'true' : 'false'; ?>"
								aria-label="<?php echo esc_attr( $page_i + 1 ); ?>"></button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

		</section>
		<?php
	}
}
