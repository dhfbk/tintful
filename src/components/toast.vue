<template>
    <transition name="fade" appear>
        <div class="fixed topHalf w-max">
            <div
                class="flex items-center bg-yellow-500 py-2 px-2 sm:py-4 sm:px-4 shadow-md mb-2 rounded text-white select-none"
                v-if="isWarning.test(msg)"
            >
                <div class="max-w-xs text-center">{{ msg }}</div>
                <button
                    class="transition-colors duration-100 ease-out hover:bg-yellow-600 rounded-full px-1 py-1 ml-2 cursor-pointer ripple focus:outline-none"
                    @click="$emit('close')"
                >
                    <svg class="fill-current" style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path
                            fill="currentColor"
                            d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"
                        />
                    </svg>
                    <span class="sr-only">Close notification</span>
                </button>
            </div>
            <div
                class="flex items-center bg-red-500 py-2 px-2 sm:py-4 sm:px-4 shadow-md mb-2 rounded text-white"
                v-else-if="isError.test(msg)"
            >
                <div class="max-w-xs text-center">{{ msg }}</div>
                <button
                    class="transition-colors duration-100 ease-out hover:bg-red-600 rounded-full px-1 py-1 ml-2 cursor-pointer ripple focus:outline-none"
                    @click="$emit('close')"
                >
                    <svg class="fill-current" style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path
                            fill="currentColor"
                            d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"
                        />
                    </svg>
                    <span class="sr-only">Close notification</span>
                </button>
            </div>
            <div class="flex items-center bg-green-500 py-2 px-2 sm:py-4 sm:px-4 shadow-md mb-2 rounded text-white" v-else>
                <div class="max-w-xs text-center">{{ msg }}</div>
                <button
                    class="transition-colors duration-100 ease-out hover:bg-green-600 rounded-full px-1 py-1 ml-2 cursor-pointer ripple focus:outline-none"
                    @click="$emit('close')"
                >
                    <svg class="fill-current" style="width:24px;height:24px" viewBox="0 0 24 24">
                        <path
                            fill="currentColor"
                            d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"
                        />
                    </svg>
                    <span class="sr-only">Close notification</span>
                </button>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'notification',
    props: {
        msg: String,
    },
    data() {
        return {
            isError: /\bError\b/,
            isWarning: /\bWarning\b/,
        }
    },
}
</script>

<style scoped>
.topHalf {
    top: 5%;
    left: 98%;
    -webkit-transform: translateY(98%) !important;
    transform: translateY(98%) !important;
    -webkit-transform: translateX(-98%) !important;
    transform: translateX(-98%) !important;
    z-index: 999;
}
</style>
