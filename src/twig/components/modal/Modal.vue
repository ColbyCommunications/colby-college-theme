<template>
    <vue-final-modal
        v-model="showModal"
        :class="classes"
        classes="modal__container flex justify-center items-center px-5"
        content-class="modal__content relative w-full max-w-xl max-h-96 overflow-hidden bg-white rounded overflow-auto"
    >
        <button class="absolute top-5 right-3" @click="showModal = false">
            <span
                class="relative block w-6 h-0.5 bg-indigo transition-all duration-200 ease-in-out bg-transparent"
            >
                <span
                    class="absolute left-0 w-full h-full bg-indigo origin-center top-[-8px] transition-all duration-200 ease-in-out !top-0 rotate-45"
                />
                <span
                    class="absolute left-0 w-full h-full bg-indigo origin-center top-[8px] transition-all duration-200 ease-in-out !top-0 rotate-[-45deg]"
                />
            </span>
        </button>
        <slot name="content" />
    </vue-final-modal>
    <button class="text-left" @click="showModal = true">
        <slot name="button" />
    </button>
</template>

<script>
    import { $vfm, VueFinalModal, ModalsContainer } from 'vue-final-modal';

    export default {
        components: {
            VueFinalModal,
            ModalsContainer,
        },
        data() {
            return {
                showModal: false,
            };
        },
        props: {
            full: {
                type: Boolean,
                required: false,
            },
            classes: {
                type: String,
                required: false,
            },
        },
    };
</script>
