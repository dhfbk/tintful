<template>
  <div class="mt-4">
    <div class="font-bold text-lg">Legend</div>
    <div :class="'grid grid-rows-' + nerDesc.length + 'grid-flow-col mb-6'">
      <div v-for="(i, x) in Object.keys(legend)" :key="x">
        <div
          class="h-3 w-3 rounded-full inline-block"
          :style="{ background: Object.values(legend)[x] }"
        ></div>
        {{ i }}
      </div>
    </div>
    <div class="divide-y divide-primary divide-opacity-75">
      <div v-for="sen in processedData.sentences" :key="sen.index" class="">
        <!-- <div class="flex flex-row"> -->
        <!-- <div style="min-width: 48px;" class="h-full bg-gray-100">
              {{ sen.index }}
            </div> -->
        <div class="flex flex-row flex-wrap">
          <span
            v-for="token in sen.tokens"
            :key="token.index"
            @click="info = token"
            class="flex flex-col place-items-center my-2 cursor-pointer"
          >
            <!-- <singleToken
             
              :token="token"
              :colorsNer="legend"
              :descNer="nerDesc"
              :mode="'ner'"
            /> -->
            <span
              class="mx-1 px-1 rounded"
              :class="
                token.ner == 'PER' || token.ner == 'ORG' || token.ner == 'LOC'
                  ? 'text-white'
                  : 'bg-gray-100'
              "
              :style="{
                background:
                  token.ner == 'PER' || token.ner == 'ORG' || token.ner == 'LOC'
                    ? legend[token.ner]
                    : '',
              }"
              >{{ token.word }}</span
            >
          </span>
        </div>
        <!-- </div> -->
      </div>
    </div>
  </div>
</template>

<script>
//import JSONFormatter from "json-formatter-js";
//import singleToken from "../components/singleToken.vue";

export default {
  name: "ner",
  components: {
    //singleToken,
  },
  data() {
    return {
      info: "",
      keys: [],
      values: [],
      ner: {},
      legend: {},
      nerDesc: [
        //"O",
        "PER",
        "ORG",
        //"MISC",
        "LOC",
        //"DATE | TIME | SET | NUMBER",
        //"MONEY",
        //"PERCENT",
      ],
      colors: ["ffbf00", "e83f6f", "2274a5"],
      processedData: JSON.parse(localStorage.getItem("processedText")),
    };
  },
  created() {
    for (var i = 0; i < this.processedData.sentences.length; i++) {
      this.ner.i = {};
      for (var x = 0; x < this.processedData.sentences[i].tokens.length; x++) {
        this.ner.i.x = this.processedData.sentences[i].tokens[x].ner;
      }
    }
    for (let i = 0; i < this.nerDesc.length; i++) {
      this.legend[this.nerDesc[i]] = "#" + this.colors[i];
    }
  },
  methods: {},
  watch: {
    info: function() {
      if (this.info != "") {
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
        // this.keys = Object.keys(this.info);
        // this.values = Object.values(this.info);
        // var i = 0;
        // for (i = 0; i < this.keys.length; i++) {
        //   if (this.keys[i] == "featuresText") {
        //     break;
        //   }
        // }
        // this.values[i] = this.values[i].replace(/\|/g, ", ");
        // console.log(this.values[i]);
      }
    },
  },
};
</script>

<style></style>
