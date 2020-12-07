<template>
  <div class="grid grid-cols-1 lg:grid-cols-8 gap-x-4">
    <div class="rounded-lg shadow-md p-4 mx-auto bg-white lg:col-span-2 w-full">
      <h2 class="text-primary font-bold text-lg">General information</h2>
      <div class="flex flex-col">
        <div class="flex flex-col">
          <div
            class="flex flex-row justify-between content-center items-center w-full"
          >
            <p>Sentences</p>
            <span class="px-2 py-1 bg-primary text-white rounded-full">{{
              myJson.sentences.length
            }}</span>
          </div>
          <div
            class="flex flex-row justify-between content-center items-center w-full mt-2"
          >
            <p>Tokens</p>
            <span class="px-2 py-1 bg-primary text-white rounded-full">{{
              myJson.readability.tokenCount
            }}</span>
          </div>
          <div
            class="flex flex-row justify-between content-center items-center w-full mt-2"
          >
            <p>Words</p>
            <span class="px-2 py-1 bg-primary text-white rounded-full">{{
              myJson.readability.wordCount
            }}</span>
          </div>
          <div
            class="flex flex-row justify-between content-center items-center w-full mt-2"
          >
            <p>Content words</p>
            <span class="px-2 py-1 bg-primary text-white rounded-full">{{
              myJson.readability.contentWordSize
            }}</span>
          </div>
          <transition name="slide-toggle">
            <div
              v-if="isOpen"
              class="content w-full flex flex-wrap justify-center relative"
            >
              <div
                class="flex flex-row justify-between content-center items-center w-full mt-2"
              >
                <p>Text length (letters only)</p>
                <span class="px-2 py-1 bg-primary text-white rounded-full">{{
                  myJson.readability.docLenLettersOnly
                }}</span>
              </div>
              <div
                class="flex flex-row justify-between content-center items-center w-full mt-2"
              >
                <p>Text length (all characters)</p>
                <span class="px-2 py-1 bg-primary text-white rounded-full">{{
                  myJson.readability.docLenWithSpaces
                }}</span>
              </div>
              <div
                class="flex flex-row justify-between content-center items-center w-full mt-2"
              >
                <p>Text length (no spaces)</p>
                <span class="px-2 py-1 bg-primary text-white rounded-full">{{
                  myJson.readability.docLenWithoutSpaces
                }}</span>
              </div>
            </div>
          </transition>
          <div class="grid grid-cols-8 items-center mt-2">
            <p class="font-bold text-right col-span-7">
              {{ isOpen ? "Hide" : "Show more" }}
            </p>
            <button
              class="col-span-1 bg-white ripple-light py-1 px-1 rounded focus:outline-none w-max justify-self-end hover:bg-gray-300 transition duration-100 ease-out"
              @click.stop="isOpen = !isOpen"
            >
              <svg
                class="transition duration-300 ease-out"
                :class="{ 'transform  rotate-180': isOpen }"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                width="24px"
                height="24px"
                fill="black"
              >
                <path d="M0 0h24v24H0V0z" fill="none" />
                <path
                  d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="lg:col-span-6 w-full">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
        <div
          class="rounded-lg shadow-md p-4 mx-auto bg-white w-full mt-2 lg:mt-0"
        >
          <div class="flex justify-between content-center items-center">
            <h2 class="text-primary font-bold text-lg">Readability</h2>
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
            height="250"
            :options="chartOptionsReadability"
            :series="seriesReadability"
          ></apexchart>
        </div>
        <div
          class="rounded-lg shadow-md p-4 mx-auto bg-white w-full mt-2 lg:mt-0"
        >
          <div class="flex justify-between content-center items-center">
            <h2 class="text-primary font-bold text-lg">Text difficulty</h2>
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
            height="250"
            :options="chartOptionsDifficulty"
            :series="seriesDifficulty"
          ></apexchart>
        </div>
      </div>
    </div>
    <div
      class="w-full rounded-lg shadow-md p-4 mx-auto bg-white mt-2 col-span-8"
    >
      <annotations />
    </div>
  </div>
</template>

<script>
import json from "../assets/test.json";
import VueApexCharts from "vue-apexcharts";
import annotations from "../components/annotations";
export default {
  name: "result",
  components: {
    apexchart: VueApexCharts,
    annotations,
  },
  data() {
    return {
      isOpen: false,
      myJson: json,
      seriesReadability: [],
      seriesDifficulty: [],
      chartOptionsReadability: {
        chart: {
          height: 300,
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
              margin: 0,
              size: "40%",
              background: "transparent",
              image: undefined,
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
          show: true,
          floating: true,
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
          height: 300,
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
              margin: 0,
              size: "40%",
              background: "transparent",
              image: undefined,
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
          show: true,
          floating: true,
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
    this.seriesReadability = [
      this.myJson.readability.subordinateRatio * 100,
      this.myJson.readability.ttrValue * 100,
      this.myJson.readability.density * 100,
    ];
    this.chartOptionsReadability.labels = [
      "Subordinate ratio",
      "Semantic richness",
      "Lexical density",
    ];
    this.seriesDifficulty = [
      this.roundNumber(this.myJson.readability.measures.main, 2),
      this.roundNumber(this.myJson.readability.measures.level1, 2),
      this.roundNumber(this.myJson.readability.measures.level2, 2),
      this.roundNumber(this.myJson.readability.measures.level3, 2),
    ];
    this.chartOptionsDifficulty.labels = [
      this.myJson.readability.labels.main,
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
        this.myJson.readability.subordinateRatio
      ) {
        min = this.myJson.readability.minYellowValues.subordinateRatio;
        max = this.myJson.readability.maxYellowValues.subordinateRatio;
      } else if (
        this.seriesReadability[i] / 100 ==
        this.myJson.readability.ttrValue
      ) {
        min = this.myJson.readability.minYellowValues.ttrValue;
        max = this.myJson.readability.maxYellowValues.ttrValue;
      } else {
        min = this.myJson.readability.minYellowValues.density;
        max = this.myJson.readability.maxYellowValues.density;
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
      if (this.seriesDifficulty[i] >= min && this.seriesDifficulty[i] <= max) {
        tmpCol = "#ffbb00";
      } else if (this.seriesDifficulty[i] < min) {
        tmpCol = "#15ff00";
      } else {
        tmpCol = "#ff0000";
      }
      this.chartOptionsDifficulty.colors.push(tmpCol);
    }
  },
  methods: {
    roundNumber(num, scale) {
      if (!("" + num).includes("e")) {
        return +(Math.round(num + "e+" + scale) + "e-" + scale);
      } else {
        var arr = ("" + num).split("e");
        var sig = "";
        if (+arr[1] + scale > 0) {
          sig = "+";
        }
        return +(
          Math.round(+arr[0] + "e" + sig + (+arr[1] + scale)) +
          "e-" +
          scale
        );
      }
    },
  },
};
</script>

<style scoped>
.slide-toggle-enter-active {
  transition: max-height 0.25s !important;
}

.slide-toggle-leave-active {
  transition: max-height 0.2s !important;
}

.slide-toggle-enter-active {
  max-height: 200px;
  overflow: hidden;
}

.slide-toggle-enter,
.slide-toggle-leave-active {
  max-height: 0;
  overflow: hidden;
}

.slide-toggle-leave {
  max-height: 200px;
  overflow: hidden;
}
</style>
