<template>
  <div v-if="show" class="grid grid-cols-1 lg:grid-cols-8 gap-x-4">
    <infoCard :jsonData="$store.state.processedData" />
    <div class="col-span-8 lg:col-span-6 w-full">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
        <statsCard
          :title="'Readability'"
          :height="300"
          :options="chartOptionsReadability"
          :series="seriesReadability"
        />
        <statsCard
          :title="'Text difficulty'"
          :height="300"
          :options="chartOptionsDifficulty"
          :series="seriesDifficulty"
        />
      </div>
    </div>
    <rawData />
    <div
      class="w-full rounded-lg shadow-md p-4 mx-auto bg-white mt-2 col-span-8"
    >
      <annotations />
    </div>
  </div>
</template>

<script>
import annotations from "../components/annotations.vue";
import statsCard from "../components/statsCard.vue";
import infoCard from "../components/infoCard.vue";
import rawData from "../components/rawData.vue";
export default {
  name: "result",
  components: {
    annotations,
    statsCard,
    infoCard,
    rawData,
  },
  data() {
    return {
      show: false,
      isOpen: false,
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
    if (this.$store.state.text == "") {
      this.$router.replace({ name: "home" });
    } else {
      this.seriesReadability = [
        this.$store.state.processedData.readability.subordinateRatio * 100,
        this.$store.state.processedData.readability.ttrValue * 100,
        this.$store.state.processedData.readability.density * 100,
      ];
      this.chartOptionsReadability.labels = [
        "Subordinate ratio",
        "Semantic richness",
        "Lexical density",
      ];
      this.seriesDifficulty = [
        this.roundNumber(
          this.$store.state.processedData.readability.measures.main,
          2
        ),
        this.roundNumber(
          this.$store.state.processedData.readability.measures.level1,
          2
        ),
        this.roundNumber(
          this.$store.state.processedData.readability.measures.level2,
          2
        ),
        this.roundNumber(
          this.$store.state.processedData.readability.measures.level3,
          2
        ),
      ];
      this.chartOptionsDifficulty.labels = [
        this.$store.state.processedData.readability.labels.main,
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
          this.$store.state.processedData.readability.subordinateRatio
        ) {
          min = this.$store.state.processedData.readability.minYellowValues
            .subordinateRatio;
          max = this.$store.state.processedData.readability.maxYellowValues
            .subordinateRatio;
        } else if (
          this.seriesReadability[i] / 100 ==
          this.$store.state.processedData.readability.ttrValue
        ) {
          min = this.$store.state.processedData.readability.minYellowValues
            .ttrValue;
          max = this.$store.state.processedData.readability.maxYellowValues
            .ttrValue;
        } else {
          min = this.$store.state.processedData.readability.minYellowValues
            .density;
          max = this.$store.state.processedData.readability.maxYellowValues
            .density;
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
