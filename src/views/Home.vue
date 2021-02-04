<template>
    <div class="dark:bg-dark01dp shadow-md rounded-lg p-3 md:p-4">
        <!-- <button
                    class="mb-4 sm:mb-0 rounded grid grid-cols-5 mx-auto bg-blue-500 px-2 py-2 hover:bg-blue-600 transition-colors duration-100 ease-out text-white w-max cursor-pointer focus:outline-none"
                >
                    <svg
                        class="fill-current col-span-1 justify-self-start w-max"
                        style="width:24px;height:24px"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z"
                        />
                    </svg>
                    <span class="col-span-4 w-auto grid text-center self-center">Facebook Login</span>
                </button> -->
        <div class="">
            <span class="font-medium text-red-500">Tint (The Italian NLP Tool)</span> is a Java-based pipeline for
            Natural Language Processing (NLP) in Italian.
            <p>
                It implements most of the common linguistic tools, such as part-of-speech tagging, dependency parsing,
                entity linking.
            </p>
        </div>
        <div class="h-full my-2">
            <div>
                <label
                    class="block tracking-wide text-primary dark:text-primaryLight text-md font-medium pb-2"
                    for="textInput"
                    @mouseover="hoverText = true"
                    @mouseleave="hoverText = false"
                >
                    Insert text to analyse
                </label>
                <div class="rounded">
                    <textarea
                        placeholder="Text"
                        :class="[
                            hoverText ? 'border border-primary dark:border-primaryLight' : '',
                            $v.text.$error ? 'border border-red-500' : 'border border-gray-200 dark:border-gray-800',
                        ]"
                        class="w-full appearance-none bg-gray-200 dark:bg-gray-800 transition-colors duration-150 ease-out hover:border-primary dark:hover:border-primaryLight rounded-md py-2 px-3 focus:outline-none"
                        id="textInput"
                        rows="8"
                        @focus="hoverText = true"
                        @blur="hoverText = false"
                        v-model.trim="$v.text.$model"
                        required
                    ></textarea>
                </div>
            </div>
            <div class="my-2">
                <label
                    class="block tracking-wide text-primary dark:text-primaryLight text-md font-medium pb-2"
                    for="action"
                    >You can also choose an example:</label
                >
                <div class="relative mb-2">
                    <select
                        class="cursor-pointer block appearance-none shadow-miniCardShadow dark:shadow-miniCardShadowDark rounded-md bg-gray-200 dark:bg-gray-800 border border-gray-200 dark:border-gray-800 w-full py-2 pl-2 pr-8 transition-colors duration-150 ease-out focus:outline-none focus:border-primary hover:border-primary dark:focus:border-primary dark:hover:border-primaryLight"
                        id="action"
                        name="action"
                        v-model="exampleChoice"
                    >
                        <option value="" disabled selected hidden>Choose example...</option>
                        <option value="1">
                            Difficult - Appostamenti di caccia realizzati secondo [...]
                        </option>
                        <option value="2">
                            Easy - Un picchio e una pallina si trovavano in un cassetto [...]
                        </option>
                    </select>
                    <div
                        class="pointer-events-none absolute pin-y pin-r flex items-center px-2 mr-2 text-gray-800 dark:text-white"
                    >
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="mt-4 width-full flow-root">
                <button
                    class="ripple flex flex-row transition-colors duration-100 ease-out bg-primary dark:bg-primaryLight dark:hover:bg-primary hover:bg-primaryDark text-white dark:text-black dark:hover:text-white py-2 px-4 rounded-md focus:outline-none"
                    @click="process()"
                >
                    Process
                    <svg
                        :class="loadBtn ? 'animate-spin ml-1 fill-current block' : 'hidden'"
                        style="width: 24px; height: 24px"
                        viewBox="0 0 24 24"
                    >
                        <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
