<template>
    <transition name="fade-bg" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full min-h-screen dark:bg-black bg-gray-900 bg-opacity-25 customZ"
            @click="toggleModal"
        >
            <transition name="fade" appear>
                <div
                    class="bg-white dark:bg-bgDark rounded-lg w-5/6 max-w-3xl max-h-96 h-auto overflow-y-auto overscroll-none"
                    @click.stop
                    v-if="showDialog"
                >
                    <div class="p-4">
                        <div class="flex w-full">
                            <div class="text-primary dark:text-primaryLight font-bold text-lg">
                                Edit basic dependency name
                            </div>
                            <button
                                v-if="startingDep != 'ROOT'"
                                class="ripple ml-auto rounded hover:bg-gray-200 dark:hover:bg-gray-600 p-1 focus:outline-none"
                                @click="toggleModal()"
                            >
                                <svg
                                    class="m-auto fill-current text-gray-700 dark:text-gray-200 w-6 h-6 cursor-pointer"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 18 18"
                                >
                                    <path
                                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                                    />
                                </svg>
                                <span class="sr-only">Close dialog</span>
                            </button>
                        </div>
                        <div class="py-2">
                            <span>
                                Head: <span class="font-medium mr-4">{{ dep.governor }}</span> Dependent:
                                <span class="font-medium mr-4">{{ dep.dependent }}</span
                                ><br />
                                from: <span class="font-medium mr-4">{{ originalDep }}</span> to:</span
                            >
                            <select
                                class="appearance-none font-medium h-full border-b inline-block bg-white border-gray-400 text-gray-700 py-1 pl-2 pr-12 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
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
                        <div class="float-right pb-4">
                            <button
                                v-if="startingDep != 'ROOT'"
                                class="font-medium ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent text-red-400 py-2 px-4 rounded mr-2"
                                @click="toggleModal()"
                            >
                                CANCEL
                            </button>
                            <button
                                class="font-medium text-primary dark:text-primaryLight ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent py-2 px-4 rounded"
                                @click="save()"
                            >
                                SAVE
                            </button>
                        </div>
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
            if (this.startingDep != 'ROOT' || (this.startingDep == 'ROOT' && this.selectedDep != 'ROOT')) {
                this.showDialog = false
                this.$emit('closeDepsModal')
            }
        },
        edit() {
            this.dep.dep = this.selectedDep
        },
        save() {
            if (this.selectedDep != 'ROOT') {
                this.$emit('edited', this.dep)
                this.toggleModal()
            } else {
                this.$emit('snack', 'Choose a dependency')
            }
        },
    },
    props: {
        dep: Object,
    },
    created() {
        this.startingDep = this.dep.dep
        this.originalDep = this.dep.dep.slice(0)
        this.selectedDep = this.dep.dep
        //console.log(this.dep)
        setTimeout(() => {
            this.showDialog = true
        }, 1)
    },
    data() {
        return {
            showDialog: false,
            startingDep: '',
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

<style scoped>
.customZ {
    z-index: 990;
}
.dark .dark\:bg-black {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
