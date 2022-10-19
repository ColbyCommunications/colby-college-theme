<?php
/**
 * Colby offices directory archive
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
$context['archive_id'] = get_queried_object_id();

$offices_args = array(
  'posts_per_page' => -1,
  'post_type'=> 'offices',
  'order' => 'DESC',
  'orderby' => 'name',
);

$context['offices'] = Timber::get_posts($offices_args);

$templates         = array( 'archive-offices.twig' );

Timber::render( $templates, $context );