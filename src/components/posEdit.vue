<template>
    <div class="mt-4">
        <!--
        <span class="mt-2 mb-4 inline-block w-full">
            <div
                class="flex flex-row content-center items-center justify-center"
                v-if="this.$store.state.editableData.sentences.length > 10"
            >
                <button
                    class="rounded flex items-center content-center mr-1 px-2 py-1 ripple transition-colors duration-100 ease-out select-none focus:outline-none"
                    :class="
                        page == 1
                            ? 'bg-gray-400 text-black hover:text-white hover:bg-gray-600 cursor-not-allowed'
                            : 'bg-primary dark:bg-primaryLight dark:hover:bg-blue-500 hover:bg-primaryDark text-white dark:text-black dark:hover:text-white cursor-pointer'
                    "
                    @click="page > 1 ? page-- : (page = page)"
                >
                    Prev.
                </button>
                <span class="mx-2">{{ page }}/{{ totalPages }}</span>
                <button
                    class="rounded flex items-center content-center mr-1 px-2 py-1 ripple transition-colors duration-100 ease-out select-none focus:outline-none"
                    :class="
                        page == totalPages
                            ? 'bg-gray-400 text-black hover:text-white hover:bg-gray-600 cursor-not-allowed'
                            : 'bg-primary dark:bg-primaryLight dark:hover:bg-blue-500 hover:bg-primaryDark text-white dark:text-black dark:hover:text-white cursor-pointer'
                    "
                    @click="page < totalPages ? page++ : (page = page)"
                >
                    Next
                </button>
            </div>
            Sentences:<br />
            <span class="font-bold" v-for="(sen, n) in sentencesToShow" :key="n">
                {{ n + 1 + (page - 1) * senPerPage }}. {{ sen }}<br />
            </span>
        </span>
        -->
        <!-- <div class="font-bold text-lg">Legend</div> -->
        <!-- <div :class="'grid grid-rows-' + nerDesc.length + 'grid-flow-col mb-6'">
            <div v-for="type in nerDesc" :key="type">
                <div v-if="type != 'O'">
                    <div class="h-3 w-3 rounded-full inline-block" :class="'bg-' + type"></div>
                    {{ type }}
                </div>
            </div>
        </div> -->
        <div class="font-bold text-lg">Legend</div>
        <div class="grid grid-rows-4 grid-flow-col gap-x-1 auto-cols-auto mb-6 max-w-max">
            <div v-for="(i, x) in Object.keys(legend)" :key="x">
                <div class="h-3 w-3 rounded-full inline-block" :style="{ background: legend[i] }"></div>
                {{ i }}{{ posDesc[i] ? ': ' + posDesc[i] : ': Other' }}
            </div>
        </div>
        <div class="divide-y divide-primary divide-opacity-75">
            <div v-for="sen in localData.sentences" :key="sen.index" class="">
                <p class="mt-3 mb-1">{{sen.text}}</p>
                <div class="flex flex-row flex-wrap">
                    <span
                        @dblclick="openModal(sen.index, i)"
                        v-for="(token, i) in sen.tokens"
                        :key="i"
                        class="flex flex-col place-items-center my-2 cursor-pointer"
                    >
                        <span
                            class="rounded px-1 text-sm text-black max-w-min mx-1 mb-1"
                            :style="{ background: legend[token.pos[0]] }"
                            >{{ token.pos }}
                        </span>
                        <span
                            class="mx-1 px-1 rounded select-none bg-gray-350 dark:bg-gray-600 text-black dark:text-white"
                        >
                            {{ token.word }}
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ner',
    data() {
        return {
            keys: [],
            values: [],
            posList: [
                { abbr: 'A', full: 'adjective' },
                { abbr: 'AP', full: 'possessive adjective' },
                { abbr: 'B', full: 'adverb' },
                { abbr: 'BN', full: 'negation adverb' },
                { abbr: 'CC', full: 'coordinative conjunction' },
                { abbr: 'CS', full: 'subordinative conjunction' },
                { abbr: 'DE', full: 'exclamative determiner' },
                { abbr: 'DI', full: 'indefinite determiner' },
                { abbr: 'DQ', full: 'interrogative determiner' },
                { abbr: 'DR', full: 'relative determiner' },
                { abbr: 'DD', full: 'demonstrative determiner' },
                { abbr: 'E', full: 'preposition' },
                { abbr: 'FB', full: '“balanced” punctuation' },
                { abbr: 'FC', full: 'clause boundary punctuation' },
                { abbr: 'FF', full: 'comma, hyphen' },
                { abbr: 'FS', full: 'sentence boundary punctuation' },
                { abbr: 'I', full: 'interjection' },
                { abbr: 'N', full: 'cardinal number' },
                { abbr: 'NO', full: 'ordinal number' },
                { abbr: 'PD', full: 'demonstrative pronoun' },
                { abbr: 'PE', full: 'personal pronoun' },
                { abbr: 'PI', full: 'indefinite pronoun' },
                { abbr: 'PP', full: 'possessive pronoun' },
                { abbr: 'PQ', full: 'interrogative pronoun' },
                { abbr: 'PR', full: 'relative pronoun' },
                { abbr: 'PC', full: 'clitic pronoun' },
                { abbr: 'RD', full: 'determinative article' },
                { abbr: 'RI', full: 'indeterminative article' },
                { abbr: 'S', full: 'common noun' },
                { abbr: 'SA', full: 'abbreviation' },
                { abbr: 'SP', full: 'proper noun' },
                { abbr: 'T', full: 'predeterminer' },
                { abbr: 'VA', full: 'auxiliary verb' },
                { abbr: 'VM', full: 'modal verb' },
                { abbr: 'V', full: 'main verb' },
                { abbr: 'X', full: 'residual class' },
            ],
            colors: [
                '#FFFFF0',
                '#FFD3E3',
                '#97be9f',
                '#82FEC3',
                '#F7AE8C',
                '#c293a0',
                '#6efff7',
                '#b5a7ff',
                '#eecd39',
                '#abff4a',
                '#bcc7b2',
                '#ff43d3',
                '#f09471',
                '#77b2cc',
                '#ff6e87',
            ].reverse(),
            poses: [],
            legend: {},
            localData: this.$store.state.editableData,
            totalPages: 0,
            page: 1,
            senPerPage: 10,
            sentencesToShow: [],
            posDesc: [],
        }
    },
    created() {
        this.posDesc = this.localData.readability.genericPosDescription
        for (let i = 0; i < this.poses.length; i++) {
            this.legend[this.poses[i]] = this.colors[i]
        }
        this.totalPages = Math.ceil(this.localData.sentences.length / this.senPerPage)
        for (let i = 0; i < this.senPerPage; i++) {
            if (this.localData.sentences[i] != undefined) {
                this.sentencesToShow.push(this.localData.sentences[i].text)
            }

            //console.log(i)
        }
        for (var i = 0; i < this.localData.sentences.length; i++) {
            for (var x = 0; x < this.localData.sentences[i].tokens.length; x++) {
                this.poses.includes(this.localData.sentences[i].tokens[x].pos[0])
                    ? ''
                    : this.poses.push(this.localData.sentences[i].tokens[x].pos[0])
            }
        }
        this.poses.sort()
        for (let i = 0; i < this.poses.length; i++) {
            this.legend[this.poses[i]] = this.colors[i]
        }
        // for (var i = 0; i < this.$store.state.editableData.sentences.length; i++) {
        //     this.ner.i = {}

        //     for (var x = 0; x < this.$store.state.editableData.sentences[i].tokens.length; x++) {
        //         this.ner.i.x = this.$store.state.editableData.sentences[i].tokens[x].ner
        //     }
        // }
    },
    methods: {
        openModal(senIndex, tokenIndex) {
            console.log(senIndex, tokenIndex, this.localData.sentences[senIndex].tokens[tokenIndex])

            this.$emit('showPosModal', { senIndex: senIndex, tokenIndex: tokenIndex })
            console.log('open modal!')
        },
        // changeNer(i, cont) {
        //     switch (this.$store.state.editableData.sentences[i].tokens[cont].ner) {
        //         case 'O':
        //             this.$store.state.editableData.sentences[i].tokens[cont].ner = 'PER'
        //             this.$emit('edited')
        //             break
        //         case 'PER':
        //             this.$store.state.editableData.sentences[i].tokens[cont].ner = 'ORG'
        //             this.$emit('edited')
        //             break
        //         case 'ORG':
        //             this.$store.state.editableData.sentences[i].tokens[cont].ner = 'LOC'
        //             this.$emit('edited')
        //             break
        //         case 'LOC':
        //             this.$store.state.editableData.sentences[i].tokens[cont].ner = 'O'
        //             this.$emit('edited')
        //             break
        //     }
        //     this.localData.sentences[i].tokens[cont].ner = this.$store.state.editableData.sentences[i].tokens[cont].ner
        // },
    },
    watch: {
        page() {
            this.sentencesToShow = []

            for (let i = (this.page - 1) * this.senPerPage; i < (this.page - 1) * this.senPerPage + 10; i++) {
                //console.log(i)

                if (this.localData.sentences[i] != undefined) {
                    this.sentencesToShow.push(this.localData.sentences[i].text)
                }
            }
        },
    },
}
</script>

<style></style>
