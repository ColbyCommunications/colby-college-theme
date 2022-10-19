<template>
  <div @click="test()">
    <slot />
  </div>
</template>

<script>
export default {
  name: 'accordion',
  data() {
    return {
      foo: true,
    };
  },
  methods: {
    test() {
      console.log('live');
    },
  },
  mounted() {
    console.log('Accordion has been mounted and is present on the page');
  },
}
</script>

<style lang="scss">
@import './_accordion.scss';
</style>