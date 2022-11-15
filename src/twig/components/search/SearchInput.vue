<template>
    <div class="flex justify-between flex-col md:flex-row">
        <h1
            class="font-extended font-normal text-24 leading-110 -tracking-3 text-indigo mb-5 md:mb-0"
            v-text="'Search'"
        />
        <form
            action=""
            role="search"
            novalidate
            :class="suit('form')"
            @submit.prevent="onFormSubmit"
            @reset.prevent="onFormReset"
            class="flex w-full md:w-1/4 justify-items-end"
        >
            <label class="relative flex text-[0] w-full max-w-sm">
                <svg class="absolute top-3 left-3 fill-indigo-800 w-2.5" viewBox="0 0 9.6 9.6">
                    <path
                        d="M3.6 1.2c-1.3 0-2.4 1.1-2.4 2.4C1.2 4.9 2.3 6 3.6 6 4.9 6 6 4.9 6 3.6c0-1.3-1.1-2.4-2.4-2.4zM0 3.6C0 1.6 1.6 0 3.6 0s3.6 1.6 3.6 3.6c0 .8-.2 1.5-.7 2.1l2.9 2.9c.2.2.2.6 0 .8-.2.2-.6.2-.8 0L5.7 6.5c-.6.5-1.3.7-2.1.7-2 0-3.6-1.6-3.6-3.6z"
                        style="fill-rule: evenodd; clip-rule: evenodd"
                    />
                </svg>
                <input
                    class="w-full h-[34px] max-w-sm p-2.5 pl-7 border border-indigo-400 border-solid rounded-md font-body font-normal text-10 leading-130 text-indigo-800 placeholder-indigo-800 bg-white"
                    name="search-input"
                    placeholder="Search"
                    ref="input"
                    type="search"
                    autocorrect="off"
                    autocapitalize="off"
                    autocomplete="off"
                    spellcheck="false"
                    required
                    maxlength="512"
                    aria-label="Search"
                    :class="suit('input')"
                    :value="value || modelValue"
                    @focus="$emit('focus', $event)"
                    @blur="$emit('blur', $event)"
                    @input="
                        $emit('input', $event.target.value);
                        $emit('update:modelValue', $event.target.value);
                    "
                />
            </label>
        </form>
    </div>
</template>

<script>
    import { createSuitMixin } from 'vue-instantsearch/vue3/es';

    export default {
        name: 'CustomSearchInput',
        mixins: [createSuitMixin({ name: 'SearchBox' })],
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
            shouldShowLoadingIndicator: {
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
                required: false,
                default: undefined,
            },
            modelValue: {
                type: String,
                required: false,
                default: undefined,
            },
        },
        emits: ['input', 'update:modelValue', 'blur', 'focus', 'reset'],
        data() {
            return {
                query: '',
            };
        },
        methods: {
            onFormSubmit() {
                const input = this.$refs.input;
                input.blur();
            },
            onFormReset() {
                this.$emit('input', '');
                this.$emit('update:modelValue', '');
                this.$emit('reset');
            },
        },
    };
</script>
