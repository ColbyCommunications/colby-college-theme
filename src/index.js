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
import ArticleGrid from './twig/components/article-grid/ArticleGrid.vue';

// Animated
import TextGroup from './twig/components/text-group/TextGroup.vue';
import ButtonGroup from './twig/components/button-group/ButtonGroup.vue';
import SectionNav from './twig/components/section-nav/SectionNav.vue';
import DarkInterstitialFact from './twig/components/dark-interstitial/DarkInterstitialFact.vue';
import SubpageNav from './twig/components/subpage-nav/SubpageNav.vue';
import AnimatedBorder from './twig/components/featured-post/AnimatedBorder.vue';

import './styles/styles.scss';

const embeds = document.getElementsByClassName('embed');
const mains = [];

// Array that sweeps the document for all embed components and removes them
Array.from(embeds).forEach((e) => {
    const embedMain = e.getElementsByClassName('embed__main')[0];
    mains.push(embedMain);
    e.removeChild(embedMain);
});

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
        TextGroup,
        ButtonGroup,
        SectionNav,
        DarkInterstitialFact,
        SubpageNav,
        AnimatedBorder,
        ArticleGrid,
    },
});

app.config.globalProperties.emitter = emitter;
app.use(InstantSearch);

app.mount('#app');

// Replace all embeds following Vue mounting
for (let i = 0; i < embeds.length; i += 1) {
    embeds[i].append(mains[i]);
}