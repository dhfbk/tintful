<template>
    <transition name="fade-bg" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-25 customZ"
            @click="toggleModal"
        >
            <transition name="fade" appear>
                <div
                    class="bg-white rounded-lg w-5/6 max-w-3xl max-h-96 h-auto overflow-y-auto overscroll-none"
                    @click.stop
                    v-if="showDialog"
                >
                    <div class="p-4">
                        <div class="flex w-full">
                            <div class="text-primary font-bold text-lg text-primary">Edit token features</div>
                            <span class="ripple ml-auto rounded hover:bg-gray-200 p-1" @click="toggleModal()">
                                <svg
                                    class="m-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 18 18"
                                >
                                    <path
                                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                                    />
                                </svg>
                                <span class="sr-only">Close dialog</span>
                            </span>
                        </div>
                        <span>POS:</span>
                        <transition name="zoom">
                            <select
                                class="appearance-none font-medium h-full border-b   block appearance-none bg-white border-gray-400 text-gray-700 py-1 pl-2 pr-12 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                name="posSelect"
                                id="posSelect"
                                v-model="pos"
                            >
                                <option v-for="(i, x) in posList" :key="x" :value="i.abbr" class="">
                                    {{ i.abbr }} ({{ i.full }})
                                </option>
                            </select>
                        </transition>
                        <div class="py-2">
                            <span class="">{{ featsToEdit }} </span>
                            <transition name="zoom">
                                <featsSelect :opt="params.Gender" :model="this.gen" v-if="showGen" />
                            </transition>
                            <featsSelect :opt="params.Number" :model="this.num" v-if="showNum" />
                            <featsSelect :opt="params.Tense" :model="this.ten" v-if="showTen" />
                            <featsSelect :opt="params.VerbForm" :model="this.form" v-if="showForm" />
                            <featsSelect :opt="params.Person" :model="this.per" v-if="showPer" />
                            <featsSelect :opt="params.Mood" :model="this.mood" v-if="showMood" />
                            <featsSelect :opt="params.Degree" :model="this.deg" v-if="showDeg" />
                            <featsSelect :opt="params.PronType" :model="this.pro" v-if="showPronType" />
                            <featsSelect :opt="params.Clitic" :model="this.cli" v-if="showCli" />
                            <featsSelect :opt="params.Poss" :model="this.poss" v-if="showPoss" />
                            <featsSelect :opt="params.Definite" :model="this.def" v-if="showDef" />
                            <featsSelect :opt="params.NumType" :model="this.numTy" v-if="showNumType" />
                        </div>
                        <div class="float-right pb-4">
                            <button
                                class="font-medium ripple transition-colors duration-100 ease-out hover:bg-gray-200 focus:outline-none bg-transparent text-text-primary py-2 px-4 rounded mr-2"
                                @click="toggleModal()"
                            >
                                CANCEL
                            </button>
                            <button
                                class="font-medium text-primary ripple transition-colors duration-100 ease-out hover:bg-gray-200 focus:outline-none bg-transparent py-2 px-4 rounded"
                                @click="save()"
                            >
                                SAVE
                            </button>
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
    },
    components: { featsSelect },
    data() {
        return {
            showDialog: false,
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
                { abbr: 'I', full: 'cardinal number' },
                { abbr: 'N', full: 'preposition' },
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
        }
    },
    methods: {
        toggleModal() {
            this.$emit('closeFeatsModal')
        },
        save() {
            this.$emit('closeFeatsModal')
        },
        gender(i) {
            console.log(i)
        },
        number(i) {
            console.log(i)
        },
        tense(i) {
            console.log(i)
        },
    },
    created() {
        setTimeout(() => {
            this.showDialog = true
        }, 1)
        console.log(this.posList.length)
        this.pos = this.featsToEdit.pos
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

        switch (this.featsToEdit.pos) {
            case 'A':
                this.showGen = true
                this.showNum = true
                if (this.deg != '') this.showDeg = true
                break
            case 'AP':
                this.showGen = true
                this.showNum = true
                this.showPronType = true
                this.showPoss = true
                break
            case 'B':
                if (this.deg != '') this.showDeg = true
                break
            case 'DD' || 'DE' || 'DI' || 'DQ' || 'DR':
                this.showNum = true
                this.showPer = true
                this.showPronType = true
                break
            case 'N' || 'NO':
                this.showNumType = true
                if (this.num != '') this.showNum = true
                if (this.gen != '') this.showGen = true
                break
            case 'P' || 'PD' || 'PI' || 'PP' || 'PQ' || 'PR':
                this.showGen = true
                this.showNum = true
                this.showPronType = true
                if (this.gen != '') this.showGen = true
                break
            case 'PC' || 'PE':
                this.showGen = true
                this.showNum = true
                this.showPronType = true
                this.showPer = true
                if (this.cli != '') this.showCli = true
                break
            case 'RD' || 'RI':
                this.showGen = true
                this.showNum = true
                this.showDef = true
                this.showPronType = true
                break
            case 'S' || 'SA':
                this.showGen = true
                this.showNum = true
                break
            case 'V' || 'VA' || 'VM':
                this.showForm = true
                if (this.mood != '') this.showMood = true
                if (this.num != '') this.showNum = true
                if (this.per != '') this.showPer = true
                if (this.tense != '') this.showTen = true
                break
        }
    },
    watch: {
        pos: function() {
            console.log('cambia')
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
            switch (this.pos) {
                case 'A':
                    this.showGen = true
                    this.showNum = true
                    if (this.deg != '') this.showDeg = true
                    break
                case 'AP':
                    this.showGen = true
                    this.showNum = true
                    this.showPronType = true
                    this.showPoss = true
                    break
                case 'B':
                    if (this.deg != '') this.showDeg = true
                    break
                case 'DD' || 'DE' || 'DI' || 'DQ' || 'DR':
                    this.showNum = true
                    this.showPer = true
                    this.showPronType = true
                    break
                case 'N' || 'NO':
                    this.showNumType = true
                    if (this.num != '') this.showNum = true
                    if (this.gen != '') this.showGen = true
                    break
                case 'P' || 'PD' || 'PI' || 'PP' || 'PQ' || 'PR':
                    this.showGen = true
                    this.showNum = true
                    this.showPronType = true
                    if (this.gen != '') this.showGen = true
                    break
                case 'PC' || 'PE':
                    this.showGen = true
                    this.showNum = true
                    this.showPronType = true
                    this.showPer = true
                    if (this.cli != '') this.showCli = true
                    break
                case 'RD' || 'RI':
                    this.showGen = true
                    this.showNum = true
                    this.showDef = true
                    this.showPronType = true
                    break
                case 'S' || 'SA':
                    this.showGen = true
                    this.showNum = true
                    break
                case 'V' || 'VA' || 'VM':
                    this.showForm = true
                    if (this.mood != '') this.showMood = true
                    if (this.num != '') this.showNum = true
                    if (this.per != '') this.showPer = true
                    if (this.tense != '') this.showTen = true
                    break
            }
        },
    },
}
</script>

<style scoped>
.customZ {
    z-index: 990;
}
</style>
