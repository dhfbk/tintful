<template>
    <div class="dark:bg-dark01dp shadow-md rounded-lg col-span-8 p-3 md:p-4">
        <!-- <button
            class="text-primary dark:text-primaryLight  bg-transparent hover:bg-gray-200 transition-colors duration-100 ease-out ripple py-1 px-1 rounded focus:outline-none w-max flex content-center items-center mb-2"
            @click="$router.push({ path: '/edit' })"
        >
            <svg class="fill-current" style="width:24px;height:24px" viewBox="0 0 24 24">
                <path
                    d="M21.04 12.13C21.18 12.13 21.31 12.19 21.42 12.3L22.7 13.58C22.92 13.79 22.92 14.14 22.7 14.35L21.7 15.35L19.65 13.3L20.65 12.3C20.76 12.19 20.9 12.13 21.04 12.13M19.07 13.88L21.12 15.93L15.06 22H13V19.94L19.07 13.88M19 3C20.1 3 21 3.9 21 5V9L11 19V21H5C3.9 21 3 20.1 3 19V5C3 3.9 3.9 3 5 3H9.18C9.6 1.84 10.7 1 12 1C13.3 1 14.4 1.84 14.82 3H19M12 3C11.45 3 11 3.45 11 4C11 4.55 11.45 5 12 5C12.55 5 13 4.55 13 4C13 3.45 12.55 3 12 3Z"
                />
            </svg>
            Edit annotations
        </button> -->
        <div class="text-primary dark:text-primaryLight text-xl w-full tracking-wide">Annotations</div>
        <div class="overflow-x-auto w-full">
            <div class="w-full grid grid-cols-4 text-center min-w-max p-2">
                <div
                    @click=";(selectedTab = 0), $emit('opensheet')"
                    class="transition-colors rounded-t duration-100 cursor-pointer py-2 min-w-max px-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                    :class="
                        selectedTab == 0 ? 'text-primary dark:text-primaryLight' : 'dark:text-gray-300 text-gray-500'
                    "
                >
                    General Information
                </div>
                <div
                    @click=";(selectedTab = 1), $emit('closesheet')"
                    class="transition-colors rounded-t duration-100 cursor-pointer py-2 min-w-max px-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                    :class="
                        selectedTab == 1 ? 'text-primary dark:text-primaryLight' : 'dark:text-gray-300 text-gray-500'
                    "
                >
                    Part Of Speech
                </div>
                <div
                    @click=";(selectedTab = 2), $emit('closesheet')"
                    class="transition-colors rounded-t duration-100 cursor-pointer py-2 min-w-max px-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                    :class="
                        selectedTab == 2 ? 'text-primary dark:text-primaryLight' : 'dark:text-gray-300 text-gray-500'
                    "
                >
                    Named Entity Recognition
                </div>
                <div
                    @click=";(selectedTab = 3), $emit('closesheet')"
                    class="transition-colors rounded-t duration-100 cursor-pointer py-2 min-w-max px-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                    :class="
                        selectedTab == 3 ? 'text-primary dark:text-primaryLight' : 'dark:text-gray-300 text-gray-500'
                    "
                >
                    Basic Dependencies
                </div>
                <div class="col-span-4">
                    <div class="h-1 w-1/4 bg-primary dark:bg-primaryLight" :class="tabScroll"></div>
                </div>
            </div>
        </div>
        <general-info v-show="selectedTab == 0" @sheet="sheet" />
        <partOfSpeech v-show="selectedTab == 1" />
        <ner v-show="selectedTab == 2" />
        <dependencies v-if="selectedTab == 3" :refresh="refreshBrat" />
        <br />
    </div>
</template>

<script>
import partOfSpeech from './partOfSpeech.vue'
import ner from './ner.vue'
import generalInfo from './generalInfo.vue'
import dependencies from './dependencies.vue'
export default {
    props: { sheetMode: String },
    data() {
        return {
            selectedTab: 0,
            tabScroll: 'transition-transform ease-out transform translate-x-0',
            refreshBrat: false,
        }
    },
    components: {
        partOfSpeech,
        ner,
        generalInfo,
        dependencies,
    },
    methods: {
        sheet(arr) {
            this.$emit('sheet', arr)
        },
    },
    watch: {
        selectedTab: function() {
            if (this.selectedTab == 0) {
                this.tabScroll = 'transition-transform ease-out transform translate-x-0'
            } else if (this.selectedTab == 1) {
                this.tabScroll = 'transition-transform ease-out transform translate-x-full'
            } else {
                this.tabScroll =
                    'transition-transform ease-out transform translate-x-' + this.selectedTab.toString() + 'full'
            }
        },
        sheetMode() {
            this.refreshBrat = true
            setTimeout(() => {
                this.refreshBrat = false
            }, 100)
        },
    },
}
</script>

<style></style>
