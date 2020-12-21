<template>
  <div class="overflow-x-auto">
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
          v-for="(d, index) in newData.sentences[currentPhrase].tokens"
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
            {{ heads[d.index] }}
          </td>
          <td class="p-1 px-2 border-r border-gray-300">
            {{ deprels[d.index] }}
          </td>
          <td class="p-1 px-2 border-r border-gray-300">-</td>
          <td class="p-1 px-2">
            NER: {{ d.ner == "O" ? "-" : d.ner }}
          </td>
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
      heads: {}, //object with the index of the token as the key and the head as the value
      deprels: {}, //object with the index of the token as the key and the deprel as the value
      deps: [
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
      currentData: JSON.parse(localStorage.getItem("processedText")),
      newData: JSON.parse(localStorage.getItem("processedText")),
      currentPhrase: 0, //da spostare poi come prop
    };
  },
  created() {
    var phraseDependencies = this.newData.sentences[this.currentPhrase][
      "basic-dependencies"
    ];
    for (let i = 0; i < phraseDependencies.length; i++) {
      this.heads[phraseDependencies[i].dependent] =
        phraseDependencies[i].governor;
      this.deprels[phraseDependencies[i].dependent] = phraseDependencies[i].dep;
    }
  },
};
</script>

<style></style>
