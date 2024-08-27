<template>
    <header>
        <slot :active="active" :toggleActive="toggleActive" :checkUrl="checkUrl" />
    </header>
</template>

<script>
    import Modal from '../modal/Modal.vue';
    import Search from '../search/Search.vue';

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
                const pathSegments = window.location.pathname.split('/');
                if (pathSegments.length > 1) {
                    // Get the first segment after the initial slash
                    const firstSegment = pathSegments[1].toLowerCase();
                    const formattedTitle = (title.replace(/\s+/g, '-') || '').toLowerCase();
                    return firstSegment.indexOf(formattedTitle) > -1;
                }
                return false;
            },
        },
    };
</script>
