<?php
/**
 * Colby directory archive
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
$context['posts'] = Timber::get_posts();
$context['foo']   = 'bar';
$context['archive_id'] = get_queried_object_id();

$people_args = Timber::get_posts(array(
  'posts_per_page' => -1,
  'post_type'=> 'people',
  'order' => 'ASC',
  'orderby' => 'name',
));

$people = [];
foreach ($people_args->to_array() as $person) {
  $title = get_post_meta($person->ID, 'title');
  $person->business_title = $title[0];
  array_push($people, $person);
}

$context['people'] = $people;

$templates         = array( 'archive-people.twig' );

Timber::render( $templates, $context );