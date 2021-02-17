<template>
    <transition name="fade-bg" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full dark:bg-black bg-gray-900 bg-opacity-25 customZ"
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
                                Edit Multitoken
                            </div>
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
                        <div class="py-2 flex flex-col">
                            <div>
                                multitoken start:
                                <div class="relative block w-max">
                                    <select
                                        class="w-full max-w-sm border border-primary appearance-none pr-4 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none cursor-not-allowed"
                                        name="start"
                                        id="start"
                                        v-model="start"
                                        disabled
                                    >
                                        <option class="font-medium">
                                            {{ start }}
                                        </option>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute pin-y pin-r flex items-center pl-2 text-gray-900"
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
                            </div>
                            <div>
                                multitoken end:
                                <div class="relative block w-max">
                                    <select
                                        class="w-full max-w-sm border border-primary appearance-none pr-4 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                                        name="end"
                                        id="end"
                                        v-model="newEnd"
                                    >
                                        <option
                                            v-for="(value, i) in tokens"
                                            :key="i"
                                            :value="i + '-' + value"
                                            class="font-medium"
                                        >
                                            {{ i + '-' + value }}
                                        </option>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute pin-y pin-r flex items-center pl-2 text-gray-900"
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
                            </div>
                            <div>
                                form:
                                <div class="relative block">
                                    <input
                                        v-model="newForm"
                                        name="form"
                                        id="form"
                                        type="text"
                                        class="px-1 border border-primary bg-gray-100 dark:bg-gray-700 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none w-full"
                                    />
                                </div>
                            </div>
                            <div>
                                misc:
                                <div class="relative block">
                                    <input
                                        v-model="newMisc"
                                        name="misc"
                                        id="misc"
                                        type="text"
                                        class="px-1 border border-primary bg-gray-100 dark:bg-gray-700 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none w-full"
                                    />
                                </div>
                            </div>
                            <!--
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
                            -->
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
                </div>
            </transition>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'mwModal',
    data() {
        return {
            start: '',
            end: '',
            newEnd: '',
            form: '',
            newForm: '',
            misc: '',
            newMisc: '',
            showDialog: false,
            tokens: {},
        }
    },
    props: {
        arrPos: Number,
        sentenceIndex: Number,
        arrLen: Number,
    },
    created() {
        let phrase = this.$store.state.editableData.sentences[this.sentenceIndex]
        this.form = phrase.tokens[this.arrPos].originalText
        this.newForm = phrase.tokens[this.arrPos].originalText
        this.start = phrase.tokens[this.arrPos].multiwordSpan.split('-', 1)[0] + '-' + phrase.tokens[this.arrPos].word
        this.end =
            phrase.tokens[this.arrPos].multiwordSpan.split('-')[1] +
            '-' +
            phrase.tokens[phrase.tokens[this.arrPos].multiwordSpan.split('-')[1] - 1].word
        this.newEnd = this.end
        if (phrase.tokens[this.arrPos].spaceAfter != undefined || phrase.tokens[this.arrPos].spaceAfter != null) {
            this.misc = 'spaceAfter:' + phrase.tokens[this.arrPos].spaceAfter
        }
        this.newMisc = this.misc
        //aggiungere misc
        for (let i = this.arrPos + 1; i < phrase.tokens.length; i++) {
            this.tokens[phrase.tokens[i].index] = phrase.tokens[i].word
        }
        setTimeout(() => {
            this.showDialog = true
        }, 1)
    },
    methods: {
        toggleModal() {
            this.showDialog = false
            this.$emit('close')
        },
        save() {
            let phrase = this.$store.state.editableData.sentences[this.sentenceIndex]
            if (this.newForm != this.form) {
                for (let i = this.arrPos; i < this.arrPos + this.arrLen; i++) {
                    phrase.tokens[i].originalText = this.newForm
                }
            }
            if (this.newEnd != this.end) {
                let startIndex = 0
                for (let i = 0; i < phrase.tokens.length; i++) {
                    if (phrase.tokens[i].index == parseInt(this.start.split('-')[0])) {
                        startIndex = i
                    }
                }
                for (let i = startIndex; i < parseInt(this.newEnd.split('-')[0]); i++) {
                    phrase.tokens[i].isMultiwordToken = true
                }
                phrase.tokens[this.arrPos].multiwordSpan =
                    parseInt(this.start.split('-')[0]) + '-' + parseInt(this.newEnd.split('-')[0])
            }
            if (this.newMisc != this.misc) {
                let miscArr = this.newMisc.split('|')
                let miscObj = { newProps: {} }
                for (let i = 0; i < miscArr.length; i++) {
                    if (miscArr[i].split(':')[0] == 'spaceAfter') {
                        phrase.tokens[this.arrPos].spaceAfter = miscArr[i].split(':')[1]
                    } else {
                        miscObj.newProps[miscArr[i].split(':')[0]] = miscArr[i].split(':')[1]
                    }
                }
                phrase.tokens[this.arrPos] = Object.assign(phrase.tokens[this.arrPos], miscObj)
            }
            //aggiungere misc
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
.pin-r {
    right: 0;
}
.pin-y {
    top: 0;
    bottom: 0;
}
</style>
