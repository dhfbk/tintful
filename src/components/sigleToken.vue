<template>
  <span @mouseenter="bg = 'bg-gray-300'" @mouseleave="bg = 'bg-gray-100'">
    <span
      class="rounded px-1 text-sm font-light max-w-min mx-1 mb-1"
      :style="{ background: color }"
      >{{ token.pos }}
    </span>
    <span class="mx-1 px-1 rounded" :class="bg">{{ token.word }}</span>
  </span>
</template>

<script>
export default {
  props: {
    token: Object,
    color: String,
  },
  data() {
    return {
      bg: "bg-gray-100",
    };
  },
};
</script>

<style></style>
