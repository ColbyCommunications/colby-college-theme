<template>
  <div ref="carousel">
    <slot
      v-if="renderApi == false"
      :activeSlide="activeSlide"
      :changeSlide="changeSlide"
      :pauseCarousel="pauseCarousel"
      :playCarousel="playCarousel"
      :featuredNews="featuredNews"
    />
    <div
      v-if="renderApi && api == 'Latest News'"
      class="carousel__inner md:grid md:grid-cols-12 gap-x-10 max-w-screen-2xl w-full px-5 my-0 mx-auto"
    >
      <div
        class="carousel__context md:col-span-4 lg:col-span-3 md:flex items-center"
      >
        <div class="context space-y-5">
          <div class="text-group">
            <h2
              class="text-group__heading font-extended font-normal text-28 md:text-36 leading-100 -tracking-3 text-left text-indigo mt-2"
              v-text="heading"
            />
            <p
              class="text-group__p font-body font-normal text-18 md:text-18 leading-130 text-left text-indigo-800 mt-2"
              v-text="paragraph"
            />
          </div>
          <div class="button-group flex flex-wrap gap-4">
            <a
              v-for="(button, index) in buttons"
              class="btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-300 font-body font-normal text-12 leading-130 text-indigo bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3.5 transition-all duration-200 ease-in-out"
              :href="button.url"
              target="_blank"
            >
              <span class="btn__text">
                {{ button.title }}
                <div
                  class="btn__border block bg-indigo h-px w-0 group-hover:w-full transition-all duration-200 ease-in-out"
                ></div>
              </span>
              <svg
                class="btn__arrow w-2 h-2 fill-azure"
                viewBox="0 0 12 12"
                xml:space="preserve"
              >
                <path
                  d="M10 1H.7V0H12v11.7h-1V1.4L.7 12l-.7-.7L10 1z"
                  style="fill-rule: evenodd; clip-rule: evenodd"
                ></path>
              </svg>
            </a>
          </div>
        </div>
      </div>
      <div
        class="carousel__main md:col-start-5 md:col-span-8 mt-12 md:mt-0"
        @mouseEnter="pauseCarousel()"
        @mouseOut="playCarousel()"
      >
        <div class="carousel__window w-full" data-glide-window ref="window">
          <div class="glide__track" data-glide-el="track">
            <div class="glide__slides">
              <div
                v-for="(item, index) in featuredNews"
                class="carousel__slide glide__slide"
              >
                <div class="relative pb-[56.578947368421055%]">
                  <picture>
                    <source
                      media="(min-width:768px)"
                      :srcset="item.yoast_head_json.og_image[0].url"
                    />
                    <img
                      class="absolute top-0 left-0 w-full h-full object-cover"
                      :src="item.yoast_head_json.og_image[0].url"
                      :alt="item.yoast_head_json.og_description"
                    />
                  </picture>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="carousel__slides-context relative mt-6 h-80 md:h-40">
          <div
            v-for="(item, index) in featuredNews"
            class="carousel__slides-context-wrap absolute top-0 left-0 w-full invisible opacity-0 translate-y-[60px] transition-all duration-300 ease-in-out"
            :class="{
              '!visible opacity-100 !translate-y-0': activeSlide == index,
            }"
          >
            <div class="context space-y-5">
              <div class="text-group">
                <div
                  class="text-group__subheading font-extended font-bold text-12 leading-130 tracking-8 text-left text-azure uppercase"
                  v-text="item['post-meta-fields'].primary_category"
                />
                <h2
                  class="text-group__heading font-extended font-normal text-28 md:text-20 leading-100 -tracking-3 text-left text-indigo mt-2"
                  v-text="decodeHtmlEntities(item.title.rendered)"
                />
                <p
                  class="text-group__p font-body font-normal text-18 md:text-14 leading-130 text-left text-indigo-800 mt-2"
                  v-text="
                    decodeHtmlEntities(item['post-meta-fields'].summary[0])
                  "
                />
              </div>
              <div class="button-group flex flex-wrap gap-4">
                <a
                  class="btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-300 font-body font-normal text-10 leading-130 text-indigo bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3 transition-all duration-200 ease-in-out"
                  :href="item.guid.rendered"
                  target="_blank"
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
          </div>
        </div>
        <div class="carousel__controls-wrap flex justify-end">
          <div class="carousel__controls">
            <div class="arrow-controls space-x-4">
              <button
                @click="changeSlide('prev')"
                class="arrow-btn inline-flex items-center justify-center w-12 h-12 bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 rounded border border-solid border-indigo-300 transition-all duration-200 ease-in-out"
              >
                <span class="sr-only">Previous</span>
                <svg
                  class="arrow-btn__arrow w-5 h-5"
                  viewBox="0 0 26.9 26.5"
                  style="enable-background: new 0 0 26.9 26.5"
                >
                  <path
                    d="M26.9 12.7h-25L14 .7l-.8-.7L0 13.2l13.2 13.3.8-.7L1.9 13.7h25z"
                  />
                </svg>
              </button>
              <button
                @click="changeSlide('next')"
                class="arrow-btn inline-flex items-center justify-center w-12 h-12 bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 rounded border border-solid border-indigo-300 transition-all duration-200 ease-in-out"
              >
                <span class="sr-only">Next</span>
                <svg
                  class="arrow-btn__arrow w-5 h-5 rotate-180"
                  viewBox="0 0 26.9 26.5"
                  style="enable-background: new 0 0 26.9 26.5"
                >
                  <path
                    d="M26.9 12.7h-25L14 .7l-.8-.7L0 13.2l13.2 13.3.8-.7L1.9 13.7h25z"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div
      v-if="renderApi && api == 'Academic News'"
      class="article-section__inner md:grid md:grid-cols-12 gap-x-10 max-w-screen-2xl w-full px-5 my-0 mx-auto space-y-16 md:space-y-0"
    >
      <div
        class="article-section__intro md:col-span-4 lg:col-span-3 space-y-10"
      >
        <div class="context w-full space-y-5">
          <div class="text-group">
            <div
              class="text-group__subheading font-extended font-bold text-12 leading-130 tracking-8 text-left text-azure uppercase"
              v-text="subheading"
            />
            <h2
              class="text-group__heading font-extended font-normal text-28 md:text-20 leading-110 -tracking-3 text-left text-indigo mt-2"
              v-text="heading"
            />
            <p
              class="text-group__p font-body font-normal text-18 md:text-14 leading-130 text-left text-indigo-800 mt-2"
              v-text="paragraph"
            />
          </div>
          <div class="button-group flex flex-wrap gap-4">
            <a
              v-for="(button, index) in buttons"
              class="btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-300 font-body font-normal text-10 leading-130 text-indigo bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3 transition-all duration-200 ease-in-out"
              :href="button.url"
            >
              <span class="btn__text"
                >{{ button.title }}
                <div
                  class="btn__border block bg-indigo h-px w-0 group-hover:w-full transition-all duration-200 ease-in-out"
                ></div
              ></span>
            </a>
          </div>
        </div>
        <div class="arrow-controls space-x-4">
          <button
            class="arrow-btn inline-flex items-center justify-center w-12 h-12 bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 rounded border border-solid border-indigo-300 transition-all duration-200 ease-in-out"
            @click="changeSlide('prev')"
          >
            <span class="sr-only">Previous</span>
            <svg class="arrow-btn__arrow w-5 h-5" viewBox="0 0 26.9 26.5">
              <path
                d="M26.9 12.7h-25L14 .7l-.8-.7L0 13.2l13.2 13.3.8-.7L1.9 13.7h25z"
              ></path>
            </svg>
          </button>
          <button
            class="arrow-btn inline-flex items-center justify-center w-12 h-12 bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 rounded border border-solid border-indigo-300 transition-all duration-200 ease-in-out"
            @click="changeSlide('next')"
          >
            <span class="sr-only">Next</span>
            <svg
              class="arrow-btn__arrow w-5 h-5 rotate-180"
              viewBox="0 0 26.9 26.5"
            >
              <path
                d="M26.9 12.7h-25L14 .7l-.8-.7L0 13.2l13.2 13.3.8-.7L1.9 13.7h25z"
              ></path>
            </svg>
          </button>
        </div>
      </div>
      <div
        class="article-section__grid md:col-start-5 md:col-span-8"
        @mouseEnter="pauseCarousel()"
        @mouseOut="playCarousel()"
      >
        <div
          class="article-grid max-w-screen-2xl my-0 mx-auto"
          data-glide-window
          ref="window"
        >
          <div class="glide__track" data-glide-el="track">
            <div class="glide__slides">
              <div
                v-for="(item, index) in featuredNews"
                class="article-grid__item glide__slide"
              >
                <article class="article space-y-4">
                  <a
                    v-if="item.yoast_head_json.og_image[0].url"
                    class="article__image relative block overflow-hidden"
                    :href="item.guid.rendered"
                  >
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
                  <div class="context w-full space-y-5">
                    <div class="text-group">
                      <div
                        v-if="item['post-meta-fields'].primary_category"
                        class="text-group__subheading font-extended font-bold text-12 leading-130 tracking-8 text-left text-azure uppercase"
                        v-text="item['post-meta-fields'].primary_category"
                      />
                      <h2
                        class="text-group__heading font-extended font-normal text-20 leading-110 -tracking-3 text-left text-indigo"
                        :class="{
                          'mt-2': item['post-meta-fields'].primary_category,
                        }"
                        v-text="decodeHtmlEntities(item.title.rendered)"
                      />
                      <p
                        class="text-group__p font-body font-normal text-14 leading-130 text-left text-indigo-800 mt-2"
                        v-text="
                          decodeHtmlEntities(
                            item['post-meta-fields'].summary[0]
                          )
                        "
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
      </div>
    </div>
    <div
      v-else-if="renderApi && api == 'Faculty Accomplishments'"
      class="article-section__inner md:grid md:grid-cols-12 gap-x-10 max-w-screen-2xl w-full px-5 my-0 mx-auto space-y-16 md:space-y-0"
    >
      <div
        class="article-section__intro md:col-span-4 lg:col-span-3 space-y-10"
      >
        <div class="context w-full space-y-5">
          <div class="text-group">
            <div
              class="text-group__subheading font-extended font-bold text-12 leading-130 tracking-8 text-left text-azure uppercase"
              v-text="subheading"
            />
            <h2
              class="text-group__heading font-extended font-normal text-28 md:text-20 leading-110 -tracking-3 text-left text-indigo mt-2"
              v-text="heading"
            />
            <p
              class="text-group__p font-body font-normal text-18 md:text-14 leading-130 text-left text-indigo-800 mt-2"
              v-text="paragraph"
            />
          </div>
          <div class="button-group flex flex-wrap gap-4">
            <a
              v-for="(button, index) in FAbuttons"
              class="btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-300 font-body font-normal text-10 leading-130 text-indigo bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3 transition-all duration-200 ease-in-out"
              :href="button.url"
            >
              <span class="btn__text"
                >{{ button.title }}
                <div
                  class="btn__border block bg-indigo h-px w-0 group-hover:w-full transition-all duration-200 ease-in-out"
                ></div
              ></span>
            </a>
          </div>
        </div>
        <div class="arrow-controls space-x-4">
          <button
            class="arrow-btn inline-flex items-center justify-center w-12 h-12 bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 rounded border border-solid border-indigo-300 transition-all duration-200 ease-in-out"
            @click="changeSlide('prev')"
          >
            <span class="sr-only">Previous</span>
            <svg class="arrow-btn__arrow w-5 h-5" viewBox="0 0 26.9 26.5">
              <path
                d="M26.9 12.7h-25L14 .7l-.8-.7L0 13.2l13.2 13.3.8-.7L1.9 13.7h25z"
              ></path>
            </svg>
          </button>
          <button
            class="arrow-btn inline-flex items-center justify-center w-12 h-12 bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 rounded border border-solid border-indigo-300 transition-all duration-200 ease-in-out"
            @click="changeSlide('next')"
          >
            <span class="sr-only">Next</span>
            <svg
              class="arrow-btn__arrow w-5 h-5 rotate-180"
              viewBox="0 0 26.9 26.5"
            >
              <path
                d="M26.9 12.7h-25L14 .7l-.8-.7L0 13.2l13.2 13.3.8-.7L1.9 13.7h25z"
              ></path>
            </svg>
          </button>
        </div>
      </div>
      <div
        class="article-section__grid md:col-start-5 md:col-span-8"
        @mouseEnter="pauseCarousel()"
        @mouseOut="playCarousel()"
      >
        <div
          class="article-grid max-w-screen-2xl my-0 mx-auto"
          data-glide-window
          ref="window"
        >
          <div class="glide__track" data-glide-el="track">
            <div class="glide__slides">
              <div
                v-for="(item, index) in featuredNews"
                class="article-grid__item glide__slide"
              >
                <article class="article space-y-4">
                  <a
                    v-if="item.yoast_head_json.og_image[0].url"
                    class="article__image relative block overflow-hidden"
                    :href="item.guid.rendered"
                  >
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
                  <div class="context w-full space-y-5">
                    <div class="text-group">
                      <div
                        v-if="item['post-meta-fields'].primary_category"
                        class="text-group__subheading font-extended font-bold text-12 leading-130 tracking-8 text-left text-azure uppercase"
                        v-text="item['post-meta-fields'].primary_category"
                      />
                      <h2
                        class="text-group__heading font-extended font-normal text-20 leading-110 -tracking-3 text-left text-indigo"
                        :class="{
                          'mt-2': item['post-meta-fields'].primary_category,
                        }"
                        v-text="decodeHtmlEntities(item.title.rendered)"
                      />
                      <p
                        class="text-group__p font-body font-normal text-14 leading-130 text-left text-indigo-800 mt-2"
                        v-text="
                          decodeHtmlEntities(
                            item['post-meta-fields'].summary[0]
                          )
                        "
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
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Glide from "@glidejs/glide";

