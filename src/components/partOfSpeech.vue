<template>
  <div class="mt-4">
    <div class="font-bold text-lg">Legend</div>
    <div class="grid grid-rows-4 grid-flow-col gap-x-1 auto-cols-auto mb-6 max-w-max">
      <div v-for="(i, x) in Object.keys(legend)" :key="x">
        <div
          class="h-3 w-3 rounded-full inline-block"
          :style="{ background: legend[i] }"
        ></div>
        {{ i }}{{ posDesc[i] ? ": " + posDesc[i] : ": Other" }}
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
            class="flex flex-col place-items-center my-2 cursor-pointer"
          >
            <!-- <singleToken
              :token="token"
              :color="legend[token.pos[0]]"
              :mode="'pos'"
            /> -->
            <span
              class="rounded px-1 text-sm font-light max-w-min mx-1 mb-1"
              :style="{ background: legend[token.pos[0]] }"
              >{{ token.pos }}
            </span>
            <span class="mx-1 px-1 rounded bg-gray-100">{{ token.word }}</span>
          </span>
        </div>
        <!-- </div> -->
      </div>
    </div>
  </div>
</template>

<script>
//import singleToken from "../components/singleToken.vue";

export default {
  components: {
    //singleToken,
  },
  data() {
    return {
      keys: [],
      values: [],
      poses: [],
      legend: {},
      posDesc: [],
      colors: [
        "#72A1E5",
        "#7977E8",
        "#AD7CEA",
        "#DF80EC",
        "#EE85CE",
        "#F08BA6",
        "#F2A090",
        "#F4CD95",
        "#F4F69B",
        "#D0F7A0",
        "#B0F8A6",
        "#ACFAC4",
        "#B2FBE7",
        "#B8F1FC",
        "#BFD8FD",
      ].reverse(),
      processedData: JSON.parse(localStorage.getItem("processedText")),
    };
  },
  created() {
    this.posDesc = this.processedData.readability.genericPosDescription;
    //console.log(this.posDesc);
    for (var i = 0; i < this.processedData.sentences.length; i++) {
      for (var x = 0; x < this.processedData.sentences[i].tokens.length; x++) {
        this.poses.includes(this.processedData.sentences[i].tokens[x].pos[0])
          ? ""
          : this.poses.push(this.processedData.sentences[i].tokens[x].pos[0]);
      }
    }
    this.poses.sort();
    for (let i = 0; i < this.poses.length; i++) {
      this.legend[this.poses[i]] = this.colors[i];
    }
    //console.log(this.legend);
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
};
</script>

<style></style>
