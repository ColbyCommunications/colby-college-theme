<?php

$block_id = '';
if ( ! empty( $block['anchor'] ) ) {
    $block_id = esc_attr( $block['anchor'] );
}

$class_name = 'acf/advanced-accordion';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}

$allowed_blocks = ['acf/advanced-accordion-panel'];
$inner_blocks_template = array(
    array(
        'core/column',
    ),
);
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
<!-- Editor view -->
    <div class="acf-block-fields acf-fields">
        <div class="acf-field">
            <div class="acf-label">
                <label>Advanced Accordion</label>
            </div>
            <div>
                <InnerBlocks
                    template=""
                    allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>"
                />
            </div>
        </div>
    </div>

<?php else : ?>
    <!-- Front end view -->
    <?php
    $context = Timber::context();
    $context['single'] = true;

    $data = [
        'single' => $context['single']
    ];

    // Render the block.
    Timber::render( 'src/twig/components/advanced-accordion/advanced-accordion.twig', $data );
    ?>
<?php endif; ?>

<?php if ( ! $is_preview ) : ?>
    </div>
<?php endif; ?>
