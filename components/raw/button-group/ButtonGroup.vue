<template>
  <div class="button-group--animated" ref="container">
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
      const target = this.$refs.container.querySelectorAll('.btn');

      gsap.to(
        target,
        {
          delay: 1,
          duration: 0.2,
          stagger: 0.08,
          opacity: 1,
          x: 0,
          ease: 'power3.easeIn',
        });
    },
  }
}
</script>

<style lang="scss">
.button-group--animated {
  .btn {
    opacity: 0;
    transform: translate(50px, 0);
  }
}
</style>