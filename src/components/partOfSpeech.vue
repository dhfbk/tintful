<template>
  <div class="grid grid-cols-4">
    <div class="col-span-3 divide-y divide-primary divide-opacity-75">
      <div
        v-for="sen in json.sentences"
        :key="sen.index"
        class=" flex flex-row flex-wrap"
      >
        <span
          v-for="token in sen.tokens"
          :key="token.index"
          @click="info = token"
          class="flex flex-col place-items-center my-2"
        >
          <span
            class="rounded bg-gray-200 px-1 text-sm font-light max-w-min mx-1 mb-1"
            >{{ token.pos }}
          </span>
          <span class="mx-1 bg-gray-100 rounded">{{ token.word }}</span>
        </span>
      </div>
    </div>
    <div class="col-span-1 sticky">
      <span v-if="!info" class="font-bold"
        >Click on a word for further information.</span
      >
      <span v-else v-for="(i, x) in keys" :key="x">
        <span class="font-bold">{{ i }}</span
        >: {{ values[x] }}<br
      /></span>
    </div>
  </div>
</template>

<script>
import json from "../assets/test.json";

export default {
  data() {
    return {
      json: json,
      info: "",
      keys: [],
      values: [],
    };
  },
  watch: {
    info: function() {
      if (this.info != "") {
        this.keys = Object.keys(this.info);
        this.values = Object.values(this.info);
        console.log(this.keys, this.values);
      }
    },
  },
};
</script>

<style></style>
