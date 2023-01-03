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
            class="absolute py-2.5"
            :class="{ 'right-5 top-7': this.full == true, 'top-3 right-3': this.full == false }"
            @click="closeModal()"
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
        @click="toggleModal()"
    >
        <slot name="button" :showModal="showModal" />
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
        methods: {
            toggleModal() {
                this.showModal = !this.showModal;

                if (this.showModal == false) {
                    this.emitter.emit('close-modal', {});
                } else {
                    this.emitter.emit('open-modal', {});
                }
            },
            closeModal() {
                this.showModal = false;
                this.emitter.emit('close-modal', {});
            },
        },
    };
</script>
