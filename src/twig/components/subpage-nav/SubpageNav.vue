<template>
    <div
        class="subpage-nav md:space-y-5 bg-white md:bg-transparent border md:border-0 border-solid border-indigo-300 rounded-md"
        @click="toggleMenu"
    >
        <div class="subpage-nav--animated" ref="container">
            <h2>
                <div
                    class="relative w-full px-6 md:px-0 py-4 md:py-0 font-extended font-bold text-14 md:text-12 leading-130 text-azure tracking-8 uppercase text-left"
                >
                    <a :href="parentpermalink" class="pointer-events-none md:pointer-events-auto"
                        >{{ heading }}
                        <div class="absolute top-6 right-8 md:hidden w-1.5">
                            <span
                                class="relative block w-6 h-0.5 bg-azure transition-all duration-200 ease-in-out"
                                :class="{ 'bg-transparent': menuOpen }"
                            >
                                <span
                                    class="absolute left-0 w-full h-full bg-azure origin-center top-[-6px] transition-all duration-200 ease-in-out"
                                    :class="{ '!top-0 rotate-45': menuOpen }"
                                ></span>
                                <span
                                    class="absolute left-0 w-full h-full bg-azure origin-center top-[6px] transition-all duration-200 ease-in-out"
                                    :class="{ '!top-0 rotate-[-45deg]': menuOpen }"
                                ></span>
                            </span>
                        </div>
                    </a>
                </div>
            </h2>
            <ul
                class="subpage-nav__items md:block py-4 md:py-0 border-t md:border-t-0 border-solid border-indigo-200 mt-0 md:mt-6"
                :class="{ hidden: !menuOpen }"
            >
                <li
                    v-for="(item, index) in items"
                    :key="index"
                    class="font-body text-14 md:text-10 font-normal md:font-medium leading-140 text-indigo-800 md:mt-2"
                >
                    <a
                        class="block py-1.5 md:py-1 px-6 md:px-0 md:pl-2.5 hover:bg-indigo-200 md:border-l-2 border-solid transition-all duration-200 ease-in-out"
                        :class="{
                            ['text-gray-1000 border-indigo']: item.active,
                            ['text-indigo-800 hover:text-indigo hover:underline border-transparent']:
                                !item.active,
                        }"
                        :href="item.url"
                        >{{ item.title }}</a
                    >
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import { gsap } from 'gsap';
    import 'waypoints/lib/noframework.waypoints';

    export default {
        data() {
            return {
                menuOpen: false,
                waypoint: undefined,
            };
        },
        mounted() {
            const component = this;

            this.waypoint = new Waypoint({
                element: this.$refs.container,
                handler() {
                    component.animateButtons();
                },
                offset: this.$refs.container.getBoundingClientRect(),
                // offset: 'bottom-in-view',
            });
        },
        props: {
            heading: {
                type: String,
                required: false,
            },
            items: {
                type: Array,
                required: false,
            },
            parentpermalink: {
                type: String,
                required: false,
            },
        },
        methods: {
            animateButtons() {
                const target = this.$refs.container.querySelectorAll('li');

                gsap.to(target, {
                    delay: 0.6,
                    duration: 0.4,
                    stagger: 0.1,
                    opacity: 1,
                    y: 0,
                    ease: 'power3.easeIn',
                });
            },
            toggleMenu() {
                this.menuOpen = !this.menuOpen;
            },
        },
    };
</script>

<style lang="scss">
    .subpage-nav--animated {
        li {
            opacity: 0;
            transform: translate(0, 20px);
        }
    }
</style>
