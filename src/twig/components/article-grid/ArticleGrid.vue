<template>
    <div class="relative">
        <div
            v-if="renderApi"
            class="article-grid grid gap-10 max-w-screen-2xl w-full my-0 mx-auto grid-cols-12"
        >
            <div
                v-for="(item, index) in data"
                class="article-grid__item glide__slide"
                :class="{
                    'col-span-12 md:col-span-6': columns == 2,
                    'col-span-12 md:col-span-6 lg:col-span-4': columns == 3,
                    'col-span-12 md:col-span-6 lg:col-span-3': columns == 4,
                }"
                :key="index"
            >
                <article
                    class="article space-y-4"
                    :class="{ 'pt-1 border-t-2 border-solid border-indigo-600': border }"
                >
                    <div class="context w-full py-4">
                        <component is="text-group" class="text-group flex">
                            <div class="mr-6 flex flex-col justify-start shrink-0">
                                <img
                                    class="h-[75px] w-[75px] lg:h-[96px] lg:w-[96px]"
                                    :src="item.image"
                                    :alt="item.title.rendered"
                                />
                            </div>
                            <div>
                                <h2
                                    class="text-group__heading font-extended font-normal text-20 leading-110 -tracking-3 text-left text-indigo"
                                    :class="{ 'lg:text-16': columns == 4 }"
                                    v-html="item.title.rendered"
                                />
                                <p
                                    class="text-group__p font-body font-normal text-14 leading-130 text-left text-indigo-800 mt-2"
                                    v-html="item['post-meta-fields'].summary[0]"
                                />
                            </div>
                        </component>
                        <div class="button-group flex flex-wrap gap-4 mt-4">
                            <a
                                class="btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-300 font-body font-normal text-10 leading-130 text-indigo bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3 transition-all duration-200 ease-in-out !no-underline"
                                :href="item.url"
                            >
                                <span class="btn__text">
                                    {{ cta }}
                                    <div
                                        class="btn__border block bg-indigo h-px w-0 group-hover:w-full transition-all duration-200 ease-in-out"
                                    ></div>
                                </span>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>
        <div
            v-if="!renderApi && style === 'accordion'"
            ref="gridContainer"
            class="article-grid relative grid gap-10 max-w-screen-2xl w-full my-0 mx-auto"
            :class="
                columns === 4
                    ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4'
                    : columns === 3
                    ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3'
                    : 'grid-cols-1 sm:grid-cols-2'
            "
        >
            <div
                v-for="(item, i) in items"
                :key="i"
                class="relative"
                :class="[
                    'article-grid__item transition-transform duration-500',
                    expandedIndex === i ? 'z-10 bg-white' : 'z-0',
                ]"
                :style="expandedIndex === i ? pickedUpStyle(i) : {}"
                :ref="(el) => (itemRefs[i] = el)"
            >
                <article
                    class="article space-y-4"
                    :class="{ 'pt-1 border-t-2 border-solid border-indigo-600': border }"
                >
                    <!-- Normal Item Content -->
                    <a
                        v-if="
                            item.image &&
                            item.image.sizes &&
                            item.image.sizes.desktop &&
                            item.image.sizes.mobile
                        "
                        class="article__image relative block overflow-hidden"
                        :href="item.url"
                        :aria-label="item.heading"
                    >
                        <picture>
                            <source media="(min-width: 768px)" :srcset="item.image.sizes.desktop" />
                            <img
                                decoding="async"
                                class="w-full object-cover hover:scale-105 transition-all duration-500 ease-in-out"
                                :src="item.image.sizes.mobile"
                                :alt="item.title"
                            />
                        </picture>
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

                <!-- MOBILE: Inline Accordion (pushes content down) -->
                <Transition
                    v-if="isMobileAccordion()"
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-2"
                >
                    <div
                        v-if="expandedIndex === i"
                        class="bg-indigo-200 text-indigo-800 text-14 border-[1px] border-indigo-500 flex flex-col mt-4 w-full"
                        :style="{ maxHeight: '80vh' }"
                    >
                        <div class="pt-4 pl-4 pr-4 flex w-full justify-end">
                            <button @click="toggleAccordion(i)">
                                <span class="material-icons-sharp text-indigo-800">close</span>
                            </button>
                        </div>

                        <div class="mb-4 p-4 flex-grow overflow-auto" v-html="item.paragraph"></div>

                        <div
                            v-if="Array.isArray(items[i].buttons)"
                            class="px-4 pb-4 flex mt-auto justify-end"
                        >
                            <a
                                v-for="(buttonObj, buttonIndex) in items[i].buttons"
                                :key="buttonIndex"
                                :href="buttonObj.button.url"
                                :target="buttonObj.button.target || '_self'"
                                class="cursor-pointer btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-300 font-body font-normal text-10 leading-130 text-indigo bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3 transition-all duration-200 ease-in-out !no-underline ml-2"
                            >
                                {{ buttonObj.button.title }}
                            </a>
                        </div>
                    </div>
                </Transition>

                <!-- DESKTOP: Side-Flyout Accordion -->
                <Transition
                    v-else
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
        <slot v-if="!renderApi && style !== 'accordion'" />
    </div>
