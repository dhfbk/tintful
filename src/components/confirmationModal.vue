<template>
    <transition name="fade" mode="out-in" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-25 customZ"
            @click="close"
        >
            <div class="bg-white rounded-lg w-5/6 max-w-3xl max-h-40 h-5/6 overflow-y-auto overscroll-none" @click.stop>
                <div class="flex flex-col p-4">
                    <div class="flex w-full">
                        <div class="text-primary font-bold text-lg text-primary">Confirm changes</div>
                        <span class="ripple ml-auto rounded hover:bg-gray-200 p-1" @click="close">
                            <svg
                                class="m-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 18 18"
                            >
                                <path
                                    d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                                />
                            </svg>
                            <span class="sr-only">Close dialog</span>
                        </span>
                    </div>
                    <div class="py-2" v-html="msg"></div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'confirmationModal',
    props: { msg: String },
    methods: {
        close() {
            this.$emit('close')
        },
    },
}
</script>

<style scoped>
.fade-enter-active {
    transition: opacity 0.25s ease-out !important;
}
.fade-leave-active {
    transition: opacity 0.2s ease-out !important;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
.customZ {
    z-index: 990;
}
</style>
