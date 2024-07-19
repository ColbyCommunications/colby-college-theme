<?php

$block_id = '';
if ( ! empty( $block['anchor'] ) ) {
    $block_id = esc_attr( $block['anchor'] );
}

$class_name = 'acf/accordion-test';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}

$allowed_blocks = ['acf/panel-test'];
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
    $context['single'] = get_field('single');

    $data = [
        'single' => $context['single']
    ];

    // Render the block.
    Timber::render( 'src/twig/components/accordion-test/accordion-test.twig', $data );
    ?>
<?php endif; ?>

<?php if ( ! $is_preview ) : ?>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.toggleAccordion = function(event) {
            const button = event.currentTarget;
            const panel = button.closest('[data-accordion-panel]'); 
            const window = panel.querySelector('[data-accordion-window]');
            const content = panel.querySelector('[data-accordion-content]');
            const siblings = getSiblings(panel);
            const isSingle = panel.getAttribute('data-single');
            console.log(event.target);

            if (window.style.visibility === 'hidden' || window.offsetHeight === 0) {
            // Expand accordion
            window.style.height = content.offsetHeight + 'px';
            window.style.visibility = 'visible';
            // Optionally, add classes or styles to indicate expanded state
            } else {
            // Collapse accordion
            window.style.height = '0';
            window.style.visibility = 'hidden';
            // Optionally, remove classes or styles for collapsed state
            }

            // Collapse other panels if required
            siblings.forEach((sibling) => {
            const siblingWindow = sibling.querySelector('[data-accordion-window]');
            siblingWindow.style.height = '0';
            siblingWindow.style.visibility = 'hidden';
            // Optionally, adjust styles for other panels
            });
        }

        function getSiblings(element) {
            let siblings = [];
            let sibling = element.parentNode.firstChild;

            while (sibling) {
            if (sibling.nodeType === 1 && sibling !== element) {
                siblings.push(sibling);
            }
            sibling = sibling.nextSibling;
            }

            return siblings;
        };
    });
</script>

