<template>
    <transition name="fade-bg" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-30 z-40 transition-opacity"
        >
            <transition name="fade" appear>
                <div
                    class=" bg-white rounded w-2/3 max-w-3xl max-h-80 overflow-y-auto focus:outline-none p-4 shadow-lg z-50"
                    v-if="showDialog"
                >
                    <div>
                        <div class="text-lg mb-2 font-medium">Edit basic dependency name</div>
                        <span class="">
                            Head: <span class="font-medium mr-4">{{ dep.governor }}</span> Dependent:
                            <span class="font-medium mr-4">{{ dep.dependent }}</span
                            ><br />
                            from: <span class="font-medium mr-4">{{ originalDep }}</span> to:</span
                        >
                        <select
                            class="appearance-none font-medium h-full border-b  inline-block appearance-none bg-white border-gray-400 text-gray-700 py-1 pl-2 pr-12 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            name="depsSelect"
                            id="depsSelect"
                            @change="edit()"
                            v-model="selectedDep"
                        >
                            <option v-for="(dep, i) in depsList" :key="i" :value="dep" class="font-medium">
                                {{ dep }}
                            </option>
                        </select>
                    </div>
                    <div class="float-right">
                        <button
                            class="font-medium ripple transition duration-100 ease-out hover:bg-gray-200 focus:outline-none mt-2 xs2:mt-0 xs2:ml-2 bg-transparent text-text-primary  py-2 px-4 rounded mr-2"
                            @click="toggleModal()"
                        >
                            CANCEL
                        </button>
                        <button
                            class="font-medium text-blue-700 ripple transition duration-100 ease-out hover:bg-gray-200 focus:outline-none mt-2 xs2:mt-0 xs2:ml-2 bg-transparent py-2 px-4 rounded"
                            @click="save()"
                        >
                            SAVE
                        </button>
                    </div>
                </div>
            </transition>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'depsModal',
    methods: {
        toggleModal() {
            this.showDialog = false
            this.$emit('closeDepsModal')
        },
        edit() {
            this.dep.dep = this.selectedDep
        },
        save() {
            this.$emit('edited', this.dep)
            this.toggleModal()
        },
    },
    props: {
        dep: Object,
    },
    created() {
        this.originalDep = this.dep.dep.slice(0)
        this.selectedDep = this.dep.dep
        console.log(this.dep)
        setTimeout(() => {
            this.showDialog = true
        }, 1)
    },
    data() {
        return {
            showDialog: false,
            selectedDep: '',
            depsList: [
                'acl',
                'acl:relcl',
                'advcl',
                'advmod',
                'amod',
                'appos',
                'aux',
                'aux:pass',
                'case',
                'cc',
                'ccomp',
                'compound',
                'conj',
                'cop',
                'csubj',
                'csubj:pass',
                'dep',
                'det',
                'det:poss',
                'det:predet',
                'discourse',
                'dislocated',
                'expl',
                'expl:impers',
                'expl:pass',
                'fixed',
                'flat',
                'flat:foreign',
                'flat:name',
                'goeswith',
                'iobj',
                'mark',
                'nmod',
                'nsubj',
                'nsubj:pass',
                'nummod',
                'obj',
                'obl',
                'obl:agent',
                'orphan',
                'parataxis',
                'punct',
                'root',
                'vocative',
                'xcomp',
            ],
        }
    },
}
</script>

<style></style>
