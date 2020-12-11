<template>
  <div class="mt-4">
    <div class="font-bold text-lg">Legend</div>
    <div class="grid grid-rows-4 grid-flow-col mb-6">
      <div v-for="(i, x) in Object.keys(legend)" :key="x">
        <div
          class="h-3 w-3 rounded-full inline-block"
          :style="{ background: Object.values(legend)[x] }"
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
          <span v-for="token in sen.tokens" :key="token.index">
            <singleToken
              class="flex flex-col place-items-center my-2 cursor-pointer"
              :token="token"
              :color="legend[token.pos[0]]"
              :mode="'pos'"
            />
          </span>
        </div>
        <!-- </div> -->
      </div>
    </div>
  </div>
</template>

<script>
import singleToken from "../components/singleToken.vue";

export default {
  components: {
    singleToken,
  },
  data() {
    return {
      keys: [],
      values: [],
      poses: [],
      legend: {},
      posDesc: [],
      processedData: JSON.parse(localStorage.getItem("processedText")),
    };
  },
  created() {
    this.posDesc = this.processedData.readability.genericPosDescription;
    //console.log(this.posDesc);
    for (var i = 0; i < this.processedData.sentences.length; i++) {
      for (var x = 0; x < this.processedData.sentences[i].tokens.length; x++) {
        this.poses.includes(this.processedData.sentences[i].tokens[x].pos)
          ? ""
          : this.poses.push(this.processedData.sentences[i].tokens[x].pos[0]);
      }
    }
    this.poses.sort();
    for (let i = 0; i < this.poses.length; i++) {
      this.legend[this.poses[i]] = this.getColor();
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
