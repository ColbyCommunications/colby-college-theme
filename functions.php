<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
    require_once $composer_autoload;
    $timber = new Timber\Timber();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

    add_action(
        'admin_notices',
        function() {
            echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
        }
    );

    add_filter(
        'template_include',
        function( $template ) {
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
  'src/twig/components'
);

$theme_base = get_template_directory() . '/';

$component_dirs = array_map(
    function($str) use (&$theme_base) {
        return str_replace($theme_base, '', $str);
    },
    array_filter(glob($theme_base . 'src/twig/components/*'), 'is_dir')
);

Timber::$dirname = array_merge($static_timber_dirs, $component_dirs);


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
    /** Add timber support. */
    public function __construct() {
        add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
        add_filter( 'timber/context', array( $this, 'add_to_context' ) );
        add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        add_action( 'acf/init', array( $this, 'my_acf_init') );
        parent::__construct();
    }
    /** This is where you can register custom post types. */
    public function register_post_types() {
        register_post_type(
            'people',
            [
                'labels' => [
                    'name'               => __( 'People' ),
                    'singular_name'      => __( 'Person' ),
                    'add_new_item'       => __( 'Add Person' ),
                    'edit_item'          => __( 'Edit Person' ),
                    'new_item'           => __( 'New Person' ),
                    'view_item'          => __( 'View Person' ),
                    'search_items'       => __( 'Search People' ),
                    'not_found'          => __( 'Person not found.' ),
                    'not_found_in_trash' => __( 'No Person found in trash.' ),
                ],
                'rewrite'     => array( 'slug' => 'people/people-directory' ),
                'public'        => true,
                'has_archive' => true,
                'show_in_rest' => false,
                'menu_icon'     => 'dashicons-id-alt',
                'show_in_nav_menus' => true,
                'supports'      => ['title', 'editor', 'revisions', 'excerpt', 'thumbnail'],
            ]
        );

        // register_post_type(
        //     'offices',
        //     [
        //         'labels' => [
        //             'name'               => __( 'Offices' ),
        //             'singular_name'      => __( 'Office' ),
        //             'add_new_item'       => __( 'Add Office' ),
        //             'edit_item'          => __( 'Edit Office' ),
        //             'new_item'           => __( 'New Office' ),
        //             'view_item'          => __( 'View Office' ),
        //             'search_items'       => __( 'Search Offices' ),
        //             'not_found'          => __( 'Office not found.' ),
        //             'not_found_in_trash' => __( 'No Office found in trash.' ),
        //         ],
        //         'rewrite'     => array( 'slug' => 'people/offices-directory' ),
        //         'public'        => true,
        //         'has_archive' => true,
        //         'show_in_rest' => true,
        //         'menu_icon'     => 'dashicons-admin-multisite',
        //         'show_in_nav_menus' => true,
        //         'supports'      => ['title', 'editor', 'revisions', 'excerpt', 'thumbnail'],
        //     ]
        // );
    }

    /** This is where you can register custom taxonomies. */
    public function register_taxonomies() {
        register_taxonomy(
            'page-categories',
            'page',
            array(
                'hierarchical' => true,
                'show_ui' => true,
                'show_in_rest' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'public' => true,
                'show_tagcloud' => false,
                'capabilities' => [
                    'manage_terms' => 'manage_options',
                    'edit_terms' => 'manage_options',
                    'delete_terms' => 'manage_options',
                    'assign_terms' => 'manage_options',
                ],
                'rewrite' => array(
                    'slug' => 'page-categories'
                ),
                'labels' => array(
                    'name' =>  __('Categories'),
                    'singular_name' =>  __('Category'),
                    'add_new_item' => __( 'Add New Category' ),
                    'menu_name' => __('Categories'),
                ),
            )
        );

        register_taxonomy(
            'groups',
            'people',
            array(
                'hierarchical' => true,
                'show_ui' => true,
                'show_in_rest' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'public' => true,
                'show_tagcloud' => false,
                'capabilities' => [
                    'manage_terms' => 'manage_options',
                    'edit_terms' => 'manage_options',
                    'delete_terms' => 'manage_options',
                    'assign_terms' => 'manage_options',
                ],
                'rewrite' => array( 
                    'slug' => 'people/groups',
                    'hierarchical' => true
                ),
                'labels' => array(
                    'name' =>  __('Groups'),
                    'singular_name' =>  __('Group'),
                    'add_new_item' => __( 'Add New Group' ),
                    'menu_name' => __('Groups'),
                    'parent_item' => __('Parent Group'),
                ),
            )
        );
    }

    public function my_acf_init() {
        
        // check function exists
        if( function_exists('acf_register_block') ) {
            
            // register a accordion block
            acf_register_block(array(
                'name'				=> 'accordion',
                'title'				=> __('Accordion'),
                'description'		=> __('A custom accordion block.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'accordion', 'panels' ),
            ));

            // register a home hero
            acf_register_block(array(
                'name'				=> 'home-hero',
                'title'				=> __('Home Hero'),
                'description'		=> __('Large hero component, supporting both video and basic images.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'cover-image',
                'keywords'			=> array( 'hero', 'video', 'image', 'home', 'loop' ),
            ));

            // register a carousel
            acf_register_block(array(
                'name'				=> 'carousel',
                'title'				=> __('Carousel'),
                'description'		=> __('Standard carousel component section.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'images-alt2',
                'keywords'			=> array( 'carousel', 'slider', 'image', 'news' ),
            ));

            // register a featured post
            acf_register_block(array(
                'name'				=> 'featured-post',
                'title'				=> __('Featured Post'),
                'description'		=> __('Large image layout for featuring important content.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'format-image',
                'keywords'			=> array( 'featured', 'content', 'image', 'news', 'events', 'post' ),
            ));

            // register dark interstitial
            acf_register_block(array(
                'name'				=> 'dark-interstitial',
                'title'				=> __('Dark Interstitial'),
                'description'		=> __('Navy colored divider component, typically utilized to highlight facts and figures or breakup page monotony.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'align-center',
                'keywords'			=> array( 'featured', 'content', 'image', 'divider', 'dark' ),
            ));

            // register facts and figures
            acf_register_block(array(
                'name'				=> 'facts-figures',
                'title'				=> __('Facts & Figures'),
                'description'		=> __('Informational section supporting both an image and supplementary factoids.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'align-pull-left',
                'keywords'			=> array( 'featured', 'facts', 'figures', 'numbers', 'image' ),
            ));

            // register testimonial carousel
            acf_register_block(array(
                'name'				=> 'testimonial-carousel',
                'title'				=> __('Testimonial Carousel'),
                'description'		=> __('Testimonial section with carousel functionality and image support.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'format-quote',
                'keywords'			=> array( 'featured', 'testimonial', 'quote', 'carousel', 'image' ),
            ));

            // register full bleed image
            acf_register_block(array(
                'name'				=> 'full-bleed-image',
                'title'				=> __('Full Bleed Image'),
                'description'		=> __('Large image section with caption for full width pages.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'cover-image',
                'keywords'			=> array( 'image', 'large', 'full' ),
            ));

            // register related content
            acf_register_block(array(
                'name'				=> 'related-content',
                'title'				=> __('Related Content'),
                'description'		=> __('Section typically found at the bottom of full-width page styles for links to supplementary pages.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'columns',
                'keywords'			=> array( 'related', 'content' ),
            ));

            // register hero
            acf_register_block(array(
                'name'				=> 'hero',
                'title'				=> __('Hero'),
                'description'		=> __('Traditional hero component typically deployed on all first-level landing pages.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'hero', 'image', 'heading' ),
            ));

            // register overlay hero
            acf_register_block(array(
                'name'				=> 'overlay-hero',
                'title'				=> __('Overlay Hero'),
                'description'		=> __('Dynamic hero component typically deployed on all first-level landing pages, featuring text overlay.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'hero', 'image', 'heading', 'landscape' ),
            ));

            // register section nav
            acf_register_block(array(
                'name'				=> 'section-nav',
                'title'				=> __('Section Navigation'),
                'description'		=> __('Top level landing page subnavigation, typically found below the hero. Used for navigation within a site subdivision.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'list', 'section', 'sub', 'navigation' ),
            ));

            // register article grid
            acf_register_block(array(
                'name'				=> 'article-grid',
                'title'				=> __('Article Grid'),
                'description'		=> __('Layout dedicated to article display. Features several column layout styles and supports varying image sizes.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'layout', 'article', 'grid', 'image' ),
            ));

            // register bordered article row
            acf_register_block(array(
                'name'				=> 'bordered-article-row',
                'title'				=> __('Bordered Article Row'),
                'description'		=> __('Special article grid layout featuring borders.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'layout', 'bordered', 'article', 'row' ),
            ));

            // register article section
            acf_register_block(array(
                'name'				=> 'article-section',
                'title'				=> __('Article Section'),
                'description'		=> __('Layout dedicated to article display. Features several variants including a carousel.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'layout', 'article', 'section', 'carousel' ),
            ));

            // register media context
            acf_register_block(array(
                'name'				=> 'media-context',
                'title'				=> __('Media Context'),
                'description'		=> __('Traditional side-by-side layout for any style of media with cooresponding context.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'media', 'image', 'context', 'layout' ),
            ));

            // register hw image section
            acf_register_block(array(
                'name'				=> 'hw-image-section',
                'title'				=> __('Half-Width Image Section'),
                'description'		=> __('Traditional side-by-side layout for any style of media with cooresponding context but in a nearly full-width display.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'media', 'image', 'context', 'layout', 'half', 'width' ),
            ));

            // list section
            acf_register_block(array(
                'name'				=> 'list-section',
                'title'				=> __('List Section'),
                'description'		=> __('Basic layout and section dedicated for the display of groups of lists.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'list', 'section', 'unordered', 'layout' ),
            ));

            // register table
            acf_register_block(array(
                'name'				=> 'table',
                'title'				=> __('Table'),
                'description'		=> __('Standard table layout with Colby API integrations for several data types.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'table', 'row', 'column', 'layout' ),
            ));

            // register image grid
            acf_register_block(array(
                'name'				=> 'image-grid',
                'title'				=> __('Image Grid'),
                'description'		=> __('Layout dedicated to a bevy of image layout styles. Supports captions.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'image', 'row', 'column', 'layout', 'grid', 'caption' ),
            ));

            // register list context
            acf_register_block(array(
                'name'				=> 'list-context',
                'title'				=> __('List Context'),
                'description'		=> __('Standard context component with accompanying large list.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'layout', 'list', 'context' ),
            ));

            // register overlay wide image
            acf_register_block(array(
                'name'				=> 'overlay-wide-image',
                'title'				=> __('Overlay Wide Image'),
                'description'		=> __('Landscape image layout with context overlayed on a dimmed background.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'layout', 'image', 'overlay', 'landscape' ),
            ));

            // register context article grid
            acf_register_block(array(
                'name'				=> 'context-article-grid',
                'title'				=> __('Context Article Grid'),
                'description'		=> __('Article Grid accompanied by context, with the ability to pull posts directly.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'layout', 'context', 'article', 'grid' ),
            ));

            // register stat group
            acf_register_block(array(
                'name'				=> 'stat-group',
                'title'				=> __('Stat Group'),
                'description'		=> __('Display style component for a group of statistics with optional context.'),
                'render_callback'	=> 'my_acf_block_render_callback',
                'category'			=> 'layout',
                'icon'				=> 'block-default',
                'keywords'			=> array( 'layout', 'context', 'stat', 'grid', 'group' ),
            ));
        }
    }

    /** This is where you add some context
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function add_to_context( $context ) {
        $context['post'] = Timber::get_post();

        global $wp;

        $crumbs = get_post_ancestors($context['post']->ID);

        if ($crumbs) {
            $breadcrumbs_menu = [];

            foreach ($crumbs as $ancestor) {
                array_push($breadcrumbs_menu, array(
                    'id' => $ancestor,
                    'title' => get_the_title($ancestor),
                    'url' => get_permalink($ancestor)
                ));
            }
        }

        if (isset($breadcrumbs_menu)) {
            $context['breadcrumbs_menu'] = array_reverse($breadcrumbs_menu);
        }

        $context['current_url'] = home_url( $wp->request );
        $context['foo']   = 'bar';
        $context['stuff'] = 'I am a value set in your functions.php file';
        $context['notes'] = 'These values are available everytime you call Timber::context();';
        $context['menu']  = new Timber\Menu();
        $context['main_menu']  = new Timber\Menu( 3 );
        $context['utility_menu']  = new Timber\Menu( 4 );
        $context['footer_menu']  = new Timber\Menu( 5 );
        $context['action_menu']  = new Timber\Menu( 6 );
        $context['people_menu']  = new Timber\Menu( 11 );
        $context['global_address'] = get_field('address', 'options');
        $context['global_phone'] = get_field('phone', 'options');
        $context['global_social'] = get_field('social_media', 'options');
        $context['site']  = $this;
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

        add_image_size('Square', 600, 600, true);
        add_image_size('Square_mobile', 300, 300, true);
        add_image_size('Rectangle', 760, 430, true);
        add_image_size('Rectangle_mobile', 410, 290, true);
        add_image_size('Landscape', 860, 400, true);
        add_image_size('Landscape_mobile', 430, 200, true);
        add_image_size('Portrait', 380, 580, true);
        add_image_size('Portrait_mobile', 190, 290, true);
        add_image_size('Hero', 1280, 700, true);

        if( function_exists('acf_add_options_page') ) {
            acf_add_options_page(array(
                'page_title' 	=> 'Global Settings',
                'menu_title'	=> 'Global Settings',
                'menu_slug' 	=> 'global-settings',
                'capability'	=> 'edit_posts',
                'redirect'		=> false
            )); 
        }
    }

    /** This Would return 'foo bar!'.
     *
     * @param string $text being 'foo', then returned 'foo bar!'.
     */
    public function myfoo( $text ) {
        $text .= ' bar!';
        return $text;
    }

    /** This is where you can add your own functions to twig.
     *
     * @param string $twig get extension.
     */
    public function add_to_twig( $twig ) {
        $twig->addExtension( new Twig\Extension\StringLoaderExtension() );

        // adding a 'path' filter to match fractals 'path'
        $twig->addFilter( new Twig\TwigFilter( 'path', function($string) {
          return get_template_directory_uri() . '/dist' . $string;
        }));

        return $twig;
    }

}

new StarterSite();

/**
 * Enqueue scripts and styles.
 */
function theme_scripts() {
    wp_enqueue_style( 'hvh', get_template_directory_uri() . '/dist/styles/scripts.css', array(), date("H:i:s"));
    wp_enqueue_style( 'tailwind', get_template_directory_uri() . '/dist/styles/tailwind.css', array(), date("H:i:s"));
    wp_enqueue_script( 'main', get_template_directory_uri() . '/dist/scripts.js', array(), date("H:i:s"), true);
}
add_action( 'wp_enqueue_scripts', 'theme_scripts' );

/**
 *  This is the callback that displays the block.
 *
 * @param   array  $block      The block settings and attributes.
 * @param   string $content    The block content (emtpy string).
 * @param   bool   $is_preview True during AJAX preview.
 */
function my_acf_block_render_callback( $block, $content = '', $is_preview = false ) {
    $context = Timber::context();

    // Store block values.
    $context['block'] = $block;

    // Store field values.
    $context['fields'] = get_fields();

    // Store $is_preview value.
    $context['is_preview'] = $is_preview;

    $context['block_name'] = substr($block['name'], 4);

    $context['recent_people'] = Timber::get_posts(array(
        'post_type' => 'people',
        'posts_per_page' => 3,
    ));

    $context['departments'] = Timber::get_posts(array(
        'post_type' => 'page',
        'posts_per_page' => -1,
        'tax_query' => array(
            array (
                'taxonomy' => 'page-categories',
                'field' => 'term_id',
                'terms' => 18,
            )
        )
    ));

    $context['offices'] = Timber::get_posts(array(
        'post_type' => 'page',
        'posts_per_page' => -1,
        'tax_query' => array(
            array (
                'taxonomy' => 'page-categories',
                'field' => 'term_id',
                'terms' => 19,
            )
        )
    ));

    // Store latest 3 posts and then merging it into the fields object before rendering component
    // Check for whether or not block is a related-articles block should be placed here for optimization
    if( $context['block_name'] == 'context-article-grid' ) {
        $context_merged = array_merge($context['fields'], array(
            'recent_people' => $context['recent_people']
        ));
    } elseif( $context['block_name'] == 'table' ) {
        $context_merged = array_merge($context['fields'], array(
            'departments' => $context['departments'],
            'offices' => $context['offices']
        ));
    } else {
        $context_merged = $context['fields'];
    }

    // Render the block.
    Timber::render( 'src/twig/components/'. $context['block_name'] . '/' . $context['block_name'] . '.twig', $context_merged );
}