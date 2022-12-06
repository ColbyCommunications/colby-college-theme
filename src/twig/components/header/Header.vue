<template>
    <header>
        <slot :active="active" :toggleActive="toggleActive" :checkUrl="checkUrl" />
    </header>
</template>

<script>
    import Modal from '/src/twig/components/modal/Modal.vue';
    import Search from '/src/twig/components/search/Search.vue';

    export default {
        components: {
            Modal,
            Search,
        },
        data() {
            return {
                active: false,
            };
        },
        methods: {
            toggleActive() {
                const el = document.body;
                if (this.active) {
                    this.active = false;
                    el.classList.remove('no-scroll');
                } else {
                    this.active = true;
                    el.classList.add('no-scroll');
                }
            },
            checkUrl(title) {
                return window.location.href.indexOf((title || '').toLowerCase()) > -1;
            },
        },
    };
</script>
