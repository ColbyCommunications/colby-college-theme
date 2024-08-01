<?php

/**
 * Author Info block (parent).
 *
 * @param array  $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool   $is_preview True during backend preview render.
 * @param int    $post_id The post ID the block is rendering content against.
 *                     This is either the post ID currently being displayed inside a query loop,
 *                     or the post ID of the post hosting this block.
 * @param array $context The context provided to the block by the post or its parent block.
 */

// Support custom id values.
$block_id = '';
if ( ! empty( $block['anchor'] ) ) {
    $block_id = esc_attr( $block['anchor'] );
}

// Create class attribute allowing for custom "className".
$class_name = 'acf/advanced-accordion-panel';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}

/**
 * A template string of blocks.
 * Need help converting block HTML markup to an array?
 * 👉 https://happyprime.github.io/wphtml-converter/
 *
 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-templates/
 */
$inner_blocks_template = array(
    array(
        'core/column',
    ),
);

$allowed_blocks = ["acf/paragraph", "acf/image", "acf/image-text", "core/heading", "acf/table", "acf/embed", "core/embed" ];
?>

<?php if ( ! $is_preview ) : ?>
    <div
        <?php
        echo wp_kses_data(
            get_block_wrapper_attributes(
                array(
                    'id'    => $block_id,
                    'class' => esc_attr( $class_name ),
                )
            )
        );
        ?>
    >
<?php endif; ?>

<?php if ( is_admin() ) : ?>
    <div class="acf-block-fields acf-fields">
        <div class="acf-field">
            <div class="acf-label">
                <label>Accordion Panel</label>
            </div>
            <div style="border: dotted 1px #ccc; padding: 10px;">
                <InnerBlocks
                    class="demo-author-block-acf__innerblocks"
                    template=""
                    allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>"
                />
            </div>
        </div>
    </div>
<?php else : ?>
    <?php
    $context2 = Timber::context();
    $context2['heading'] = get_field('heading');
    $context2['single'] = $context['acf/fields'];

    // Render the block.
    Timber::render( 'src/twig/components/advanced-accordion-panel/advanced-accordion-panel.twig', $context2 );
    ?>
<?php endif; ?>

<?php if ( ! $is_preview ) : ?>
    </div>
<?php endif; ?>
