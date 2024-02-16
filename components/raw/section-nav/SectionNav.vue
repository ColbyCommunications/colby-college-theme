<template>
  <div class="section-nav--animated" ref="container">
    <slot />
  </div>
</template>

<script>
import gsap from 'gsap';
import 'waypoints/lib/noframework.waypoints';

export default {
  mounted() {
    const component = this;

    this.waypoint = new Waypoint({
      element: this.$refs.container,
      handler() {
        component.animateButtons();
      },
      offset: 'bottom-in-view',
    })
  },
  methods: {
    animateButtons() {
      const target = this.$refs.container.querySelectorAll('li');

      gsap.to(
        target,
        {
          delay: 0.6,
          duration: 0.4,
          stagger: 0.1,
          opacity: 1,
          x: 0,
          ease: 'power3.easeIn',
        });
    },
  }
}
</script>

<style lang="scss">
.section-nav--animated {
  li {
    opacity: 0;
    transform: translate(20px, 0);
  }
}
</style>