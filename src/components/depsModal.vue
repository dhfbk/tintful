<template>
  <transition name="fade" mode="out-in" appear>
    <div
      class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-25 z-50"
    >
      <div
        class=" bg-white rounded w-5/6 max-w-3xl max-h-80 overflow-y-auto focus:outline-none"
      >
        <select
          class="appearance-none h-full rounded border block appearance-none bg-white border-gray-400 text-gray-700 py-1 pl-2 pr-12 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
          name="depsSelect"
          id="depsSelect"
          @change="edit()"
          v-model="selectedDep"
        >
          <option v-for="(dep, i) in depsList" :key="i" :value="dep">
            {{ dep }}
          </option>
          <!-- <option value="dots">Dot Matrix</option>
          <option value="table">Table</option> -->
        </select>
        <button
          class="ripple transition duration-100 ease-out hover:bg-gray-200 focus:outline-none mt-2 xs2:mt-0 xs2:ml-2 bg-transparent text-gray-800 py-2 px-4 rounded"
          @click="save()"
        >
          Save changes
        </button>
        <button
          class="ripple transition duration-100 ease-out hover:bg-gray-200 focus:outline-none mt-2 xs2:mt-0 xs2:ml-2 bg-transparent text-gray-800 py-2 px-4 rounded"
          @click="toggleModal()"
        >
          Cancel
        </button>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: "depsModal",
  methods: {
    toggleModal() {
      this.$emit("closeDepsModal");
    },
    edit() {
      this.dep.dep = this.selectedDep;
    },
    save() {
      this.$emit("edited", this.dep);
      this.toggleModal();
    },
  },
  props: {
    dep: Object,
  },
  created() {
    this.selectedDep = this.dep.dep;
    console.log(this.dep);
  },
  data() {
    return {
      selectedDep: "",
      depsList: [
        "acl",
        "acl:relcl",
        "advcl",
        "advmod",
        "amod",
        "appos",
        "aux",
        "aux:pass",
        "case",
        "cc",
        "ccomp",
        "compound",
        "conj",
        "cop",
        "csubj",
        "csubj:pass",
        "dep",
        "det",
        "det:poss",
        "det:predet",
        "discourse",
        "dislocated",
        "expl",
        "expl:impers",
        "expl:pass",
        "fixed",
        "flat",
        "flat:foreign",
        "flat:name",
        "goeswith",
        "iobj",
        "mark",
        "nmod",
        "nsubj",
        "nsubj:pass",
        "nummod",
        "obj",
        "obl",
        "obl:agent",
        "orphan",
        "parataxis",
        "punct",
        "root",
        "vocative",
        "xcomp",
      ],
    };
  },
};
</script>

<style></style>
