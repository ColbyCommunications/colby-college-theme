<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package    WordPress
 * @subpackage Timber
 * @since      Timber 0.1
 */

add_theme_support( 'responsive-embeds' );

include __DIR__ . '/acf_fields.php';


/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
	include_once $composer_autoload;
	Timber\Timber::init();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function () {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function ( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
$static_timber_dirs = array(
	'src/twig/layouts',
	'src/twig/pages',
	'src/twig/components',
);

$theme_base = get_template_directory() . '/';

$component_dirs = array_map(
	function ( $str ) use ( &$theme_base ) {
		return str_replace( $theme_base, '', $str );
	},
	array_filter( glob( $theme_base . 'src/twig/components/*' ), 'is_dir' )
);

Timber::$dirname = array_merge( $static_timber_dirs, $component_dirs );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {

	/**
	 * Add timber support.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'acf/init', array( $this, 'my_acf_init' ) );
		parent::__construct();
	}
	/**
	 * This is where you can register custom post types.
	 */
	public function register_post_types() {
		register_post_type(
			'people',
			array(
				'labels'            => array(
					'name'               => __( 'People' ),
					'singular_name'      => __( 'Person' ),
					'add_new_item'       => __( 'Add Person' ),
					'edit_item'          => __( 'Edit Person' ),
					'new_item'           => __( 'New Person' ),
					'view_item'          => __( 'View Person' ),
					'search_items'       => __( 'Search People' ),
					'not_found'          => __( 'Person not found.' ),
					'not_found_in_trash' => __( 'No Person found in trash.' ),
				),
				'rewrite'           => array( 'slug' => 'people/people-directory' ),
				'public'            => true,
				'has_archive'       => true,
				'show_in_rest'      => false,
				'menu_icon'         => 'dashicons-id-alt',
				'show_in_nav_menus' => true,
				'supports'          => array( 'title', 'revisions', 'excerpt', 'thumbnail' ),
			)
		);
	}

	/**
	 * This is where you can register custom taxonomies.
	 */
	public function register_taxonomies() {
		register_taxonomy(
			'page-categories',
			'page',
			array(
				'hierarchical'      => true,
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'public'            => true,
				'show_tagcloud'     => false,
				'capabilities'      => array(
					'manage_terms' => 'manage_options',
					'edit_terms'   => 'manage_options',
					'delete_terms' => 'manage_options',
					'assign_terms' => 'manage_options',
				),
				'rewrite'           => array(
					'slug' => 'page-categories',
				),
				'labels'            => array(
					'name'          => __( 'Categories' ),
					'singular_name' => __( 'Category' ),
					'add_new_item'  => __( 'Add New Category' ),
					'menu_name'     => __( 'Categories' ),
				),
			)
		);
	}

	function get_current_url() {
    global $wp;
    return home_url(add_query_arg(array(), $wp->request));
	}

	function my_acf_block_render_callback($block) {
    // Get the current URL
    $context = Timber::context();
    $context['current_url'] = get_current_url();

    // Render the Twig template
    Timber::render('blocks/people-grid.twig', $context);
	}

	public function my_acf_init() {

		// check function exists
		if ( function_exists( 'acf_register_block' ) ) {

			// register a accordion block
			acf_register_block(
				array(
					'name'            => 'accordion',
					'title'           => __( 'Accordion' ),
					'description'     => __( 'A custom accordion block.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'accordion', 'panels' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register an advanced accordion block (parent)
			register_block_type( __DIR__ . '/src/twig/components/advanced-accordion', array( 'icon' => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' )));
			register_block_type( __DIR__ . '/src/twig/components/advanced-accordion-panel', array( 'icon' => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' )));

			// register a home hero
			acf_register_block(
				array(
					'name'            => 'home-hero',
					'title'           => __( 'Home Hero' ),
					'description'     => __( 'Large hero component, supporting both video and basic images.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'hero', 'video', 'image', 'home', 'loop' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a carousel
			acf_register_block(
				array(
					'name'            => 'carousel',
					'title'           => __( 'Carousel' ),
					'description'     => __( 'Standard carousel component section.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'carousel', 'slider', 'image', 'news' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a featured post
			acf_register_block(
				array(
					'name'            => 'featured-post',
					'title'           => __( 'Featured Post' ),
					'description'     => __( 'Large image layout for featuring important content.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'featured', 'content', 'image', 'news', 'events', 'post' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register dark interstitial
			acf_register_block(
				array(
					'name'            => 'dark-interstitial',
					'title'           => __( 'Dark Interstitial' ),
					'description'     => __( 'Navy colored divider component, typically utilized to highlight facts and figures or breakup page monotony.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'featured', 'content', 'image', 'divider', 'dark' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register facts and figures
			acf_register_block(
				array(
					'name'            => 'facts-figures',
					'title'           => __( 'Facts & Figures' ),
					'description'     => __( 'Informational section supporting both an image and supplementary factoids.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'featured', 'facts', 'figures', 'numbers', 'image' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register testimonial carousel
			acf_register_block(
				array(
					'name'            => 'testimonial-carousel',
					'title'           => __( 'Testimonial Carousel' ),
					'description'     => __( 'Testimonial section with carousel functionality and image support.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'featured', 'testimonial', 'quote', 'carousel', 'image' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register full bleed image
			acf_register_block(
				array(
					'name'            => 'full-bleed-image',
					'title'           => __( 'Full Bleed Image' ),
					'description'     => __( 'Large image section with caption for full width pages.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'image', 'large', 'full' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register related content
			acf_register_block(
				array(
					'name'            => 'related-content',
					'title'           => __( 'Related Content' ),
					'description'     => __( 'Section typically found at the bottom of full-width page styles for links to supplementary pages.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'related', 'content' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register hero
			acf_register_block(
				array(
					'name'            => 'hero',
					'title'           => __( 'Hero' ),
					'description'     => __( 'Traditional hero component typically deployed on all first-level landing pages.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'hero', 'image', 'heading' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register overlay hero
			acf_register_block(
				array(
					'name'            => 'overlay-hero',
					'title'           => __( 'Overlay Hero' ),
					'description'     => __( 'Dynamic hero component typically deployed on all first-level landing pages, featuring text overlay.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'hero', 'image', 'heading', 'landscape' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register section nav
			acf_register_block(
				array(
					'name'            => 'section-nav',
					'title'           => __( 'Section Navigation' ),
					'description'     => __( 'Top level landing page subnavigation, typically found below the hero. Used for navigation within a site subdivision.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'list', 'section', 'sub', 'navigation' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register article grid
			acf_register_block(
				array(
					'name'            => 'article-grid',
					'title'           => __( 'Article Grid' ),
					'description'     => __( 'Layout dedicated to article display. Features several column layout styles and supports varying image sizes.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'layout', 'article', 'grid', 'image' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register article grid
			acf_register_block(
				array(
					'name'            => 'people-grid',
					'title'           => __( 'People Grid' ),
					'description'     => __( 'Layout dedicated to displaying people posts for academic department and office pages.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'people', 'layout', 'article', 'grid', 'image' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register bordered article row
			acf_register_block(
				array(
					'name'            => 'bordered-article-row',
					'title'           => __( 'Bordered Article Row' ),
					'description'     => __( 'Special article grid layout featuring borders.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'layout', 'bordered', 'article', 'row' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register article section
			acf_register_block(
				array(
					'name'            => 'article-section',
					'title'           => __( 'Article Section' ),
					'description'     => __( 'Layout dedicated to article display. Features several variants including a carousel.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'layout', 'article', 'section', 'carousel' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register media context
			acf_register_block(
				array(
					'name'            => 'media-context',
					'title'           => __( 'Media Context' ),
					'description'     => __( 'Traditional side-by-side layout for any style of media with cooresponding context.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'media', 'image', 'context', 'layout' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register hw image section
			acf_register_block(
				array(
					'name'            => 'hw-image-section',
					'title'           => __( 'Half-Width Image Section' ),
					'description'     => __( 'Traditional side-by-side layout for any style of media with cooresponding context but in a nearly full-width display.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'media', 'image', 'context', 'layout', 'half', 'width' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// list section
			acf_register_block(
				array(
					'name'            => 'list-section',
					'title'           => __( 'List Section' ),
					'description'     => __( 'Basic layout and section dedicated for the display of groups of lists.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'list', 'section', 'unordered', 'layout' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register table
			acf_register_block(
				array(
					'name'            => 'table',
					'title'           => __( 'Table' ),
					'description'     => __( 'Standard table layout with Colby API integrations for several data types.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'table', 'row', 'column', 'layout' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register image grid
			acf_register_block(
				array(
					'name'            => 'image-grid',
					'title'           => __( 'Image Grid' ),
					'description'     => __( 'Layout dedicated to a bevy of image layout styles. Supports captions.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'image', 'row', 'column', 'layout', 'grid', 'caption' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register list context
			acf_register_block(
				array(
					'name'            => 'list-context',
					'title'           => __( 'List Context' ),
					'description'     => __( 'Standard context component with accompanying large list.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'layout', 'list', 'context' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register overlay wide image
			acf_register_block(
				array(
					'name'            => 'overlay-wide-image',
					'title'           => __( 'Overlay Wide Image' ),
					'description'     => __( 'Landscape image layout with context overlayed on a dimmed background.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'layout', 'image', 'overlay', 'landscape' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register context article grid
			acf_register_block(
				array(
					'name'            => 'context-article-grid',
					'title'           => __( 'Context Article Grid' ),
					'description'     => __( 'Article Grid accompanied by context, with the ability to pull posts directly.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'layout', 'context', 'article', 'grid' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register stat group
			acf_register_block(
				array(
					'name'            => 'stat-group',
					'title'           => __( 'Stat Group' ),
					'description'     => __( 'Display style component for a group of statistics with optional context.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'layout', 'context', 'stat', 'grid', 'group' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			acf_register_block(
				array(
					'name'            => 'paragraph',
					'title'           => __( 'Paragraph' ),
					'description'     => __( 'A custom paragraph block.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'paragraph', 'body' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			acf_register_block(
				array(
					'name'            => 'image-text',
					'title'           => __( 'Image with Text' ),
					'description'     => __( 'A custom image with text block.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'paragraph', 'body', 'image' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			acf_register_block(
				array(
					'name'            => 'image',
					'title'           => __( 'Image' ),
					'description'     => __( 'A custom image  block.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'image' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register embed
			acf_register_block(
				array(
					'name'            => 'embed',
					'title'           => __( 'Embed' ),
					'description'     => __( 'A custom block that supports embed code.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'layout', 'context', 'embed' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register block quote
			acf_register_block(
				array(
					'name'            => 'block-quote',
					'title'           => __( 'Block Quote' ),
					'description'     => __( 'Large interstitial text to facilitate body copy quotes.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'layout', 'context', 'paragraph', 'quote' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a media aside
			acf_register_block(
				array(
					'name'            => 'media-aside',
					'title'           => __( 'Media Aside' ),
					'description'     => __( 'Large image with small context. Supports carousel functionality.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'media', 'context', 'aside', 'carousel' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a context section
			acf_register_block(
				array(
					'name'            => 'context-section',
					'title'           => __( 'Context Section' ),
					'description'     => __( 'Dedicated section component for context outside of the confines of a typical component.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'context', 'section', 'heading', 'subheading', 'paragraph' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register table section
			acf_register_block(
				array(
					'name'            => 'table-section',
					'title'           => __( 'Table Section' ),
					'description'     => __( 'Dedicated section for full-width inset pages.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'table', 'row', 'column', 'layout', 'section' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a intro context
			acf_register_block(
				array(
					'name'            => 'intro-context',
					'title'           => __( 'Intro Context' ),
					'description'     => __( 'Two column context section component typically deployed as an alternative aesthetic for introductory context' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'introductory', 'context', 'section' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a full bleed hero
			acf_register_block(
				array(
					'name'            => 'full-bleed-hero',
					'title'           => __( 'Full Bleed Hero' ),
					'description'     => __( 'Hero with inset image, context, and full width background image' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'hero', 'media', 'image', 'context', 'background' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a inset widget
			acf_register_block(
				array(
					'name'            => 'inset-widget',
					'title'           => __( 'Inset Widget' ),
					'description'     => __( 'Decorative interstitial block' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'widget', 'inset', 'context' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a featured events
			acf_register_block(
				array(
					'name'            => 'featured-events',
					'title'           => __( 'Featured Events' ),
					'description'     => __( 'Exclusive featured events component for ColbyArts' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'featured', 'events', 'media', 'context' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a endpoint filter
			acf_register_block(
				array(
					'name'            => 'endpoint-filter',
					'title'           => __( 'Endpoint Filter' ),
					'description'     => __( 'Exclusive filter component for ColbyArts' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'filter', 'events', 'context' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a media context section
			acf_register_block(
				array(
					'name'            => 'media-context-section',
					'title'           => __( 'Media Context Section' ),
					'description'     => __( 'Section component dedicated to grouping media context with a decorative background' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'media', 'context', 'section' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a collage section
			acf_register_block(
				array(
					'name'            => 'collage-section',
					'title'           => __( 'Collage Section' ),
					'description'     => __( 'Decorative section with curated image group and context' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'collage', 'context', 'media', 'section', 'image' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a related section
			acf_register_block(
				array(
					'name'            => 'related-section',
					'title'           => __( 'Related Section' ),
					'description'     => __( 'Component typically found at the bottom of post style pages.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'media', 'context', 'section', 'related' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a icon section
			acf_register_block(
				array(
					'name'            => 'icon-section',
					'title'           => __( 'Icon Section' ),
					'description'     => __( 'Section dedicated to grouping svg assets or transparent pngs.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'icon', 'context', 'section' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a list block grid
			acf_register_block(
				array(
					'name'            => 'list-block-grid',
					'title'           => __( 'List Block Grid' ),
					'description'     => __( 'Dedicated grid for list blocks' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'list', 'block', 'grid' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a bg inset media context
			acf_register_block(
				array(
					'name'            => 'bg-inset-media-context',
					'title'           => __( 'Background Inset Media Context' ),
					'description'     => __( 'Container component for the media context supporting background textures.' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'media', 'context', 'background', 'inset' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);

			// register a home section
			acf_register_block(
				array(
					'name'            => 'home-section',
					'title'           => __( 'Home Section' ),
					'description'     => __( 'Advancement site exclusive block for rendering latest events and alumni news from the Colby News site' ),
					'render_callback' => 'my_acf_block_render_callback',
					'category'        => 'layout',
					'icon'            => file_get_contents( get_template_directory() . '/src/images/svg/c.svg' ),
					'keywords'        => array( 'home', 'context', 'section', 'news', 'events' ),
					'mode'            => 'edit',
					'supports'        => array(
						'align' => false,
					),
				)
			);
		}
	}

	/**
	 * This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$context['post'] = Timber::get_post();

		global $wp;
		if ( ! is_404() ) {
			
		// Get the current URL path
    $current_url = $_SERVER['REQUEST_URI'];
    
    $breadcrumbs_menu = array();

		$post = Timber::get_post();

		if (is_page()) {
				// Logic for pages
				$ancestors = get_post_ancestors($post->ID);

				// Reverse the order of ancestors to go from the top-level ancestor to the direct parent
				$ancestors = array_reverse($ancestors);

				foreach ($ancestors as $ancestor) {
						$title = get_the_title($ancestor);
						$link = get_permalink($ancestor);
						
						$breadcrumbs_menu[] = array(
								'title' => $title,
								'url'   => $link,
						);
				}

				// Add the current page to the breadcrumb array
				$breadcrumbs_menu[] = array(
						'title' => $post->title(),
						'url'   => $post->link(),
				);

		} elseif (is_single()) {
    // Logic for posts

			$categories = get_the_category($post->ID);

			if (!empty($categories)) {
					$primary_category = $categories[0];
					
					$category_ancestors = get_ancestors($primary_category->term_id, 'category');
					$category_ancestors = array_reverse($category_ancestors);

					// Add ancestor categories to breadcrumbs
					foreach ($category_ancestors as $ancestor_id) {
							$ancestor = get_category($ancestor_id);
							$category_link = get_category_link($ancestor->term_id);

							// Check if 'category' is the first segment after the domain
							$cleaned_link = preg_replace('/\/category\//', '/', $category_link, 1);

							$breadcrumbs_menu[] = array(
									'title' => $ancestor->name,
									'url'   => $cleaned_link,
							);
					}

							$primary_category_link = get_category_link($primary_category->term_id);
							$cleaned_primary_link = preg_replace('/\/category\//', '/', $primary_category_link, 1);

							$breadcrumbs_menu[] = array(
									'title' => $primary_category->name,
									'url'   => $cleaned_primary_link,
							);
    		}

				// Construct the "News" breadcrumb
				$current_url = $_SERVER['REQUEST_URI']; // Get the current URL path
				$news_url = rtrim(str_replace(trailingslashit($post->post_name), '', $current_url), '/');

				// Add the "News" breadcrumb
				$breadcrumbs_menu[] = array(
						'title' => 'News',
						'url'   => $news_url . '/',
				);


				// Add the current post to the breadcrumb array
				$breadcrumbs_menu[] = array(
						'title' => $post->title(),
						'url'   => $post->link(),
				);
		}

	if (!empty($breadcrumbs_menu)) {
			$context['breadcrumbs_menu'] = $breadcrumbs_menu;
	}
}

		$context['current_url']    = home_url( $wp->request );
		$context['foo']            = 'bar';
		$context['stuff']          = 'I am a value set in your functions.php file';
		$context['notes']          = 'These values are available everytime you call Timber::context();';
		$context['menu']           = Timber::get_menu();
		$context['main_menu']      = Timber::get_menu( 'Main Menu' );
		$context['utility_menu']   = Timber::get_menu( 'Utility Menu' );
		$context['footer_menu']    = Timber::get_menu( 'Footer Menu' );
		$context['action_menu']    = Timber::get_menu( 'Action Menu' );
		$context['people_menu']    = Timber::get_menu( 'People Menu' );
		$context['global_address'] = get_field( 'address', 'options' );
		$context['global_phone']   = get_field( 'phone', 'options' );
		$context['global_social']  = get_field( 'social_media', 'options' );
		$context['global_alert']   = get_field( 'alert', 'options' );
		$context['site']           = $this;
		return $context;
	}

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
		add_theme_support( 'title-tag' );

		/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
		add_theme_support( 'post-thumbnails' );

		/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		* Enable support for Post Formats.
		*
		* See: https://codex.wordpress.org/Post_Formats
		*/
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );

		add_image_size( 'Square', 800, 800, true );
		add_image_size( 'Square_mobile', 300, 300, true );
		add_image_size( 'Rectangle', 760, 430, true );
		add_image_size( 'Rectangle_mobile', 410, 290, true );
		add_image_size( 'Landscape', 860, 400, true );
		add_image_size( 'Landscape_mobile', 430, 200, true );
		add_image_size( 'Portrait', 380, 580, true );
		add_image_size( 'Portrait_mobile', 190, 290, true );
		add_image_size( 'Hero', 2400, 1320, true );

		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page(
				array(
					'page_title' => 'Global Settings',
					'menu_title' => 'Global Settings',
					'menu_slug'  => 'global-settings',
					'capability' => 'edit_colbyedu_global_settings',
					'redirect'   => false,
				)
			);
		}
	}

	/**
	 * This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/**
	 * This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig\Extension\StringLoaderExtension() );

		// adding a 'path' filter to match fractals 'path'
		$twig->addFilter(
			new Twig\TwigFilter(
				'path',
				function ( $string ) {
						return get_template_directory_uri() . '/dist' . $string;
				}
			)
		);

		return $twig;
	}

}

new StarterSite();

/**
 * Enqueue scripts and styles.
 */
function theme_scripts() {
	wp_enqueue_style( 'hvh', get_template_directory_uri() . '/dist/styles/scripts.css', array(), date( 'H:i:s' ) );
	// wp_enqueue_style('tailwind', get_template_directory_uri() . '/dist/styles/tailwind.css', array(), date("H:i:s"));
	wp_enqueue_script( 'main', get_template_directory_uri() . '/dist/scripts.js', array(), date( 'H:i:s' ), true );
}
add_action( 'wp_enqueue_scripts', 'theme_scripts' );

function get_current_url_path() {
    return $_SERVER['REQUEST_URI'];
}

function get_people_posts_by_department($segment3) {

	// Handle cases where departments have commas in their name
	switch ($segment3) {
		case 'performance theater and dance':
			$segment3 = "performance, theater, and dance";
			break;
		case 'science technology and society':
			$segment3 = "science, technology, and society";
			break;
		case 'womens gender and sexuality studies':
			$segment3 = "women's, gender, and sexuality studies";
			break;
	}
    // Define query arguments
    $args = array(
    'post_type'      => 'people',       // Specify the post type
    'posts_per_page' => -1,             // Retrieve all posts
    'post_status'    => 'publish',      // Retrieve only published posts
    'meta_query'     => array(
        'relation' => 'AND',            // Ensure both conditions must be true
        array(
            'key'     => 'department',  // Custom field key
            'value'   => $segment3,     // Value to match
            'compare' => '=',           // Match exactly
        ),
        array(
            'key'     => 'is_retiree',  // Custom field key for retirees
            'value'   => '1',           // Exclude people with '1' for is_retiree
            'compare' => '!=',          // Exclude if the value is 1
        ),
    ),
	);

    // Instantiate WP_Query
    $query = new WP_Query($args);

    // Initialize an array to hold the posts
    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post = get_post();
            if ($post) { // Check if get_post() returns a valid post object
                $posts[] = ['post' => $post];
            }
        }
        wp_reset_postdata();
    }

    return $posts;
}

function get_people($segment1, $segment2, $segment3) {
    switch ($segment1) {
        case 'academics':
            switch ($segment2) {
                case 'departments-and-programs':
                    if ($segment3 !== '') {
                        return get_people_posts_by_department($segment3);
                    }
                    break;
                default:
                    break;
            }
            break;
        default:
            break;
    }
}

/**
 *  This is the callback that displays the block.
 *
 * @param array  $block      The block settings and attributes.
 * @param string $content    The block content (emtpy string).
 * @param bool   $is_preview True during AJAX preview.
 */
function my_acf_block_render_callback( $block, $content = '', $is_preview = false ) {
	$context = Timber::context();

	// Store block values.
	$context['block'] = $block;

	// Store field values.
	$context['fields'] = get_fields();

	// Store $is_preview value.
	$context['is_preview'] = $is_preview;

	$context['block_name'] = substr( $block['name'], 4 );

	// Store the current URL.
  $context['current_url_path'] = get_current_url_path();

	$context['recent_people'] = Timber::get_posts(
		array(
			'post_type'      => 'people',
			'posts_per_page' => 3,
		)
	);

	$retrieved_departments = Timber::get_posts(
		array(
			'post_type'      => 'page',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'title',
			'tax_query'      => array(
				array(
					'taxonomy' => 'page-categories',
					'field'    => 'slug',
					'terms'    => 'department',
				),
			),
		)
	);
	$departments  = [];
	foreach ($retrieved_departments->to_array() as $department) {
		$department_code = get_post_meta($department->ID, 'department_code');
		$department->department_code = $department_code[0];
		array_push($departments, $department);
	}
	$context['departments'] = $departments;

	$context['offices'] = Timber::get_posts(
		array(
			'post_type'      => 'page',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'title',
			'tax_query'      => array(
				array(
					'taxonomy' => 'page-categories',
					'field'    => 'slug',
					'terms'    => 'office',
				),
			),
		)
	);

	// Store latest 3 posts and then merging it into the fields object before rendering component
	// Check for whether or not block is a related-articles block should be placed here for optimization
	$context_merged = $context['fields'];

	if ( $context['block_name'] == 'context-article-grid' ) {
		$context_merged = array_merge(
			$context['fields'],
			array(
				'recent_people' => $context['recent_people'],
			)
		);
	} 
	
	if ( $context['block_name'] == 'table' ) {
		$context_merged = array_merge(
			$context['fields'],
			array(
				'departments' => $context['departments'],
				'offices'     => $context['offices'],
			)
		);
	}

	if ($context['block_name'] == 'people-grid') {
			// Split the URL path into segments
			$url_segments = explode('/', trim($context['current_url_path'], '/'));

			// Retrieve segments 1, 2, and 3 from the URL (if they exist)
			$segment1 = $url_segments[0] ?? '';
			$segment2 = $url_segments[1] ?? '';
			$segment3 = $url_segments[2] ?? '';

			// Replace dashes in third url segment with spaces
			$segment3 = strtolower(str_replace('-', ' ', trim($segment3)));

			// Handle auto populate if enabled
			$is_enabled_auto_populate = get_field('auto_populate');

			$is_enabled_auto_populate = get_field('auto_populate');
			$people_posts = $is_enabled_auto_populate ? get_people($segment1, $segment2, $segment3) : [];
			$acf_items = get_field('items') ?: [];
	
			// Merge ACF items and people posts
			$merged_items = array_merge(is_array($acf_items) ? $acf_items : [], is_array($people_posts) ? $people_posts : []);
			
			// get people exclusions
			$people_exclusions = get_field('exclude_from_listings');

			$final_people_items = [];

			// process/obtain the final list of folks
			if ($people_exclusions){
				foreach ( $merged_items as $person) {
					if(!in_array($person, $people_exclusions)) {
						$final_people_items[] = $person;
					}
				}
			} else {
				$final_people_items = $merged_items;
			}

			foreach ($final_people_items as &$item) {
            if (isset($item['post'])) {
                $post_id = $item['post']->ID;
                if ($post_id) { // Ensure $post_id is valid
                    $item['last_name'] = strtolower(get_post_meta($post_id, 'last_name', true));
                }
            }
        }
        unset($item);

		// Sort the merged_items array by last_name
		usort($final_people_items, function($a, $b) {
		return strcmp(strtolower($a['last_name']), strtolower($b['last_name']));
		});

		// Update context with merged items
		$context_merged['acf_items'] = $acf_items;
		$context_merged['people_posts'] = $people_posts;
		$context_merged['people'] = $final_people_items;
	}

	if ($context['block_name'] == 'article-grid' && isset($context['fields']['exclude_internal_posts'])) {
		if (is_array($context['fields']['exclude_internal_posts'])){
			$exclude_post_ids = array_map(function($o) { return $o['post']->ID;}, $context['fields']['exclude_internal_posts']);
			$context_merged['internal_post_exclusions'] = $exclude_post_ids;
		}
	}

	// Render the block.
	Timber::render( 'src/twig/components/' . $context['block_name'] . '/' . $context['block_name'] . '.twig', $context_merged );
}

/**
 * ===================================================
 *
 * Colby Directory Import/Sync
 *
 * ===================================================
 */

function updateStaffDirectory() {
	if ( ! is_admin() ) {
		$directory_data = json_decode( file_get_contents( WP_CONTENT_DIR . '/directory_data/Colby_Directory_Webservice_Output.json' ), true )['Report_Entry'];
		// $directory_data = json_decode(file_get_contents(WP_CONTENT_DIR . "/directory_data/Colby_Directory_Webservice_Output.json"), true)['Report_Entry'];
		deletePeople( $directory_data );
		getNewPeople( $directory_data );
	}
}
// herexyz
function deletePeople( $directory_data ) {
	$args = array(
		'numberposts' => -1,
		'post_type'   => 'people',
		'post_status' => 'publish',
	);

	$all_posts   = get_posts( $args );
	$total_posts = count( $all_posts );

	if ( $total_posts > 0 ) {
		foreach ( $all_posts as $post ) {
			// Get the employee_id meta value using the post ID
			$employee_id = get_post_meta( $post->ID, 'employee_id', true );

			// Check if the employee id in the DB exists in the WD file
			$match = false;

			foreach ( $directory_data as $WDPerson ) {
				$WDEmployeeID = str_pad( $WDPerson['employeeID'], 7, '0', STR_PAD_LEFT );
				if ( $employee_id === $WDEmployeeID ) {
					$match = true;
					break;
				}
			}

			// If record is missing from WD, delete the record in the DB
			if ( $match !== true ) {
				$thumb_id = get_post_thumbnail_id( $post->ID );
				wp_delete_attachment( $thumb_id, true );
				wp_delete_post( $post->ID, true );
			}
		}
	}
}

// use phpseclib3\Crypt\RSA;
use phpseclib3\Net\SFTP;

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

function getNewPeople( $directory_data ) {

	$sftp = new SFTP( 'colby0.colby.edu' );
	$sftp->login( PLATFORM_VARIABLES['sftp_username'], PLATFORM_VARIABLES['sftp_pw'] );

	// Loop through the WD array
	foreach ( $directory_data as $WDPerson ) {
		// Assign variables to desired WD fields
		$WDEmployeeID    = str_pad( $WDPerson['employeeID'], 7, '0', STR_PAD_LEFT );
		$WDPrefFirstName = $WDPerson['preferredFirstName'];
		$WDPrefLastName  = $WDPerson['preferredLastName'];

		// Skip person if no email associated
		if ( ! $WDPerson['primaryWorkEmail'] ) {
			continue;
		}

		$WDEmail = strtolower( $WDPerson['primaryWorkEmail'] );
		$WDTitle = $WDPerson['businessTitle'];

		$WDPhone = '';
		if ( isset( $WDPerson['primaryWorkPhone'] ) ) {
			$WDPhone = $WDPerson['primaryWorkPhone'];
		}

		$wd_pronouns = '';
		if ( isset( $WDPerson['pronouns'] ) ) {
			$wd_pronouns = stripcslashes( $WDPerson['pronouns'] );
		}

		$WDBuilding = '';
		if ( isset( $WDPerson['workSpaceLocation'] ) ) {
			$WDBuilding = $WDPerson['workSpaceLocation'];
		}

		$WDFax = '';
		if ( isset( $WDPerson['faxPhoneNumber'] ) && $WDPerson['faxPhoneNumber'] ) {
			$WDFax = $WDPerson['faxPhoneNumber'];
		}

		$emailSlug = strtolower( substr( $WDEmail, 0, strpos( $WDEmail, '@' ) ) );

		/* Academic unit for faculty, Superior org for staff (department metadata) */

		$WDAcademicUnit = $WDPerson['Academic_Units'];
		$WDSupOrg       = $WDPerson['supervisoryOrganization'];
		$WDSOH          = $WDPerson['supervisoryOrgHierarchy'];
		$WDOrgsManaged  = $WDPerson['organizationsManaged'];
		$supOrgRegex    = '/.+?(?=[-|(])/';

		$orgResult = '';

		if ( $WDSupOrg ) {
			preg_match( $supOrgRegex, $WDSupOrg, $deptResult );
			$orgResult = $deptResult[0];
		}

		if ( $WDSOH && ( count( explode( '>', $WDSOH ) ) === 2 || count( explode( '>', $WDSOH ) ) === 3 ) ) {
			if ( preg_match( $supOrgRegex, $WDOrgsManaged ) ) {
				preg_match( $supOrgRegex, $WDOrgsManaged, $matches );
				$orgResult = $matches[0];
			}
		}

		$WDDepartment = $WDAcademicUnit;
		if ( is_null( $WDAcademicUnit ) ) {
			$WDDepartment = $orgResult;
		}

		$WDIsRetiree = 0;
		if (!is_null($WDPerson['Is_Retiree'])) {
			$WDIsRetiree = 1;
		}

		// Set api endpoint url with $emailSlug
		$url = 'https://www.colby.edu/endpoints/v1/profile/' . $emailSlug;

		// Initialize a CURL session.
		$ch = curl_init();
		// Return Page contents.
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		// grab URL and pass it to the variable.
		curl_setopt( $ch, CURLOPT_URL, $url );
		$CXPerson = json_decode( curl_exec( $ch ), true );

		$CXCourses = '';

		if ( isset( $CXPerson['courses'] ) ) {
			$CXCourses = $CXPerson['courses'];
		}

		$CXMailing = '';
		if ( isset( $CXPerson['box'] ) && $CXPerson['box'] ) {
			$CXMailing = $CXPerson['box'] . " Mayflower Hill \nWaterville, Maine 04901-8853";
		}

		$args = array(
			'numberposts' => -1,
			'post_type'   => 'people',
			'post_status' => array('publish', 'draft'),
			'meta_query'  => array(
				array(
					'key'     => 'employee_id',
					'value'   => $WDEmployeeID,
					'compare' => '=',
				),
			),
		);

		// Combine fields from WD and CX
		$post = array(
			'post_title'   => $WDPrefFirstName . ' ' . $WDPrefLastName,
			'post_content' => '',
			'post_type'    => 'people',
			'post_status'  => 'publish',
			'meta_input'   => array(
				'employee_id'      => $WDEmployeeID,
				'first_name'       => $WDPrefFirstName,
				'last_name'        => $WDPrefLastName,
				'pronouns'         => $wd_pronouns,
				'title'            => $WDTitle,
				'department'       => $WDDepartment,
				'phone'            => $WDPhone,
				'email'            => $WDEmail,
				'building'         => $WDBuilding,
				'curriculum_vitae' => '',
				'current_courses'  => json_encode( $CXCourses ),
				'fax'              => $WDFax,
				'mailing_address'  => $CXMailing,
				'is_retiree'	=> $WDIsRetiree
			),
		);

		$DBMatchingPost = get_posts( $args );

		$photosWithDates = array_filter(
			$sftp->nlist( '/web/staticweb/college/WorkdayPhotos/v2/MD5' ),
			function ( $item ) {
				return strpos( $item, '.jpg' ) !== false;
			}
		);

		$matchingPhoto = false;
		if ( ( ! $DBMatchingPost ) ) {
			$ID = wp_insert_post( $post );

			foreach ( $photosWithDates as $photo ) {
				if ( strpos( $photo, md5( $WDEmployeeID ) ) !== false ) {
					$matchingPhoto = $photo;
					break;
				}
			}

			if ( $matchingPhoto ) {
				$imageURL = 'https://colby.edu/college/WorkdayPhotos/v2/MD5/' . $matchingPhoto;
				$desc     = $WDPrefFirstName . ' ' . $WDPrefLastName;
				$image    = media_sideload_image( $imageURL, $ID, $desc, 'id' );
				set_post_thumbnail( $ID, $image );
			}
		} else {
			$post            = $DBMatchingPost[0];
			$ID              = $DBMatchingPost[0]->ID;
			$person_metadata = get_post_meta( $ID );

			// Update title metadata with latest title from WD
			update_post_meta( $ID, 'title', $WDTitle );

			// Update courses metadata with latest courses from CX
			if ( $CXCourses ) {
				update_post_meta( $ID, 'current_courses', json_encode( $CXCourses ) );
			}

			// Update metadata for fields not changed in Gravity Forms with latest WD data

			update_post_meta( $ID, 'first_name', $WDPrefFirstName );
			update_post_meta( $ID, 'last_name', $WDPrefLastName );

			if ( $post->post_title !== $WDPrefFirstName . ' ' . $WDPrefLastName ) {
				wp_update_post(
					array(
						'ID'         => $ID,
						'post_title' => $WDPrefFirstName . ' ' . $WDPrefLastName,
						'post_name'  => sanitize_title( $WDPrefFirstName . ' ' . $WDPrefLastName ),
					)
				);
			}

			update_post_meta( $ID, 'email', $WDEmail );
			update_post_meta( $ID, 'phone', $WDPhone );
			update_post_meta( $ID, 'building', $WDBuilding );
			update_post_meta( $ID, 'fax', $WDFax );
			update_post_meta( $ID, 'mailing_address', $CXMailing );
			update_post_meta( $ID, 'pronouns', $wd_pronouns );
			update_post_meta( $ID, 'is_retiree', $WDIsRetiree );

			if ( empty( $person_metadata['unsync_department'][0] ) ) {
				update_post_meta( $ID, 'department', $WDDepartment );
			}

			foreach ( $photosWithDates as $photo ) {
				if ( strpos( $photo, md5( $WDEmployeeID ) ) !== false ) {
					$matchingPhoto = $photo;
					break;
				}
			}

			if ( $matchingPhoto ) {
				$img_parts   = explode( '_', $matchingPhoto );
				$date        = substr( $img_parts[1], 0, 8 );
				$imageURL    = 'https://colby.edu/college/WorkdayPhotos/v2/MD5/' . $matchingPhoto;
				$desc        = $WDPrefFirstName . ' ' . $WDPrefLastName;
				$DBImageName = get_the_post_thumbnail_url( $ID );
				if ( $DBImageName ) {
					if ( strpos( $DBImageName, '_' ) !== false ) {
						$DB_img_parts = explode( '_', $DBImageName );
						$DB_date      = substr( $DB_img_parts[1], 0, 8 );

						if ( $date !== $DB_date ) {
							$thumb_id = get_post_thumbnail_id( $ID );
							wp_delete_attachment( $thumb_id, true );
							$image = media_sideload_image( $imageURL, $ID, $desc, 'id' );
							set_post_thumbnail( $ID, $image );
						}
					}
				} else {
					$image = media_sideload_image( $imageURL, $ID, $desc, 'id' );
					set_post_thumbnail( $ID, $image );
				}
			}
		}
	}
}

add_action( 'gform_after_submission_12', 'update_directory_profile', 10, 2 );
function update_directory_profile( $entry, $form ) {

	// get attributes from SimpleSAML session
	$as         = new \SimpleSAML\Auth\Simple( 'default-sp' );
	$attributes = $as->getAttributes();
	$e_id       = $attributes['WorkdayID'][0];

	$department        = $entry[5];
	$curriculum_vitae  = $entry[9];
	$office_hours      = $entry[15];
	$bio               = $entry[1];
	$hide_pronouns     = $entry[34];
	$hide_phone_number = $entry[35];
	$hide_fax          = $entry[36];
	$hide_location     = $entry[37];
	$hide_department   = $entry[38];
	$hide_cv           = $entry[39];
	$hide_office_hours = $entry[40];
	$hide_bio          = $entry[41];
	$unsync_department = $entry['43.1'];
	$hide_photo        = $entry[44];
	$hide_email        = $entry[51];

	// get person post by employee ID
	$args = array(
		'post_type'  => 'people',
		'meta_query' => array(
			array(
				'key'     => 'employee_id',
				'value'   => $e_id,
				'compare' => '=',
			),
		),
	);

	$person_post     = get_posts( $args );
	$id              = $person_post[0]->ID;
	$person_metadata = get_post_meta( $id );

	// update post
	$meta_values = array(
		'department'        => $unsync_department === 'yes' ? $department : $person_metadata['department'][0],
		'curriculum_vitae'  => $curriculum_vitae,
		'office_hours'      => $office_hours,
		'bio'               => $bio,

		// remove/hide fields
		'hide_pronouns'     => $hide_pronouns === 'yes' ? 1 : 0,
		'hide_phone_number' => $hide_phone_number === 'yes' ? 1 : 0,
		'hide_fax'          => $hide_fax === 'yes' ? 1 : 0,
		'hide_location'     => $hide_location === 'yes' ? 1 : 0,
		'hide_department'   => $hide_department === 'yes' ? 1 : 0,
		'hide_cv'           => $hide_cv === 'yes' ? 1 : 0,
		'hide_office_hours' => $hide_office_hours === 'yes' ? 1 : 0,
		'hide_bio'          => $hide_bio === 'yes' ? 1 : 0,
		'hide_photo'        => $hide_photo === 'yes' ? 1 : 0,
		'hide_email'        => $hide_email === 'yes' ? 1 : 0,
		'unsync_department' => $unsync_department === 'yes' ? 1 : 0,
	);

	wp_update_post(
		array(
			'ID'         => $person_post[0]->ID,
			'post_title' => $person_metadata['first_name'][0] . ' ' . $person_metadata['last_name'][0],
			'meta_input' => $meta_values,
		)
	);

}

function gravity_forms_buttons() {
	return array(
		'formatselect',
		'bold',
		'italic',
		'bullist',
		'underline',
		'numlist',
		'undo',
		'redo',
		'link',
		'unlink',
		'sub',
		'sup',
		'justifyleft',
		'justifycenter',
		'justifyright',
		'justifyfull',
		'hr',
	);
}

add_filter( 'gform_rich_text_editor_buttons', 'gravity_forms_buttons', 1, 1 );

add_action( 'directory_sync', 'updateStaffDirectory' );

if ( ! wp_next_scheduled( 'directory_sync' ) ) {
	$time = strtotime( 'today' );
	wp_schedule_event( $time, 'daily', 'directory_sync' );
}

// Disables WordPress partial match URL redirects
add_filter( 'do_redirect_guess_404_permalink', 'stop_redirect_guess' );

function stop_redirect_guess() {
	return false;
}

function wporg_block_wrapper( $block_content, $block ) {
	if ( $block['blockName'] === 'core/paragraph' ) {
		$content  = '<div class="wp-block-paragraph">';
		$content .= $block_content;
		$content .= '</div>';
		return $content;
	} elseif ( $block['blockName'] === 'core/heading' ) {
		$content  = '<div class="wp-block-heading">';
		$content .= $block_content;
		$content .= '</div>';
		return $content;
	} elseif ( $block['blockName'] === 'core/image' ) {
		$content  = '<div class="wp-block-image">';
		$content .= $block_content;
		$content .= '</div>';
		return $content;
	} elseif ( $block['blockName'] === 'core/embed' ) {
		$content  = '<div class="wp-block-embed">';
		$content .= $block_content;
		$content .= '</div>';
		return $content;
	} elseif ( $block['blockName'] === 'core/table' ) {
		$content  = '<div class="wp-block-table">';
		$content .= $block_content;
		$content .= '</div>';
		return $content;
	}
	return $block_content;
}

add_filter( 'render_block', 'wporg_block_wrapper', 10, 2 );

add_filter( 'manage_pages_columns', 'wpse248405_columns', 25, 1 );
function wpse248405_columns( $cols ) {
	$user = wp_get_current_user();
	if ( ! in_array( 'administrator', $user->roles ) && in_array( 'editor', $user->roles ) ) {
		// remove title column
		unset( $cols['title'] );
		unset( $cols['taxonomy-page-categories'] );
		// add custom column in second place
		$cols = array(
			'foo'    => __( 'Title', 'textdomain' ),
			'parent' => __( 'Parent Page', 'textdomain' ),
		) + $cols;
		// return columns

	}
	return $cols;
}

add_action( 'manage_pages_custom_column', 'wpse248405_custom_column', 10, 2 );
function wpse248405_custom_column( $col, $post_id ) {
	$user = wp_get_current_user();
	if ( ! in_array( 'administrator', $user->roles ) && in_array( 'editor', $user->roles ) ) {

		global $mode;
		if ( $col === 'foo' ) {

			$current_level = 0;
			$post          = get_post( $post_id );
			// Sent current_level 0 by accident, by default, or because we don't know the actual level.
			$find_main_page = (int) $post->post_parent;

			while ( $find_main_page > 0 ) {
				$parent = get_post( $find_main_page );

				if ( is_null( $parent ) ) {
					break;
				}

				$current_level++;
				$find_main_page = (int) $parent->post_parent;

				if ( ! isset( $parent_name ) ) {
					/** This filter is documented in wp-includes/post-template.php */
					$parent_name = apply_filters( 'the_title', $parent->post_title, $parent->ID );
				}
			}

			$can_edit_post = current_user_can( 'edit_post', $post->ID );

			if ( $can_edit_post && 'trash' !== $post->post_status ) {
				$lock_holder = wp_check_post_lock( $post->ID );

				if ( $lock_holder ) {
					$lock_holder   = get_userdata( $lock_holder );
					$locked_avatar = get_avatar( $lock_holder->ID, 18 );
					/* translators: %s: User's display name. */
					$locked_text = esc_html( sprintf( __( '%s is currently editing' ), $lock_holder->display_name ) );
				} else {
					$locked_avatar = '';
					$locked_text   = '';
				}

				echo '<div class="locked-info"><span class="locked-avatar">' . $locked_avatar . '</span> <span class="locked-text">' . $locked_text . "</span></div>\n";
			}

			$pad = '';
			echo '<strong>';

			$title = _draft_or_post_title();

			if ( $can_edit_post && 'trash' !== $post->post_status ) {
				printf(
					'<a class="row-title" href="%s" aria-label="%s">%s%s</a>',
					get_edit_post_link( $post->ID ),
					/* translators: %s: Post title. */
					esc_attr( sprintf( __( '&#8220;%s&#8221; (Edit)' ), $title ) ),
					$pad,
					$title
				);
			} else {
				printf(
					'<span>%s%s</span>',
					$pad,
					$title
				);
			}

			// _post_states( $post );
			if ( isset( $parent_name ) ) {
				if ( html_entity_decode( $parent_name ) === html_entity_decode( 'Departments & Programs' ) ) {
					echo ' - Department Homepage';
				}

				if ( $parent_name === 'Offices Directory' ) {
					echo ' - Office Homepage';
				}
			}

			echo "</strong>\n";

			if ( 'excerpt' === $mode
				&& ! is_post_type_hierarchical( $post->post_type )
				&& current_user_can( 'read_post', $post->ID )
			) {
				if ( post_password_required( $post ) ) {
					echo '<span class="protected-post-excerpt">' . esc_html( get_the_excerpt() ) . '</span>';
				} else {
					echo esc_html( get_the_excerpt() );
				}
			}

			get_inline_data( $post );
		}

		if ( $col === 'parent' ) {

			$current_level = 0;
			$post          = get_post( $post_id );
			// Sent current_level 0 by accident, by default, or because we don't know the actual level.
			$find_main_page = (int) $post->post_parent;

			while ( $find_main_page > 0 ) {
				$parent = get_post( $find_main_page );

				if ( is_null( $parent ) ) {
					break;
				}

				$current_level++;
				$find_main_page = (int) $parent->post_parent;

				if ( ! isset( $parent_name ) ) {
					/** This filter is documented in wp-includes/post-template.php */
					$pid         = $parent->ID;
					$parent_name = apply_filters( 'the_title', $parent->post_title, $parent->ID );
				}
			}
			// echo $parent_name;
			if ( isset( $parent_name ) ) {
				printf(
					'<a href="%s" aria-label="%s">%s</a>',
					get_edit_post_link( $pid ),
					$parent_name,
					$parent_name
				);
			}
		}
	}
}

add_filter( 'manage_edit-post_columns', 'yoast_seo_admin_remove_columns', 10, 1 );
add_filter( 'manage_edit-page_columns', 'yoast_seo_admin_remove_columns', 10, 1 );

function yoast_seo_admin_remove_columns( $columns ) {
	$user = wp_get_current_user();
	if ( ! in_array( 'administrator', $user->roles ) && in_array( 'editor', $user->roles ) ) {

		unset( $columns['wpseo-score'] );
		unset( $columns['wpseo-score-readability'] );
		unset( $columns['wpseo-title'] );
		unset( $columns['wpseo-metadesc'] );
		unset( $columns['wpseo-focuskw'] );
		unset( $columns['wpseo-links'] );
		unset( $columns['wpseo-linked'] );
		unset( $columns['editor'] );

	}
	return $columns;
}

add_filter( 'admin_body_class', 'admin_body_classes' );
function admin_body_classes( $classes ) {
	if ( is_user_logged_in() ) {
		$user     = wp_get_current_user();
		$roles    = $user->roles;
		$classes .= ' user-role-' . $roles[0] . ' ';
	}
	return $classes;
}

add_action( 'admin_head', 'custom_admin_css' );

function custom_admin_css() {
	echo '<style>
    .user-role-editor.post-type-page .fixed .column-parent,
    .user-role-editor.post-type-page .fixed .column-author,
    .user-role-editor.post-type-page .fixed .column-date {
      width: auto;
    } 
    .user-role-editor.post-type-page .fixed .column-foo {
        width: 40%;
    }
  </style>';
}

if ( class_exists( 'acf_revisions' ) ) {
	// Reference to ACF's <code>acf_revisions</code> class
	// We need this to target its method, acf_revisions::acf_validate_post_id
	$acf_revs_cls = acf()->revisions;

	// This hook is added the ACF file: includes/revisions.php:36 (in ACF PRO v5.11)
	remove_filter( 'acf/validate_post_id', array( $acf_revs_cls, 'acf_validate_post_id', 10 ) );
}

add_filter( 'ajax_query_attachments_args', 'hide_directory_attachments' );

function hide_directory_attachments( $query = array() ) {
	$user = wp_get_current_user();
	if ( in_array( 'editor', $user->roles ) ) {
		$posts = get_posts(
			array(
				'post_type'   => 'people',
				'post_status' => 'publish',
				'numberposts' => -1,
			)
		);

		$query['post_parent__not_in'] = array_column( $posts, 'ID' );
	}
	// comment
	return $query;
}

require_once( 'lib/simplesamlphp/src/_autoload.php' );
add_action( 'template_redirect', 'directory_auth_check' );
function directory_auth_check() {
	if ( is_page( 'directory-profile-update-form' ) ) {
		$as = new \SimpleSAML\Auth\Simple( 'default-sp' );

		if ( ! $as->isAuthenticated() ) {

			// unset the person data just to be safe
			if ( array_key_exists( 'person', $_SESSION ) ) {
				unset( $_SESSION['person'] );
			}

			// send to Okta
			$as->requireAuth();

		} else {

			$attributes = $as->getAttributes();
			$e_id       = $attributes['WorkdayID'][0];

			// get person post by employee ID
			$args            = array(
				'post_type'  => 'people',
				'meta_query' => array(
					array(
						'key'     => 'employee_id',
						'value'   => $e_id,
						'compare' => '=',
					),
				),
			);
			$person_post     = get_posts( $args );
			$id              = $person_post[0]->ID;
			$person_metadata = get_post_meta( $id );

			// assign metadata to session
			$_SESSION['colby_directory_id'] = $e_id;
			$_SESSION['person']             = $person_metadata;
		};

	}
}

function greeting( $content ) {
	if ( is_page( 'directory-profile-update-form' ) && isset( $_SESSION['colby_directory_id'] ) ) {
			return "<div class='mb-8'><h2 class='mb-2 font-bold' style='font-size:30px'>Hello, {$_SESSION['person']['first_name'][0]} {$_SESSION['person']['last_name'][0]} </h2 ></div>" . $content;
	}
	return $content;
}
add_filter( 'the_content', 'greeting' );

/* Gravity Forms Prepopulation Functions */

// Workday Email
add_filter( 'gform_field_value_directory_email', 'email_prepopulation' );
function email_prepopulation( $value ) {
	return $_SESSION['person']['email'][0];
}

// First Name
add_filter( 'gform_field_value_directory_first_name', 'first_name_prepopulation' );
function first_name_prepopulation( $value ) {
	return $_SESSION['person']['first_name'][0];
}

// Last Name
add_filter( 'gform_field_value_directory_last_name', 'last_name_prepopulation' );
function last_name_prepopulation( $value ) {
	return $_SESSION['person']['last_name'][0];
}

// Hide Pronouns
add_filter( 'gform_field_value_directory_hide_pronouns', 'hide_pronouns_prepopulation' );
function hide_pronouns_prepopulation( $value ) {
	if ( empty( $_SESSION['person']['hide_pronouns'][0] ) || $_SESSION['person']['hide_pronouns'][0] == 0 ) {
		return 'no';
	}
	return 'yes';
}

// Hide Office Phone Number
add_filter( 'gform_field_value_directory_hide_phone', 'hide_phone_prepopulation' );
function hide_phone_prepopulation( $value ) {
	if ( empty( $_SESSION['person']['hide_phone_number'][0] ) || $_SESSION['person']['hide_phone_number'][0] == 0 ) {
		return 'no';
	}
	return 'yes';
}

// Hide Fax Number
add_filter( 'gform_field_value_directory_hide_fax', 'hide_fax_prepopulation' );
function hide_fax_prepopulation( $value ) {
	if ( empty( $_SESSION['person']['hide_fax'][0] ) || $_SESSION['person']['hide_fax'][0] == 0 ) {
		return 'no';
	}
	return 'yes';
}

// Hide Location
add_filter( 'gform_field_value_directory_hide_location', 'hide_location_prepopulation' );
function hide_location_prepopulation( $value ) {
	if ( empty( $_SESSION['person']['hide_location'][0] ) || $_SESSION['person']['hide_location'][0] == 0 ) {
		return 'no';
	}
	return 'yes';
}

// Unsync Department Selection
add_filter( 'gform_field_value_directory_unsyc_department', 'unsync_department_prepopulation' );
function unsync_department_prepopulation( $value ) {
	if ( ! empty( $_SESSION['person']['unsync_department'] ) && $_SESSION['person']['unsync_department'][0] == 1 ) {
		return 'yes';
	}
	return '';
}

// Department
add_filter( 'gform_field_value_directory_department', 'department_prepopulation' );
function department_prepopulation( $value ) {
	if ( ! empty( $_SESSION['person']['department'][0] ) ) {
		return $_SESSION['person']['department'][0];
	}
}

// Hide Department
add_filter( 'gform_field_value_directory_hide_department', 'hide_department_prepopulation' );
function hide_department_prepopulation( $value ) {
	if ( empty( $_SESSION['person']['hide_department'][0] ) || $_SESSION['person']['hide_department'][0] == 0 ) {
		return 'no';
	}
	return 'yes';
}

// Curriculum Vitae
add_filter( 'gform_field_value_directory_cv', 'cv_prepopulation' );
function cv_prepopulation( $value ) {
	if ( ! empty( $_SESSION['person']['curriculum_vitae'][0] ) ) {
		return $_SESSION['person']['curriculum_vitae'][0];
	}
}

// Hide Curriculum Vitae
add_filter( 'gform_field_value_directory_hide_cv', 'hide_cv_prepopulation' );
function hide_cv_prepopulation( $value ) {
	if ( empty( $_SESSION['person']['hide_cv'][0] ) || $_SESSION['person']['hide_cv'][0] == 0 ) {
		return 'no';
	}
	return 'yes';
}

// Office Hours
add_filter( 'gform_field_value_directory_office_hours', 'office_hours_prepopulation' );
function office_hours_prepopulation( $value ) {
	if ( ! empty( $_SESSION['person']['office_hours'][0] ) ) {
		return $_SESSION['person']['office_hours'][0];
	}
}

// Hide Office Hours
add_filter( 'gform_field_value_directory_hide_office_hours', 'hide_office_hours_prepopulation' );
function hide_office_hours_prepopulation( $value ) {
	if ( empty( $_SESSION['person']['hide_office_hours'][0] ) || $_SESSION['person']['hide_office_hours'][0] == 0 ) {
		return 'no';
	}
	return 'yes';
}

// Hide Profile Photo
add_filter( 'gform_field_value_directory_hide_photo', 'hide_photo_prepopulation' );
function hide_photo_prepopulation( $value ) {
	if ( empty( $_SESSION['person']['hide_photo'][0] ) || $_SESSION['person']['hide_photo'][0] == 0 ) {
		return 'no';
	}
	return 'yes';
}

// Bio
add_filter( 'gform_field_value_directory_bio', 'bio_prepopulation' );
function bio_prepopulation( $value ) {
	if ( ! empty( $_SESSION['person']['bio'][0] ) ) {
		return $_SESSION['person']['bio'][0];
	}
}

// Hide Bio
add_filter( 'gform_field_value_directory_hide_bio', 'hide_bio_prepopulation' );
function hide_bio_prepopulation( $value ) {
	if ( empty( $_SESSION['person']['hide_bio'][0] ) || $_SESSION['person']['hide_bio'][0] == 0 ) {
		return 'no';
	}
	return 'yes';
}

// Hide Email
add_filter( 'gform_field_value_directory_hide_email', 'hide_email_prepopulation' );
function hide_email_prepopulation( $value ) {
	if ( empty( $_SESSION['person']['hide_email'][0] ) || $_SESSION['person']['hide_email'][0] == 0 ) {
		return 'no';
	}
	return 'yes';
}

function custom_meta_description( $description ) {
	// Check if the meta description is empty or not set
	if ( empty( $description ) ) {
			// Get the current post ID and its content
		$post_id      = get_the_ID();
		$post_content = get_post_field( 'post_content', $post_id );

				// Extract the content from the first block (paragraph or image-text)
		preg_match( '/<!--\s+wp:acf\/(paragraph|image-text).+?{"paragraph_text":"(.*?)"/s', $post_content, $matches );

				// Check if a match is found and extract the content from the block
		$match = isset( $matches[2] ) ? json_decode( '"' . $matches[2] . '"' ) : null;

				// Decode Unicode escape sequences in the extracted text
		$decoded_match = isset( $match ) ? html_entity_decode( $match ) : null;

				// Remove unwanted characters from the extracted text except hyphens
		$clean_match = isset( $decoded_match ) ? preg_replace( '/[\x00-\x1F\x7F-\xFF\xA0]/u', ' ', $decoded_match ) : null;

				// Trim the description to 40 words if it exists
		$description = isset( $clean_match ) ? wp_trim_words( $clean_match, 40, '' ) : 'Colby College is an intellectual community working to solve the world’s most complex challenges.';
	}

	return $description;
}
add_filter( 'wpseo_metadesc', 'custom_meta_description' );

add_filter( 'auto_core_update_send_email', '__return_false' );

add_filter(
	'wpseo_title',
	function ( $title ) {
		if ( get_query_var( 'post_type' ) === 'people' && is_post_type_archive( 'people' ) ) {
			$title = 'People Directory | Colby College';
		}
		return  $title;
	}
);

function _purgeCF() {
	$cf_api_email = get_option( 'cloudflare_api_email' );
	$cf_api_key   = get_option( 'cloudflare_api_key' );
	$data         = array(
		// get host from database
		'hosts' => array( wp_parse_url(home_url())['host'] ),
	);

	$json = json_encode( $data );

	$ch = curl_init();

	// Set options
	curl_setopt( $ch, CURLOPT_URL, 'https://api.cloudflare.com/client/v4/zones/bcccb3fcba241fabbe73cd335f7507bc/purge_cache' );
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt(
		$ch,
		CURLOPT_HTTPHEADER,
		array(
			'Content-Type: application/json',
			'X-Auth-Email: ' . $cf_api_email,
			'X-Auth-Key:' . $cf_api_key,
		)
	);
	curl_setopt(
		$ch,
		CURLOPT_POSTFIELDS,
		$json
	);

	// Receive server response ...
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

	// execute cURL
	$server_output = curl_exec( $ch );

	curl_close( $ch );
}

add_action( 'acf/options_page/save', 'general_settings_onsave', 10, 2 );
function general_settings_onsave( $post_id, $menu_slug ) {
	if ( 'global-settings' === $menu_slug ) {
		_purgeCF();
		return;
	}
}

function on_save_post( $post_id ) {

	// Find parent post_id.
	if ( $post_parent_id = wp_get_post_parent_id( $post_id ) ) {
		$post_id = $post_parent_id;
	}

	$post = get_post($post_id);

	if ($post->post_title === "Colby College Updates") {
		_purgeCF();
	}
}
add_action( 'save_post', 'on_save_post' );

add_filter( 'ppp_nonce_life', 'public_post_preview_time_window' );
function public_post_preview_time_window() {
	// one month
	return 2628288;
}

// Handles 404 for trying to visit category pages in the url, such as colby.edu/academics/news
function return_404_for_category_archives() {
    if (is_category()) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        nocache_headers();
        include(get_query_template('404'));
        exit();
    }
}
add_action('template_redirect', 'return_404_for_category_archives');

function exclude_specific_posts_from_algolia_index( $should_index, $post ) {
    // Array of post IDs to exclude
    $excluded_post_ids = array(7443, 7441); // Replace these IDs with the IDs of the posts you want to exclude

    if ( in_array( $post->ID, $excluded_post_ids ) ) {
        return false;
    }

    return $should_index;
}
add_filter( 'algolia_should_index_searchable_post', 'exclude_specific_posts_from_algolia_index', 10, 2 );


