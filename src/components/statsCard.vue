<template>
  <div class="rounded-lg shadow-md p-4 mx-auto bg-white w-full mt-2 lg:mt-0">
    <div class="flex justify-between content-center items-center">
      <h2 class="text-primary font-bold text-lg">{{ title }}</h2>
      <button
        class="ripple p-2 bg-transparent hover:bg-gray-200 rounded-full focus:outline-none transition duration-100 ease-out"
      >
        <svg
          class="fill-current text-primary"
          style="width: 24px; height: 24px"
          viewBox="0 0 24 24"
        >
          <path
            d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11V17Z"
          />
        </svg>
      </button>
    </div>
    <apexchart
      type="radialBar"
      :height="height"
      :options="options"
      :series="series"
    ></apexchart>
  </div>
</template>
<script>
import VueApexCharts from "vue-apexcharts";
export default {
  name: "statsCard",
  props: {
    title: String,
    options: Object,
    series: Array,
    height: Number,
  },
  components: {
    apexchart: VueApexCharts,
  },
};
</script>