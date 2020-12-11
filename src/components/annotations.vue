<template>
  <div>
    <div class="w-full grid grid-cols-4 text-center">
      <div
        @click="selectedTab = 0"
        class="transition duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t"
        :class="selectedTab == 0 ? 'text-primary' : 'text-gray-500'"
      >
        Basic information
      </div>
      <div
        @click="selectedTab = 1"
        class="transition duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t"
        :class="selectedTab == 1 ? 'text-primary' : 'text-gray-500'"
      >
        Part of Speech
      </div>
      <div
        @click="selectedTab = 2"
        class="transition duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t"
        :class="selectedTab == 2 ? 'text-primary' : 'text-gray-500'"
      >
        Named Entity Recognition
      </div>
      <div
        @click="selectedTab = 3"
        class="transition duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t"
        :class="selectedTab == 3 ? 'text-primary' : 'text-gray-500'"
      >
        Basic Dependencies
      </div>
    </div>
    <div class="h-1 w-1/4 bg-primary" :class="tabScroll"></div>
    <basicInfo v-if="selectedTab == 0" />
    <partOfSpeech v-else-if="selectedTab == 1" />
    <ner v-else-if="selectedTab == 2" />
    <basicDependencies v-else />
  </div>
</template>

<script>
import partOfSpeech from "./partOfSpeech.vue";
import basicDependencies from "./basicDependencies.vue";
import ner from "./ner.vue";
import basicInfo from "./basicInfo.vue";
export default {
  data() {
    return {
      selectedTab: 0,
      tabScroll: "transition transform translate-x-0",
    };
  },
  components: {
    partOfSpeech,
    ner,
    basicDependencies,
    basicInfo,
  },
  watch: {
    selectedTab: function() {
      if (this.selectedTab == 0) {
        this.tabScroll = "transition transform translate-x-0";
      } else if (this.selectedTab == 1) {
        this.tabScroll = "transition transform translate-x-full";
      } else {
        this.tabScroll =
          "transition transform translate-x-" + this.selectedTab + "full";
      }
    },
  },
};
</script>

<style></style>