</template>

<script>
    import axios from 'axios';
    import moment from 'moment';
    import TextGroup from '../text-group/TextGroup.vue';

    export default {
        components: { TextGroup },
        props: {
            renderApi: Boolean,
            posts: String,
            api: String,
            range: Number,
            border: String,
            cta: { type: String, default: 'Read Story' },
            columns: Number,
            style: String,
            size: String,
            items: Array,
        },
        data() {
            return {
                data: [],
                expandedIndex: null,
                closingIndex: null,
                itemRefs: [],
                accordionWidth: 'auto',
                currentColumns: this.columns,
            };
        },
        mounted() {
            this.updateColumns();
            window.addEventListener('resize', this.updateColumns);
            this.fetchApiData();
        },
        beforeUnmount() {
            window.removeEventListener('resize', this.updateColumns);
        },
        methods: {
            async fetchApiData() {
                if (this.renderApi) {
                    this.endpoint = 'https://news.colby.edu/wp-json/custom/v1/external-posts';

                    await axios.get(this.endpoint).then((output) => {
                        this.data = output.data
                            .filter((item) => {
                                switch (this.posts) {
                                    case 'media_coverage':
                                        if (this.api === 'all_media') {
                                            return (
                                                item.story_type &&
                                                Array.isArray(item.story_type) &&
                                                item.story_type[0].slug === 'media-coverage' &&
                                                item.content.rendered
                                            );
                                        } else if (this.api === 'president') {
                                            return (
                                                item.story_type &&
                                                Array.isArray(item.story_type) &&
                                                item.story_type[0].slug === 'media-coverage' &&
                                                item.content.rendered &&
                                                item.tags &&
                                                item.tags.some((tag) => tag.name === 'president')
                                            );
                                        } else if (this.api === 'highlight') {
                                            return (
                                                item.story_type &&
                                                Array.isArray(item.story_type) &&
                                                item.story_type[0].slug === 'media-coverage' &&
                                                item.content.rendered &&
                                                item.tags &&
                                                item.tags.some((tag) => tag.name === 'editors-pick')
                                            );
                                        }
                                        break;
                                }
                                return false;
                            })
                            .map((item) => {
                                return {
                                    title: {
                                        rendered: item.title.rendered.replace(
                                            /<(?!\/?(i|em)\b)[^>]+>/gi,
                                            ''
                                        ),
                                    },
                                    'post-meta-fields': {
                                        summary: [
                                            `${item.content.rendered
                                                .replace(/<(?!\/?(i|em)\b)[^>]+>/gi, '')
                                                .substring(0, 120)}...`,
                                        ],
                                    },
                                    url: item.external_url,
                                    image: item.image,
                                    date: moment(item.date).format('MMM DD, YYYY'),
                                };
                            })
                            .slice(0, this.range);
                    });
                }
            },
            isMobileAccordion() {
                return this.currentColumns === 1;
            },
            updateColumns() {
                const container = this.$refs.gridContainer;
                if (!container || container.children.length === 0) return;

                const itemWidth = container.children[0].offsetWidth;
                const containerWidth = container.clientWidth;

                const newColumns = Math.floor(containerWidth / itemWidth) || 1;

                if (newColumns !== this.currentColumns) {
                    this.currentColumns = newColumns;
                    if (this.expandedIndex !== null) {
                        this.expandedIndex = null;
                        this.accordionWidth = 'auto';
                    }
                } else {
                    this.currentColumns = newColumns;
                }

                if (this.expandedIndex !== null && !this.isMobileAccordion()) {
                    this.printWidths(this.expandedIndex);
                }
            },
            toggleAccordion(index) {
                this.expandedIndex = this.expandedIndex === index ? null : index;
                this.$nextTick(() => {
                    if (this.expandedIndex !== null && !this.isMobileAccordion()) {
                        this.printWidths(this.expandedIndex);
                    } else {
                        this.accordionWidth = 'auto';
                    }
                });
            },
            accordionDirection(index) {
                if (this.isMobileAccordion()) return 'down';
                const positionInRow = index % this.currentColumns;
                return positionInRow < this.currentColumns / 2 ? 'right' : 'left';
            },
            pickedUpStyle(index) {
                if (this.isMobileAccordion()) return {};
                const positionInRow = index % this.currentColumns;
                const gap = '1.25rem';
                if (positionInRow === 0 || positionInRow === this.currentColumns - 1) return {};
                const direction = this.accordionDirection(index);
                return direction === 'right'
                    ? { transform: `translateX(calc(-100% - ${gap}))`, zIndex: 10 }
                    : { transform: `translateX(calc(100% + ${gap}))`, zIndex: 10 };
            },
            printWidths(index) {
                const currentItem = this.itemRefs[index];
                if (!currentItem) return;

                const currentWidth = currentItem.offsetWidth;
                const container = currentItem.closest('.article-grid');
                if (!container) return;
                const containerWidth = container.clientWidth;

                const remainingWidth = containerWidth - currentWidth;
                const tailwindM4px = 16;
                const adjustedWidth = remainingWidth - tailwindM4px;
                this.accordionWidth = adjustedWidth + 'px';
            },
        },
    };
</script>
