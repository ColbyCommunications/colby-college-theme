<template>
    <div class="relative">
        <div
            v-if="!renderApi && style === 'accordion'"
            class="article-grid relative grid gap-10 max-w-screen-2xl w-full my-0 mx-auto"
            :class="columns === 4 ? 'grid-cols-8' : columns === 3 ? 'grid-cols-9' : 'grid-cols-4'"
        >
            <div
                v-for="(item, i) in items"
                :key="i"
                class="relative"
                :class="[
                    'article-grid__item transition-transform duration-500',
                    expandedIndex === i ? 'z-10 bg-white' : 'z-0',
                    columns === 4
                        ? 'md:col-span-2 col-span-4'
                        : columns === 3
                        ? 'md:col-span-3 col-span-9'
                        : 'col-span-4',
                ]"
                :style="expandedIndex === i ? pickedUpStyle(i) : {}"
                :ref="(el) => (itemRefs[i] = el)"
            >
                <article
                    class="article space-y-4"
                    :class="{ 'pt-1 border-t-2 border-solid border-indigo-600': border }"
                >
                    <!-- Normal Item Content -->
                    <a class="article__image relative block overflow-hidden">
                        <a
                            class="article__image relative block overflow-hidden article__image__person"
                            :href="item.url"
                            :aria-label="item.heading"
                        >
                            <picture>
                                <source
                                    media="(min-width: 768px)"
                                    :srcset="item.image.sizes.desktop"
                                />
                                <img
                                    decoding="async"
                                    class="w-full object-cover hover:scale-105 transition-all duration-500 ease-in-out"
                                    :src="item.image.sizes.mobile"
                                    :alt="item.title"
                                />
                            </picture>
                        </a>
                    </a>

                    <div class="context w-full space-y-5">
                        <component is="text-group" class="text-group">
                            <div class="mr-6 flex flex-col justify-start shrink-0">
                                <div
                                    v-if="item.heading && item.subheading"
                                    class="text-12 text-azure text-group__subheading font-extended font-bold leading-130 tracking-8 uppercase"
                                >
                                    {{ item.subheading }}
                                </div>
                                <h2
                                    class="text-group__heading font-extended font-normal text-20 leading-110 -tracking-3 text-left text-indigo"
                                    :class="{ 'lg:text-16': columns == 4 }"
                                    v-html="item.heading"
                                />
                            </div>
                        </component>

                        <div class="button-group flex flex-wrap gap-4 mt-4">
                            <a
                                class="cursor-pointer btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-300 font-body font-normal text-10 leading-130 text-indigo bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3 transition-all duration-200 ease-in-out !no-underline"
                                @click="toggleAccordion(i)"
                            >
                                <button class="btn__text">
                                    {{ expandedIndex === i ? 'Close' : 'Read More' }}
                                </button>
                            </a>
                        </div>
                    </div>
                </article>

                <!-- Floating Accordion Panel -->
                <Transition
                    enter-active-class="transition-opacity duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="transition-opacity duration-300"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-if="expandedIndex === i"
                        class="absolute top-0 bg-indigo-200 text-indigo-800 text-14 border-[1px] border-indigo-500 flex flex-col h-full"
                        :class="
                            accordionDirection(i) === 'right' ? 'left-full ml-4' : 'right-full mr-4'
                        "
                        :style="{ width: accordionWidth, maxHeight: '80vh' }"
                    >
                        <div
                            :class="[
                                'pt-4 pl-4 pr-4 flex w-full',
                                accordionDirection(i) === 'left' ? 'justify-end' : 'justify-start',
                            ]"
                        >
                            <button @click="toggleAccordion(i)">
                                <span class="material-icons-sharp text-indigo-800">close</span>
                            </button>
                        </div>

                        <div class="mb-4 p-4 flex-grow overflow-auto" v-html="item.paragraph"></div>

                        <div
                            v-if="Array.isArray(items[i].buttons)"
                            :class="[
                                'px-4 pb-4 flex mt-auto',
                                accordionDirection(i) === 'left' ? 'justify-start' : 'justify-end',
                            ]"
                        >
                            <a
                                v-for="(buttonObj, buttonIndex) in items[i].buttons"
                                :key="buttonIndex"
                                :href="buttonObj.button.url"
                                :target="buttonObj.button.target || '_self'"
                                :class="[
                                    'cursor-pointer btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-300 font-body font-normal text-10 leading-130 text-indigo bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3 transition-all duration-200 ease-in-out !no-underline',
                                    accordionDirection(i) === 'left' ? 'mr-2' : 'ml-2',
                                ]"
                            >
                                {{ buttonObj.button.title }}
                            </a>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
        <slot v-if="!renderApi" />
    </div>
</template>

<script>
    import TextGroup from '../text-group/TextGroup.vue';

    export default {
        components: {
            TextGroup,
        },
        props: {
            renderApi: Boolean,
            posts: String,
            api: String,
            range: Number,
            border: String,
            cta: {
                type: String,
                default: 'Read Story',
            },
            columns: Number,
            style: String,
            size: String,
            items: Array,
        },
        data() {
            return {
                expandedIndex: null,
                itemRefs: [],
                accordionWidth: 'auto',
            };
        },
        mounted() {
            window.addEventListener('resize', this.handleResize);
            console.log(this.items);
        },
        beforeUnmount() {
            window.removeEventListener('resize', this.handleResize);
        },
        methods: {
            toggleAccordion(index) {
                this.expandedIndex = this.expandedIndex === index ? null : index;
                this.$nextTick(() => {
                    this.$nextTick(() => {
                        // second nextTick to wait for content rendering
                        if (this.expandedIndex !== null) {
                            this.printWidths(this.expandedIndex);
                        } else {
                            this.accordionWidth = 'auto';
                        }
                    });
                });
            },
            accordionDirection(index) {
                const positionInRow = index % this.columns;
                return positionInRow < this.columns / 2 ? 'right' : 'left';
            },
            pickedUpStyle(index) {
                const positionInRow = index % this.columns;
                const gap = '1.25rem';

                if (positionInRow === 0 || positionInRow === this.columns - 1) {
                    return {};
                }

                const direction = this.accordionDirection(index);

                if (direction === 'right') {
                    // Shift left by 100% plus half gap
                    return {
                        transform: `translateX(calc(-100% - ${gap}))`,
                        zIndex: 10,
                    };
                } else {
                    // Shift right by 100% plus half gap
                    return {
                        transform: `translateX(calc(100% + ${gap}))`,
                        zIndex: 10,
                    };
                }
            },
            printWidths(index) {
                const currentItem = this.itemRefs[index];
                if (!currentItem) return;

                const currentWidth = currentItem.offsetWidth;
                const container = currentItem.closest('.article-grid');
                if (!container) return;
                const containerWidth = container.clientWidth;

                const remainingWidth = containerWidth - currentWidth;

                // Tailwind m-4 equals 1rem = 16px (assuming 16px root font size)
                const tailwindM4px = 16;

                // Subtract margin for both directions
                const adjustedWidth = remainingWidth - tailwindM4px;

                console.log(`Item ${index} width: ${currentWidth}px`);
                console.log(`Remaining width in row: ${remainingWidth}px`);
                console.log(`Adjusted accordion width (both directions): ${adjustedWidth}px`);

                this.accordionWidth = adjustedWidth + 'px';
            },
            handleResize() {
                if (this.expandedIndex !== null) {
                    this.printWidths(this.expandedIndex);
                }
            },
        },
    };
</script>
