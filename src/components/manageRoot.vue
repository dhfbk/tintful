<template>
    <transition name="fade-bg" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full min-h-screen dark:bg-black bg-gray-900 bg-opacity-25 customZ"
            @click="toggleModal"
        >
            <transition name="fade" appear>
                <div
                    class="bg-white dark:bg-bgDark rounded-lg w-5/6 max-w-xl max-h-96 h-auto overflow-y-auto overscroll-none"
                    @click.stop
                    v-if="!wait"
                >
                    <div class="p-4">
                        <div class="flex w-full mb-2">
                            <div class="text-primary dark:text-primaryLight font-bold text-lg">Manage root tokens</div>
                            <button
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
                        <div v-if="current.length != 0">
                            <div
                                class="flex flex-col sm:flex-row items-center content-center mb-1 justify-between flex-wrap"
                                v-for="(root, i) in current"
                                :key="i"
                            >
                                <div
                                    v-if="!root.deleted"
                                    class="flex flex-col justify-center md:flex-row md:justify-between items-center content-center gap-1 flex-wrap w-full"
                                >
                                    <span
                                        class="flex flex-row items-center content-center gap-1 flex-wrap justify-center md:justify-start mx-auto md:mx-0"
                                    >
                                        <span class="flex flex-row items-center content-center gap-1 mt-1 sm:mt-0">
                                            <p>Root {{ i + 1 }}</p>
                                            <div
                                                class="relative"
                                                v-if="root.dependent == -1 || root.dependent == undefined"
                                            >
                                                <select
                                                    :name="'gloss' + root.dependent"
                                                    :id="'gloss' + root.dependent"
                                                    v-model="root.dependent"
                                                    @change="editData(i, 'root')"
                                                    class="mt-1 w-full inline-block border border-primary appearance-none pl-1 pr-4 py-1 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                                                >
                                                    <option
                                                        v-for="(r, c) in availableRoots"
                                                        :key="'root' + c"
                                                        :value="r.dependent"
                                                    >
                                                        {{ r.dependentGloss }}
                                                    </option>
                                                </select>
                                                <div
                                                    class="pointer-events-none absolute pin-y pin-r flex items-center p-1 text-gray-900"
                                                >
                                                    <svg
                                                        class="h-4 w-4 fill-current text-gray-900 dark:text-gray-200"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                                        />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div v-else>
                                                <input
                                                    type="text"
                                                    class="w-full cursor-not-allowed px-2 py-1 border border-primary bg-gray-100 dark:bg-gray-700 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                                                    :value="root.dependentGloss"
                                                    disabled
                                                />
                                            </div>
                                        </span>
                                    </span>
                                    <span class="flex flex-row ml-2 mt-1 sm:mt-0" v-if="!invalid && !invalidCheck">
                                        <button
                                            v-if="current.length != 1"
                                            class="p-2 ripple rounded-full focus:outline-none text-red-500 transition duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600"
                                            @click="checkBeforeRemove(i)"
                                        >
                                            <svg
                                                style="width: 24px; height: 24px"
                                                viewBox="0 0 24 24"
                                                class="fill-current"
                                            >
                                                <path
                                                    fill="currentColor"
                                                    d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"
                                                />
                                            </svg>
                                            <span class="sr-only">Remove root</span>
                                        </button>
                                        <button
                                            v-if="i == current.length - 1"
                                            class="p-2 ripple rounded-full transition text-primary dark:text-primaryLight duration-100 ease-out focus:outline-none hover:bg-gray-200 dark:hover:bg-gray-600"
                                            @click="newElement()"
                                        >
                                            <svg
                                                style="width: 24px; height: 24px"
                                                viewBox="0 0 24 24"
                                                class="fill-current"
                                            >
                                                <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                                            </svg>
                                            <span class="sr-only">Add root</span>
                                        </button>
                                    </span>
                                </div>
                                <div
                                    v-if="root.deletedCheck && !root.deleted"
                                    class="flex flex-row items-center content-center my-2 w-full"
                                >
                                    <div
                                        class="flex flex-col justify-center md:flex-row md:justify-between items-center content-center w-full"
                                    >
                                        <span
                                            class="flex flex-col items-center content-center gap-1 flex-wrap justify-start mx-auto md:mx-0"
                                        >
                                            <span class="flex flex-row items-center content-center gap-1 mt-1 sm:mt-0">
                                                <p>New governor</p>
                                                <div class="relative">
                                                    <select
                                                        :name="'governor' + root.newData.governor"
                                                        :id="'governor' + root.newData.governor"
                                                        v-model="root.newData.governor"
                                                        @change="editData(i, 'gov')"
                                                        class="mt-1 w-full inline-block border border-primary appearance-none pl-1 pr-4 py-1 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                                                    >
                                                        <option
                                                            v-for="(g, c) in governors"
                                                            :key="'gov' + c"
                                                            :value="g"
                                                            :disabled="parseInt(g.split('-')[0]) == root.dependent"
                                                        >
                                                            {{ g }}
                                                        </option>
                                                    </select>
                                                    <div
                                                        class="pointer-events-none absolute pin-y pin-r flex items-center p-1 text-gray-900"
                                                    >
                                                        <svg
                                                            class="h-4 w-4 fill-current text-gray-900 dark:text-gray-200"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20"
                                                        >
                                                            <path
                                                                d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                                            />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </span>
                                            <span
                                                class="flex flex-row items-center content-center gap-1 mt-1 sm:mt-0 -ml-3"
                                            >
                                                <p>Dependency</p>
                                                <div class="relative">
                                                    <select
                                                        :name="'governor' + root.newData.dep"
                                                        :id="'governor' + root.newData.dep"
                                                        v-model="root.newData.dep"
                                                        @change="editData(i, 'dep')"
                                                        class="mt-1 w-full inline-block border border-primary appearance-none pl-1 pr-4 py-1 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                                                    >
                                                        <option
                                                            v-for="(r, c) in dependencies"
                                                            :key="'dep' + c"
                                                            :value="r"
                                                        >
                                                            {{ r }}
                                                        </option>
                                                    </select>
                                                    <div
                                                        class="pointer-events-none absolute pin-y pin-r flex items-center p-1 text-gray-900"
                                                    >
                                                        <svg
                                                            class="h-4 w-4 fill-current text-gray-900 dark:text-gray-200"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20"
                                                        >
                                                            <path
                                                                d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                                            />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </span>
                                        </span>
                                        <span class="flex flex-row mt-1 sm:mt-0">
                                            <button
                                                v-if="current.length != 1"
                                                :class="invalid ? 'cursor-not-allowed' : ''"
                                                class="p-2 ripple rounded-full focus:outline-none text-red-500 transition duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600"
                                                @click="removeElement(i)"
                                            >
                                                <svg
                                                    style="width: 24px; height: 24px"
                                                    viewBox="0 0 24 24"
                                                    class="fill-current"
                                                >
                                                    <path
                                                        d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M12 20C7.59 20 4 16.41 4 12S7.59 4 12 4 20 7.59 20 12 16.41 20 12 20M16.59 7.58L10 14.17L7.41 11.59L6 13L10 17L18 9L16.59 7.58Z"
                                                    />
                                                </svg>
                                                <span class="sr-only">Confirm removal</span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="float-right pb-4">
                                <button
                                    class="font-medium ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent text-red-400 py-2 px-4 rounded mr-2"
                                    @click="toggleModal()"
                                >
                                    CANCEL
                                </button>
                                <button
                                    :class="invalid || invalidCheck ? 'cursor-not-allowed' : ''"
                                    class="font-medium text-primary dark:text-primaryLight ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent py-2 px-4 rounded"
                                    @click="save()"
                                >
                                    SAVE
                                </button>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-center italic">No root token found</p>
                            <div class="flex items-center justify-center content-center">
                                <button
                                    class="p-2 ripple rounded-full transition text-primary dark:text-primaryLight duration-100 ease-out focus:outline-none hover:bg-gray-200 dark:hover:bg-gray-600"
                                    @click="newElement()"
                                >
                                    <svg style="width: 24px; height: 24px" viewBox="0 0 24 24" class="fill-current">
                                        <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                                    </svg>
                                    <span class="sr-only">Add root</span>
                                </button>
                            </div>
                            <div class="float-right pb-4">
                                <button
                                    class="font-medium ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent text-red-400 py-2 px-4 rounded mr-2"
                                    @click="toggleModal()"
                                >
                                    CANCEL
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'manageRoot',
    props: { sentenceIndex: Number },
    data() {
        return {
            current: [],
            availableRoots: [],
            governors: [],
            governorsGloss: {},
            dependencies: [
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
            wait: true,
            invalid: false,
            invalidCheck: false,
        }
    },
    created() {
        var phrase = this.$store.state.editableData.sentences[this.sentenceIndex]
        for (let i = 0; i < phrase['basic-dependencies'].length; i++) {
            if (phrase['basic-dependencies'][i].dep == 'ROOT') {
                this.current.push(phrase['basic-dependencies'][i])
                this.current[this.current.length - 1].deleted = false
                this.current[this.current.length - 1].deletedCheck = false
                this.current[this.current.length - 1].newData = {
                    dep: '',
                    dependent: this.current[this.current.length - 1].dependent,
                    dependentGloss: this.current[this.current.length - 1].dependentGloss,
                    governor: '',
                    governorGloss: '',
                }
            } else {
                this.availableRoots.push(phrase['basic-dependencies'][i])
                this.governorsGloss[phrase['basic-dependencies'][i].governor] =
                    phrase['basic-dependencies'][i].governorGloss
            }
        }
        for (let i = 0; i < phrase.tokens.length; i++) {
            this.governors.push(phrase.tokens[i].index + '-' + phrase.tokens[i].word)
        }
        this.wait = false
    },
    methods: {
        toggleModal() {
            this.$emit('close')
            this.wait = true
        },
        newElement() {
            this.invalid = true
            this.current.push({
                dep: '',
                dependent: -1,
                dependentGloss: '',
                governor: -1,
                governorGloss: '',
                deleted: false,
                deletedCheck: false,
                newData: { dep: '', dependent: -1, dependentGloss: '', governor: '', governorGloss: '' },
            })
        },
        removeElement(arrInd) {
            if (!this.invalid) {
                var phrase = this.$store.state.editableData.sentences[this.sentenceIndex]
                this.current[arrInd].deleted = true
                for (let i = 0; i < phrase['basic-dependencies'].length; i++) {
                    for (let x = 0; x < this.current.length; x++) {
                        if (!this.current[x].deleted) {
                            if (this.current[x].dependent != phrase['basic-dependencies'][i].dependent) {
                                this.availableRoots.push(phrase['basic-dependencies'][i])
                            } else {
                                break
                            }
                        }
                    }
                }
                this.invalidCheck = false
                this.invalid = false
                this.wait = true
                this.wait = false
            }
        },
        checkBeforeRemove(arrInd) {
            this.invalid = true
            this.current[arrInd].deletedCheck = true
            this.wait = true
            this.wait = false
            this.invalidCheck = true
        },
        editData(index, mode) {
            if (mode == 'root') {
                let dependentGloss = ''
                for (let i = 0; i < this.availableRoots.length; i++) {
                    if (this.availableRoots[i].dependent == this.current[index].dependent) {
                        dependentGloss = this.availableRoots[i].dependentGloss
                        break
                    }
                }
                this.current[index] = {
                    dep: 'ROOT',
                    dependent: this.current[index].dependent,
                    dependentGloss: dependentGloss,
                    governor: 0,
                    governorGloss: 'ROOT',
                    deleted: false,
                    deletedCheck: false,
                    newData: {
                        dep: '',
                        dependent: this.current[index].dependent,
                        dependentGloss: dependentGloss,
                        governor: '',
                        governorGloss: '',
                    },
                }
                this.invalid = false
            } else {
                if (this.current[index].newData.dep != '' && this.current[index].newData.governor != '') {
                    this.invalid = false
                }
            }
        },
        save() {
            if (!this.invalid && !this.invalidCheck) {
                var phrase = this.$store.state.editableData.sentences[this.sentenceIndex]
                var phraseMt = this.$store.state.tableData.sentences[this.sentenceIndex]
                //assign governor glosses
                for (let x = 0; x < this.current.length; x++) {
                    this.current[x].newData.governor = parseInt(this.current[x].newData.governor.split('-')[0])
                    this.current[x].newData.governorGloss = this.governorsGloss[this.current[x].newData.governor]
                }
                //assign new dependencies in basic phrase
                for (let i = 0; i < phrase['basic-dependencies'].length; i++) {
                    for (let x = 0; x < this.current.length; x++) {
                        if (this.current[x].deleted) {
                            if (phrase['basic-dependencies'][i].dependent == this.current[x].dependent) {
                                phrase['basic-dependencies'][i] = this.current[x].newData
                                break
                            }
                        }
                    }
                }
                //assign new dependencies in multitoken phrase
                for (let i = 0; i < phraseMt['basic-dependencies'].length; i++) {
                    for (let x = 0; x < this.current.length; x++) {
                        if (this.current[x].deleted) {
                            if (phraseMt['basic-dependencies'][i].dependent == this.current[x].dependent) {
                                phraseMt['basic-dependencies'][i] = this.current[x].newData
                                this.current.splice(x, 1)
                                break
                            }
                        }
                    }
                }
                //edit basic phrase
                for (let i = 0; i < phrase['basic-dependencies'].length; i++) {
                    if (phrase['basic-dependencies'][i].dep == 'ROOT') {
                        phrase['basic-dependencies'].splice(i, 1)
                        i--
                    } else {
                        for (let x = 0; x < this.current.length; x++) {
                            if (!this.current[x].deleted) {
                                if (phrase['basic-dependencies'][i].dependent == this.current[x].dependent) {
                                    phrase['basic-dependencies'].splice(i, 1)
                                    i--
                                    break
                                }
                            }
                        }
                    }
                }
                //edit multitoken phrase
                for (let i = 0; i < phraseMt['basic-dependencies'].length; i++) {
                    if (phraseMt['basic-dependencies'][i].dep == 'ROOT') {
                        phraseMt['basic-dependencies'].splice(i, 1)
                        i--
                    } else {
                        for (let x = 0; x < this.current.length; x++) {
                            if (!this.current[x].deleted) {
                                if (phraseMt['basic-dependencies'][i].dependent == this.current[x].dependent) {
                                    phraseMt['basic-dependencies'].splice(i, 1)
                                    i--
                                    break
                                }
                            }
                        }
                    }
                }
                //remove properties, we don't need them anymore
                for (let x = 0; x < this.current.length; x++) {
                    delete this.current[x].deleted
                    delete this.current[x].deletedCheck
                    delete this.current[x].newData
                }
                //assign the remaining roots to both objects
                phrase['basic-dependencies'].splice(0, 0, ...this.current)
                phraseMt['basic-dependencies'].splice(0, 0, ...this.current)
                this.$emit('edited')
                this.toggleModal()
            }
        },
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
