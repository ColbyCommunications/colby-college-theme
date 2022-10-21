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

/**
 * ===================================================
 * 
 * Colby Directory Import/Sync
 * 
 * ===================================================
 */

function updateStaffDirectory() {
   
    if (!is_admin()) {
        $directory_data = json_decode(file_get_contents(WP_CONTENT_DIR . "/directory_data/extra.json"), true)['Report_Entry'];
        // $directory_data = json_decode(file_get_contents(WP_CONTENT_DIR . "/directory_data/Colby_Directory_Webservice_Output.json"), true)['Report_Entry'];
        deletePeople($directory_data);
        getNewPeople($directory_data);
    }
}

function deletePeople($directory_data) {
    $args = array(
        'numberposts' => -1,
        'post_type'   => 'people',
        'post_status' => 'publish',
    );

    $all_posts   = get_posts($args);
    $total_posts = count($all_posts);

    if ($total_posts > 0) {
        foreach ($all_posts as $post) {
            // Get the employee_id meta value using the post ID
            $employee_id = get_post_meta($post->ID, 'employee_id', true);
            
            // Check if the employee id in the DB exists in the WD file
            $match = false;
   
            foreach ($directory_data as $WDPerson) {
                $WDEmployeeID = str_pad($WDPerson['employeeID'], 7, "0", STR_PAD_LEFT);
                if ($employee_id === $WDEmployeeID) {
                    $match = true;
                    break;
                }
            }
   
            // If record is missing from WD, delete the record in the DB
            if ($match !== true) {
                $thumb_id = get_post_thumbnail_id($post->ID);
                wp_delete_attachment($thumb_id, true);
                wp_delete_post($post->ID, true);
            }
        }
    }
}

// use phpseclib3\Crypt\RSA;
use phpseclib3\Net\SFTP;

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

