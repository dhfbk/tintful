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
                                class="flex flex-col sm:flex-row items-center content-center mb-1 justify-between"
                                v-for="(root, i) in current"
                                :key="i"
                            >
                                <div
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
                                                    @change="editData(i)"
                                                    class="w-full inline-block border border-primary appearance-none pl-1 pr-4 py-1 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                                                >
                                                    <option
                                                        v-for="(r, i) in availableRoots"
                                                        :key="'root' + i"
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
                                    <span class="flex flex-row ml-2 mt-1 sm:mt-0" v-if="!invalid">
                                        <button
                                            v-if="current.length != 1"
                                            class="p-2 ripple rounded-full focus:outline-none text-red-500 transition duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600"
                                            @click="removeElement(i)"
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
                            </div>
                            <div class="float-right pb-4">
                                <button
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
            wait: true,
            invalid: false,
        }
    },
    created() {
        var phrase = this.$store.state.editableData.sentences[this.sentenceIndex]
        for (let i = 0; i < phrase['basic-dependencies'].length; i++) {
            if (phrase['basic-dependencies'][i].dep == 'ROOT') {
                this.current.push(phrase['basic-dependencies'][i])
            } else {
                this.availableRoots.push(phrase['basic-dependencies'][i])
            }
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
            this.current.push({ dep: '', dependent: -1, dependentGloss: '', governor: -1, governorGloss: '' })
        },
        removeElement(arrInd) {
            var phrase = this.$store.state.editableData.sentences[this.sentenceIndex]
            this.current.splice(arrInd, 1)
            for (let i = 0; i < phrase['basic-dependencies'].length; i++) {
                for (let x = 0; x < this.current.length; x++) {
                    if (this.current[x].dependent != phrase['basic-dependencies'][i].dependent) {
                        this.availableRoots.push(phrase['basic-dependencies'][i])
                    } else {
                        break
                    }
                }
            }
        },
        editData(index) {
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
            }
            this.invalid = false
        },
        save() {
            var phrase = this.$store.state.editableData.sentences[this.sentenceIndex]
            for (let i = 0; i < phrase['basic-dependencies'].length; i++) {
                if (phrase['basic-dependencies'][i].dep == 'ROOT') {
                    phrase['basic-dependencies'].splice(i, 1)
                    i--
                } else {
                    for (let x = 0; x < this.current.length; x++) {
                        if (phrase['basic-dependencies'][i].dependent == this.current[x].dependent) {
                            phrase['basic-dependencies'].splice(i, 1)
                            i--
                            break
                        }
                    }
                }
            }
            phrase['basic-dependencies'].splice(0, 0, ...this.current)
            this.$emit('edited')
            this.toggleModal()
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
