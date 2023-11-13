<template>
  <div ref="carousel">
    <slot
      :activeSlide="activeSlide"
      :changeSlide="changeSlide"
      :pauseCarousel="pauseCarousel"
      :playCarousel="playCarousel"
    />
  </div>
</template>

<script>
import Glide from "@glidejs/glide";

export default {
  data() {
    return {
      endpoint: undefined,
      activeSlide: 0,
      window: undefined,
      glide: undefined,
      featuredNews: undefined,
    };
  },
  props: {
    carousel: {
      type: Boolean,
      required: false,
      default: false,
    },
    perView: {
      type: Number,
      required: false,
      default: 1,
    },
    gap: {
      type: Number,
      required: false,
      default: 0,
    },
  },
  mounted() {
    if (this.carousel) {
      this.renderGlide();
    }
  },
  methods: {
    changeSlide(s) {
      if (s == "next") {
        s = ">";
      } else {
        s = "<";
      }

      this.glide.go(s);
    },
    pauseCarousel() {
      if (this.glide) {
        this.glide.pause();
      }
    },
    playCarousel() {
      if (this.glide) {
        this.glide.play();
      }
    },
    renderGlide() {
      // JS was apparently initializing faster than
      // Twig could render in this instance, returning
      // a window element without it's slide children.
      // To Remedy this, I have applied a timeout.

      setTimeout(() => {
        this.window = this.$refs.carousel.querySelector("[data-glide-window]");

        if (this.window) {
          this.glide = new Glide(this.window, {
            type: "carousel",
            gap: this.gap,
            animationDuration: 600,
            autoplay: 4000,
            perView: this.perView,
          });

          this.glide.on("run", () => {
            this.activeSlide = this.glide.index;
          });

          this.glide.mount();
        }
      }, 100);
    },
    decodeHtmlEntities(input) {
      const doc = new DOMParser().parseFromString(input, "text/html");
      return doc.documentElement.textContent;
    },
  },
}
</script>

<style lang="scss">
@import "node_modules/@glidejs/glide/src/assets/sass/glide.core";
</style>