<template>
    <div class="rounded-lg shadow-md p-4 mx-auto bg-white col-span-8 lg:col-span-2 w-full">
        <h2 class="text-primary font-bold text-lg">General information</h2>
        <div class="flex flex-col relative h-full">
            <div class="flex flex-row justify-between content-center items-center w-full">
                <p>Sentences</p>
                <span class="px-2 py-1 bg-primary text-white rounded-full">{{ jsonData.sentences.length }}</span>
            </div>
            <div class="flex flex-row justify-between content-center items-center w-full mt-2">
                <p>Tokens</p>
                <span class="px-2 py-1 bg-primary text-white rounded-full">{{ jsonData.readability.tokenCount }}</span>
            </div>
            <div class="flex flex-row justify-between content-center items-center w-full mt-2">
                <p>Words</p>
                <span class="px-2 py-1 bg-primary text-white rounded-full">{{ jsonData.readability.wordCount }}</span>
            </div>
            <div class="flex flex-row justify-between content-center items-center w-full mt-2">
                <p>Content words</p>
                <span class="px-2 py-1 bg-primary text-white rounded-full">{{
                    jsonData.readability.contentWordSize
                }}</span>
            </div>
            <div class="flex flex-row justify-between content-center items-center w-full mt-2 mb-8 xl:hidden">
                <p>Text length (letters only)</p>
                <span class="px-2 py-1 bg-primary text-white rounded-full">{{
                    jsonData.readability.docLenLettersOnly
                }}</span>
            </div>
            <transition name="slide-toggle">
                <div v-if="isOpen" class="content w-full flex flex-wrap justify-center relative -mt-7">
                    <div class="flex-row justify-between content-center items-center w-full mb-2 hidden xl:flex">
                        <p class="w-max inline">Text length (letters only)</p>
                        <span class="px-2 py-1 bg-primary text-white rounded-full">{{
                            jsonData.readability.docLenLettersOnly
                        }}</span>
                    </div>
                    <div class="flex flex-row justify-between content-center items-center w-full mt-1 xl:mt-0">
                        <p>Text length (all characters)</p>
                        <span class="px-2 py-1 bg-primary text-white rounded-full">{{
                            jsonData.readability.docLenWithSpaces
                        }}</span>
                    </div>
                    <div class="flex flex-row justify-between content-center items-center w-full mt-2 mb-8">
                        <p>Text length (no spaces)</p>
                        <span class="px-2 py-1 bg-primary text-white rounded-full">{{
                            jsonData.readability.docLenWithoutSpaces
                        }}</span>
                    </div>
                </div>
            </transition>
            <div
                class="grid grid-cols-8 items-center cursor-pointer mb-6 absolute bottom-0 w-full"
                @click.stop="isOpen = !isOpen"
            >
                <p class="font-bold col-span-7">
                    {{ isOpen ? 'Hide' : 'Show more' }}
                </p>
                <button
                    class="col-span-1 bg-transparent ripple py-1 px-1 rounded focus:outline-none justify-self-end self-end"
                >
                    <svg
                        class="transition-transform duration-300 ease-out mx-auto"
                        :class="{ 'transform  rotate-180': isOpen }"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        width="24px"
                        height="24px"
                        fill="black"
                    >
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'infoCard',
    props: {
        jsonData: Object,
    },
    data() {
        return {
            isOpen: false,
        }
    },
}
</script>

<style scoped>
.slide-toggle-enter-active {
    transition: max-height 0.25s !important;
}

.slide-toggle-leave-active {
    transition: max-height 0.2s !important;
}

.slide-toggle-enter-active {
    max-height: 200px;
    overflow: hidden;
}

.slide-toggle-enter,
.slide-toggle-leave-active {
    max-height: 0;
    overflow: hidden;
}

.slide-toggle-leave {
    max-height: 200px;
    overflow: hidden;
}
</style>
