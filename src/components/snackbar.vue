<template>
    <transition name="fade" appear>
        <div class="fixed anchor w-max">
            <div class="flex items-center bg-gray-800 dark:bg-gray-200 px-4 py-2 shadow-md mb-2 rounded">
                <div class="text-center text-white dark:text-black">{{ msg }}</div>
                <button
                    class="transition-colors text-primaryLight dark:text-primary duration-100 ease-out hover:bg-gray-600 dark:hover:bg-gray-300 rounded px-1 py-1 ml-3 cursor-pointer ripple focus:outline-none"
                    @click="$emit('close')"
                >
                    CLOSE
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
.anchor {
    bottom: 3%;
    left: 50%;
    -webkit-transform: translateY(50%) !important;
    transform: translateY(50%) !important;
    -webkit-transform: translateX(-50%) !important;
    transform: translateX(-50%) !important;
    z-index: 999;
}
</style>
