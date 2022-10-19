<template>
  <slot
    v-if="renderApi == false"
  />
  <div
    v-if="renderApi"
  >

  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      items: [],
    };
  },
  async mounted() {
    if (this.renderApi) {
      await axios.get('https://www.colby.edu/communications/feeds/majors_minors.json')
        .then(outputa => {
          console.log(outputa);
        });
    }
  },
  props: {
    renderApi: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
}
</script>