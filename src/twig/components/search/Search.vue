<template>
  <div class="search">
    <div class="flex justify-between mb-5">
      <h1
        class="font-extended font-normal text-24 leading-110 -tracking-3 text-indigo"
        v-text="'Search'"
      />
      <label class="relative flex text-[0] w-full max-w-sm">
        Search
        <svg class="absolute top-3 left-3 fill-indigo-800 w-2.5" viewBox="0 0 9.6 9.6">
          <path d="M3.6 1.2c-1.3 0-2.4 1.1-2.4 2.4C1.2 4.9 2.3 6 3.6 6 4.9 6 6 4.9 6 3.6c0-1.3-1.1-2.4-2.4-2.4zM0 3.6C0 1.6 1.6 0 3.6 0s3.6 1.6 3.6 3.6c0 .8-.2 1.5-.7 2.1l2.9 2.9c.2.2.2.6 0 .8-.2.2-.6.2-.8 0L5.7 6.5c-.6.5-1.3.7-2.1.7-2 0-3.6-1.6-3.6-3.6z" style="fill-rule:evenodd; clip-rule:evenodd;"/>
        </svg>
        <input
          class="w-full h-[34px] max-w-sm p-2.5 pl-7 border border-indigo-400 border-solid rounded-md font-body font-normal text-10 leading-130 text-indigo-800 placeholder-indigo-800 bg-white"
          type="text"
          id="search-input"
          name="search-input"
          placeholder="Search"
          v-model="searchTerm"
        />
      </label>
    </div>
    <div class="button-group flex justify-end flex-wrap gap-4 mb-10">
      <button
        v-for="(item, index) in suggestedQueryItems"
        @click="searchTerm = item.objectID"
        class="btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-300 font-body font-normal text-10 leading-130 text-indigo bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3 transition-all duration-200 ease-in-out"
      >
        <span class="btn__text">
          {{ item.objectID }}
          <div class="btn__border block bg-indigo h-px w-0 group-hover:w-full transition-all duration-200 ease-in-out"></div>
        </span>
      </button>
    </div>
    <p
      class="font-extended font-normal text-24 leading-110 -tracking-3 text-indigo"
      v-if="items.length == 0"
      v-text="'Enter search terms above...'"
    />
    <div class="article-grid grid grid-cols-8 gap-10 max-w-screen-xl w-full my-0 mx-auto">
      <div
        v-for="(item, index) in items"
        class="article-grid__item md:col-span-2 col-span-4"
      >
        <article class="article space-y-4">
          <div class="context w-full space-y-5">
            <div class="text-group">
              <div
                class="text-group__subheading font-extended font-bold text-12 leading-130 tracking-8 text-left text-azure uppercase"
                v-text="item.originIndexLabel"
              />
              <h2
                class="text-group__heading font-extended font-normal text-20 leading-110 -tracking-3 text-left text-indigo mt-2"
                v-html="highlight(item.post_title)"
              />
              <p
                class="text-group__p font-body font-normal text-12 md:text-12 leading-130 text-left text-indigo-800 mt-2"
                v-html="item.content ? `${highlight(item.content.substring(0, 120))} ...` : ''"
              />
            </div>
            <div class="button-group flex flex-wrap gap-4">
              <a
                class="btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-300 font-body font-normal text-10 leading-130 text-indigo bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3 transition-all duration-200 ease-in-out"
                :href="item.permalink"
              >
                <span class="btn__text">Read More<div class="btn__border block bg-indigo h-px w-0 group-hover:w-full transition-all duration-200 ease-in-out"></div></span>
              </a>
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>
</template>

<script>
import algoliasearch from 'algoliasearch/lite';
import Emitter from 'component-emitter';

export default {
  data() {
    return {
      searchTerm: '',
      checkedTerm: '',
      searchClient: algoliasearch(
        '2XJQHYFX2S',
        '63c304c04c478fd0c4cb1fb36cd666cb'
      ),
      index: undefined,
      queryIndex: undefined,
      suggestedQueryItems: [],
      items: [],
    };
  },
  created() {
    this.index = this.searchClient.initIndex('prod_colbyedu_aggregated');
    this.queryIndex = this.searchClient.initIndex('prod_colbyedu_aggregated_query_suggestions');

    setInterval(this.runSearch, 500);
  },
  mounted() {
    this.queryIndex.search('')
      .then(({ hits }) => {
        this.suggestedQueryItems = hits;
      });

    this.emitter.on('close-modal', () => {
      this.searchTerm = '';
    });
  },
  methods: {
    runSearch() {

      if (this.searchTerm !== this.checkedTerm) {

        if (this.searchTerm == '') {
          this.items = [];

          this.queryIndex.search('')
            .then(({ hits }) => {
              this.suggestedQueryItems = hits;
            });

          return;
        }

        this.index.search(this.searchTerm)
          .then(({ hits }) => {
            this.items = hits;
            this.checkedTerm = this.searchTerm;

            this.highlight();

            return hits;
          });

        this.queryIndex.search(this.searchTerm)
          .then(({ hits }) => {
            this.suggestedQueryItems = hits;
          });
      }
    },
    highlight(text) {
      let newText;
      const re = new RegExp(this.searchTerm, 'i');

      if (text) {
        newText = String(text).replace(re, `<mark>${text.match(re)}</mark>`);    
      } else {
        newText = String(text).replace(re, `<mark>${this.searchTerm}</mark>`);    
      }

      return newText;
    },
  }
}

</script>