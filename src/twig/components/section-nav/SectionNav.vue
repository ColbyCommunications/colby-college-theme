<template>
    <div 
        class="section-nav--animated" 
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
            // 1. Detect bot status from the global window variable
            this.isBot = window?.colby?.DISABLE_ANIMATIONS === true;

            // 2. If it's a bot, we stop here and don't initialize Waypoints or GSAP
            if (this.isBot || !this.$refs.container) return;

            const component = this;
            this.waypoint = new Waypoint({
                element: this.$refs.container,
                handler() {
                    component.animateButtons();
                },
                offset: 'bottom-in-view',
            });
        },
        methods: {
            animateButtons() {
                // Double check bot status before running GSAP
                if (this.isBot) return;

                const target = this.$refs.container.querySelectorAll('li');

                gsap.to(target, {
                    delay: 0.6,
                    duration: 0.4,
                    stagger: 0.1,
                    opacity: 1,
                    x: 0,
                    ease: 'power3.easeIn',
                });
            },
        },
    };
</script>

<style lang="scss">
    .section-nav--animated {
        li {
            opacity: 0;
            transform: translate(20px, 0);
        }
    }

    // 3. CSS override for bots: forces immediate visibility of all slotted list items
    .is-bot-visitor {
        &.section-nav--animated li {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }
    }
</style>