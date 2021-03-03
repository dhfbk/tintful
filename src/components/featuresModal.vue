<template>
    <transition name="fade-bg" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full min-h-screen dark:bg-black bg-gray-900 bg-opacity-25 customZ"
            @click="toggleModal"
        >
            <transition name="fade" appear>
                <div
                    class="bg-white dark:bg-bgDark rounded-lg w-5/6 max-w-3xl max-h-96 h-auto overflow-y-auto overscroll-none"
                    @click.stop
                    v-if="showDialog"
                >
                    <div class="p-4">
                        <div class="flex w-full">
                            <div class="text-primary dark:text-primaryLight font-bold text-lg">
                                Edit token features
                            </div>
                            <button
                                class="ripple ml-auto rounded hover:bg-gray-200 dark:hover:bg-gray-600 p-1 focus:outline-none"
                                @click="toggleModal()"
                            >
                                <svg
                                    class="m-auto fill-current text-gray-700 dark:text-gray-200 w-6 h-6 cursor-pointer"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 18 18"
                                >
                                    <path
                                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                                    />
                                </svg>
                                <span class="sr-only">Close dialog</span>
                            </button>
                        </div>
                        <div class="flex content-center items-center justify-between my-2">
                            Form:
                            <form class="w-full max-w-xs">
                                <input
                                    type="text"
                                    class="p-1 border border-primary bg-gray-100 dark:bg-gray-700 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none w-full"
                                    v-model="word"
                                />
                            </form>
                        </div>
                        <div class="flex content-center items-center justify-between my-2">
                            Lemma:
                            <form class="w-full max-w-xs">
                                <input
                                    type="text"
                                    class="p-1 border border-primary bg-gray-100 dark:bg-gray-700 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none w-full"
                                    v-model="lemma"
                                />
                            </form>
                        </div>
                        <div
                            class="flex content-center items-center justify-between my-2"
                            v-if="mode == 'brat' || mode == 'upos'"
                        >
                            <span>POS: </span>
                            <select
                                class="appearance-none font-medium h-full border-b-2 inline-block bg-transparent border-gray-400 text-gray-700 dark:text-gray-200 py-1 pl-2 leading-tight focus:outline-none focus:bg-white dark:focus:bg-gray-800 focus:border-gray-500"
                                name="posSelect"
                                id="posSelect"
                                v-model="pos"
                            >
                                <option v-for="(i, x) in posList" :key="x" :value="i.abbr" class="">
                                    {{ i.abbr }} ({{ i.full }})
                                </option>
                            </select>
                        </div>
                        <div v-if="mode == 'brat' || mode == 'feats'">
                            <!-- <span class="">{{ featsToEdit }} </span> -->
                            <transition-group name="zoom">
                                <featsSelect
                                    :opt="params.Gender"
                                    :label="'Gender'"
                                    :model="this.gen"
                                    v-if="showGen"
                                    key="0"
                                    @updateData="updateData"
                                />
                                <featsSelect
                                    :opt="params.Number"
                                    :label="'Number'"
                                    :model="this.num"
                                    v-if="showNum && form != 'Inf' && form != 'Ger'"
                                    key="1"
                                    @updateData="updateData"
                                />
                                <featsSelect
                                    :opt="params.Tense"
                                    :label="'Tense'"
                                    :model="this.ten"
                                    v-if="showTen && form != 'Inf' && form != 'Ger'"
                                    key="2"
                                    @updateData="updateData"
                                />
                                <featsSelect
                                    :opt="params.VerbForm"
                                    :label="'VerbForm'"
                                    :model="this.form"
                                    v-if="showForm"
                                    key="3"
                                    @updateData="updateData"
                                />
                                <featsSelect
                                    :opt="params.Person"
                                    :label="'Person'"
                                    :model="this.per"
                                    v-if="showPer && form != 'Inf' && form != 'Part' && form != 'Ger'"
                                    key="4"
                                    @updateData="updateData"
                                />
                                <featsSelect
                                    :opt="params.Mood"
                                    :label="'Mood'"
                                    :model="this.mood"
                                    v-if="showMood && form != 'Inf' && form != 'Part' && form != 'Ger'"
                                    key="5"
                                    @updateData="updateData"
                                />
                                <featsSelect
                                    :opt="params.Degree"
                                    :label="'Degree'"
                                    :model="this.deg"
                                    v-if="showDeg"
                                    key="6"
                                    @updateData="updateData"
                                />
                                <featsSelect
                                    :opt="params.PronType"
                                    :label="'PronType'"
                                    :model="this.pro"
                                    v-if="showPronType"
                                    key="7"
                                    @updateData="updateData"
                                    :unselectable="pos == 'AP' || pos == 'RD' || pos == 'RI'"
                                />
                                <featsSelect
                                    :opt="params.Clitic"
                                    :label="'Clitic'"
                                    :model="this.cli"
                                    v-if="showCli"
                                    key="8"
                                    @updateData="updateData"
                                    :unselectable="pos == 'PC'"
                                />
                                <featsSelect
                                    :opt="params.Poss"
                                    :label="'Possessive'"
                                    :model="this.poss"
                                    v-if="showPoss"
                                    key="9"
                                    @updateData="updateData"
                                    :unselectable="pos == 'AP'"
                                />
                                <featsSelect
                                    :opt="params.Definite"
                                    :label="'Definite'"
                                    :model="this.def"
                                    v-if="showDef"
                                    key="10"
                                    @updateData="updateData"
                                    :unselectable="pos == 'RD' || pos == 'RI'"
                                />
                                <featsSelect
                                    :opt="params.NumType"
                                    :label="'NumType'"
                                    :model="this.numTy"
                                    v-if="showNumType"
                                    key="11"
                                    @updateData="updateData"
                                    :unselectable="pos == 'NO' || pos == 'N'"
                                />
                            </transition-group>
                        </div>
                        <div class="flex flex-row justify-between content-center items-center">
                            <span>Miscellaneous: </span>
                            <input
                                name="misc"
                                id="misc"
                                class="p-1 border border-primary bg-gray-100 dark:bg-gray-700 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none w-full max-w-xs"
                                v-model="misc"
                            />
                        </div>
                        <div class="flow-root mt-4">
                            <div class="float-right">
                                <button
                                    class="font-medium ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent text-red-400 py-2 px-4 rounded mr-2"
                                    @click="toggleModal()"
                                >
                                    CANCEL
                                </button>
                                <button
                                    class="font-medium text-primary dark:text-primaryLight ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent py-2 px-4 rounded"
                                    @click="save()"
                                >
                                    SAVE
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </transition>
</template>

