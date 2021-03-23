<template>
    <div class="mt-4">
        <div class="font-bold text-lg">Legend</div>
        <div class="grid grid-rows-4 grid-flow-col gap-x-1 auto-cols-auto mb-6 max-w-max">
            <div v-for="(i, x) in Object.keys(legend)" :key="x">
                <div class="h-3 w-3 rounded-full inline-block" :style="{ background: legend[i] }"></div>
                {{ i }}{{ posDesc[i] ? ': ' + posDesc[i] : ': Other' }}
            </div>
        </div>
        <p class="font-bold text-primary dark:text-primaryLight">
            Check<br />
            to save
        </p>
        <div class="divide-y divide-primary divide-opacity-75">
            <div v-for="sen in localData.sentences" :key="sen.index" class="flex">
                <div
                    class="mt-4 flex items-center content-center flex-shrink-0 justify-center bg-white dark:bg-gray-800 border-2 rounded border-gray-400 w-6 h-6 focus-within:border-blue-500 mx-4"
                >
                    <input type="checkbox" class="opacity-0 absolute w-6 h-6" v-model="checked[sen.index]" />
                    <svg
                        class="fill-current hidden w-4 h-4 text-primary dark:text-primaryLight pointer-events-none"
                        viewBox="0 0 20 20"
                    >
                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
                    </svg>
                </div>
                <div>
                    <p class="mt-3 mb-1">{{ sen.text }}</p>
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
    </div>
</template>

<script>
export default {
    name: 'posEdit',
    props: { checkProp: Array },
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
            checked: [],
            backup: JSON.parse(localStorage.getItem('processedText')),
        }
    },
    created() {
        this.posDesc = this.localData.readability.genericPosDescription
        for (let i = 0; i < this.poses.length; i++) {
            this.legend[this.poses[i]] = this.colors[i]
        }
        this.totalPages = Math.ceil(this.localData.sentences.length / this.senPerPage)
        /*
        for (let i = 0; i < this.senPerPage; i++) {
            if (this.localData.sentences[i] != undefined) {
                this.sentencesToShow.push(this.localData.sentences[i].text)
            }
        }
        */
        for (var i = 0; i < this.localData.sentences.length; i++) {
            this.checked[i] = false
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
    },
    methods: {
        openModal(senIndex, tokenIndex) {
            this.$emit('showPosModal', { senIndex: senIndex, tokenIndex: tokenIndex })
        },
    },
    watch: {
        page() {
            this.sentencesToShow = []
            for (let i = (this.page - 1) * this.senPerPage; i < (this.page - 1) * this.senPerPage + 10; i++) {
                if (this.localData.sentences[i] != undefined) {
                    this.sentencesToShow.push(this.localData.sentences[i].text)
                }
            }
        },
        checked() {
            this.$emit('sendID', this.checked)
        },
        checkProp() {
            if (this.checkProp[1] == 'token') {
                this.checked[this.checkProp[0]] = true
                this.$emit('sendID', this.checked)
            }
        },
    },
}
</script>

<style scoped>
input:checked + svg {
    display: block;
}
</style>
