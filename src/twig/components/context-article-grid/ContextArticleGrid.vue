<template>
    <div ref="contextArticleGrid">
        <slot v-if="renderApi == false" />
        <div
            v-if="renderApi"
            class="context-article-grid__inner md:grid md:grid-cols-12 gap-x-10 max-w-screen-2xl w-full px-5 my-0 mx-auto"
        >
            <div class="md:col-start-3 md:col-span-8">
                <div class="context space-y-5">
                    <div class="text-group">
                        <div
                            v-if="subheading"
                            class="text-group__subheading font-extended font-bold text-14 leading-130 tracking-8 text-center text-azure uppercase"
                            v-text="subheading"
                        />
                        <h2
                            v-if="heading"
                            class="text-group__heading font-extended font-normal text-36 md:text-24 leading-110 -tracking-3 text-center text-indigo mt-2"
                            v-text="heading"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div
            v-if="renderApi"
            class="context-article-grid__inner gap-x-10 max-w-screen-2xl w-full px-5 my-0 mx-auto"
        >
            <div class="article-grid grid grid-cols-9 gap-10 max-w-screen-2xl w-full">
                <div
                    v-for="(item, index) in featuredNews"
                    class="article-grid__item md:col-span-3 col-span-9"
                >
                    <article class="article space-y-4">
                        <div class="article__image relative">
                            <a class="relative block overflow-hidden" :href="item.guid.rendered">
                                <picture>
                                    <source
                                        media="(min-width:768px)"
                                        :srcset="item.yoast_head_json.og_image[0].url"
                                    />
                                    <img
                                        class="w-full object-cover hover:scale-105 transition-all duration-500 ease-in-out"
                                        :src="item.yoast_head_json.og_image[0].url"
                                        :alt="item.yoast_head_json.og_description"
                                    />
                                </picture>
                            </a>
                        </div>
                        <div class="context space-y-5">
                            <div class="text-group">
                                <div
                                    class="text-group__subheading font-extended font-bold text-12 leading-130 tracking-8 text-left text-azure uppercase"
                                    v-text="item['post-meta-fields'].primary_category"
                                />
                                <h2
                                    class="text-group__heading font-extended font-normal text-20 leading-110 -tracking-3 text-left text-indigo mt-2"
                                    v-text="decodeHtmlEntities(item.title.rendered)"
                                />
                                <p
                                    class="text-group__p font-body font-normal text-14 leading-130 text-left text-indigo-800 mt-2"
                                    v-text="item['post-meta-fields'].summary[0]"
                                />
                            </div>
                            <div class="button-group flex flex-wrap gap-4">
                                <a
                                    class="btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-300 font-body font-normal text-10 leading-130 text-indigo bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3 transition-all duration-200 ease-in-out"
                                    :href="item.guid.rendered"
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
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                featuredNews: undefined,
            };
        },
        async mounted() {
            if (this.renderApi) {
                await axios
                    .get('https://news.colby.edu/wp-json/wp/v2/posts?per_page=6&tags=569&_embed=1')
                    .then((outputa) => {
                        this.featuredNews = outputa.data.slice(0, 6);
                    });
            }
        },
        props: {
            subheading: {
                type: String,
                required: false,
                default: 'News Profiles',
            },
            heading: {
                type: String,
                required: false,
            },
            paragraph: {
                type: String,
                required: false,
            },
            cta: {
                type: String,
                required: false,
                default: 'Read Story',
            },
            renderApi: {
                type: Boolean,
                required: false,
                default: false,
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
