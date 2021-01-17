<template>
    <div v-if="show" class="grid grid-cols-8 gap-x-6">
        <modalInfo v-if="modal" @modal="modal = !modal" :mode="modalMode" :type="'results'" />
        <!-- <infoCard :jsonData="processedData" /> -->
        <!--
        <div class="col-span-8 mb-4  text-primaryDark text-xl tracking-wide">General Information</div>
        <span
            v-for="(i, x) in infoMiniCards.values"
            :key="x"
            class="cursor-default select-none col-span-4 md:col-span-2 2xl:col-span-1 dark:bg-dark01dp shadow-md rounded-md mb-4 p-2 md:p-3"
        >
            <p class=" text-xl tracking-wide sm:text-2xl md:text-4xl" style="font-family: 'Eczar', sans-serif">{{ i }}</p>
            <p class="text-gray-300 text-sm overflow-ellipsis block overflow-hidden">
                {{ infoMiniCards.keys[x] }}
            </p>
        </span>
        -->
        <div class="grid grid-cols-9 col-span-8 gap-x-6 gap-y-6 mb-6">
            <div class="col-span-9 lg:col-span-2 sm:col-span-4 dark:bg-dark01dp shadow-md rounded-lg p-2 md:p-4">
                <div class="col-span-8 mb-4 text-primaryDark text-xl tracking-wide ">General Information</div>

                <span v-for="(i, x) in infoMiniCards.values" :key="x" class="cursor-default select-none flex flex-col">
                    <span class="flex flex-row justify-between w-full items-center">
                        <p class="dark:text-gray-300 text-gray-600 text-sm overflow-ellipsis overflow-hidden">
                            {{ infoMiniCards.keys[x] }}
                        </p>
                        <p class="text-xl sm:text-2xl tracking-widest ml-2" style="font-family: 'Eczar', sans-serif">
                            {{ i }}
                        </p></span
                    >
                    <div
                        class="h-px w-full dark:bg-dark02dp bg-gray-300 my-0.5"
                        v-if="x <= infoMiniCards.values.length - 2"
                    ></div>
                </span>
            </div>
            <div class="col-span-9 lg:col-span-3 sm:col-span-5 ">
                <div class="shadow-md rounded-lg dark:bg-dark01dp p-3 md:p-4 flex flex-col w-full h-full">
                    <div class="w-full flex flex-row mb-4">
                        <div class="text-primaryDark text-xl tracking-wide w-full">Readability</div>
                        <div
                            @click=";(modal = !modal), (modalMode = 'readability')"
                            class="ripple w-8 p-1 h-8 hover:bg-gray-200 bg-transparent dark:hover:bg-dark02dp rounded-full focus:outline-none transition-colors duration-100 ease-out"
                        >
                            <svg class="fill-current text-primary h-6 w-6" viewBox="0 0 24 24">
                                <path
                                    d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11V17Z"
                                />
                            </svg>
                            <span class="sr-only">Open information dialog</span>
                        </div>
                    </div>
                    <div class="flex flex-row my-auto">
                        <div
                            class="w-1/3 flex flex-col place-items-center mx-1"
                            v-for="(i, x) in seriesReadability"
                            :key="x"
                        >
                            <div class="inline-block h-40 w-4 sm:w-6 relative rounded-lg dark:bg-dark02dp bg-gray-200">
                                <div :style="{ height: i + '%' }" class="absolute bottom-0 w-4 sm:w-6">
                                    <div
                                        class="heightTrns h-full  bg-gradient-to-tl w-full absolute bottom-0 rounded-lg	"
                                        style="border-radius:10px"
                                        :class="chartOptionsReadability.colors[x]"
                                    ></div>
                                </div>
                            </div>
                            <p
                                class="text-xs dark:font-thin w-2/3 min-w-min text-center text-gray-600  tracking-wider dark:text-gray-300 mt-2 overflow-ellipsis block overflow-hidden"
                            >
                                {{ chartOptionsReadability.labels[x] }}
                            </p>
                            <p
                                class="w-2/3 text-center  tracking-widest text-gray-600 dark:text-gray-300 inline mt-1"
                                style="font-family: 'Eczar', sans-serif"
                            >
                                {{ i.toFixed(2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-4 col-span-9">
                <div class=" dark:bg-dark01dp shadow-md rounded-lg h-full p-3 md:p-4 flex flex-col w-full">
                    <div class="w-full flex flex-row mb-4">
                        <div class=" text-primaryDark text-xl tracking-wide w-full">Difficulty</div>
                        <button
                            @click=";(modal = !modal), (modalMode = 'difficulty')"
                            class="ripple p-2 bg-transparent dark:hover:bg-dark02dp rounded-full focus:outline-none transition-colors duration-100 ease-out"
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
                    <div class="flex flex-row my-auto">
                        <div
                            class="w-1/3 flex flex-col place-items-center items-center content-center mx-1"
                            v-for="(i, x) in seriesDifficulty"
                            :key="x"
                        >
                            <div
                                class="shadow-insetShadow dark:shadow-insetShadowDark inline-block h-40	 w-4 sm:w-6 relative rounded-lg bg-gray-200 dark:bg-dark02dp"
                            >
                                <div :style="{ height: i + '%' }" class="absolute bottom-0 w-4 sm:w-6">
                                    <div
                                        class="heightTrns h-full bg-gradient-to-tl w-full absolute bottom-0 rounded-lg	"
                                        style="border-radius:10px"
                                        :class="chartOptionsDifficulty.colors[x]"
                                    ></div>
                                </div>
                            </div>
                            <div
                                class="text-xs w-2/3 text-center dark:font-thin text-gray-600  tracking-wider dark:text-gray-300 mt-2 overflow-ellipsis block overflow-hidden"
                            >
                                {{ chartOptionsDifficulty.labels[x] }}
                            </div>
                            <div
                                class="w-2/3 text-center tracking-widest text-gray-600  dark:text-gray-300 inline mt-1"
                                style="font-family: 'Eczar', sans-serif"
                            >
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
            @opensheet=";(info = ''), (values = []), (keys = []), sheetData('empty')"
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
    beforeCreate() {
        if (localStorage.getItem('text') === '' || localStorage.getItem('text') == undefined) {
            this.$router.replace({ name: 'home' })
        }
    },
    mounted() {
        if (!localStorage.theme) {
            document.querySelector('.card').classList.remove('cardDark')
            document.querySelector('.card').classList.remove('barDark')
        } else if (localStorage.theme === 'dark') {
            document.querySelector('.card').classList.add('cardDark')
            document.querySelector('.card').classList.add('barDark')
        } else {
            document.querySelector('.card').classList.remove('cardDark')
            document.querySelector('.card').classList.remove('barDark')
        }
    },
    created() {
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
        this.show = true
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
    },
    watch: {
        sheetMode() {
            if (this.sheetMode == 'dark') {
                document.querySelector('.card').classList.add('cardDark')
                document.querySelector('.card').classList.add('barDark')
            } else {
                document.querySelector('.card').classList.remove('cardDark')
                document.querySelector('.card').classList.remove('barDark')
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