export default {
  data() {
    return {
      endpoint: undefined,
      activeSlide: 0,
      window: undefined,
      glide: undefined,
      featuredNews: undefined,
    };
  },
  async mounted() {
    if (this.renderApi) {
      switch (this.api) {
        case "Latest News":
          this.endpoint =
            "https://news.colby.edu/wp-json/wp/v2/posts?per_page=5&tags=561&_embed=1";
          break;
        case "Academic News":
          this.endpoint =
            "https://news.colby.edu/wp-json/wp/v2/posts?per_page=5&categories=8,12,14,15&_embed=1";
          this.subheading = this.api;
          break;
        case "Faculty Accomplishments":
          this.endpoint =
            "https://news.colby.edu/wp-json/wp/v2/external_post?story_type_slug=faculty-accomplishments&per_page=5&_embed=1";
          this.subheading = this.api;
          break;
      }

      await axios.get(this.endpoint).then((outputa) => {
        if (this.api == "Faculty Accomplishments") {
          this.featuredNews = outputa.data.map((item) => {
            return {
              yoast_head_json: {
                og_image: [
                  {
                    url: "",
                  },
                ],
              },
              title: {
                rendered: item.title.rendered.replace(/<\/?[^>]+(>|$)/g, ""),
              },
              "post-meta-fields": {
                primary_category: "",
                summary: [
                  `${item.content.rendered
                    .replace(/<\/?[^>]+(>|$)/g, "")
                    .substring(0, 120)}...`,
                ],
              },
              guid: {
                // use item.external_url for faculty accomplishments instead of item.guid.rendered
                rendered: item.external_url,
              },
            };
          });
        } else {
          this.featuredNews = outputa.data;
        }

        this.renderGlide();
      });
    } else {
      this.renderGlide();
    }
  },
  props: {
    perView: {
      type: Number,
      required: false,
      default: 1,
    },
    gap: {
      type: Number,
      required: false,
      default: 0,
    },
    heading: {
      type: String,
      required: false,
      default: "News",
    },
    paragraph: {
      type: String,
      required: false,
      default: "Latest from Colby.",
    },
    cta: {
      type: String,
      required: false,
      default: "Read Story",
    },
    buttons: {
      type: Array,
      required: false,
      default: [
        {
          url: "https://news.colby.edu/",
          title: "All News",
        },
      ],
    },
    FAbuttons: {
      type: Array,
      required: false,
      default: [
        {
          url: "https://news.colby.edu/external/faculty-accomplishments/",
          title: "All News",
        },
      ],
    },
    renderApi: {
      type: [Boolean, Number],
      required: false,
      default: false,
    },
    api: {
      type: String,
      required: false,
    },
  },
  methods: {
    changeSlide(s) {
      if (s == "next") {
        s = ">";
      } else {
        s = "<";
      }

      this.glide.go(s);
    },
    pauseCarousel() {
      if (this.glide) {
        this.glide.pause();
      }
    },
    playCarousel() {
      if (this.glide) {
        this.glide.play();
      }
    },
    renderGlide() {
      // JS was apparently initializing faster than
      // Twig could render in this instance, returning
      // a window element without it's slide children.
      // To Remedy this, I have applied a timeout.

      setTimeout(() => {
        this.window = this.$refs.carousel.querySelector("[data-glide-window]");

        if (this.window) {
          this.glide = new Glide(this.window, {
            type: "carousel",
            gap: this.gap,
            animationDuration: 600,
            autoplay: 4000,
            perView: this.perView,
          });

          this.glide.on("run", () => {
            this.activeSlide = this.glide.index;
          });

          this.glide.mount();
        }
      }, 100);
    },
    decodeHtmlEntities(input) {
      const doc = new DOMParser().parseFromString(input, "text/html");
      return doc.documentElement.textContent;
    },
  },
};
</script>

<style lang="scss">
@import "node_modules/@glidejs/glide/src/assets/sass/glide.core";
</style>
