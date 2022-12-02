<template>
    <div ref="accordion">
        <slot :toggleActive="toggleActive" :active="active" />
    </div>
</template>

<script>
    export default {
        name: 'accordion',
        data() {
            return {
                active: [],
            };
        },
        props: {
            single: {
                type: Boolean,
                required: false,
                default: false,
            },
        },
        methods: {
            toggleActive(b, e) {
                const panel = e.target.closest('[data-accordion-panel]');
                const panelSiblings = this.getSiblings(panel);
                const window = panel.querySelector('[data-accordion-window]');
                const content = panel.querySelector('[data-accordion-content]');
                const iconOpen = panel.querySelector('[data-accordion-icon-open]');
                const iconClosed = panel.querySelector('[data-accordion-icon-closed]');

                if (this.active.includes(b)) {
                    this.active = this.active.filter((item) => item !== b);
                    iconClosed.setAttribute('style', 'display: inline');
                    iconOpen.setAttribute('style', 'display: none');
                } else {
                    this.active.push(b);
                    iconClosed.setAttribute('style', 'display: none');
                    iconOpen.setAttribute('style', 'display: inline');
                }

                this.active.includes(b)
                    ? window.setAttribute(
                          'style',
                          `height: ${content.offsetHeight}px; visibility: visible;`
                      )
                    : window.setAttribute('style', 'height: 0; visibility: hidden;');

                if (this.single == 1) {
                    this.active = this.active.filter((item) => item == b);
                    panelSiblings
                        .filter((item) => item.localName !== 'code')
                        .forEach((sibling) => {
                            const siblingWindow = sibling.querySelector('[data-accordion-window]');
                            const siblingIconClosed = sibling.querySelector(
                                '[data-accordion-icon-closed]'
                            );
                            const siblingIconOpen = sibling.querySelector(
                                '[data-accordion-icon-open]'
                            );
                            siblingIconClosed.setAttribute('style', 'display: inline');
                            siblingIconOpen.setAttribute('style', 'display: none');
                            siblingWindow.setAttribute('style', 'height: 0; visibility: hidden;');
                        });
                }
            },
            getSiblings(element) {
                let siblings = [];
                let sibling = element.parentNode.firstChild;

                while (sibling) {
                    if (sibling.nodeType === 1 && sibling !== element) {
                        siblings.push(sibling);
                    }
                    sibling = sibling.nextSibling;
                }

                return siblings;
            },
        },
    };
</script>

<style lang="scss">
    @import './_accordion.scss';
</style>
