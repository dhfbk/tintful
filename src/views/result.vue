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
      </div>
    </div>
    <div class="lg:col-span-6 w-full">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
        <div
          class="rounded-lg shadow-md p-4 mx-auto bg-white w-full mt-2 lg:mt-0"
        >
          <h2 class="text-primary font-bold text-lg">Readability</h2>
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
          <h2 class="text-primary font-bold text-lg">Text difficulty</h2>
          <apexchart
            type="radialBar"
            height="250"
            :options="chartOptionsDifficulty"
            :series="seriesDifficulty"
          ></apexchart>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import json from "../assets/test.json";
import VueApexCharts from "vue-apexcharts";
export default {
  name: "result",
  components: {
    apexchart: VueApexCharts,
  },
  data() {
    return {
      myJson: json,
      seriesReadability: [],
      seriesDifficulty: [],
      chartOptionsReadability: {
        chart: {
          height: 300,
          type: "radialBar",
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
        colors: ["#1ab7ea", "#0084ff", "#39539E", "#0077B5"],
        labels: [],
        legend: {
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
          formatter: function (seriesName, opts) {
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
        colors: ["#1ab7ea", "#0084ff", "#39539E", "#0077B5"],
        labels: [],
        legend: {
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
          formatter: function (seriesName, opts) {
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
    console.log(this.roundNumber(this.myJson.readability.measures.main, 2));
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
</style>