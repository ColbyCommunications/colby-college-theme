import { createApp } from 'vue/dist/vue.esm-bundler';
import mitt from 'mitt';
import { $vfm, VueFinalModal, ModalsContainer } from 'vue-final-modal';
import InstantSearch from 'vue-instantsearch/vue3/es';

import Header from './twig/components/header/Header.vue';
import OverlayHero from './twig/components/overlay-hero/OverlayHero.vue';
import Carousel from './twig/components/carousel/Carousel.vue';
import Video from './twig/components/video/Video.vue';
import IndexSection from './twig/components/index-section/IndexSection.vue';
import Table from './twig/components/table/Table.vue';
import ContextArticleGrid from './twig/components/context-article-grid/ContextArticleGrid.vue';
import Search from './twig/components/search/Search.vue';
import Modal from './twig/components/modal/Modal.vue';
import Accordion from './twig/components/accordion/Accordion.vue';

import './styles/styles.scss';

const emitter = mitt();
const app = createApp({
  components: {
    Header,
    OverlayHero,
    Carousel,
    Video,
    IndexSection,
    Table,
    ContextArticleGrid,
    Search,
    Modal,
    VueFinalModal,
    ModalsContainer,
    Accordion,
  },
});

app.config.globalProperties.emitter = emitter;
app.use(InstantSearch);

app.mount('#app');
