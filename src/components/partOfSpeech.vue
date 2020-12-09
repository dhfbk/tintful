<template>
  <div class="grid grid-cols-3 mt-4">
    <div class="col-span-2">
      <div class="text-center font-bold">Legend</div>
      <div class="grid grid-rows-4 grid-flow-col mb-4">
        <div v-for="(i, x) in Object.keys(legend)" :key="x">
          <div
            class="h-3 w-3 rounded-full inline-block"
            :style="{ background: Object.values(legend)[x] }"
          ></div>
          {{ i }}{{ posDesc[i] ? ": " + posDesc[i] : ": Other" }}
        </div>
      </div>
      <div class="divide-y divide-primary divide-opacity-75">
        <div
          v-for="sen in json.sentences"
          :key="sen.index"
          class="flex flex-row flex-wrap"
        >
          <span
            v-for="token in sen.tokens"
            :key="token.index"
            @click="info = token"
            class="flex flex-col place-items-center my-2"
          >
            <span
              class="rounded px-1 text-sm font-light max-w-min mx-1 mb-1"
              :style="{ background: legend[token.pos[0]] }"
              >{{ token.pos }}
            </span>
            <span class="mx-1 bg-gray-100 rounded">{{ token.word }}</span>
          </span>
        </div>
      </div>
    </div>
    <div class="col-span-1">
      <span v-if="!info" class="font-light text-lg text-center sticky top-0"
        >Click on a tag for further information.</span
      >
      <div class="sticky top-0" v-else>
        <div class="font-light text-lg text-center">
          Info on "{{ info.word }}"
        </div>
        <div class="overflow-x-auto" id="formatter1"></div>

        <!-- <span v-for="(i, x) in keys" :key="x">
          <span class="font-bold">{{ i }}</span
          >: {{ values[x] }}<br
        /></span> -->
      </div>
    </div>
  </div>
</template>

<script>
import json from "../assets/test.json";
import JSONFormatter from "json-formatter-js";

export default {
  data() {
    return {
      json: json,
      info: "",
      keys: [],
      values: [],
      poses: [],
      legend: {},
      posDesc: [],
    };
  },
  created() {
    this.posDesc = json.readability.genericPosDescription;
    console.log(this.posDesc);
    for (var i = 0; i < this.json.sentences.length; i++) {
      for (var x = 0; x < this.json.sentences[i].tokens.length; x++) {
        if (!this.poses.includes(json.sentences[i].tokens[x].pos)) {
          this.poses.push(json.sentences[i].tokens[x].pos[0]);
        }
      }
    }
    this.poses.sort();
    for (let i = 0; i < this.poses.length; i++) {
      this.legend[this.poses[i]] = this.getColor();
    }
    console.log(this.legend);
  },
  methods: {
    getColor() {
      return (
        "hsl(" +
        360 * Math.random() +
        "," +
        (25 + 80 * Math.random()) +
        "%," +
        (80 + 10 * Math.random()) +
        "%)"
      );
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
        if (
          document.getElementsByClassName(
            "json-formatter-row json-formatter-open"
          )[0]
        ) {
          document
            .getElementsByClassName("json-formatter-row json-formatter-open")[0]
            .remove();
        }
        if (
          document.getElementsByClassName(
            "json-formatter-row json-formatter-open"
          )[1]
        ) {
          document
            .getElementsByClassName("json-formatter-row json-formatter-open")[1]
            .remove();
        }

        const formatter = new JSONFormatter(this.info);

        setTimeout(() => {
          document.getElementById("formatter1").appendChild(formatter.render());
          // console.log(document.getElementById("formatter1").re);
        }, 1);

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
