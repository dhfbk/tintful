<template>
    <div v-if="show" class="grid grid-cols-8 gap-x-6">
        <modalInfo v-if="modal" @modal="modal = !modal" :mode="modalMode" :type="'results'" />
        <rawData />
        <!-- <infoCard :jsonData="processedData" /> -->
        <!--
        <div class="col-span-8 mb-4  text-primary dark:text-primaryLightDark text-xl tracking-wide">General Information</div>
        <span
            v-for="(i, x) in infoMiniCards.values"
            :key="x"
            class="cursor-default select-none col-span-4 md:col-span-2 2xl:col-span-1 dark:bg-dark01dp shadow-md rounded-md mb-4 p-2 md:p-3"
        >
            <p class=" text-xl tracking-wide sm:text-2xl md:text-4xl" style="font-family: 'Barlow', sans-serif">{{ i }}</p>
            <p class="text-gray-300 text-sm overflow-ellipsis block overflow-hidden">
                {{ infoMiniCards.keys[x] }}
            </p>
        </span>
        -->
        <div class="grid grid-cols-9 col-span-8 gap-x-6 gap-y-6 mb-6">
            <div class="col-span-9 lg:col-span-2 sm:col-span-4 dark:bg-dark01dp shadow-md rounded-lg p-3 md:p-4">
                <div class="col-span-8 mb-4 text-primary dark:text-primaryLight text-xl tracking-wide ">
                    General Information
                </div>

                <span v-for="(i, x) in infoMiniCards.values" :key="x" class="cursor-default select-none flex flex-col">
                    <span class="flex flex-row justify-between w-full items-center">
                        <p class="dark:text-gray-300 text-gray-600 text-md overflow-ellipsis overflow-hidden">
                            {{ infoMiniCards.keys[x] }}
                        </p>
                        <p
                            class="text-xl sm:text-2xl tracking-widest ml-2 font-semibold"
                            style="font-family: 'Barlow', sans-serif"
                        >
                            {{ i }}
                        </p></span
                    >
                    <div
                        class="h-px w-full dark:bg-dark02dp bg-gray-300 my-0.5"
                        v-if="x <= infoMiniCards.values.length - 2"
                    ></div>
                </span>
            </div>
            <div class="col-span-9 lg:col-span-3 sm:col-span-5">
                <levelsCard
                    :series="seriesReadability"
                    :options="chartOptionsReadability"
                    :title="'Readability'"
                    @modal="openModal"
                />
            </div>
            <div class="lg:col-span-4 col-span-9">
                <levelsCard
                    :series="seriesDifficulty"
                    :options="chartOptionsDifficulty"
                    :title="'Difficulty'"
                    @modal="openModal"
                />
            </div>
        </div>
        <!-- <div class="col-span-8 w-full mt-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16">
                <statsCard
                    :title="'Readability'"
                    :height="230"
                    :options="chartOptionsReadability"
                    :series="seriesReadability"
                    :id="'stats0'"
                    :dividerValue="100"
                    @modal=";(modal = !modal), (modalMode = 'readability')"
                />
                <statsCard
                    :title="'Text difficulty'"
                    :height="230"
                    :options="chartOptionsDifficulty"
                    :series="seriesDifficulty"
                    :id="'stats1'"
                    :dividerValue="1"
                    @modal=";(modal = !modal), (modalMode = 'difficulty')"
                />
            </div>
        </div> -->
        <annotations
            @sheet="sheetData"
            @closesheet="$refs.swipeableBottomSheet.setState('close')"
            @opensheet=";(info = ''), (values = []), (keys = []), sheetData('empty')"
            :sheetMode="sheetMode"
        />
        <swipeable-bottom-sheet ref="swipeableBottomSheet" class="md:hidden">
            <h1 v-if="info == ''" class="px-4 text-center">
                Click on a token for further information
            </h1>
            <div v-else class="px-4">
                <div class="font-light text-lg text-center">
                    Info on the token "
                    <span class="font-bold">{{ info.word }}</span>
                    "
                </div>
                <span v-for="(i, x) in keys" :key="x">
                    <span class="font-bold">{{ i }}</span
                    >: {{ values[x] }}<br
                /></span>
            </div>
        </swipeable-bottom-sheet>
    </div>
</template>

