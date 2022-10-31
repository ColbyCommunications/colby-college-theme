<template>
  <div />
</template>

<script>
import algoliasearch from 'algoliasearch/lite';
// import { createInsightsMiddleware } from 'instantsearch.js/es/middlewares';
// const insightsMiddleware = createInsightsMiddleware({
//   insightsClient: aa,
// });

// aa('init', {
//   appId: '2XJQHYFX2S',
//   apiKey: '63c304c04c478fd0c4cb1fb36cd666cb',
//   useCookie: true,
//   cookieDuration: 15552000000,
// });

export default {
  data() {
    return {
      // middlewares: [insightsMiddleware],
      searchClient: algoliasearch(
        '2XJQHYFX2S',
        '63c304c04c478fd0c4cb1fb36cd666cb'
      ),
      index: undefined,
    };
  },
  mounted() {
    this.index = this.searchClient.initIndex('prod_colbyedu_aggregated');
    console.log(this.index);

    this.index
      .search('Athletics')
      .then(({ hits }) => {
        console.log(hits);
      })
      .catch(err => {
        console.log(err);
      });
  },
}

</script>