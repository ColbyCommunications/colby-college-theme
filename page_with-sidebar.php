<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/templates/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * Template Name: Page with Sidebar
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

$timber_post            = new Timber\Post();
$context['post']        = $timber_post;
$context['page_blocks'] = parse_blocks( $timber_post->post_content );

if ( has_term( array( 'department', 'office', 'site' ), 'page-categories' ) ) {
	$parent = $post->ID;
} else {
	if ( $post->post_parent ) {
		$ancestors = get_post_ancestors( $post->ID );

		$ancestor_found = false;

		foreach ( $ancestors as $ancestor ) {
			if ( has_term( array( 'department', 'office', 'site' ), 'page-categories', $ancestor ) ) {
				$parent         = $ancestor;
				$ancestor_found = true;
			}
		}

		if ( ! $ancestor_found ) {
			$root   = count( $ancestors ) - 1;
			$parent = $ancestors[ $root ];
		}
	} else {
		$parent = $post->ID;
	}
}

if ( has_term( 'office', 'page-categories' ) ) {
	$template = 'single-office.twig';
} else {
	$template = 'page_with-sidebar.twig';
}

if ( get_post( $post->post_parent )->post_name == 'people' ) {
	$menu_items = wp_get_nav_menu_items( 'People Menu' );

	$parent_map = array(
		'id'        => $parent,
		'title'     => get_the_title( $parent ),
		'permalink' => get_permalink( $parent ),
		'children'  => $menu_items,
		'menu'      => true,
	);
} else {
	$parent_map = array(
		'id'        => $parent,
		'title'     => get_the_title( $parent ),
		'permalink' => get_permalink( $parent ),
		'children'  => get_pages(
			array(
				'parent'      => $parent,
				'sort_column' => 'menu_order',
			)
		),
	);
}

$context['alpha_parent'] = $parent_map;

Timber::render( $template, $context );