function getNewPeople($directory_data) {
    //  $key  = PLATFORM_VARIABLES['sftp_pw'];
    $sftp = new SFTP('colby0.colby.edu');
    $sftp->login(PLATFORM_VARIABLES['sftp_username'], PLATFORM_VARIABLES['sftp_pw']);

    // Loop through the WD array
    foreach ($directory_data as $WDPerson) {
        // Assign variables to desired WD fields
        $WDEmployeeID    = str_pad($WDPerson['employeeID'], 7, "0", STR_PAD_LEFT);
        $WDPrefFirstName = $WDPerson['preferredFirstName'];
        $WDLastName      = $WDPerson['lastName'];

        // Skip person if no email associated
        if (!$WDPerson['primaryWorkEmail']) {
            continue;
        }

        $WDEmail = $WDPerson['primaryWorkEmail'];
        $WDTitle = $WDPerson['businessTitle'];
        $WDPhone = "";

        if (isset($WDPerson['primaryWorkPhone'])) {
            $WDPhone = $WDPerson['primaryWorkPhone'];
        }

        $WDBuilding = "";

        if (isset($WDPerson['workSpaceSuperiorLocation'])) {
            $WDBuilding = $WDPerson['workSpaceSuperiorLocation'];
        }
  
        $emailSlug = strtolower(substr($WDEmail, 0, strpos($WDEmail, "@")));

        /* Academic unit for faculty, Superior org for staff (department metadata) */

        $WDAcademicUnit = $WDPerson['Academic_Units'];
        $WDSupOrg       = $WDPerson['supervisoryOrganization'];
        $WDSOH          = $WDPerson['supervisoryOrgHierarchy'];
        $WDOrgsManaged  = $WDPerson['organizationsManaged'];
        $supOrgRegex    = '/.+?(?=[-|(])/';
        $orgResult;

        if (count(explode('>', $WDSOH)) === 2 || count(explode('>', $WDSOH)) === 3) {
            if (preg_match($supOrgRegex, $WDOrgsManaged)) {
                preg_match($supOrgRegex, $WDOrgsManaged, $matches);
                $orgResult = $matches[0];
            }
        } else {
            if (preg_match($supOrgRegex, $WDSupOrg)) {
                preg_match($supOrgRegex, $WDSupOrg, $matches);
                $orgResult = $matches[0];
            }
        }

        $WDDepartment = $WDAcademicUnit;
        if (is_null($WDAcademicUnit)) {
            $WDDepartment = $orgResult;
        }

        // Set api endpoint url with $emailSlug
        $url = 'https://www.colby.edu/endpoints/v1/profile/' . $emailSlug;

        // Initialize a CURL session.
        $ch = curl_init();
        // Return Page contents.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //grab URL and pass it to the variable.
        curl_setopt($ch, CURLOPT_URL, $url);
        $CXPerson = json_decode(curl_exec($ch), true);

        // Extract and assign desired fields from CX
        $CXEducation = "";
        if (isset($CXPerson['profedu']) && $CXPerson['profedu']['text']) {
            $CXEducation = '<h2>Education</h2>' . $CXPerson['profedu']['text'];
        }

        $CXExpertise      = "";
        $CXExpertiseArray = [];

        if (!empty($CXPerson['expertise1'])) {
            for ($i = 1; $i <= 20; $i++) {
                if (($CXPerson['expertise' . strval($i)])) {
                    $CXAOEValue = $CXPerson['expertise' . strval($i)];
                    array_push($CXExpertiseArray, '<li>' . '<p>' . $CXAOEValue . '</p>' . '</li>');
                }
            }

            $CXExpertiseLI = implode(" ", $CXExpertiseArray);
            $CXExpertise   = '<h2>Areas of Expertise</h2>' . '<ul>' . $CXExpertiseLI . '</ul>';
        }

        $CXCourses = "";
  
        if (isset($CXPerson['courses'])) {
            $CXCourses = $CXPerson['courses'];
        }

        $CXPersonalInfo = "";
        if (isset($CXPerson['profbio']) && $CXPerson['profbio']['text']) {
            $CXPersonalInfo = '<h2>Personal Information</h2>' . $CXPerson['profbio']['text'];
        }

        $CXCurrentResearch = "";
        if (isset($CXPerson['research']) && $CXPerson['research']['text']) {
            $CXCurrentResearch = '<h2>Current Research</h2>' . $CXPerson['research']['text'];
        }

        $CXPubs = "";
        if (isset($CXPerson['publicat']) && $CXPerson['publicat']['text']) {
            $CXPubs = '<h2>Publications</h2>' . $CXPerson['publicat']['text'];
        }

        // Concatenate all CX fields for bio
        $CXBio = $CXEducation . '<br><br>' . $CXExpertise . '<br><br>' . $CXPersonalInfo . '<br><br>' . $CXCurrentResearch . '<br><br>' . $CXPubs;

        $args = array(
            'numberposts' => -1,
            'post_type'   => 'people',
            'post_status' => 'publish',
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
            'post_title'   => $WDPrefFirstName . " " . $WDLastName,
            'post_content' => "",
            'post_type'    => 'people',
            'post_status'  => 'publish',
            'meta_input'   => array(
                'employee_id'     => $WDEmployeeID,
                'first_name'      => $WDPrefFirstName,
                'last_name'       => $WDLastName,
                'pronouns'        => "",
                'title'           => $WDTitle,
                'department'      => $WDDepartment,
                'phone'           => $WDPhone,
                'email'           => $WDEmail,
                'building'        => $WDBuilding,
                'curriculum_vitae'              => "",
                'bio'             => $CXBio,
                'current_courses' => json_encode($CXCourses),
            ),
        );

        $DBMatchingPost = get_posts($args);

        $photosWithDates = array_filter(
            $sftp->nlist('/web/staticweb/college/WorkdayPhotosTest'), function ($item) {
                return strpos($item, '.jpg') !== false;
            }
        );

        $matchingPhoto = false;
        if ((!$DBMatchingPost)) {
            $ID = wp_insert_post($post);

            foreach ($photosWithDates as $photo) {
                if (strpos($photo, $WDEmployeeID) !== false) {
                    $matchingPhoto = $photo;
                    break;
                }
            }

            if ($matchingPhoto) {
                $imageURL = 'https://colby.edu/college/WorkdayPhotosTest/' . $matchingPhoto;
                $desc     = $WDPrefFirstName . ' ' . $WDLastName;
                $image    = media_sideload_image($imageURL, $ID, $desc, 'id');
                set_post_thumbnail($ID, $image);
            }
        } else {
            $ID              = $DBMatchingPost[0]->ID;
            $person_metadata = get_post_meta($ID);

            // Update title metadata with latest title from WD
            update_post_meta($ID, 'title', $WDTitle);

            // Update courses metadata with latest courses from CX
            if ($CXCourses) {
                update_post_meta($ID, 'current_courses', json_encode($CXCourses));
            }

            // Update metadata for fields not changed in Gravity Forms with latest WD data
            if (empty($person_metadata['preferred_name_changed'])) {
                update_post_meta($ID, 'first_name', $WDPrefFirstName);
            }

            if (empty($person_metadata['phone_number_changed'][0])) {
                update_post_meta($ID, 'phone', $WDPhone);
            }

            if (empty($person_metadata['location_changed'][0])) {
                update_post_meta($ID, 'building', $WDBuilding);
            }

            if (empty($person_metadata['department_changed'][0])) {
                update_post_meta($ID, 'department', $WDDepartment);
            }

            if (empty($person_metadata['curriculum_vitae_changed'][0])) {
                update_post_meta($ID, 'curriculum_vitae', "");
            }

            if (empty($person_metadata['bio_changed'][0])) {
                update_post_meta($ID, 'bio', $CXBio);
            }

            if (empty($person_metadata['image_changed'][0]) && empty($person_metadata['remove_image_changed'][0])) {
                foreach ($photosWithDates as $photo) {
                    if (strpos($photo, $WDEmployeeID) !== false) {
                        $matchingPhoto = $photo;
                        break;
                    }
                }

                if ($matchingPhoto) {
                    $img_parts    = explode("_", $matchingPhoto);
                    $date         = substr($img_parts[1], 0, 8);
                    $imageURL     = 'https://colby.edu/college/WorkdayPhotosTest/' . $matchingPhoto;
                    $desc         = $WDPrefFirstName . ' ' . $WDLastName;
                    $DBImageName  = get_the_post_thumbnail_url($ID);
                    $DB_img_parts = explode("_", $DBImageName);
                    $DB_date      = substr($DB_img_parts[1], 0, 8);
                    
                    if ($date !== $DB_date) {
                        $thumb_id = get_post_thumbnail_id($ID);
                        wp_delete_attachment($thumb_id, true);
                        $image = media_sideload_image($imageURL, $ID, $desc, 'id');
                        set_post_thumbnail($ID, $image);
                    }
                }
            }
        }
    }
}

