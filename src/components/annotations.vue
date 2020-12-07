<template>
  <div>
    <div class="w-full grid grid-cols-3 text-center">
      <div @click="selectedTab = 0">
        Part of Speech
      </div>
      <div @click="selectedTab = 1">Named Entity Recognition</div>
      <div @click="selectedTab = 2">Basic Dependencies</div>
    </div>
    <div class="h-1 w-1/3 bg-primary " :class="tabScroll"></div>
    <partOfSpeech v-if="selectedTab == 0" />
  </div>
</template>

<script>
import partOfSpeech from "../components/partOfSpeech.vue";
export default {
  data() {
    return {
      selectedTab: 0,
      tabScroll: "transition transform translate-x-0",
    };
  },
  components: {
    partOfSpeech,
  },
  watch: {
    selectedTab: function() {
      if (this.selectedTab == 0) {
        this.tabScroll = "transition transform translate-x-0";
      } else if (this.selectedTab == 1) {
        this.tabScroll = "transition transform translate-x-full";
      } else {
        this.tabScroll = "transition transform translate-x-2full";
      }
    },
  },
};
</script>

<style></style>
