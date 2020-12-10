<template>
  <div class="overflow-x-auto depsContainer"></div>
</template>
 
<script>
import { Annotator } from "poplar-annotation";
export default {
  name: "basicDependencies",
  data() {
    return {
      obj: {
        content: "",
        labelCategories: [],
        labels: [],
        connectionCategories: [],
        connections: [],
      },
    };
  },
  mounted() {
    this.obj.content = this.$store.state.processedData.sentences[0].text;
    var tmp = {};
    var idConnCat = 0;
    var idLabelCat = 0;
    var lbCatId = 0;
    var connCatId = 0;
    this.$store.state.processedData.sentences[0]["basic-dependencies"].forEach(
      (el) => {
        if (!this.check("connCat", el.dep)) {
          tmp = {
            id: idConnCat,
            text: el.dep,
          };
          this.obj.connectionCategories.push(tmp);
          idConnCat++;
        }
      }
    );
    this.$store.state.processedData.sentences[0].tokens.forEach((token) => {
      if (!this.check("labelCat", token.pos)) {
        tmp = {
          id: idLabelCat,
          text: token.pos,
          color: "red",
          borderColor: "blue",
        };
        this.obj.labelCategories.push(tmp);
        idLabelCat++;
      }
    });
    for (
      var i = 0;
      i <
      this.$store.state.processedData.sentences[0]["basic-dependencies"].length;
      i++
    ) {
      this.$store.state.processedData.sentences[0].tokens.forEach((el) => {
        if (
          el.index ==
          this.$store.state.processedData.sentences[0]["basic-dependencies"][i]
            .dependent
        ) {
          this.obj.labelCategories.forEach((label) => {
            if (label.text == el.pos) {
              lbCatId = label.id;
            }
          });
        }
      });
      tmp = {
        id:
          this.$store.state.processedData.sentences[0]["basic-dependencies"][i]
            .dependent - 1,
        categoryId: lbCatId,
        startIndex:
          this.$store.state.processedData.sentences[0]["basic-dependencies"][i]
            .dependent - 1,
        endIndex: this.$store.state.processedData.sentences[0][
          "basic-dependencies"
        ][i].dependent,
      };
      this.obj.labels.push(tmp);
    }
    var cont = 0;
    this.$store.state.processedData.sentences[0]["basic-dependencies"].forEach(
      (el) => {
        cont = 0;
        while (cont < this.obj.connectionCategories.length) {
          if (this.obj.connectionCategories[cont].text == el.dep) {
            connCatId = this.obj.connectionCategories[cont].id;
            console.log(connCatId);
            cont = this.obj.connectionCategories.length;
          }
          cont++;
        }
        tmp = {
          id: el.dependent - 1,
          categoryId: connCatId,
          fromId: el.dependent - 1,
          toId: el.governor,
        };
        this.obj.connections.push(tmp);
        connCatId++;
      }
    );
    setTimeout(() => {
      const annotator = new Annotator(
        this.obj,
        document.getElementsByClassName("depsContainer")[0]
      );
      console.log(annotator);
    }, 1000);
  },
  methods: {
    /*
    getCategoryId(position) {
      var toRtn = 0;
      this.$store.state.processedData.sentences[0].tokens.forEach((el) => {
        if (el.index == position) {
          this.obj.labelCategories.forEach((label) => {
            if (label.text == el.pos) {
              toRtn = label.id;
              return toRtn;
            }
          });
        }
      });
    },
    */
    check(mode, text) {
      var toRtn = false;
      if (mode == "connCat") {
        this.obj.connectionCategories.forEach((element) => {
          if (element.text == text) toRtn = true;
        });
      } else if (mode == "labelCat") {
        this.obj.labelCategories.forEach((element) => {
          if (element.text == text) toRtn = true;
        });
      }
      return toRtn;
    },
  },
};
</script>
 
<style>
#depsContainer > svg {
  width: 500px;
}
/* content */
.poplar-annotation-content {
  font-family: "Roboto Mono", monospace !important;
  font-size: 16px;
}
/* Label */
.poplar-annotation-label {
  font-family: "Roboto Mono", monospace !important;
  font-size: 14px;
}
/* Connection */
.poplar-annotation-connection {
  font-family: "Roboto Mono", monospace !important;
  font-size: 12px;
}
/* Lines color */
#depsContainer .poplar-annotation-connection-line {
  stroke: "#d02838";
}
.poplar-annotation-connection-line.hover-from {
  stroke: "#d02838";
}
.poplar-annotation-connection-line.hover-to {
  stroke: "#d02838";
}
.poplar-annotation-connection-line.hover {
  stroke: "#d02838";
}
</style>