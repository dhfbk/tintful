<template>
    <div v-if="show" class="grid grid-cols-8 gap-x-8">
        <modalInfo v-if="modal" @modal="modal = !modal" :mode="modalMode" :type="'results'" />
        <!-- <infoCard :jsonData="processedData" /> -->
        <div class="col-span-8 mb-4  text-primaryDark text-xl">General Information</div>
        <span
            v-for="(i, x) in infoMiniCards.values"
            :key="x"
            class="col-span-4 md:col-span-2 2xl:col-span-1 miniCardShadow mb-4 p-4 transition-colors transition-transform transform hover:skew-y-1 bg-gradient-to-tl hover:to-primaryLight hover:via-primary hover:from-primaryDark text-gray-700 hover:text-white"
        >
            <div class=" font-bold text-4xl">{{ i }}</div>
            <div class="font-thin  text-sm">{{ infoMiniCards.keys[x] }}</div>
        </span>
        <div class="grid grid-cols-1 md:grid-cols-7 col-span-8 gap-x-8 mb-4">
            <div class="md:col-span-3 mt-6 ">
                <div class="miniCardShadow p-4 flex flex-col w-full">
                    <div class="w-full flex flex-row mb-4 ">
                        <div class="   text-primaryDark text-xl w-full">Readability</div>
                        <button
                            @click=";(modal = !modal), (modalMode = 'readability')"
                            class="ripple p-2 bg-transparent hover:bg-gray-200 rounded-full focus:outline-none transition-colors duration-100 ease-out"
                        >
                            <svg
                                class="fill-current text-primary"
                                style="width: 24px; height: 24px"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11V17Z"
                                />
                            </svg>
                            <span class="sr-only">Open information dialog</span>
                        </button>
                    </div>
                    <div class="flex flex-row">
                        <div
                            class="w-1/3 flex flex-col place-items-center"
                            v-for="(i, x) in seriesReadability"
                            :key="x"
                        >
                            <div class="insetShadow inline-block h-32 w-6 relative">
                                <div
                                    class="bg-gradient-to-tl to-primaryLight via-primary from-primaryDark w-6 absolute bottom-0"
                                    style="border-radius:10px"
                                    :style="{ height: i + '%' }"
                                ></div>
                            </div>
                            <div class="text-xs font-thin w-2/3 text-center text-gray-600 inline mt-2">
                                {{ chartOptionsReadability.labels[x] }}
                            </div>
                            <div class="text-sm w-2/3 text-center font-bold text-gray-600 inline mt-1">
                                {{ i.toFixed(2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:col-span-4 mt-6 ">
                <div class="miniCardShadow h-full p-4 flex flex-col w-full">
                    <div class="w-full  flex flex-row mb-4 ">
                        <div class=" text-primaryDark text-xl w-full">Difficulty</div>
                        <button
                            @click=";(modal = !modal), (modalMode = 'difficulty')"
                            class="ripple p-2 bg-transparent hover:bg-gray-200 rounded-full focus:outline-none transition-colors duration-100 ease-out"
                        >
                            <svg
                                class="fill-current text-primary"
                                style="width: 24px; height: 24px"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11V17Z"
                                />
                            </svg>
                            <span class="sr-only">Open information dialog</span>
                        </button>
                    </div>
                    <div class="flex flex-row">
                        <div class="w-1/3 flex flex-col place-items-center" v-for="(i, x) in seriesDifficulty" :key="x">
                            <div class="insetShadow inline-block h-32 w-6 relative ">
                                <div
                                    class="bg-gradient-to-tl to-primaryLight via-primary from-primaryDark w-6 absolute bottom-0"
                                    style="border-radius:10px"
                                    :style="{ height: i + '%' }"
                                ></div>
                            </div>
                            <div class="text-xs w-2/3 text-center font-thin text-gray-600 inline mt-2">
                                {{ chartOptionsDifficulty.labels[x] }}
                            </div>
                            <div class="text-sm w-2/3 text-center font-bold text-gray-600 inline mt-1">
                                {{ i.toFixed(2) }}
                            </div>
                        </div>
                    </div>
                </div>
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
            @opensheet=";(info = ''), (values = []), (keys = []), $refs.swipeableBottomSheet.setState('half')"
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
        <rawData />
    </div>
</template>

<script>
import annotations from '../components/annotations.vue'
//import statsCard from '../components/statsCard.vue'
//import infoCard from '../components/infoCard.vue'
import rawData from '../components/rawData.vue'
import modalInfo from '../components/modalInfo.vue'
import { SwipeableBottomSheet } from 'vue-swipeable-bottom-sheet'
export default {
    name: 'result',
    components: {
        annotations,
        //statsCard,
        //infoCard,
        rawData,
        modalInfo,
        SwipeableBottomSheet,
    },
    data() {
        return {
            info: '',
            values: [],
            keys: [],
            processedData: JSON.parse(localStorage.getItem('processedText')),
            modalMode: '',
            modal: false,
            show: false,
            isOpen: false,
            infoMiniCards: {},
            seriesReadability: [],
            seriesDifficulty: [],
            chartOptionsReadability: {
                chart: {
                    height: 230,
                    type: 'radialBar',
                },
                stroke: {
                    lineCap: 'round',
                },
                plotOptions: {
                    radialBar: {
                        offsetY: 0,
                        startAngle: -90,
                        endAngle: 90,
                        hollow: {
                            size: '30%',
                        },
                        dataLabels: {
                            name: {
                                show: false,
                            },
                            value: {
                                show: false,
                            },
                        },
                    },
                },
                colors: [],
                labels: [],
                legend: {
                    onItemClick: {
                        toggleDataSeries: false,
                    },
                    show: false,
                    floating: false,
                    fontSize: '16px',
                    position: 'bottom',
                    offsetX: 0,
                    offsetY: 5,
                    labels: {
                        useSeriesColors: true,
                    },
                    markers: {
                        size: 0,
                    },
                    formatter: function(seriesName, opts) {
                        return seriesName + ':  ' + opts.w.globals.series[opts.seriesIndex] / 100
                    },
                    itemMargin: {
                        vertical: 2,
                    },
                },
            },
            chartOptionsDifficulty: {
                chart: {
                    height: 230,
                    type: 'radialBar',
                },
                stroke: {
                    lineCap: 'round',
                },
                plotOptions: {
                    radialBar: {
                        offsetY: 0,
                        startAngle: -90,
                        endAngle: 90,
                        hollow: {
                            size: '30%',
                        },
                        dataLabels: {
                            name: {
                                show: false,
                            },
                            value: {
                                show: false,
                            },
                        },
                    },
                },
                colors: [],
                labels: [],
                legend: {
                    onItemClick: {
                        toggleDataSeries: false,
                    },
                    show: false,
                    floating: false,
                    fontSize: '16px',
                    position: 'bottom',
                    offsetX: 0,
                    offsetY: 10,
                    labels: {
                        useSeriesColors: true,
                    },
                    markers: {
                        size: 4,
                    },
                    formatter: function(seriesName, opts) {
                        return seriesName + ':  ' + opts.w.globals.series[opts.seriesIndex]
                    },
                    itemMargin: {
                        vertical: 2,
                    },
                },
            },
        }
    },
    created() {
        if (localStorage.getItem('text') == '' || localStorage.getItem('text') == undefined) {
            this.$router.replace({ name: 'home' })
        } else {
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
                    tmpCol = '#ffbb00'
                } else if (this.seriesReadability[i] / 100 < min) {
                    tmpCol = '#15ff00'
                } else {
                    tmpCol = '#ff0000'
                }
                this.chartOptionsReadability.colors.push(tmpCol)
            }
            for (let i = 0; i < this.chartOptionsDifficulty.labels.length; i++) {
                min = 40
                max = 80
                if (this.seriesDifficulty[i] >= min && this.seriesDifficulty[i] <= max) {
                    tmpCol = '#ffbb00'
                } else if (this.seriesDifficulty[i] < min) {
                    tmpCol = '#15ff00'
                } else {
                    tmpCol = '#ff0000'
                }
                this.chartOptionsDifficulty.colors.push(tmpCol)
            }
            this.show = true
        }
    },
    methods: {
        sheetData(arr) {
            this.info = arr[0]
            this.keys = arr[1]
            this.values = arr[2]
            this.$refs.swipeableBottomSheet.setState('half')
        },
    },
}
</script>

<style scoped>
@import url('../css/brat.css');
</style>
