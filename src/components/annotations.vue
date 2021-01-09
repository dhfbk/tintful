<template>
    <div class="cardShadow shadow-cardShadow dark:shadow-cardShadowDark rounded-2xl col-span-8 mt-8 p-6">
        <!-- <button
            class="text-primary  bg-transparent hover:bg-gray-200 transition-colors duration-100 ease-out ripple py-1 px-1 rounded focus:outline-none w-max flex content-center items-center mb-2"
            @click="$router.push({ path: '/edit' })"
        >
            <svg class="fill-current" style="width:24px;height:24px" viewBox="0 0 24 24">
                <path
                    d="M21.04 12.13C21.18 12.13 21.31 12.19 21.42 12.3L22.7 13.58C22.92 13.79 22.92 14.14 22.7 14.35L21.7 15.35L19.65 13.3L20.65 12.3C20.76 12.19 20.9 12.13 21.04 12.13M19.07 13.88L21.12 15.93L15.06 22H13V19.94L19.07 13.88M19 3C20.1 3 21 3.9 21 5V9L11 19V21H5C3.9 21 3 20.1 3 19V5C3 3.9 3.9 3 5 3H9.18C9.6 1.84 10.7 1 12 1C13.3 1 14.4 1.84 14.82 3H19M12 3C11.45 3 11 3.45 11 4C11 4.55 11.45 5 12 5C12.55 5 13 4.55 13 4C13 3.45 12.55 3 12 3Z"
                />
            </svg>
            Edit annotations
        </button> -->
        <div class="text-primaryDark text-xl w-full">Annotations</div>
        <div class="overflow-x-auto w-full">
            <div class="w-full grid grid-cols-4 text-center min-w-max gap-x-6 p-3">
                <div
                    @click=";(selectedTab = 0), $emit('opensheet')"
                    class="transition-colors  rounded-lg duration-150 cursor-pointer py-2 w-full px-2"
                    :class="
                        selectedTab == 0
                            ? 'text-primary shadow-insetShadow dark:shadow-insetShadowDark'
                            : 'text-gray-500 shadow-microShadow dark:shadow-microShadowDark'
                    "
                >
                    General Information
                </div>
                <div
                    @click=";(selectedTab = 1), $emit('closesheet')"
                    class="transition-colors  rounded-lg duration-150 cursor-pointer py-2 min-w-max px-2"
                    :class="
                        selectedTab == 1
                            ? 'text-primary shadow-insetShadow dark:shadow-insetShadowDark'
                            : 'text-gray-500 shadow-microShadow dark:shadow-microShadowDark'
                    "
                >
                    Part Of Speech
                </div>
                <div
                    @click=";(selectedTab = 2), $emit('closesheet')"
                    class="transition-colors  rounded-lg duration-150 cursor-pointer py-2 min-w-max px-2"
                    :class="
                        selectedTab == 2
                            ? 'text-primary shadow-insetShadow dark:shadow-insetShadowDark'
                            : 'text-gray-500 shadow-microShadow dark:shadow-microShadowDark'
                    "
                >
                    Named Entity Recognition
                </div>
                <div
                    @click=";(selectedTab = 3), $emit('closesheet')"
                    class="transition-colors rounded-lg duration-150 cursor-pointer py-2 min-w-max px-2"
                    :class="
                        selectedTab == 3
                            ? 'text-primary shadow-insetShadow dark:shadow-insetShadowDark'
                            : 'text-gray-500 shadow-microShadow dark:shadow-microShadowDark '
                    "
                >
                    Basic Dependencies
                </div>
                <!-- <div class="col-span-4">
                    <div class="h-1 w-1/4 bg-primary" :class="tabScroll"></div>
                </div> -->
            </div>
        </div>
        <general-info v-if="selectedTab == 0" @sheet="sheet" />
        <partOfSpeech v-else-if="selectedTab == 1" />
        <ner v-else-if="selectedTab == 2" />
        <dependencies v-else />
        <br />
    </div>
</template>

<script>
import partOfSpeech from './partOfSpeech.vue'
import ner from './ner.vue'
import generalInfo from './generalInfo.vue'
import dependencies from './dependencies.vue'
export default {
    data() {
        return {
            selectedTab: 0,
            tabScroll: 'transition-transform ease-out transform translate-x-0',
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
    },
}
</script>

<style></style>
