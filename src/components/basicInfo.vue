<template>
  <div class="grid grid-cols-3 gap-x-2 mt-4">
    <div class="col-span-3 md:col-span-2">
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
              @click="(info = token), (selected = token.characterOffsetBegin)"
              class="flex flex-col place-items-center my-2 cursor-pointer"
            >
              <span
                class="mx-1 px-1 bg-gray-100 rounded ripple"
                :class="
                  selected == token.characterOffsetBegin ? 'bg-gray-300' : ''
                "
                >{{ token.word }}</span
              >
            </span>
          </div>
          <div>info</div>
          <!-- </div> -->
        </div>
      </div>
    </div>
    <div class="hidden md:block md:col-span-1">
      <div v-if="!info" class="font-bold text-lg text-center sticky top-1/2">
        Click on a token for further information.
      </div>
      <div class="sticky top-0 overflow-y-auto h-screen" v-else>
        <div class="font-light text-lg text-center">
          Info on the token "
          <span class="font-bold">{{ info.word }}</span>
          "
        </div>
        <!-- <div class="overflow-x-auto" id="formatter1"></div> -->

        <span v-for="(i, x) in keys" :key="x">
          <span class="font-bold">{{ i }}</span
          >: {{ values[x] }}<br
        /></span>
      </div>
    </div>
  </div>
</template>

<script>
//import JSONFormatter from "json-formatter-js";
//import singleToken from "../components/singleToken.vue";

export default {
  name: "basicInfo",
  components: {
    // singleToken,
  },
  data() {
    return {
      info: "",
      keys: [],
      values: [],
      // poses: [],
      // legend: {},
      processedData: JSON.parse(localStorage.getItem("processedText")),
      selected: null,
    };
  },
  created() {
    //console.log(this.posDesc);
    // for (var i = 0; i < this.processedData.sentences.length; i++) {
    //   for (var x = 0; x < this.processedData.sentences[i].tokens.length; x++) {
    //     this.poses.includes(this.processedData.sentences[i].tokens[x].pos)
    //       ? ""
    //       : this.poses.push(this.processedData.sentences[i].tokens[x].pos[0]);
    //   }
    // }
    // this.poses.sort();
    // for (let i = 0; i < this.poses.length; i++) {
    //   this.legend[this.poses[i]] = this.getColor();
    // }
    //console.log(this.legend);
  },
  methods: {
    sheet() {
      var arr = [this.info, this.keys, this.values];
      this.$emit("sheet", arr);
    },
  },
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
        delete this.info.originalText;
        delete this.info.ner;
        delete this.info.guessed_lemma;
        this.keys = Object.keys(this.info);
        this.values = Object.values(this.info);
        var i = 0;
        for (i = 0; i < this.keys.length; i++) {
          if (this.keys[i] == "featuresText") {
            this.values[i] = this.values[i].replace(/\|/g, ", ");
          }
          if (
            this.keys[i] == "full_morpho" ||
            this.keys[i] == "selected_morpho"
          ) {
            this.values[i] = this.values[i].replace(/\+/g, " + ");
          }
        }
      }
      this.sheet();
    },
  },
};
</script>

<style></style>