//import json from '../assets/test.json'
const { required } = require('vuelidate/lib/validators')
export default {
    name: 'home',
    data() {
        return {
            //myJson: json,
            hoverText: false,
            text: '',
            examples: [
                "Appostamenti di caccia realizzati secondo le disposizioni provinciali vigenti in materia di protezione della fauna selvatica ed esercizio della caccia, realizzati in difformità rispetto ai criteri e alle tipologie approvati dalla sottocommissione della CUP con riferimento alle relazioni con il contesto, alle forme e ai materiali da impiegare nella realizzazione. Il proprietario dichiara di essere a conoscenza l'autorizzazione paesaggistica è efficace per cinque anni dal rilascio. Se l'autorizzazione è necessaria per l'esecuzione di un intervento soggetto a permesso di costruire o a SCIA, la scadenza dell'autorizzazione paesaggistica coincide con quella del titolo abilitativo edilizio, anche nel caso di proroga del titolo. In ogni caso la domanda di permesso di costruire o la SCIA sono richieste o presentate, rispettivamente, entro un anno dal rilascio dell'autorizzazione paesaggistica.",
                'Un picchio e una pallina si trovavano in un cassetto in mezzo agli altri giocattoli, e il picchio propose: "Visto che siamo insieme qui nel cassetto, perché non ci fidanziamo?" Ma la palla, che era di cuoio marocchino e si credeva una signorina di buona società, nemmeno volle rispondere. Il giorno dopo il bambino che possedeva tutti i giocattoli ridipinse il picchio di rosso e di giallo, e ci piantò un chiodo d&apos;ottone nel mezzo: che figurone che faceva adesso, soprattutto quando faceva la ruota. "Guardami, adesso", disse alla pallina. "Perché non ci fidanziamo? Tu salti, io ballo, siamo fatti l&apos;uno per l&apos;altra! Chi potrebbe essere più felice di noi?" "Davvero?", diceva la pallina, "ma lo sai che mio padre e mia madre erano pantofole in marocchino, e che io ho un tappo in corpo!" "E allora? Io sono fatto di mogano, ed è stato il sindaco del paese a farmi! Lui ha un tornio, e si è divertito tantissimo!" "Mah... mi devo fidare?", disse la pallina. "Che io sia frustato, se ho mai detto una bugia!", rispose il picchio. "Dici bene, tu", disse la pallina, "ma io sono già quasi fidanzata con un rondone! Ogni volta che mi lancio in aria, lui si affaccia dal nido e dice "mi vuoi? Mi vuoi?" Io ormai ho detto di sì nel mio cuore , e questo è quasi un mezzo fidanzamento! Comunque ti prometto che non ti dimenticherò mai!" "Sì, capirai!", disse il picchio, e non si rivolsero più la parola. Il giorno dopo la pallina fu tolta dal cassetto: il picchio la vide lanciarsi alta nell&apos;aria, come un uccello, finché non scompariva.',
            ],
            exampleChoice: '',
            loadBtn: false,
        }
    },
    validations() {
        return {
            text: {
                required,
            },
        }
    },
    created() {
        localStorage.setItem('text', '')
        localStorage.setItem('processedText', '')
    },
    methods: {
        process() {
            this.loadBtn = true
            this.$v.$touch()
            if (!this.$v.$invalid) {
                axios({
                    mode: 'get',
                    url: 'http://dh-server.fbk.eu:8013/tint/?text=' + encodeURIComponent(this.text),
                })
                    .then(res => {
                        localStorage.setItem('text', this.text)
                        localStorage.setItem('processedText', JSON.stringify(res.data))
                        this.$store.state.editableData = res.data
                        this.loadBtn = false
                        this.$router.push({ name: 'result' })
                    })
                    .catch(err => {
                        this.$emit('snack', 'Server error. Try again')
                        console.log(err)
                        this.loadBtn = false
                    })
            } else {
                this.loadBtn = false
            }
        },
        decodeEntities(string) {
            return string
                .replace(/&apos;/g, "'")
                .replace(/&quot;/g, '"')
                .replace(/&gt;/g, '>')
                .replace(/&lt;/g, '<')
                .replace(/&amp;/g, '&')
        },
    },
    watch: {
        exampleChoice() {
            this.exampleChoice != ''
                ? (this.text = this.decodeEntities(this.examples[parseInt(this.exampleChoice) - 1]))
                : ''
        },
    },
}
</script>

<style scoped>
.pin-r {
    right: 0;
}

.pin-y {
    top: 0;
    bottom: 0;
}
</style>
