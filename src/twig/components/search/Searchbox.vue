<template>
    <div class="mb-5 w-full flex items-center border-b border-black pb-4">
        <div v-if="state" :class="`${suit()} w-full`">
            <slot
                :current-refinement="currentRefinement"
                :is-search-stalled="state.isSearchStalled"
                :refine="state.refine"
            >
                <search-input
                    id="site-search-searchbox"
                    @focus="$emit('focus', $event)"
                    @blur="$emit('blur', $event)"
                    @reset="$emit('reset')"
                    :placeholder="placeholder"
                    :show-loading-indicator="showLoadingIndicator"
                    :should-show-loading-indicator="state.isSearchStalled"
                    :submit-title="submitTitle"
                    :reset-title="resetTitle"
                    :class-names="classNames"
                    v-model="currentRefinement"
                >
                    <slot name="loading-indicator" />

                    <slot name="submit-icon" />
                    <slot name="reset-icon" />
                </search-input>
            </slot>
        </div>
    </div>
</template>
<script>
    import { connectSearchBox } from 'instantsearch.js/es/connectors';
    import { createSuitMixin } from 'vue-instantsearch/vue3/es';
    import { createWidgetMixin } from 'vue-instantsearch/vue3/es';
    import SearchInput from './SearchInput.vue';

    export default {
        name: 'CustomSearchBox',
        components: {
            SearchInput,
        },
        mixins: [
            createWidgetMixin(
                {
                    connector: connectSearchBox,
                },
                {
                    $$widgetType: 'ais.searchBox',
                }
            ),
            createSuitMixin({ name: 'SearchBox' }),
        ],
        props: {
            placeholder: {
                type: String,
                default: 'Search here…',
            },
            autofocus: {
                type: Boolean,
                default: false,
            },
            showLoadingIndicator: {
                type: Boolean,
                default: false,
            },
            submitTitle: {
                type: String,
                default: 'Search',
            },
            resetTitle: {
                type: String,
                default: 'Clear',
            },
            value: {
                type: String,
                default: undefined,
            },
            modelValue: {
                type: String,
                default: undefined,
            },
        },
        data() {
            return {
                localQuery: '',
            };
        },
        computed: {
            currentRefinement: {
                get() {
                    return this.localQuery;
                },
                set(val) {
                    this.localQuery = val;
                    this.state.refine(this.localQuery);
                },
            },
        },
    };
</script>