<script>
import featsSelect from '../components/featsSelect'
export default {
    props: {
        featsToEdit: Object,
        mode: String,
    },
    components: { featsSelect },
    data() {
        return {
            notFirstIter: false,
            showDialog: false,
            word: '',
            lemma: '',
            pos: '',
            gen: '',
            num: '',
            ten: '',
            form: '',
            per: '',
            mood: '',
            deg: '',
            pro: '',
            numTy: '',
            cli: '',
            def: '',
            poss: '',
            showGen: false,
            showNum: false,
            showTen: false,
            showForm: false,
            showPer: false,
            showMood: false,
            showDeg: false,
            showPronType: false,
            showPoss: false,
            showCli: false,
            showNumType: false,
            showDef: false,
            misc: '',

            params: {
                Gender: ['Fem', 'Masc'],
                Number: ['Sing', 'Plur'],
                Tense: ['Pres', 'Fut', 'Imp', 'Past'],
                VerbForm: ['Fin', 'Inf', 'Part', 'Ger'],
                Person: ['1', '2', '3'],
                Mood: ['Ind', 'Imp', 'Cnd', 'Sub'],
                Degree: ['Cmp', 'Sup', 'Abs'],
                PronType: ['Prs', 'Art', 'Int', 'Rel', 'Dem', 'Tot', 'Neg', 'Ind', 'Exc', 'Predet', 'Ord'],
                Clitic: ['Yes', 'No'],
                Poss: ['Yes', 'No'],
                Definite: ['Ind', 'Def'],
                NumType: ['Card', 'Ord', 'Range', 'Gen'],
            },
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
            miscObj: {},
        }
    },
    methods: {
        toggleModal() {
            this.$emit('closeFeatsModal')
        },
        // changePos() {
        //     this.$store.state.editableData.sentences[this.featsToEdit.senIndex].tokens[
        //         this.featsToEdit.tokIndex
        //     ].pos = this.pos
        //     this.$emit('edited', 'noBrat')
        // },
        // changeForm() {
        //     this.$store.state.editableData.sentences[this.featsToEdit.senIndex].tokens[
        //         this.featsToEdit.tokIndex
        //     ].word = this.featsToEdit.word
        //     this.$emit('edited', 'noBrat')
        // },
        // changeLemma() {
        //     this.$store.state.editableData.sentences[this.featsToEdit.senIndex].tokens[
        //         this.featsToEdit.tokIndex
        //     ].lemma = this.featsToEdit.lemma
        //     /*console.log(
        //         this.$store.state.editableData.sentences[this.featsToEdit.senIndex].tokens[this.featsToEdit.tokIndex]
        //             .lemma
        //     )
        //     */
        //     this.$emit('edited', 'noBrat')
        // },
        save() {
            let toCheck = {
                Gender: [this.gen],
                Number: [this.num],
                Tense: [this.ten],
                VerbForm: [this.form],
                Person: [this.per],
                Mood: [this.mood],
                Degree: [this.deg],
                PronType: [this.pro],
                Clitic: [this.cli],
                Poss: [this.poss],
                Definite: [this.def],
                NumType: [this.numTy],
            }
            let values = Object.values(toCheck)
            let keys = Object.keys(toCheck)
            var newFeatures = {}

            for (let i = 0; i < values.length; i++) {
                if (values[i][0] != '') {
                    newFeatures[keys[i]] = values[i]
                }
            }
            let dataToSend = {
                senIndex: this.featsToEdit.senIndex,
                tokIndex: parseInt(this.featsToEdit.tokIndex),
                newPos: this.pos,
                newWord: this.word,
                newLemma: this.lemma,
                newFeats: newFeatures,
            }

            if (this.misc != '') {
                let value = this.misc.split('|')
                for (let i = 0; i < value.length; i++) {
                    if (value[i].split(':')[0] != undefined && value[i].split(':')[0] != '') {
                        if (value[i].split(':')[1] != undefined && value[i].split(':')[1] != '') {
                            if (value[i].split(':')[0] == 'spaceAfter') {
                                this.miscObj[value[i].split(':')[0]] = value[i].split(':')[1]
                            } else {
                                if (this.miscObj.misc == undefined) {
                                    this.miscObj.misc = {}
                                }
                                this.miscObj.misc[value[i].split(':')[0]] = value[i].split(':')[1]
                            }
                        }
                    }
                }
                console.log(this.misc, this.miscObj)
                this.$store.state.editableData.sentences[this.featsToEdit.senIndex].tokens[
                    this.featsToEdit.tokIndex
                ] = Object.assign(
                    this.$store.state.editableData.sentences[this.featsToEdit.senIndex].tokens[
                        this.featsToEdit.tokIndex
                    ],
                    this.miscObj
                )
                let senMW = this.$store.state.tableData.sentences[this.featsToEdit.senIndex].tokens
                for (let i = 0; i < senMW.length; i++) {
                    if (
                        senMW[i].index ==
                        this.$store.state.editableData.sentences[this.featsToEdit.senIndex].tokens[
                            this.featsToEdit.tokIndex
                        ].index
                    ) {
                        this.$store.state.tableData.sentences[this.featsToEdit.senIndex].tokens[i] = Object.assign(
                            this.$store.state.tableData.sentences[this.featsToEdit.senIndex].tokens[i],
                            this.miscObj
                        )
                    }
                }
            }
            this.$emit('edited', dataToSend)
            this.$emit('closeFeatsModal')
        },
        updateData(i) {
            switch (i.type) {
                case 'Gender':
                    this.gen = i.info
                    break
                case 'Number':
                    this.num = i.info
                    break
                case 'Tense':
                    this.ten = i.info
                    break
                case 'VerbForm':
                    this.form = i.info
                    break
                case 'Person':
                    this.per = i.info
                    break
                case 'Mood':
                    this.mood = i.info
                    break
                case 'Degree':
                    this.deg = i.info
                    break
                case 'PronType':
                    this.pro = i.info
                    break
                case 'Clitic':
                    this.cli = i.info
                    break
                case 'Poss':
                    this.poss = i.info
                    break
                case 'Definite':
                    this.def = i.info
                    break
                case 'NumType':
                    this.numTy = i.info
                    break
            }
        },
        checkSelects() {
            switch (this.pos) {
                case 'A':
                    this.showGen = true
                    this.showNum = true
                    this.showDeg = true
                    break
                case 'AP':
                    this.showGen = true
                    this.showNum = true
                    this.showPronType = true
                    this.showPoss = true
                    this.pro = 'Prs'
                    this.poss = 'Yes'
                    break
                case 'B':
                    this.showDeg = true
                    break
                case 'DD':
                case 'DE':
                case 'DI':
                case 'DQ':
                case 'DR':
                    this.showNum = true
                    this.showGen = true
                    this.showPronType = true
                    break
                case 'N':
                    this.showNumType = true
                    this.numTy = 'Card'
                    break
                case 'NO':
                    this.showNumType = true
                    this.showNum = true
                    this.showGen = true
                    this.numTy = 'Ord'
                    break
                case 'P':
                case 'PD':
                case 'PI':
                case 'PP':
                case 'PQ':
                case 'PR':
                    this.showGen = true
                    this.showNum = true
                    this.showPronType = true
                    this.showGen = true
                    break
                case 'PC':
                    this.showGen = true
                    this.showNum = true
                    this.showPronType = true
                    this.showPer = true
                    this.showCli = true
                    this.cli = 'Yes'
                    break
                case 'PE':
                    this.showGen = true
                    this.showNum = true
                    this.showPronType = true
                    this.showPer = true
                    break
                case 'RD':
                    this.showGen = true
                    this.showNum = true
                    this.showDef = true
                    this.showPronType = true
                    this.pro = 'Art'
                    this.def = 'Def'
                    break
                case 'RI':
                    this.showGen = true
                    this.showNum = true
                    this.showDef = true
                    this.showPronType = true
                    this.pro = 'Art'
                    this.def = 'Ind'
                    break
                case 'S':
                case 'SA':
                    this.showGen = true
                    this.showNum = true
                    break
                case 'V':
                case 'VA':
                case 'VM':
                    this.showForm = true
                    this.showMood = true
                    this.showNum = true
                    this.showPer = true
                    this.showTen = true
                    break
            }
            this.showDialog = true
        },
        objToStr(object) {
            let str = ''
            for (var k in object) {
                if (Object.prototype.hasOwnProperty.call(object, k)) {
                    str += k + ':' + object[k] + '|'
                }
            }
            return str
        },
    },
    created() {
        if (this.featsToEdit.misc.spaceAfter != undefined) {
            this.misc = this.objToStr(this.featsToEdit.misc.spaceAfter)
        }
        if (this.featsToEdit.misc.misc != undefined) {
            this.misc += this.objToStr(this.featsToEdit.misc.misc)
        }
        this.pos = this.featsToEdit.pos
        this.word = this.featsToEdit.word
        this.lemma = this.featsToEdit.lemma
        let features = this.featsToEdit.feats
        this.gen = features.Gender == undefined ? '' : features.Gender[0]
        this.num = features.Number == undefined ? '' : features.Number[0]
        this.ten = features.Tense == undefined ? '' : features.Tense[0]
        this.form = features.VerbForm == undefined ? '' : features.VerbForm[0]
        this.per = features.Person == undefined ? '' : features.Person[0]
        this.mood = features.Mood == undefined ? '' : features.Mood[0]
        this.deg = features.Degree == undefined ? '' : features.Degree[0]
        this.pro = features.PronType == undefined ? '' : features.PronType[0]
        this.cli = features.Clitic == undefined ? '' : features.Clitic[0]
        this.poss = features.Poss == undefined ? '' : features.Poss[0]
        this.def = features.Definite == undefined ? '' : features.Definite[0]
        this.numTy = features.NumType == undefined ? '' : features.NumType[0]
        this.checkSelects()
    },
    watch: {
        pos: function() {
            this.showGen = false
            this.showNum = false
            this.showTen = false
            this.showForm = false
            this.showPer = false
            this.showMood = false
            this.showDeg = false
            this.showPronType = false
            this.showPoss = false
            this.showCli = false
            this.showNumType = false
            this.showDef = false
            if (this.notFirstIter) {
                //console.log('entra')
                this.gen = ''
                this.num = ''
                this.ten = ''
                this.form = ''
                this.per = ''
                this.mood = ''
                this.deg = ''
                this.pro = ''
                this.numTy = ''
                this.cli = ''
                this.def = ''
                this.poss = ''
            }
            this.notFirstIter = true
            this.checkSelects()
        },
    },
}
</script>

<style scoped>
.customZ {
    z-index: 990;
}
.dark .dark\:bg-black {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
