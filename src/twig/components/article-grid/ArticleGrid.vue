<template>
  <div>
    <slot v-if="!renderApi" />
    <div
      v-if="renderApi && api == 'president'"
      class="article-grid grid grid-cols-8 gap-10 max-w-screen-2xl w-full my-0 mx-auto"
      :class="{
        'grid-cols-9': columns == 3,
      }"
    >
      <div
        v-for="(item, index) in data"
        class="article-grid__item glide__slide col-span-4"
        :class="{
          'md:col-span-2 col-span-4': columns == 4,
          'md:col-span-3 col-span-9': columns == 3,
        }"
        :key="index"
      >
        <article
          class="article space-y-4"
          :class="{ 'pt-1 border-t-2 border-solid border-indigo-600': border }"
        >
          <div class="context w-full py-4">
            <component is="text-group" class="text-group">
              <div
                v-if="item.date"
                class="text-group__subheading font-extended font-bold text-12 leading-130 tracking-8 text-left text-azure uppercase"
                v-text="item.date"
              />
              <h2
                class="text-group__heading font-extended font-normal text-20 leading-110 -tracking-3 text-left text-indigo"
                :class="{
                  'mt-2': item.date,
                }"
                v-text="decodeHtmlEntities(item.title.rendered)"
              />
              <p
                class="text-group__p font-body font-normal text-14 leading-130 text-left text-indigo-800 mt-2"
                v-text="item['post-meta-fields'].summary[0]"
              />
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
import axios from "axios";
import moment from "moment";
import TextGroup from "/src/twig/components/text-group/TextGroup.vue";

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
      switch (this.api) {
        case "president":
          this.endpoint =
            "https://news.colby.edu/wp-json/wp/v2/external_post?&tags=577&_embed=1";
          break;
      }

      await axios.get(this.endpoint).then((output) => {
        this.data = output.data.map((item) => {
          return {
            title: {
              rendered: item.title.rendered.replace(/<\/?[^>]+(>|$)/g, ""),
            },
            "post-meta-fields": {
              summary: [
                `${item.content.rendered
                  .replace(/<\/?[^>]+(>|$)/g, "")
                  .substring(0, 120)}...`,
              ],
            },
            url: item.external_url,
            date: moment(item.date).format("MMM DD, YYYY"),
          };
        });
      });
    }
  },
  props: {
    renderApi: {
      required: true,
    },
    api: {
      type: String,
      required: false,
    },
    border: {
      type: String,
      required: false,
    },
    cta: {
      type: String,
      required: false,
      default: "Read Story",
    },
    columns: {
      type: Number,
      required: false,
    },
  },
  methods: {
    decodeHtmlEntities(input) {
      const doc = new DOMParser().parseFromString(input, "text/html");
      return doc.documentElement.textContent;
    },
  },
};
</script>
