<template>
    <div class="grid grid-cols-3 gap-x-2 mt-4 md:divide-x dark:divide-primaryLight divide-primary">
        <div class="col-span-3 md:col-span-2">
            <div class="divide-y dark:divide-primaryLight divide-primary">
                <div v-for="sen in processedData.sentences" :key="sen.index" class="">
                    <div class="flex flex-row flex-wrap">
                        <span
                            v-for="token in sen.tokens"
                            :key="token.index"
                            @click=";(info = token), (selected = token.characterOffsetBegin)"
                            class="flex flex-col place-items-center my-2 cursor-pointer"
                        >
                            <span
                                class="mx-1 px-1 bg-gray-300 dark:bg-gray-600 rounded ripple"
                                :class="
                                    selected == token.characterOffsetBegin
                                        ? 'bg-primary dark:bg-primaryLight dark:text-black text-white'
                                        : 'text-black dark:text-white'
                                "
                                >{{ token.word }}</span
                            >
                        </span>
                    </div>
                    <div
                        class="px-1 mx-1 my-2 bg-gray-300 dark:bg-gray-600 rounded text-black dark:text-white text-sm grid lg:grid-cols-3 grid-cols-2 xl:grid-cols-4"
                    >
                        <span class="p-1">
                            Length:
                            <span
                                style="font-family: 'Barlow', sans-serif"
                                class="px-2 rounded-full font-medium"
                                :class="
                                    infoData.length[sen.index] > 25
                                        ? 'bg-yellow-500 dark:bg-yellow-400 text-black'
                                        : 'bg-green-500 dark:bg-green-400 text-black'
                                "
                            >
                                {{ infoData.length[sen.index] }}
                            </span>
                        </span>
                        <span class="p-1">
                            Level 1:
                            <span
                                style="font-family: 'Barlow', sans-serif"
                                class="px-2 rounded-full font-medium"
                                :class="
                                    infoData.level_1[sen.index] < gaugeLevelsOptions.yellowFrom
                                        ? 'bg-red-400 text-black'
                                        : infoData.level_1[sen.index] > gaugeLevelsOptions.yellowTo
                                        ? 'bg-green-500 dark:bg-green-400 text-black'
                                        : 'bg-yellow-500 dark:bg-yellow-400 text-black'
                                "
                            >
                                {{ infoData.level_1[sen.index] }}
                            </span>
                        </span>
                        <span class="p-1">
                            Level 2:
                            <span
                                style="font-family: 'Barlow', sans-serif"
                                class="px-2 rounded-full font-medium"
                                :class="
                                    infoData.level_2[sen.index] < gaugeLevelsOptions.yellowFrom
                                        ? 'bg-red-400 text-black'
                                        : infoData.level_2[sen.index] > gaugeLevelsOptions.yellowTo
                                        ? 'bg-green-500 dark:bg-green-400 text-black'
                                        : 'bg-yellow-500 dark:bg-yellow-400 text-black'
                                "
                            >
                                {{ infoData.level_2[sen.index] }}
                            </span>
                        </span>
                        <span class="p-1">
                            Level 3:
                            <span
                                style="font-family: 'Barlow', sans-serif"
                                class="px-2 rounded-full font-medium"
                                :class="
                                    infoData.level_3[sen.index] < gaugeLevelsOptions.yellowFrom
                                        ? 'bg-red-400 text-black'
                                        : infoData.level_3[sen.index] > gaugeLevelsOptions.yellowTo
                                        ? 'bg-green-500 dark:bg-green-400 text-black'
                                        : 'bg-yellow-500 dark:bg-yellow-400 text-black'
                                "
                            >
                                {{ infoData.level_3[sen.index] }}
                            </span>
                        </span>
                        <span class="p-1 ">
                            Sentences:
                            <span
                                style="font-family: 'Barlow', sans-serif"
                                class="px-2 rounded-full font-medium"
                                :class="
                                    infoData.sentences[sen.index] <
                                    processedData.readability.minYellowValues.propositionsAvg
                                        ? 'bg-green-500 dark:bg-green-400 text-black'
                                        : infoData.sentences[sen.index] >
                                          processedData.readability.maxYellowValues.propositionsAvg
                                        ? 'bg-red-400 text-black'
                                        : 'bg-yellow-500 dark:bg-yellow-400 text-black'
                                "
                            >
                                {{ infoData.sentences[sen.index] }}
                            </span>
                        </span>
                        <span class="p-1 ">
                            Lexical density:
                            <span
                                style="font-family: 'Barlow', sans-serif"
                                class="px-2 rounded-full font-medium"
                                :class="
                                    infoData.lexical_density[sen.index] <
                                    processedData.readability.minYellowValues.density
                                        ? 'bg-green-500 dark:bg-green-400 text-black'
                                        : infoData.lexical_density[sen.index] >
                                          processedData.readability.maxYellowValues.density
                                        ? 'bg-red-400 text-black'
                                        : 'bg-yellow-500 dark:bg-yellow-400 text-black'
                                "
                            >
                                {{ infoData.lexical_density[sen.index] }}
                            </span>
                        </span>
                        <span class="p-1 ">
                            Words per sentence:
                            <span
                                style="font-family: 'Barlow', sans-serif"
                                class="px-2 rounded-full font-medium"
                                :class="
                                    infoData.words_per_sentence[sen.index] <
                                    processedData.readability.minYellowValues.wordsAvg
                                        ? 'bg-green-500 dark:bg-green-400 text-black'
                                        : infoData.words_per_sentence[sen.index] >
                                          processedData.readability.maxYellowValues.wordsAvg
                                        ? 'bg-red-400 text-black'
                                        : 'bg-yellow-500 dark:bg-yellow-400 text-black'
                                "
                            >
                                {{ infoData.words_per_sentence[sen.index] }}
                            </span>
                        </span>
                        <span class="p-1 ">
                            Syntactic depth:
                            <span
                                style="font-family: 'Barlow', sans-serif"
                                class="px-2 rounded-full font-medium"
                                :class="
                                    infoData.syntactic_depth[sen.index] <
                                    processedData.readability.minYellowValues.deepAvg
                                        ? 'bg-green-500 dark:bg-green-400 text-black'
                                        : infoData.syntactic_depth[sen.index] >
                                          processedData.readability.maxYellowValues.deepAvg
                                        ? 'bg-red-400 text-black'
                                        : 'bg-yellow-500 dark:bg-yellow-400 text-black'
                                "
                            >
                                {{ infoData.syntactic_depth[sen.index] }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden md:block md:col-span-1 pl-2">
            <div v-if="!info" class="font-bold text-lg text-center sticky top-1/2">
                Click on a token for further information.
            </div>
            <div class="sticky top-0 overflow-y-auto h-screen" v-else>
                <div class="font-light text-lg text-center text-primary dark:text-primaryLight">
                    Info on the token "
                    <span class="font-bold">{{ info.word }}</span>
                    "
                </div>
                <span v-for="(i, x) in keys" :key="x">
                    <span class="font-medium dark:text-primaryLight text-primary"> {{ i }}: </span>

                    <span style="font-family: 'Barlow', sans serif">
                        {{ values[x] }}
                    </span>
                    <br />
                </span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'generalInfo',
    data() {
        return {
            info: '',
            keys: [],
            values: [],
            processedData: JSON.parse(localStorage.getItem('processedText')),
            selected: null,
            infoData: {
                length: [],
                level_1: [],
                level_2: [],
                level_3: [],
                sentences: [],
                lexical_density: [],
                words_per_sentence: [],
                syntactic_depth: [],
            },
            gaugeLevelsOptions: {
                redFrom: 0,
                redTo: 40,
                yellowFrom: 40,
                yellowTo: 80,
                greenFrom: 80,
                greenTo: 100,
                minorTicks: 5,
                width: 110,
                height: 110,
            },
        }
    },
    created() {
        let litWord = 0,
            lv1 = 0,
            lv2 = 0,
            sen = 0,
            ld = 0,
            wps = 0,
            sd = 0,
            diff = [0, 0, 0]
        for (let i = 0; i < this.processedData.sentences.length; i++) {
            for (let x = 0; x < this.processedData.sentences[i].tokens.length; x++) {
                if (this.processedData.sentences[i].tokens[x].literalWord) {
                    litWord++
                }
                if (this.processedData.sentences[i].tokens[x].easyWord) {
                    lv1++
                }
                if (this.processedData.sentences[i].tokens[x].difficultyLevel == 1) {
                    diff[0]++
                    diff[1]++
                    diff[2]++
                } else if (this.processedData.sentences[i].tokens[x].difficultyLevel == 2) {
                    diff[1]++
                    diff[2]++
                } else if (this.processedData.sentences[i].tokens[x].difficultyLevel == 3) {
                    diff[2]++
                }
                if (this.processedData.sentences[i].tokens[x].contentWord) {
                    lv2++
                }
            }
            sen = this.processedData.sentences[i].verbs.length
            ld = (1.0 * lv2) / litWord
            if (sen == 0) {
                wps = litWord
            } else {
                wps = (1.0 * litWord) / sen
            }
            sd = this.processedData.readability.deeps[i]
            this.infoData.length.push(litWord)
            this.infoData.level_1.push(this.roundNumber((100 * diff[0]) / lv1, 2))
            this.infoData.level_2.push(this.roundNumber((100 * diff[1]) / lv2, 2))
            this.infoData.level_3.push(this.roundNumber((100 * diff[2]) / lv2, 2))
            this.infoData.sentences.push(sen)
            this.infoData.lexical_density.push(this.roundNumber(ld, 2))
            this.infoData.words_per_sentence.push(this.roundNumber(wps, 2))
            this.infoData.syntactic_depth.push(this.roundNumber(sd, 2))
            litWord = 0
            wps = 0
            ld = 0
            lv1 = 0
            lv2 = 0
            diff = [0, 0, 0]
            sen = 0
            sd = 0
        }
        //console.log(this.infoData)
    },
    methods: {
        sheet() {
            var arr = [this.info, this.keys, this.values]
            this.$emit('sheet', arr)
        },
        roundNumber(num, scale) {
            if (!('' + num).includes('e')) {
                return +(Math.round(num + 'e+' + scale) + 'e-' + scale)
            } else {
                var arr = ('' + num).split('e')
                var sig = ''
                if (+arr[1] + scale > 0) {
                    sig = '+'
                }
                return +(Math.round(+arr[0] + 'e' + sig + (+arr[1] + scale)) + 'e-' + scale)
            }
        },
    },
    watch: {
        info: function() {
            if (this.info != '') {
                // if (document.getElementById("formatter1").firstChild) {
                //   document
                //     .getElementById("formatter1")
                //     .removeChild(document.getElementById("formatter1").firstChild);
                // }
                // if (
                //   document.getElementsByClassName(
                //     "json-formatter-row json-formatter-open"
                //   )[0]
                // ) {
                //   document
                //     .getElementsByClassName("json-formatter-row json-formatter-open")[0]
                //     .remove();
                // }
                // if (
                //   document.getElementsByClassName(
                //     "json-formatter-row json-formatter-open"
                //   )[1]
                // ) {
                //   document
                //     .getElementsByClassName("json-formatter-row json-formatter-open")[1]
                //     .remove();
                // }
                // let node = document.getElementById("formatter1");
                // if (node != null) {
                //   while (node.firstChild) {
                //     node.removeChild(node.firstChild);
                //   }
                // }
                // const formatter = new JSONFormatter(this.info);
                // setTimeout(() => {
                //   document.getElementById("formatter1").appendChild(formatter.render());
                //   // console.log(document.getElementById("formatter1").re);
                // }, 1);
                delete this.info.originalText
                delete this.info.ner
                delete this.info.guessed_lemma
                this.keys = Object.keys(this.info)
                this.values = Object.values(this.info)
                var i = 0
                for (i = 0; i < this.keys.length; i++) {
                    if (this.keys[i] == 'featuresText') {
                        this.values[i] = this.values[i].replace(/\|/g, ', ')
                    }
                    if (this.keys[i] == 'full_morpho' || this.keys[i] == 'selected_morpho') {
                        this.values[i] = this.values[i].replace(/\+/g, ' + ')
                    }
                }
            }
            this.sheet()
        },
    },
}
</script>

<style></style>
