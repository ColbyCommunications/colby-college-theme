<template>
  <div class="border--animated" ref="container" />
</template>

<script>
import gsap from 'gsap';
import 'waypoints/lib/noframework.waypoints';

export default {
  data() {
    return {
      waypoint: undefined,
    };
  },
  mounted() {
    const component = this;

    this.waypoint = new Waypoint({
      element: this.$refs.container,
      handler() {
        component.animateBorder();
      },
      offset: 'bottom-in-view',
    })
  },
  methods: {
    animateBorder() {
      const target = this.$refs.container;

      gsap.to(
        target,
        {
          width: '100%',
          duration: 2.5,
          ease: 'Circ.easeInOut',
        });
    },
  },
}
</script>