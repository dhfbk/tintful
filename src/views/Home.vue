<template>
  <div>
    <div class="rounded-lg shadow-md p-4 max-w-5xl mx-auto bg-white">
      <div class="m-2">
        <label
          class="block tracking-wide text-gray-900 text-md font-bold pb-2"
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
              hoverText ? 'border-primary' : 'border-gray-200',
              $v.text.$error ? 'border-primary' : 'border-gray-200',
            ]"
            class="w-full border-2 transition duration-150 ease-out hover:border-primary rounded-md py-2 px-4 focus:outline-none"
            id="textInput"
            rows="8"
            @focus="hoverText = true"
            @blur="hoverText = false"
            v-model.trim="$v.text.$model"
            required
          ></textarea>
        </div>
      </div>
      <div class="m-2">
        <label
          class="block tracking-wide text-gray-900 text-md font-bold mr-2"
          for="action"
          >You can also choose an example:</label
        >
        <div class="relative">
          <select
            class="block border-b-2 border-gray-200 appearance-none w-full py-2 pl-2 pr-8 text-gray-700 transition duration-150 ease-out focus:outline-none focus:border-primary hover:border-primary"
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
            class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900"
          >
            <svg
              class="h-4 w-4"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
            >
              <path
                d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
              />
            </svg>
          </div>
        </div>
      </div>
      <div class="m-2">
        <p
          class="text-blue-500 text-lg text-center"
          :class="serverError ? 'block' : 'hidden'"
        >
          A server error happened! Check the browser's console for more details
        </p>
      </div>
      <div class="m-2 mt-4 width-full flow-root">
        <button
          class="ripple flex flex-row transition duration-100 ease-out bg-primary hover:bg-primaryDark text-white py-2 px-4 rounded focus:outline-none float-right"
          @click="process()"
        >
          <svg
            :class="loading ? 'animate-spin mr-1 fill-current' : 'hidden'"
            style="width: 24px; height: 24px"
            viewBox="0 0 24 24"
          >
            <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" /></svg
          >Process
        </button>
      </div>
    </div>
  </div>
</template>

<script>
//import axios from "axios";
const { required } = require("vuelidate/lib/validators");
export default {
  name: "home",
  data() {
    return {
      hoverText: false,
      text: "",
      examples: [
        "Appostamenti di caccia realizzati secondo le disposizioni provinciali vigenti in materia di protezione della fauna selvatica ed esercizio della caccia, realizzati in difformità rispetto ai criteri e alle tipologie approvati dalla sottocommissione della CUP con riferimento alle relazioni con il contesto, alle forme e ai materiali da impiegare nella realizzazione. Il proprietario dichiara di essere a conoscenza l'autorizzazione paesaggistica è efficace per cinque anni dal rilascio. Se l'autorizzazione è necessaria per l'esecuzione di un intervento soggetto a permesso di costruire o a SCIA, la scadenza dell'autorizzazione paesaggistica coincide con quella del titolo abilitativo edilizio, anche nel caso di proroga del titolo. In ogni caso la domanda di permesso di costruire o la SCIA sono richieste o presentate, rispettivamente, entro un anno dal rilascio dell'autorizzazione paesaggistica.",
        'Un picchio e una pallina si trovavano in un cassetto in mezzo agli altri giocattoli, e il picchio propose: "Visto che siamo insieme qui nel cassetto, perché non ci fidanziamo?" Ma la palla, che era di cuoio marocchino e si credeva una signorina di buona società, nemmeno volle rispondere. Il giorno dopo il bambino che possedeva tutti i giocattoli ridipinse il picchio di rosso e di giallo, e ci piantò un chiodo d&apos;ottone nel mezzo: che figurone che faceva adesso, soprattutto quando faceva la ruota. "Guardami, adesso", disse alla pallina. "Perché non ci fidanziamo? Tu salti, io ballo, siamo fatti l&apos;uno per l&apos;altra! Chi potrebbe essere più felice di noi?" "Davvero?", diceva la pallina, "ma lo sai che mio padre e mia madre erano pantofole in marocchino, e che io ho un tappo in corpo!" "E allora? Io sono fatto di mogano, ed è stato il sindaco del paese a farmi! Lui ha un tornio, e si è divertito tantissimo!" "Mah... mi devo fidare?", disse la pallina. "Che io sia frustato, se ho mai detto una bugia!", rispose il picchio. "Dici bene, tu", disse la pallina, "ma io sono già quasi fidanzata con un rondone! Ogni volta che mi lancio in aria, lui si affaccia dal nido e dice "mi vuoi? Mi vuoi?" Io ormai ho detto di sì nel mio cuore , e questo è quasi un mezzo fidanzamento! Comunque ti prometto che non ti dimenticherò mai!" "Sì, capirai!", disse il picchio, e non si rivolsero più la parola. Il giorno dopo la pallina fu tolta dal cassetto: il picchio la vide lanciarsi alta nell&apos;aria, come un uccello, finché non scompariva.',
      ],
      exampleChoice: "",
      loading: false,
      serverError: false,
    };
  },
  validations() {
    return {
      text: {
        required,
      },
    };
  },
  methods: {
    process() {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        this.loading = true;
        this.$store.state.text = this.text;
        /*
        axios({
          url:
            "https://cors-anywhere.herokuapp.com/https://dh.fbk.eu/tint-server",
          method: "POST",
          data: {
            text: this.$store.state.text,
          },
        })
          .then((res) => {
            this.$store.state.processedData = res.data;
            console.log(res.data);
            this.loading = false;
          })
          .catch((err) => {
            console.error(err);
            this.serverError = true;
            this.loading = false;
          });
          */
        this.loading = false;
        this.$router.push({ name: "result" });
      }
    },
    decodeEntities(string) {
      return string
        .replace(/&apos;/g, "'")
        .replace(/&quot;/g, '"')
        .replace(/&gt;/g, ">")
        .replace(/&lt;/g, "<")
        .replace(/&amp;/g, "&");
    },
  },
  watch: {
    exampleChoice() {
      this.exampleChoice != ""
        ? (this.text = this.decodeEntities(
            this.examples[parseInt(this.exampleChoice) - 1]
          ))
        : "";
    },
  },
};
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