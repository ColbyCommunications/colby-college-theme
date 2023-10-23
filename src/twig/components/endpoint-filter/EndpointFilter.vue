<template>
  <div class="endpoint-filter">
    <div v-if="filters" class="section-nav full-bleed py-6 bg-gray-100 overflow-x-auto !mt-0">
      <div class="section-nav__inner flex px-5 space-x-10 lg:justify-center">
        <h2 class="section-nav__heading font-extended font-bold text-14 md:text-12 tracking-8 text-azure uppercase whitespace-nowrap">
          Event types:
        </h2>
        <ul class="flex items-center space-x-7 pr-9 md:pr-0">
          <li v-for="(filter, index) in filters" class="font-body font-medium text-14 md:text-10 text-indigo-800 leading-130 whitespace-nowrap">
            <button
              class="text-indigo-800 hover:text-indigo hover:underline transition-all duration-200 ease-in-out"
              :class="{'underline !text-indigo': endpoint == filter.url }"
              v-text="filter.title"
              @click="requestData(filter.url)"
            />
          </li>
        </ul>
      </div>
    </div>
    <div
      class="grid grid-cols-9 gap-10 max-w-screen-2xl w-full my-0 px-5 mx-auto"
      :class="{'mt-14': filters}"
    >
      <div v-for="(item, index) in items" class="article-grid__item md:col-span-3 col-span-9">
        <article class="article space-y-4">
          <div class="context w-full space-y-5">
            <div class="text-group--animated text-group text-group--animated">
              <div
                class="text-group__subheading font-extended font-bold text-12 leading-130 tracking-8 text-left uppercase"
                :class="[ type == 'dark' ? 'text-canary' : 'text-azure']"
                v-text="item.date"
              />
              <h2
                class="text-group__heading font-extended font-bold text-24 md:text-18 leading-110 -tracking-3 text-left mt-2"
                :class="[ type == 'dark' ? 'text-white' : 'text-indigo']"
                v-text="item.title"
              />
              <p
                class="text-group__p font-body font-normal text-12 md:text-12 leading-130 text-left mt-2 !opacity-100"
                :class="[ type == 'dark' ? 'text-white' : 'text-indigo-800']"
                v-text="item.time"
              />
            </div>
            <div class="button-group--animated button-group flex flex-wrap gap-4 shrink-0">
              <a v-if="type == 'light'" class="btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-300 hover:border-indigo-500 font-body font-normal text-10 leading-130 !no-underline text-indigo bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3 transition-all duration-200 ease-in-out !opacity-100 !translate-x-0" :href="item.url" target="_blank">
                <span class="btn__text"> Learn More <div class="btn__border block bg-indigo h-px w-0 group-hover:w-full transition-all duration-200 ease-in-out"></div></span>
              </a>
              <a v-if="type == 'dark'" class="btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-800 hover:border-indigo-800 font-body font-normal text-10 leading-130 !no-underline text-white bg-indigo/20 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3 transition-all duration-200 ease-in-out !opacity-100 !translate-x-0" :href="item.url" target="_blank">
                <span class="btn__text"> Learn More <div class="btn__border block bg-white h-px w-0 group-hover:w-full transition-all duration-200 ease-in-out"></div></span>
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

  export default {
    data() {
      return {
        endpoint: undefined,
        items: undefined,
      };
    },
    props: {
      type: {
        type: String,
        required: false,
        default: 'light',
      },
      initialEndpoint: {
        type: String,
        required: false,
        default: 'https://events.colby.edu/live/json/events/group/Colby%20Arts%20EMS/group/Museum%20of%20Art/group/Art/group/Center%20for%20Arts%20and%20Humanities/group/Creative%20Writing/group/Music/group/Cinema%20Studies/group/Performance%2C%20Theater%2C%20%26%20Dance'
      },
      limit: {
        type: Number,
        required: false,
      },
      filters: {
        type: Array,
        required: false,
        default: [
          {
            title: 'All',
            url: 'https://events.colby.edu/live/json/events/group/Colby%20Arts%20EMS/group/Museum%20of%20Art/group/Art/group/Center%20for%20Arts%20and%20Humanities/group/Creative%20Writing/group/Music/group/Cinema%20Studies/group/Performance%2C%20Theater%2C%20%26%20Dance',
          },
          {
            title: 'Art',
            url: 'https://events.colby.edu/live/json/events/group/Art'
          },
          {
            title: 'Music',
            url: 'https://events.colby.edu/live/json/events/group/Music'
          },
          {
            title: 'Cinema Studies',
            url: 'https://events.colby.edu/live/json/events/group/Cinema%20Studies'
          },
          {
            title: 'Performance, Theater, and Dance',
            url: 'https://events.colby.edu/live/json/events/group/Performance%2C%20Theater%2C%20%26%20Dance'
          },
          {
            title: 'Center for the Arts and Humanities',
            url: 'https://events.colby.edu/live/json/events/group/Center%20for%20Arts%20and%20Humanities'
          },
          {
            title: 'Creative Writing',
            url: 'https://events.colby.edu/live/json/events/group/Creative%20Writing'
          },
          {
            title: 'Museum of Art',
            url: 'https://events.colby.edu/live/json/events/group/Museum%20of%20Art'
          }
        ]
      }
    },
    async mounted() {
      this.endpoint = this.initialEndpoint;
      this.requestData(this.endpoint);
    },
    methods: {
      async requestData(endpoint) {
        const component = this;

        component.endpoint = endpoint;

        await axios.get(endpoint).then((outputa) => {
          console.log(outputa);

          component.items = outputa.data.map((item) => {
            return {
              date: item.date,
              title: item.title,
              time: item.date_time,
              url: item.url,
            };
          });

          if (component.limit) {
            component.items = component.items.slice(0, component.limit);
          }
        });
      }
    }
  }
</script>