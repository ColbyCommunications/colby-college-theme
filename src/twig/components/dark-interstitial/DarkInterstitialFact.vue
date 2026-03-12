<template>
    <div 
        class="dark-interstitial__fact--animated" 
        ref="container"
        :class="{ 'is-bot-visitor': isBot }"
    >

        <slot />
    </div>
</template>

<script>
    import { gsap } from 'gsap';
    import 'waypoints/lib/noframework.waypoints';

    export default {
        data() {
            return {
                isBot: false,
                waypoint: null
            };
        },
        mounted() {
            // 1. Set bot status from the global configuration
            this.isBot = window?.colby?.DISABLE_ANIMATIONS === true;

            // 2. If it is a bot, exit early to prevent Waypoint/GSAP initialization
            if (this.isBot || !this.$refs.container) return;

            const component = this;

            this.waypoint = new Waypoint({
                element: this.$refs.container,
                handler() {
                    component.animateFact();
                },
                offset: 'bottom-in-view',
            });
        },
        methods: {
            animateFact() {
                // Secondary check for bot status
                if (this.isBot) return;

                const heading = this.$refs.container.querySelector('h3');
                const paragraph = this.$refs.container.querySelector('p');

                gsap.to(heading, {
                    duration: 0.4,
                    opacity: 1,
                    scale: 1,
                    ease: 'power3.easeInOut',
                    onComplete: () => {
                        gsap.to(paragraph, {
                            opacity: 1,
                        });
                    },
                });
            },
        },
    };
</script>

<style lang="scss">
    .dark-interstitial__fact--animated {
        h3 {
            display: inline-block;
            opacity: 0;
            transform: scale(0.6);
        }

        p {
            opacity: 0;
        }
    }


    // 3. Forced visibility for crawlers (Google, Siteimprove, etc.)
    .is-bot-visitor {
        &.dark-interstitial__fact--animated {
            h3 {
                opacity: 1 !important;
                transform: scale(1) !important;
            }

            p {
                opacity: 1 !important;
            }
        }
    }

</style>