add_action('gform_after_submission', 'update_directory_profile', 10, 2);

function update_directory_profile($entry, $form) {

    /*
        1 - bio
        2- image
        5 - dept
        6 - phone #
        7 - location
        9 - CV
        10 - employee ID
        11 - pronouns
        12 - pref name
        13 - Remove image
    */

    $employee_id      = str_pad($entry[10], 7, "0", STR_PAD_LEFT);
    $preferred_name   = $entry[12];
    $pronouns         = $entry[11];
    $phone_number     = $entry[6];
    $location         = $entry[7];
    $department       = $entry[5];
    $image            = $entry[2];
    $remove_image     = $entry[13];
    $curriculum_vitae = $entry[9];
    $bio              = $entry[1];

    // get person post by employee ID
    $args = array(
        'post_type'  => 'people',
        'meta_query' => array(
            array(
                'key'     => 'employee_id',
                'value'   => $employee_id,
                'compare' => '=',
            ),
        ),
    );

    $person_post     = get_posts($args);
    $person_metadata = get_post_meta($person_post[0]->ID);

    $preferred_name_changed   = false;
    $pronouns_changed         = false;
    $phone_number_changed     = false;
    $location_changed         = false;
    $department_changed       = false;
    $curriculum_vitae_changed = false;
    $bio_changed              = false;
    $image_changed            = false;
    $remove_image_changed     = false;

    if ($preferred_name) {
    $preferred_name_changed = true;
    }

    if ($pronouns) {
    $pronouns_changed = true;
    }

    if ($phone_number) {
    $phone_number_changed = true;
    }

    if ($location) {
    $location_changed = true;
    }

    if ($department) {
    $department_changed = true;
    }

    if ($curriculum_vitae) {
    $curriculum_vitae_changed = true;
    }

    if ($bio) {
    $bio_changed = true;
    }

    if ($image) {
    $image_changed        = true;
    $remove_image_changed = false;
    }

    if ($remove_image === 'Yes') {
    $remove_image_changed = true;
    $image_changed        = false;
    }

    //update post
    $metaValues = array(
        'first_name'               => $preferred_name_changed ? $preferred_name : $person_metadata['first_name'][0],
        'pronouns'                 => $pronouns_changed ? $pronouns : $person_metadata['pronouns'][0],
        'phone'                    => $phone_number_changed ? $phone_number : $person_metadata['phone'][0],
        'building'                 => $location_changed ? $location : $person_metadata['building'][0],
        'department'               => $department_changed ? $department : $person_metadata['department'][0],
        'curriculum_vitae'                       => $curriculum_vitae_changed ? $curriculum_vitae : $person_metadata['curriculum_vitae'][0],
        'bio'                      => $bio_changed ? $bio : $person_metadata['bio'][0],

        // save override fields
        'preferred_name_changed'   => $preferred_name_changed,
        'pronouns_changed'         => $pronouns_changed,
        'phone_number_changed'     => $phone_number_changed,
        'location_changed'         => $location_changed,
        'department_changed'       => $department_changed,
        'curriculum_vitae_changed' => $curriculum_vitae_changed,
        'bio_changed'              => $bio_changed,
        'image_changed'            => $image_changed,
        'remove_image_changed'     => $remove_image_changed,
    );

    wp_update_post(
        array(
            'ID'         => $person_post[0]->ID,
            'post_title' => $preferred_name_changed ? $preferred_name . " " . $person_metadata['last_name'][0] : $person_metadata['first_name'][0] . " " . $person_metadata['last_name'][0],
            'meta_input' => $metaValues,
        )
    );

    $ID       = $person_post[0]->ID;
    $desc     = $employee_id;
    $thumb_id = get_post_thumbnail_id($ID);

    wp_delete_attachment($thumb_id, true);

    if ($image_changed) {
        $image = media_sideload_image($image, $ID, $desc, 'id');
        set_post_thumbnail($ID, $image);
    }
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
        'strikethrough',
        'image',
        'justifyleft',
        'justifycenter',
        'justifyright',
        'justifyfull',
        'hr',
    );
}

add_filter('gform_rich_text_editor_buttons', 'gravity_forms_buttons', 1, 1);

add_action('directory_sync', 'updateStaffDirectory');

if (! wp_next_scheduled('directory_sync')) {
    $time = strtotime('today');
    wp_schedule_event($time, 'daily', 'directory_sync');
}