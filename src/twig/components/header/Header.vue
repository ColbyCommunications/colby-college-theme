<template>
  <header>
    <slot
      :active="active"
      :toggleActive="toggleActive"
      :checkUrl="checkUrl"
    />
  </header>
</template>

<script>
export default {
  data() {
    return {
      active: false,
    };
  },
  methods: {
    toggleActive() {
      this.active = !this.active;
    },
    checkUrl(title) {
      return (window.location.href.indexOf((title || '').toLowerCase()) > -1);
    }
  }
}
</script>
