<template>
    <div>
        <slot v-if="!renderApi" />
        <div
            v-if="renderApi"
            class="article-grid grid gap-10 max-w-screen-2xl w-full my-0 mx-auto grid-cols-12"
        >
            <div
                v-for="(item, index) in data"
                class="article-grid__item glide__slide col-span-4"
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
                                    v-text="decodeHtmlEntities(item.title.rendered)"
                                />
                                <p
                                    class="text-group__p font-body font-normal text-14 leading-130 text-left text-indigo-800 mt-2"
                                    v-text="item['post-meta-fields'].summary[0]"
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
    </div>
</template>
<script>
    import axios from 'axios';
    import moment from 'moment';
    import TextGroup from '/src/twig/components/text-group/TextGroup.vue';

    export default {
        components: {
            TextGroup,
        },
        data() {
            return {
                data: [],
            };
        },
        async mounted() {
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
                                    rendered: item.title.rendered.replace(/<\/?[^>]+(>|$)/g, ''),
                                },
                                'post-meta-fields': {
                                    summary: [
                                        `${item.content.rendered
                                            .replace(/<\/?[^>]+(>|$)/g, '')
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
        props: {
            renderApi: {
                required: true,
            },
            posts: {
                type: String,
                required: false,
            },
            api: {
                type: String,
                required: false,
            },
            range: {
                type: Number,
                required: false,
            },
            border: {
                type: String,
                required: false,
            },
            cta: {
                type: String,
                required: false,
                default: 'Read Story',
            },
            columns: {
                type: Number,
                required: false,
            },
        },
        methods: {
            decodeHtmlEntities(input) {
                const doc = new DOMParser().parseFromString(input, 'text/html');
                return doc.documentElement.textContent;
            },
        },
    };
</script>
