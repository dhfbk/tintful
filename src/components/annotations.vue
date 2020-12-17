<template>
  <div>
    <span
      class="rounded ripple bg-gray-200 p-1 m-1 cursor-pointer"
      @click="$router.push({ path: '/edit' })"
    >
      Edit
    </span>
    <div class="overflow-x-auto">
      <div class="w-full grid grid-cols-4 text-center min-w-max">
        <div
          @click="(selectedTab = 0), $emit('opensheet')"
          class="transition-colors duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t min-w-max px-1"
          :class="selectedTab == 0 ? 'text-primary' : 'text-gray-500'"
        >
          General Information
        </div>
        <div
          @click="(selectedTab = 1), $emit('closesheet')"
          class="transition-colors duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t min-w-max px-1"
          :class="selectedTab == 1 ? 'text-primary' : 'text-gray-500'"
        >
          Part Of Speech
        </div>
        <div
          @click="(selectedTab = 2), $emit('closesheet')"
          class="transition-colors duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t min-w-max px-1"
          :class="selectedTab == 2 ? 'text-primary' : 'text-gray-500'"
        >
          Named Entity Recognition
        </div>
        <div
          @click="(selectedTab = 3), $emit('closesheet')"
          class="transition-colors duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t min-w-max px-1"
          :class="selectedTab == 3 ? 'text-primary' : 'text-gray-500'"
        >
          Basic Dependencies
        </div>
        <div class="col-span-4">
          <div class="h-1 w-1/4 bg-primary" :class="tabScroll"></div>
        </div>
      </div>
    </div>
    <general-info v-if="selectedTab == 0" @sheet="sheet" />
    <partOfSpeech v-else-if="selectedTab == 1" />
    <ner v-else-if="selectedTab == 2" />
    <dependencies v-else />
    <br />
  </div>
</template>

<script>
import partOfSpeech from "./partOfSpeech.vue";
import ner from "./ner.vue";
import generalInfo from "./generalInfo.vue";
import dependencies from "./dependencies.vue";
export default {
  data() {
    return {
      selectedTab: 0,
      tabScroll: "transition-transform ease-out transform translate-x-0",
    };
  },
  components: {
    partOfSpeech,
    ner,
    generalInfo,
    dependencies,
  },
  methods: {
    sheet(arr) {
      this.$emit("sheet", arr);
    },
  },
  watch: {
    selectedTab: function() {
      if (this.selectedTab == 0) {
        this.tabScroll =
          "transition-transform ease-out transform translate-x-0";
      } else if (this.selectedTab == 1) {
        this.tabScroll =
          "transition-transform ease-out transform translate-x-full";
      } else {
        this.tabScroll =
          "transition-transform ease-out transform translate-x-" +
          this.selectedTab.toString() +
          "full";
      }
    },
  },
};
</script>

<style></style>
