<template>
    <transition name="fade-bg" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full dark:bg-black bg-gray-900 bg-opacity-25 customZ"
            @click="close"
        >
            <transition name="fade" appear>
                <div
                    class="bg-white dark:bg-bgDark rounded-lg w-5/6 max-w-3xl max-h-96 h-auto overflow-y-auto overscroll-none"
                    @click.stop
                >
                    <div class="p-4">
                        <div class="flex w-full">
                            <div class="text-primary font-bold text-lg text-primary">Confirm changes</div>
                            <span class="ripple ml-auto rounded hover:bg-gray-200 dark:hover:bg-gray-700 p-1" @click="close">
                                <svg
                                    class="m-auto fill-current text-gray-700 dark:text-gray-200 w-6 h-6 cursor-pointer"
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
                        <div class="float-right pb-4">
                            <button
                                class="font-medium ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none bg-transparent text-text-primary py-2 px-4 rounded mr-2"
                                @click="close"
                            >
                                CANCEL
                            </button>
                            <button
                                class="font-medium text-primary ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none bg-transparent py-2 px-4 rounded"
                                @click="ok"
                            >
                                PROCEED
                            </button>
                        </div>
                    </div>
                </div>
            </transition>
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
        ok() {
            this.$emit('confirm')
        },
    },
}
</script>

<style scoped>
.customZ {
    z-index: 990;
}
.dark .dark\:bg-black {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
