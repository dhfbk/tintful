<template>
    <div
        class="w-full rounded-lg shadow-md p-4 mx-auto bg-white mt-2 col-span-8 transition-shadow duration-150 ease-out"
        :class="hoverCard ? 'shadow-lg' : ''"
    >
        <div
            class="flex w-full flex-row justify-between content-center items-center cursor-pointer"
            @click.stop=";(isOpen = !isOpen) && (selectedTab = 0)"
            @mouseenter="hoverCard = true"
            @mouseleave="hoverCard = false"
        >
            <h3 class="font-bold">Show raw text and json</h3>
            <button class="bg-transparent ripple py-1 px-1 rounded focus:outline-none w-max justify-self-end">
                <svg
                    class="transition-transform duration-300 ease-out"
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
                        class="transition-colors ease-out duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t"
                        :class="selectedTab == 0 ? 'text-primary' : 'text-gray-500'"
                    >
                        Raw text
                    </div>
                    <div
                        @click="selectedTab = 1"
                        class="transition-colors ease-out duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t"
                        :class="selectedTab == 1 ? 'text-primary' : 'text-gray-500'"
                    >
                        JSON data
                    </div>
                </div>
                <div class="h-1 w-1/2 bg-primary" :class="tabScroll"></div>
                <p v-if="selectedTab == 0">
                    <button
                        @click="downloadFile('downloadText', 'raw_text.txt')"
                        class="my-1 bg-transparent ripple py-1 px-1 rounded focus:outline-none w-max flex flex-row content-center items-center text-primary transition-colors duration-150 ease-out hover:bg-gray-100"
                    >
                        <span>Download text</span>
                        <svg
                            class="fill-current text-primary ml-2"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            width="24px"
                            height="24px"
                        >
                            <path
                                d="M8,13H10.55V10H13.45V13H16L12,17L8,13M19.35,10.04C21.95,10.22 24,12.36 24,15A5,5 0 0,1 19,20H6A6,6 0 0,1 0,14C0,10.91 2.34,8.36 5.35,8.04C6.6,5.64 9.11,4 12,4C15.64,4 18.67,6.59 19.35,10.04M19,18A3,3 0 0,0 22,15C22,13.45 20.78,12.14 19.22,12.04L17.69,11.93L17.39,10.43C16.88,7.86 14.62,6 12,6C9.94,6 8.08,7.14 7.13,8.97L6.63,9.92L5.56,10.03C3.53,10.24 2,11.95 2,14A4,4 0 0,0 6,18H19Z"
                            />
                        </svg>
                    </button>
                    <a id="downloadText" style="display: none"></a>
                    {{ text }}
                </p>
                <div v-else>
                    <button
                        @click="downloadFile('downloadJSON', 'processed_text.json')"
                        class="my-1 bg-transparent ripple py-1 px-1 rounded focus:outline-none w-max flex flex-row content-center items-center text-primary transition-colors duration-150 ease-out hover:bg-gray-100"
                    >
                        <span>Download JSON</span>
                        <svg
                            class="fill-current text-primary ml-2"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            width="24px"
                            height="24px"
                        >
                            <path
                                d="M8,13H10.55V10H13.45V13H16L12,17L8,13M19.35,10.04C21.95,10.22 24,12.36 24,15A5,5 0 0,1 19,20H6A6,6 0 0,1 0,14C0,10.91 2.34,8.36 5.35,8.04C6.6,5.64 9.11,4 12,4C15.64,4 18.67,6.59 19.35,10.04M19,18A3,3 0 0,0 22,15C22,13.45 20.78,12.14 19.22,12.04L17.69,11.93L17.39,10.43C16.88,7.86 14.62,6 12,6C9.94,6 8.08,7.14 7.13,8.97L6.63,9.92L5.56,10.03C3.53,10.24 2,11.95 2,14A4,4 0 0,0 6,18H19Z"
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
