<template>
  <div ref="overlayHero">
    <slot
      :active="active"
      :setActive="setActive"
    />
  </div>
</template>

<script>
export default {
  data() {
    return {
      active: false,
    };
  },
  methods: {
    setActive() {
      this.active = true;
      this.$refs.overlayHero.getElementsByClassName('video')[0].click();
    },
  },
  mounted() {
    console.log('wae');
  }
}
</script>