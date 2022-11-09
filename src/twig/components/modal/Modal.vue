<template>
    <vue-final-modal
        v-model="showModal"
        :class="classes"
        :classes="{
            '!px-0': this.full == true,
            'modal__container flex justify-center items-center px-5': true,
        }"
        :content-class="{
            'w-full max-w-none h-full max-h-none': this.full == true,
            'max-w-xl max-h-96 rounded': !this.full,
            'modal__content relative w-full overflow-hidden bg-white overflow-y-auto': true,
        }"
    >
        <button
            class="absolute top-5 right-3"
            :class="{ 'right-5': this.full == true }"
            @click="showModal = false"
        >
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
        <slot name="content" :showModal="showModal" />
    </vue-final-modal>
    <button
        class="text-left group active"
        :class="{
            '[&>span]:text-indigo-1000 [&>span>svg]:fill-indigo-1000': this.showModal == true,
        }"
        @click="showModal = !showModal"
    >
        <slot
          name="button"
          :showModal="showModal"
        />
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
