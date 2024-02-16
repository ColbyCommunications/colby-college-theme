<template>
  <div @click="setActive()" ref="video">
    <slot
      :active="active"
      :setActive="setActive"
    />
  </div>
</template>

<script>
import YTPlayer from 'yt-player';

export default {
  data() {
    return {
      active: false,
      player: null,
    };
  },
  mounted() {
    this.player = new YTPlayer(this.$refs.video.querySelector('.iframe'));

    this.player.load(this.id);
  },
  props: {
    id: {
      type: String,
      required: true,
    },
  },
  methods: {
    setActive() {
      this.active = true;

      this.player.play();
    },
  },
}
</script>