<template>
    <div class="shadow-md dark:bg-dark01dp p-3 md:p-4 col-span-8 rounded-lg mb-6">
        <div
            class="flex w-full flex-row justify-between content-center items-center cursor-pointer"
            @click.stop=";(isOpen = !isOpen) && (selectedTab = 0)"
            @mouseenter="hoverCard = true"
            @mouseleave="hoverCard = false"
        >
            <h3 class="text-primary dark:text-primaryLight text-xl w-full">Show raw text and json</h3>
            <button class="bg-transparent ripple py-1 px-1 rounded focus:outline-none w-max justify-self-end">
                <svg
                    class="transition-transform duration-300 ease-out fill-current text-primary dark:text-primaryLight"
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
        <transition name="slide-toggle">
            <div v-if="isOpen">
                <div class="w-full grid grid-cols-2 text-center">
                    <div
                        @click="selectedTab = 0"
                        class="transition-colors ease-out duration-100 hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer py-2 rounded-t"
                        :class="
                            selectedTab == 0
                                ? 'text-primary dark:text-primaryLight'
                                : 'dark:text-gray-300 text-gray-500'
                        "
                    >
                        Raw text
                    </div>
                    <div
                        @click="selectedTab = 1"
                        class="transition-colors ease-out duration-100 hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer py-2 rounded-t"
                        :class="
                            selectedTab == 1
                                ? 'text-primary dark:text-primaryLight'
                                : 'dark:text-gray-300 text-gray-500'
                        "
                    >
                        JSON data
                    </div>
                </div>
                <div class="h-1 w-1/2 bg-primary dark:bg-primaryLight" :class="tabScroll"></div>
                <div v-if="selectedTab == 0">
                    <button
                        @click="downloadFile('downloadText', 'raw_text.txt')"
                        class="my-2 bg-transparent ripple py-1 px-1 rounded focus:outline-none w-max flex flex-row content-center items-center text-primary dark:text-primaryLight transition-colors duration-150 ease-out hover:bg-gray-200 dark:hover:bg-gray-600"
                    >
                        <span>Download text</span>
                        <svg
                            class="fill-current text-primary dark:text-primaryLight ml-2"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            width="24px"
                            height="24px"
                        >
                            <path
                                d="M13,5V11H14.17L12,13.17L9.83,11H11V5H13M15,3H9V9H5L12,16L19,9H15V3M19,18H5V20H19V18Z"
                            />
                        </svg>
                    </button>
                    <a id="downloadText" style="display: none"></a>
                    {{ text }}
                </div>
                <div v-else>
                    <button
                        @click="downloadFile('downloadJSON', 'processed_text.json')"
                        class="my-2 bg-transparent ripple py-1 px-1 rounded focus:outline-none w-max flex flex-row content-center items-center text-primary dark:text-primaryLight transition-colors duration-150 ease-out hover:bg-gray-200 dark:hover:bg-gray-600"
                    >
                        <span>Download JSON</span>
                        <svg
                            class="fill-current text-primary dark:text-primaryLight ml-2"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            width="24px"
                            height="24px"
                        >
                            <path
                                d="M13,5V11H14.17L12,13.17L9.83,11H11V5H13M15,3H9V9H5L12,16L19,9H15V3M19,18H5V20H19V18Z"
                            />
                        </svg>
                    </button>
                    <a id="downloadJSON" style="display: none"></a>
                    <div class="overflow-x-auto" id="formatter"></div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import JSONFormatter from 'json-formatter-js'
export default {
    name: 'rawData',
    data() {
        return {
            isOpen: false,
            hoverCard: false,
            selectedTab: 0,
            tabScroll: 'transition-transform ease-out transform translate-x-0',
            downloadableJSON: {},
            text: localStorage.getItem('text'),
            processedData: JSON.parse(localStorage.getItem('processedText')),
        }
    },
    methods: {
        downloadFile(id, name) {
            var dataStr = ''
            if (id == 'downloadJSON') {
                dataStr = 'data:text/json;charset=utf-8,' + encodeURIComponent(this.downloadableJSON)
            } else {
                dataStr = 'data:text/json;charset=utf-8,' + encodeURIComponent(this.text)
            }
            var dlAnchorElem = document.getElementById(id)
            dlAnchorElem.setAttribute('href', dataStr)
            dlAnchorElem.setAttribute('download', name)
            dlAnchorElem.click()
        },
    },
    watch: {
        selectedTab: function() {
            if (this.selectedTab == 0) {
                this.tabScroll = 'transition-transform ease-out transform translate-x-0'
            } else {
                this.tabScroll = 'transition-transform ease-out transform translate-x-full'
                const formatter = new JSONFormatter(this.processedData)
                this.downloadableJSON = JSON.stringify(this.processedData, undefined, 2)
                setTimeout(() => {
                    document.getElementById('formatter').appendChild(formatter.render())
                }, 1)
            }
        },
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
