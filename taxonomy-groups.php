<?php
/**
 * Colby groups template
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$context          = Timber::context();
$context['posts'] = new Timber\PostQuery();
$context['foo']   = 'bar';
$context['title'] = single_term_title('', false);

$term_id = get_queried_object()->term_id;
$term_slug = get_queried_object()->slug;
$ancestors = array_reverse(get_ancestors( $term_id, 'groups' ));

// $ancestors[0] ? $top_term_id = $ancestors[0] : $top_term_id = $term_id;

if (count($ancestors) > 0) {
  $top_term_id = $ancestors[0];
} else {
  $top_term_id = $term_id;
}

$term = get_term( $top_term_id, 'groups' );

$child_ids = get_term_children($term->term_id, 'groups');
$term_children = [];

foreach ($child_ids as $child_id) {
  $newterm = get_term($child_id);

  array_push($term_children, array(
    'id' => $child_id,
    'post_title' => $newterm->name,
    'guid' => get_term_link($child_id)
  ));
}

$parent_map = array(
  'id' => $term->term_id,
  'title' => $term->name,
  'children' => $term_children,
  );

$context['alpha_parent'] = $parent_map;

if ($term_slug == 'office-of-the-president') {
  $timber_post     = new Timber\Post(612);
  $context['post'] = $timber_post;
  $context['page_blocks'] = parse_blocks($timber_post->post_content);

  Timber::render( 'page_with-sidebar.twig', $context );
} else {
  $templates = array( 'taxonomy-groups-' . $term_slug . '.twig', 'taxonomy-groups.twig' );

  Timber::render( $templates, $context );
}
