<template>
  <span
    v-if="mode == 'pos'"
    @mouseenter="bg = 'bg-gray-300'"
    @mouseleave="bg = 'bg-gray-100'"
  >
    <span
      class="rounded px-1 text-sm font-light max-w-min mx-1 mb-1"
      :style="{ background: color }"
      >{{ token.pos }}
    </span>
    <span class="mx-1 px-1 rounded" :class="bg">{{ token.word }}</span>
  </span>
  <span v-else>
    <span class="mx-1 px-1 rounded" :style="{ background: nerColor }">{{
      token.word
    }}</span>
  </span>
</template>

<script>
export default {
  props: {
    token: Object,
    color: String,
    mode: String,
    colorsNer: Object,
    descNer: Array,
  },
  data() {
    return {
      bg: "bg-gray-100",
      nerColor: "",
    };
  },
  created() {
    if (this.mode == "ner") {
      for (var i = 0; i < this.descNer.length; i++) {
        if (this.descNer[i] == this.token.ner) {
          this.nerColor = this.colorsNer[i];
          break;
        }
      }
    }
  },
};
</script>

<style></style>
