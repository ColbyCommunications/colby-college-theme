<template>
  <div class="dark-interstitial__fact--animated" ref="container">
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
        component.animateFact();
      },
      offset: 'bottom-in-view',
    });
  },
  methods: {
    animateFact() {
      const heading = this.$refs.container.querySelector('h3');
      const paragraph = this.$refs.container.querySelector('p');

      gsap.to(
        heading,
        {
          duration: 0.4,
          opacity: 1,
          scale: 1,
          ease: 'power3.easeInOut',
          onComplete: () => {
            gsap.to(
              paragraph,
              {
                opacity: 1,
              });
          }
        });
    },
  },
}
</script>

<style lang="scss">
.dark-interstitial__fact--animated {
  h3 {
    display: inline-block;
    opacity: 0;
    transform: scale(0.6);
  }

  p {
    opacity: 0;
  }
}
</style>