<script>
import annotations from '../components/annotations.vue'
//import statsCard from '../components/statsCard.vue'
//import infoCard from '../components/infoCard.vue'
import rawData from '../components/rawData.vue'
import modalInfo from '../components/modalInfo.vue'
import levelsCard from '../components/levelsCard.vue'
import { SwipeableBottomSheet } from 'vue-swipeable-bottom-sheet'
export default {
    name: 'result',
    props: {
        sheetMode: String,
    },
    components: {
        annotations,
        //statsCard,
        //infoCard,
        rawData,
        modalInfo,
        SwipeableBottomSheet,
        levelsCard,
    },
    data() {
        return {
            info: '',
            values: [],
            keys: [],
            processedData: {},
            modalMode: '',
            modal: false,
            show: false,
            isOpen: false,
            infoMiniCards: {},
            seriesReadability: [],
            seriesDifficulty: [],
            chartOptionsReadability: {
                colors: [],
                labels: [],
            },
            chartOptionsDifficulty: {
                colors: [],
                labels: [],
            },
        }
    },
    beforeCreate() {
        if (localStorage.getItem('text') === '' || localStorage.getItem('text') == undefined) {
            this.$router.replace({ name: 'home' })
        }
    },
    mounted() {
        if (
            localStorage.getItem('processedText') != null ||
            localStorage.getItem('processedText') != undefined ||
            localStorage.getItem('processedText') != ''
        ) {
            this.show = true
        }
    },
    created() {
        if (
            localStorage.getItem('processedText') != null ||
            localStorage.getItem('processedText') != undefined ||
            localStorage.getItem('processedText') != ''
        ) {
            this.$store.state.editableData = JSON.parse(localStorage.getItem('processedText'))
            this.processedData = JSON.parse(localStorage.getItem('processedText'))
            this.infoMiniCards = {
                keys: [
                    'Sentences',
                    'Tokens',
                    'Words',
                    'Content words',
                    'Text length (letters only)',
                    'Text length (all characters)',
                    'Text length (no spaces)',
                    'Hyphen count',
                ],
                values: [
                    this.processedData.sentences.length,
                    this.processedData.readability.tokenCount,
                    this.processedData.readability.wordCount,
                    this.processedData.readability.contentWordSize,
                    this.processedData.readability.docLenLettersOnly,
                    this.processedData.readability.docLenWithSpaces,
                    this.processedData.readability.docLenWithoutSpaces,
                    this.processedData.readability.hyphenCount,
                ],
            }
            this.seriesReadability = [
                this.processedData.readability.subordinateRatio * 100,
                this.processedData.readability.ttrValue * 100,
                this.processedData.readability.density * 100,
            ]
            this.chartOptionsReadability.labels = ['Subordinate ratio', 'Semantic richness', 'Lexical density']
            this.seriesDifficulty = [
                this.processedData.readability.measures.main,
                this.processedData.readability.measures.level1,
                this.processedData.readability.measures.level2,
                this.processedData.readability.measures.level3,
            ]
            this.chartOptionsDifficulty.labels = [
                this.processedData.readability.labels.main,
                'Level 1',
                'Level 2',
                'Level 3',
            ]
            var tmpCol = ''
            var min = 0
            var max = 0
            for (let i = 0; i < this.chartOptionsReadability.labels.length; i++) {
                if (this.seriesReadability[i] / 100 == this.processedData.readability.subordinateRatio) {
                    min = this.processedData.readability.minYellowValues.subordinateRatio
                    max = this.processedData.readability.maxYellowValues.subordinateRatio
                } else if (this.seriesReadability[i] / 100 == this.processedData.readability.ttrValue) {
                    min = this.processedData.readability.minYellowValues.ttrValue
                    max = this.processedData.readability.maxYellowValues.ttrValue
                } else {
                    min = this.processedData.readability.minYellowValues.density
                    max = this.processedData.readability.maxYellowValues.density
                }
                if (this.seriesReadability[i] / 100 >= min && this.seriesReadability[i] / 100 <= max) {
                    tmpCol = 'to-yellow-300 via-yellow-500 from-yellow-700'
                } else if (this.seriesReadability[i] / 100 < min) {
                    tmpCol = 'to-red-300 via-red-500 from-red-700'
                } else {
                    tmpCol = 'to-green-300 via-green-500 from-green-700'
                }
                this.chartOptionsReadability.colors.push(tmpCol)
            }
            for (let i = 0; i < this.chartOptionsDifficulty.labels.length; i++) {
                min = 40
                max = 80
                if (this.seriesDifficulty[i] >= min && this.seriesDifficulty[i] <= max) {
                    tmpCol = 'to-yellow-300 via-yellow-500 from-yellow-700'
                } else if (this.seriesDifficulty[i] < min) {
                    tmpCol = 'to-red-300 via-red-500 from-red-700'
                } else {
                    tmpCol = 'to-green-300 via-green-500 from-green-700'
                }
                this.chartOptionsDifficulty.colors.push(tmpCol)
            }
        }
    },
    methods: {
        sheetData(arr) {
            if (arr == 'empty') {
                this.$refs.swipeableBottomSheet.setState('half')
            } else {
                this.info = arr[0]
                this.keys = arr[1]
                this.values = arr[2]
                this.$refs.swipeableBottomSheet.setState('half')
            }
        },
        openModal(type) {
            this.modalMode = type.toLowerCase()
            this.modal = !this.modal
        },
    },
    watch: {
        sheetMode() {
            if (this.show) {
                if (this.sheetMode == 'dark') {
                    document.querySelector('.card').classList.add('cardDark')
                    document.querySelector('.card').classList.add('barDark')
                } else {
                    document.querySelector('.card').classList.remove('cardDark')
                    document.querySelector('.card').classList.remove('barDark')
                }
            }
        },
    },
}
</script>

<style scoped>
@import url('../css/brat.css');

.gradient::before {
    border-radius: 0.75rem;
    position: absolute;
    content: '';
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-image: linear-gradient(to top right, #0284e2, #98ecfd);
    z-index: -1;
    transition: opacity 0.15s ease-out;
    opacity: 0;
}

.gradient:hover::before {
    opacity: 1;
}
</style>
