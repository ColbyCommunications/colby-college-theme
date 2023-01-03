<template>
    <div class="text-group--animated" ref="container">
        <slot />
    </div>
</template>

<script>
    import gsap from 'gsap';
    import Letterize from 'letterizejs';
    import 'waypoints/lib/noframework.waypoints';

    export default {
        data() {
            return {
                subheading: undefined,
                heading: undefined,
                waypoint: undefined,
            };
        },
        mounted() {
            const component = this;
            const subheading = this.$refs.container.querySelector('.text-group__subheading');
            const paragraph = this.$refs.container.querySelector('.text-group__p');
            let heading = this.$refs.container.querySelector('.text-group__heading');
            let headingText;

            if (subheading) {
                this.subheading = new Letterize({
                    targets: subheading,
                });
            }

            if (heading) {
                headingText = heading.innerHTML.split(' ');
                heading.innerHTML = '';

                headingText.forEach((word) => {
                    let wordWrap = document.createElement('span');
                    wordWrap.innerHTML = word;

                    heading.append(wordWrap);
                    heading.append(' ');
                });
            }

            this.waypoint = new Waypoint({
                element: this.$refs.container,
                handler() {
                    if (subheading) {
                        component.animateSubheading();
                    } else if (heading) {
                        component.animateHeading();
                        component.animateParagraph();
                    } else if (paragraph) {
                        component.animateParagraph();
                    }

                    this.destroy();
                },
                offset: 'bottom-in-view',
            });
        },
        methods: {
            animateSubheading() {
                const target = this.$refs.container.querySelectorAll(
                    '.text-group__subheading span'
                );

                gsap.to(target, {
                    duration: 0.2,
                    stagger: 0.04,
                    opacity: 1,
                    y: 0,
                    ease: 'power3.easeInOut',
                    onComplete: () => {
                        this.animateHeading();
                        this.animateParagraph();
                    },
                });
            },
            animateHeading() {
                const target = this.$refs.container.querySelectorAll('.text-group__heading span');

                gsap.to(target, {
                    duration: 0.2,
                    stagger: 0.1,
                    opacity: 1,
                    y: 0,
                    ease: 'power3.easeInOut',
                });
            },
            animateParagraph() {
                const target = this.$refs.container.querySelectorAll('p');

                gsap.to(target, {
                    delay: 0.4,
                    duration: 0.4,
                    stagger: 0.08,
                    opacity: 1,
                    ease: 'Circ.easeIn',
                });
            },
        },
    };
</script>

<style lang="scss">
    .text-group--animated {
        .text-group__subheading span {
            display: inline-block;
            opacity: 0;
            transform: translate(0, 5px);
        }

        .text-group__heading span {
            display: inline-block;
            opacity: 0;
            transform: translate(0, 10px);
        }

        .text-group__p {
            opacity: 0;
        }
    }
</style>
