<template>
  <div class="search" v-if="modalOpen">
    <ais-instant-search
      id="modal-top"
      ref="aisIS"
      index-name="prod_colbyedu_aggregated"
      :search-client="searchClient"
    >
      <ais-configure :hits-per-page.camel="1" />
      <!-- Widgets -->
      <!-- searchbox widget-->
      <searchbox ref="customSearchBox"></searchbox>
      <!-- query suggestions -->
      <div class="qs mb-12" role="region" aria-label="Query Suggestions`">
        <ais-index
          index-name="prod_colbyedu_aggregated_query_suggestions"
          index-id="colby-qs"
        >
          <ais-configure :hits-per-page.camel="8" />

          <ais-hits :transform-items="removeExactQueryQuerySuggestion">
            <template v-slot="{ items, sendEvent }">
              <ul class="button-group flex justify-end flex-wrap gap-4 mb-10">
                <li
                  v-for="item in items"
                  :key="item.objectID"
                  :aria-label="item.query"
                  @click="search(item.query)"
                  class="cursor-pointer btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-300 font-body font-normal text-10 leading-130 text-indigo bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3 transition-all duration-200 ease-in-out"
                >
                  <span class="btn__text">
                    <ais-highlight :hit="item" attribute="query" />
                  </span>
                </li>
              </ul>
            </template>
          </ais-hits>
        </ais-index>
      </div>
      <p
        class="font-extended font-normal text-24 leading-110 -tracking-3 text-indigo text-left"
        v-if="!query"
        v-text="'What are you looking for?'"
      />
      <div v-if="query">
        <ais-index index-name="prod_colbyedu_aggregated" index-id="results">
          <ais-configure :hits-per-page.camel="20" />
          <ais-state-results>
            <template v-slot="{ results: { hits, query } }">
              <ais-hits
                class="article-grid grid grid-cols-8 gap-10 max-w-screen-2xl w-full my-0 mx-auto pb-6"
                v-if="hits.length > 0"
              >
                <template #default="{ items, sendEvent }">
                  <div
                    v-for="item in items"
                    class="article-grid__item md:col-span-2 col-span-4"
                    v-bind:key="item.objectID"
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
                          >
                            <ais-highlight
                              attribute="cleaned_title"
                              :hit="item"
                            />
                          </h2>
                          <p
                            class="text-group__p font-body font-normal text-12 md:text-12 leading-130 text-left text-indigo-800 mt-2"
                          >
                            <ais-snippet attribute="content" :hit="item" />
                          </p>
                        </div>
                        <div class="button-group flex flex-wrap gap-4">
                          <a
                            class="btn group inline-flex flex-row items-center space-x-1.5 rounded border border-solid border-indigo-300 font-body font-normal text-10 leading-130 text-indigo bg-indigo-100 hover:bg-indigo-200 focus:bg-indigo-200 focus:outline focus:outline-2 focus:outline-canary outline-offset-[-1px] py-1 px-3 transition-all duration-200 ease-in-out"
                            :href="item.permalink"
                          >
                            <span class="btn__text"
                              >Read More
                              <div
                                class="btn__border block bg-indigo h-px w-0 group-hover:w-full transition-all duration-200 ease-in-out"
                              ></div
                            ></span>
                          </a>
                        </div>
                      </div>
                    </article>
                  </div>
                </template>
              </ais-hits>
              <div v-else>
                <p
                  class="font-extended font-normal text-24 leading-110 -tracking-3 text-indigo"
                >
                  No results found for the query: <q>{{ this.query }}</q>
                </p>
              </div>
            </template>
          </ais-state-results>
        </ais-index>
      </div>
    </ais-instant-search>
  </div>
</template>

<script>
import algoliasearch from "algoliasearch/lite";
import Searchbox from "./Searchbox.vue";
export default {
  components: {
    Searchbox,
  },
  mounted() {
    this.emitter.on("close-modal", () => {
      this.modalOpen = false;
    });
    this.emitter.on("open-modal", () => {
      this.modalOpen = true;
    });
  },
  data() {
    return {
      searchClient: algoliasearch(
        "2XJQHYFX2S",
        "63c304c04c478fd0c4cb1fb36cd666cb"
      ),
      modalOpen: false,
      query: "",
    };
  },
  methods: {
    search(query) {
      this.query = query;
      this.$refs.customSearchBox.currentRefinement = query;
    },
    removeExactQueryQuerySuggestion(items) {
      if ("query" in this.$refs.aisIS.instantSearchInstance.helper.state) {
        const currentQuery =
          this.$refs.aisIS.instantSearchInstance.helper.state.query.toLowerCase();
        this.query = currentQuery;
        return items.filter(
          (item) => item.query.toLowerCase() !== currentQuery
        );
      }
      return items;
    },
  },
};
</script>
