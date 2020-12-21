<template>
  <div class="overflow-x-auto" v-if="!loading">
    <table class="xs2:min-w-5/6 bg-white mx-auto border border-gray-300">
      <thead>
        <tr
          class="text-left text-white bg-primary border-b border-gray-300 uppercase"
        >
          <th class="px-2" v-for="h in headers" :key="h">{{ h }}</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(d, index) in $store.state.editableData.sentences[
            currentPhrase
          ].tokens"
          :key="index"
          class="border-b border-gray-300 hover:bg-gray-100"
        >
          <td
            class="p-1 px-2 border-r border-gray-300 overflow-x-auto"
            v-html="d.index"
          ></td>
          <td class="p-1 px-2 border-r border-gray-300" v-html="d.word"></td>
          <td class="p-1 px-2 border-r border-gray-300" v-html="d.lemma"></td>
          <!--riga 21 da ricavare-->
          <td class="p-1 px-2 border-r border-gray-300 uppercase">
            {{
              d.pos.indexOf("+") == -1
                ? upos[d.pos]
                : upos[d.pos.split("+")[0]] + " + " + upos[d.pos.split("+")[1]]
            }}
          </td>
          <td class="p-1 px-2 border-r border-gray-300" v-html="d.pos"></td>
          <td
            class="p-1 px-2 border-r border-gray-300"
            v-html="d.featuresText"
          ></td>
          <td
            class="p-1 px-2 border-r border-gray-300"
            v-html="d.hyphenation"
          ></td>
          <td class="p-1 px-2 border-r border-gray-300">
            <select
              :name="'head' + d.index"
              :id="'head' + d.index"
              v-model="headsEditable[d.index]"
              class="w-full"
            >
              <option
                v-for="(n, c) in $store.state.editableData.sentences[
                  currentPhrase
                ]['basic-dependencies'].length + 1"
                :key="c"
                :value="c"
                @click="$set(headsEditable, d.index, c)"
                >{{ c }}</option
              >
            </select>
          </td>
          <td class="p-1 px-2 border-r border-gray-300">
            <select
              :name="'dep' + d.index"
              :id="'dep' + d.index"
              v-model="deprelsEditable[d.index]"
              class="w-full"
            >
              <option
                v-for="i in deps"
                :key="i"
                :value="i"
                @click="$set(deprelsEditable, d.index, i)"
                >{{ i }}</option
              >
            </select>
          </td>
          <td class="p-1 px-2 border-r border-gray-300">-</td>
          <td class="p-1 px-2">NER: {{ d.ner == "O" ? "-" : d.ner }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "tmp",
  data() {
    return {
      headers: [
        "id",
        "form",
        "lemma",
        "upos",
        "xpos",
        "feats",
        "hyphenation",
        "head",
        "deprel",
        "deps",
        "misc",
      ],
      upos: {
        A: "adjective",
        AP: "possessive adjective",
        B: "adverb",
        BN: "negation adverb",
        CC: "coordinative conjunction",
        CS: "subordinative conjunction",
        DE: "exclamative determiner",
        DI: "indefinite determiner",
        DQ: "interrogative determiner",
        DR: "relative determiner",
        DD: "demonstrative determiner",
        E: "preposition",
        EA: "articulated preposition",
        FB: "“balanced” punctuation",
        FC: "clause boundary punctuation",
        FF: "comma, hyphen",
        FS: "sentence boundary punctuation",
        I: "interjection",
        N: "cardinal number",
        NO: "ordinal number",
        PD: "demonstrative pronoun",
        PE: "personal pronoun",
        PI: "indefinite pronoun",
        PP: "possessive pronoun",
        PQ: "interrogative pronoun",
        PR: "relative pronoun",
        PC: "clitic pronoun",
        RD: "determinative article",
        RI: "indeterminative article",
        S: "common noun",
        SA: "abbreviation",
        SP: "proper noun",
        T: "predeterminer",
        VA: "auxiliary verb",
        VM: "modal verb",
        V: "main verb",
        X: "residual class",
      },
      headsRef: {}, //object with the index of the token as the key and the head as the values
      headsEditable: {},
      deprelsRef: {}, //object with the index of the token as the key and the deprel as the value
      deprelsEditable: {},
      deps: [
        "ROOT", //aggiunta a mano
        "acl",
        "acl:relcl",
        "advcl",
        "advmod",
        "amod",
        "appos",
        "aux",
        "aux:pass",
        "case",
        "cc",
        "ccomp",
        "compound",
        "conj",
        "cop",
        "csubj",
        "csubj:pass",
        "dep",
        "det",
        "det:poss",
        "det:predet",
        "discourse",
        "dislocated",
        "dobj", //aggiunta a mano
        "expl",
        "expl:impers",
        "expl:pass",
        "fixed",
        "flat",
        "flat:foreign",
        "flat:name",
        "goeswith",
        "iobj",
        "mark",
        "nmod",
        "nsubj",
        "nsubj:pass",
        "nummod",
        "obj",
        "obl",
        "obl:agent",
        "orphan",
        "parataxis",
        "punct",
        "root",
        "vocative",
        "xcomp",
      ],
      currentData: JSON.parse(localStorage.getItem("processedText")), //da lasciare poi solo in edit
      currentPhrase: 0, //da spostare poi come prop
      loading: true,
    };
  },
  beforeCreate() {
    this.$store.state.editableData = JSON.parse(
      localStorage.getItem("processedText")
    );
  },
  created() {
    var phraseDependencies = this.$store.state.editableData.sentences[
      this.currentPhrase
    ]["basic-dependencies"];
    for (let i = 0; i < phraseDependencies.length; i++) {
      this.headsRef[phraseDependencies[i].dependent] =
        phraseDependencies[i].governor;
      this.deprelsRef[phraseDependencies[i].dependent] =
        phraseDependencies[i].dep;
    }
    this.deprelsEditable = this.deprelsRef;
    this.headsEditable = this.headsRef;
    this.loading = false;
  },
  watch: {
    deprelsEditable: {
      deep: true,
      handler() {
        for (
          let i = 0;
          i <
          this.$store.state.editableData.sentences[this.currentPhrase][
            "basic-dependencies"
          ].length;
          i++
        ) {
          if (
            this.deprelsRef[
              this.$store.state.editableData.sentences[this.currentPhrase][
                "basic-dependencies"
              ][i].dependent
            ] !=
            this.deprelsEditable[
              this.$store.state.editableData.sentences[this.currentPhrase][
                "basic-dependencies"
              ][i].dependent
            ]
          ) {
            this.$store.state.editableData.sentences[this.currentPhrase][
              "basic-dependencies"
            ][i].dep = this.deprelsEditable[
              this.$store.state.editableData.sentences[this.currentPhrase][
                "basic-dependencies"
              ][i].dependent
            ];
            console.log(
              "cambiato deprel a posizione: " +
                this.$store.state.editableData.sentences[this.currentPhrase][
                  "basic-dependencies"
                ][i].dependent +
                ". nuovo valore: " +
                this.deprelsEditable[
                  this.$store.state.editableData.sentences[this.currentPhrase][
                    "basic-dependencies"
                  ][i].dependent
                ]
            );
          }
        }
      },
    },
    headsEditable: {
      deep: true,
      handler() {
        for (
          let i = 0;
          i <
          this.$store.state.editableData.sentences[this.currentPhrase][
            "basic-dependencies"
          ].length;
          i++
        ) {
          if (
            this.headsRef[
              this.$store.state.editableData.sentences[this.currentPhrase][
                "basic-dependencies"
              ][i].dependent
            ] !=
            this.headsEditable[
              this.$store.state.editableData.sentences[this.currentPhrase][
                "basic-dependencies"
              ][i].dependent
            ]
          ) {
            this.$store.state.editableData.sentences[this.currentPhrase][
              "basic-dependencies"
            ][i].governor = this.headsEditable[
              this.$store.state.editableData.sentences[this.currentPhrase][
                "basic-dependencies"
              ][i].dependent
            ];
            console.log(
              "cambiato head a posizione: " +
                this.$store.state.editableData.sentences[this.currentPhrase][
                  "basic-dependencies"
                ][i].dependent +
                ". nuovo valore: " +
                this.headsEditable[
                  this.$store.state.editableData.sentences[this.currentPhrase][
                    "basic-dependencies"
                  ][i].dependent
                ]
            );
          }
        }
      },
    },
  },
};
</script>

<style></style>
