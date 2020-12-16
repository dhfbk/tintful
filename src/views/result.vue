<template>
  <div v-if="show" class="grid grid-cols-1 lg:grid-cols-8 gap-x-4">
    <modalInfo v-if="modal" @modal="modal = !modal" :mode="modalMode" />
    <infoCard :jsonData="processedData" />
    <div class="col-span-8 lg:col-span-6 w-full">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
        <statsCard
          :title="'Readability'"
          :height="230"
          :options="chartOptionsReadability"
          :series="seriesReadability"
          :id="'stats0'"
          :dividerValue="100"
          @modal="(modal = !modal), (modalMode = 'readability')"
        />
        <statsCard
          :title="'Text difficulty'"
          :height="230"
          :options="chartOptionsDifficulty"
          :series="seriesDifficulty"
          :id="'stats1'"
          :dividerValue="1"
          @modal="(modal = !modal), (modalMode = 'difficulty')"
        />
      </div>
    </div>
    <rawData />
    <div
      class="w-full rounded-lg shadow-md p-4 mx-auto bg-white mt-2 col-span-8"
    >
      <annotations
        @sheet="sheetData"
        @closesheet="$refs.swipeableBottomSheet.setState('close')"
        @opensheet="
          (info = ''),
            (values = []),
            (keys = []),
            $refs.swipeableBottomSheet.setState('half')
        "
      />
    </div>
    <swipeable-bottom-sheet ref="swipeableBottomSheet" class="md:hidden">
      <h1 v-if="info == ''" class="px-4 text-center">
        Click on a token for further information
      </h1>
      <div v-else class="px-4">
        <div class="font-light text-lg text-center">
          Info on the token "
          <span class="font-bold">{{ info.word }}</span>
          "
        </div>
        <span v-for="(i, x) in keys" :key="x">
          <span class="font-bold">{{ i }}</span
          >: {{ values[x] }}<br
        /></span>
      </div>
    </swipeable-bottom-sheet>
  </div>
</template>

<script>
import annotations from "../components/annotations.vue";
import statsCard from "../components/statsCard.vue";
import infoCard from "../components/infoCard.vue";
import rawData from "../components/rawData.vue";
import modalInfo from "../components/modalInfo.vue";
import { SwipeableBottomSheet } from "vue-swipeable-bottom-sheet";
export default {
  name: "result",
  components: {
    annotations,
    statsCard,
    infoCard,
    rawData,
    modalInfo,
    SwipeableBottomSheet,
  },
  data() {
    return {
      info: "",
      values: [],
      keys: [],
      processedData: JSON.parse(localStorage.getItem("processedText")),
      modalMode: "",
      modal: false,
      show: false,
      isOpen: false,
      seriesReadability: [],
      seriesDifficulty: [],
      chartOptionsReadability: {
        chart: {
          height: 230,
          type: "radialBar",
        },
        stroke: {
          lineCap: "round",
        },
        plotOptions: {
          radialBar: {
            offsetY: 0,
            startAngle: -90,
            endAngle: 90,
            hollow: {
              size: "30%",
            },
            dataLabels: {
              name: {
                show: false,
              },
              value: {
                show: false,
              },
            },
          },
        },
        colors: [],
        labels: [],
        legend: {
          onItemClick: {
            toggleDataSeries: false,
          },
          show: false,
          floating: false,
          fontSize: "16px",
          position: "bottom",
          offsetX: 0,
          offsetY: 5,
          labels: {
            useSeriesColors: true,
          },
          markers: {
            size: 0,
          },
          formatter: function(seriesName, opts) {
            return (
              seriesName + ":  " + opts.w.globals.series[opts.seriesIndex] / 100
            );
          },
          itemMargin: {
            vertical: 2,
          },
        },
      },
      chartOptionsDifficulty: {
        chart: {
          height: 230,
          type: "radialBar",
        },
        stroke: {
          lineCap: "round",
        },
        plotOptions: {
          radialBar: {
            offsetY: 0,
            startAngle: -90,
            endAngle: 90,
            hollow: {
              size: "30%",
            },
            dataLabels: {
              name: {
                show: false,
              },
              value: {
                show: false,
              },
            },
          },
        },
        colors: [],
        labels: [],
        legend: {
          onItemClick: {
            toggleDataSeries: false,
          },
          show: false,
          floating: false,
          fontSize: "16px",
          position: "bottom",
          offsetX: 0,
          offsetY: 10,
          labels: {
            useSeriesColors: true,
          },
          markers: {
            size: 4,
          },
          formatter: function(seriesName, opts) {
            return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex];
          },
          itemMargin: {
            vertical: 2,
          },
        },
      },
    };
  },
  created() {
    if (localStorage.getItem("text") == "") {
      this.$router.replace({ name: "home" });
    } else {
      this.seriesReadability = [
        this.processedData.readability.subordinateRatio * 100,
        this.processedData.readability.ttrValue * 100,
        this.processedData.readability.density * 100,
      ];
      this.chartOptionsReadability.labels = [
        "Subordinate ratio",
        "Semantic richness",
        "Lexical density",
      ];
      this.seriesDifficulty = [
        this.processedData.readability.measures.main,
        this.processedData.readability.measures.level1,
        this.processedData.readability.measures.level2,
        this.processedData.readability.measures.level3,
      ];
      this.chartOptionsDifficulty.labels = [
        this.processedData.readability.labels.main,
        "Level 1",
        "Level 2",
        "Level 3",
      ];
      var tmpCol = "";
      var min = 0;
      var max = 0;
      for (let i = 0; i < this.chartOptionsReadability.labels.length; i++) {
        if (
          this.seriesReadability[i] / 100 ==
          this.processedData.readability.subordinateRatio
        ) {
          min = this.processedData.readability.minYellowValues.subordinateRatio;
          max = this.processedData.readability.maxYellowValues.subordinateRatio;
        } else if (
          this.seriesReadability[i] / 100 ==
          this.processedData.readability.ttrValue
        ) {
          min = this.processedData.readability.minYellowValues.ttrValue;
          max = this.processedData.readability.maxYellowValues.ttrValue;
        } else {
          min = this.processedData.readability.minYellowValues.density;
          max = this.processedData.readability.maxYellowValues.density;
        }
        if (
          this.seriesReadability[i] / 100 >= min &&
          this.seriesReadability[i] / 100 <= max
        ) {
          tmpCol = "#ffbb00";
        } else if (this.seriesReadability[i] / 100 < min) {
          tmpCol = "#15ff00";
        } else {
          tmpCol = "#ff0000";
        }
        this.chartOptionsReadability.colors.push(tmpCol);
      }
      for (let i = 0; i < this.chartOptionsDifficulty.labels.length; i++) {
        min = 40;
        max = 80;
        if (
          this.seriesDifficulty[i] >= min &&
          this.seriesDifficulty[i] <= max
        ) {
          tmpCol = "#ffbb00";
        } else if (this.seriesDifficulty[i] < min) {
          tmpCol = "#15ff00";
        } else {
          tmpCol = "#ff0000";
        }
        this.chartOptionsDifficulty.colors.push(tmpCol);
      }
      this.show = true;
    }
  },
  methods: {
    sheetData(arr) {
      this.info = arr[0];
      this.keys = arr[1];
      this.values = arr[2];
      this.$refs.swipeableBottomSheet.setState("half");
    },
  },
};
</script>

<style scoped>
@import url("../css/brat.css");
</style>
