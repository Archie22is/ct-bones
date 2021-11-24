<?php
// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Codetot_Related_Posts {

	/**
	 * Singleton instance
	 *
	 * @var Codetot_Related_Posts
	 */
	private static $instance;

	/**
	 * @var bool
	 */
	protected $enable;

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * Get singleton instance.
	 *
	 * @return Codetot_Related_Posts
	 */
	final public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Class constructor
	 */
	public function __construct() {
		 add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );

		$this->query_type = codetot_get_theme_mod( 'related_posts_query_type' ) ?? 'category';

		add_action( 'codetot_after_post', array( $this, 'render_section' ), 10 );
	}

	public function register_customizer_settings( $wp_customize ) {
		$section_settings_id = 'codetot_theme_single_post_settings';

		codetot_customizer_register_control(
			array(
				'id'                  => 'related_posts_query_type',
				'label'               => esc_html__( 'Related Posts Query Type', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => 'category_tag' ),
				'control_args'        => array(
					'type'    => 'select',
					'choices' => array(
						'none'         => __( 'Hide Related Posts', 'ct-bones' ),
						'category_tag' => __( 'Both of Tags and Categories', 'ct-bones' ),
						'category'     => esc_html__( 'Same Category', 'ct-bones' ),
						'tag'          => esc_html__( 'Same Tag', 'ct-bones' ),
					),
				),
			),
			$wp_customize
		);

		codetot_customizer_register_control(
			array(
				'id'                  => 'related_posts_number',
				'label'               => esc_html__( 'Related Posts Number', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => 3 ),
				'control_args'        => array(
					'type'              => 'number',
					'sanitize_callback' => 'absint',
					'input_attrs'       => array(
						'min' => 2,
						'max' => 5,
					),
				),
			),
			$wp_customize
		);

		return $wp_customize;
	}

	public function get_categories() {
		$categories = get_the_category();

		return ! empty( $categories ) ? wp_list_pluck( $categories, 'term_id' ) : array();
	}

	public function get_tags() {
		$tags = get_the_tags();

		return ! empty( $tags ) ? wp_list_pluck( $tags, 'term_id' ) : array();
	}

	public function get_query_args() {
		if ( ! is_singular( 'post' ) ) {
			return new WP_Error(
				'400',
				__( 'This query only works with singular post only.', 'ct-bones' )
			);
		}

		$post_id = get_the_ID();

		$post_args = array(
			'posts_per_page' => codetot_get_theme_mod( 'related_posts_number' ) ?? 3,
			'post__not_in'   => array( $post_id ),
		);

		switch ( $this->query_type ) :

			case 'category':
				$categories = $this->get_categories();

				if ( ! empty( $categories ) ) {
					$post_args['category__in'] = $categories;
				}

				break;

			case 'tag':
				$tags = $this->get_tags();

				if ( ! empty( $tags ) ) {
					$post_args['tag__in'] = $tags;
				}

				break;

				// Both of categories and tags
			case 'category_tag':
				$categories = $this->get_categories();

				$tax_query = array();

				if ( ! empty( $categories ) ) {
					$tax_query[] = array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => $categories,
					);
				}

				$tags = $this->get_tags();

				if ( ! empty( $tags ) ) {
					$tax_query[] = array(
						'taxonomy' => 'tag',
						'field'    => 'term_id',
						'terms'    => $tags,
					);
				}

				if ( count( $tax_query ) > 1 ) {
					$tax_query['relation'] = 'OR';
				}

				$post_args['tax_query'] = $tax_query;

			default:
				return array();

		endswitch;

		return $post_args;
	}

	public function render_section() {
		if ( $this->query_type === 'none' ) {
			return '';
		}

		$post_args = $this->get_query_args();

		if ( empty( $post_args ) ) {
			return '';
		}

		$post_query = new WP_Query( $post_args );
		$class      = 'post-grid--related-posts default-section--no-container';

		if ( $post_query->have_posts() ) {
			the_block(
				'post-grid',
				array(
					'class' => $class,
					'title' => esc_html__( 'Related posts', 'ct-bones' ),
					'query' => $post_query,
					'columns' => array(
						'desktop' => absint( $post_args['posts_per_page'] )
					)
				)
			);
		}
	}
}

Codetot_Related_Posts::instance();
