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
                            <div class="text-primary dark:text-primaryLight font-bold text-lg">
                                Manage multiword tokens
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
                        <div v-if="current.length != 0">
                            <div
                                class="flex flex-col sm:flex-row items-center content-center mb-1 justify-between"
                                v-for="(mw, i) in current"
                                :key="i"
                            >
                                <div
                                    class="flex flex-col justify-center md:flex-row md:justify-between items-center content-center gap-1 flex-wrap w-full"
                                >
                                    <span
                                        class="flex flex-row items-center content-center gap-1 flex-wrap justify-center md:justify-start mx-auto md:mx-0"
                                    >
                                        <span class="flex flex-row items-center content-center gap-1 mt-1 sm:mt-0">
                                            <p>Start</p>
                                            <div class="relative" v-if="mw.start == '' || mw.start == undefined">
                                                <select
                                                    :name="'start' + mw.start"
                                                    :id="'start' + mw.start"
                                                    v-model="mw.start"
                                                    @change="editData('start', mw.start)"
                                                    class="w-max inline-block border border-primary appearance-none pl-2 py-1 pr-4 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                                                >
                                                    <option
                                                        v-for="(s, i) in availableStarts"
                                                        :key="'start' + i"
                                                        :value="s"
                                                    >
                                                        {{ s }}
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
                                                    class="w-28 cursor-not-allowed px-2 py-1 border border-primary bg-gray-100 dark:bg-gray-700 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                                                    v-model="mw.start"
                                                    disabled
                                                />
                                            </div>
                                        </span>
                                        <span class="flex flex-row items-center content-center gap-1 mt-1 sm:mt-0">
                                            <p>End</p>
                                            <div class="relative">
                                                <select
                                                    :name="'end' + mw.end"
                                                    :id="'end' + mw.end"
                                                    v-model="mw.end"
                                                    @change="editData('end')"
                                                    class="w-max inline-block border border-primary appearance-none pl-2 py-1 pr-4 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                                                >
                                                    <option
                                                        v-for="(e, i) in availableEnds[mw.start.split('-')[0]]"
                                                        :key="'end' + i"
                                                        :value="e"
                                                    >
                                                        {{ e }}
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
                                        <span class="flex flex-row items-center content-center gap-1 sm:mt-0">
                                            <p>Form</p>
                                            <div class="relative">
                                                <input
                                                    :name="'form' + mw.start"
                                                    :id="'form' + mw.start"
                                                    class="px-2 py-1 border border-primary bg-gray-100 dark:bg-gray-700 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none w-28"
                                                    v-model="mw.word"
                                                    @keydown="checkWords"
                                                />
                                            </div>
                                        </span>
                                    </span>
                                    <span class="flex flex-row ml-2 mt-1 sm:mt-0" v-if="!invalid">
                                        <button
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
                                            <span class="sr-only">Remove multiword</span>
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
                                            <span class="sr-only">Add multiword</span>
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
                                    :class="invalid ? 'cursor-not-allowed' : ''"
                                    class="font-medium text-primary dark:text-primaryLight ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent py-2 px-4 rounded"
                                    @click="save()"
                                >
                                    SAVE
                                </button>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-center italic">No multiword token found</p>
                            <div class="flex items-center justify-center content-center">
                                <button
                                    class="p-2 ripple rounded-full transition text-primary dark:text-primaryLight duration-100 ease-out focus:outline-none hover:bg-gray-200 dark:hover:bg-gray-600"
                                    @click="newElement()"
                                >
                                    <svg style="width: 24px; height: 24px" viewBox="0 0 24 24" class="fill-current">
                                        <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                                    </svg>
                                    <span class="sr-only">Add multiword</span>
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
    name: 'manageMultiword',
    props: { sentenceIndex: Number },
    data() {
        return {
            current: [],
            misc: [],
            availableEnds: {},
            availableStarts: [],
            notUsable: [],
            wait: true,
            invalid: false,
        }
    },
    created() {
        var phrase = this.$store.state.tableData.sentences[this.sentenceIndex]
        let tmp = {}
        let endTmp = []
        let miscStr = ''
        for (let i = 0; i < phrase.tokens.length; i++) {
            if (typeof phrase.tokens[i].index == 'string') {
                tmp.start = phrase.tokens[i].index.split('-')[0] + '-' + phrase.tokens[i + 1].word
                var diff =
                    parseInt(phrase.tokens[i].index.split('-')[1]) - parseInt(phrase.tokens[i].index.split('-')[0]) + 1
                tmp.end = phrase.tokens[i].index.split('-')[1] + '-' + phrase.tokens[i + diff].word
                tmp.word = phrase.tokens[i].word
                if (phrase.tokens[i].spaceAfter != undefined || phrase.tokens[i].spaceAfter != null) {
                    miscStr = 'spaceAfter:' + phrase.tokens[i].spaceAfter + '|'
                }
                if (
                    phrase.tokens[i].misc != undefined &&
                    phrase.tokens[i].misc != null &&
                    Object.keys(phrase.tokens[i].misc).length !== 0
                ) {
                    miscStr += this.objToString(miscStr, phrase.tokens[i].misc)
                }
                this.current.push(tmp)
                this.misc.push(miscStr)
                tmp = {}
                miscStr = ''
                for (let x = i + 1; x < phrase.tokens.length; x++) {
                    if (typeof phrase.tokens[x].index == 'string') {
                        break
                    } else if (phrase.tokens[x].index != phrase.tokens[i].index.split('-')[0]) {
                        endTmp.push(phrase.tokens[x].index + '-' + phrase.tokens[x].word)
                    }
                }
                this.availableEnds[phrase.tokens[i].index.split('-')[0]] = []
                this.availableEnds[phrase.tokens[i].index.split('-')[0]] = endTmp
                endTmp = []
            } else {
                if (phrase.tokens[i].isMultiwordFirstToken) {
                    this.notUsable.push(phrase.tokens[i].multiwordSpan)
                }
            }
        }
        let found = false
        for (let i = 0; i < phrase.tokens.length; i++) {
            if (typeof phrase.tokens[i].index != 'string') {
                for (let x = 0; x < this.notUsable.length; x++) {
                    if (
                        phrase.tokens[i].index >= parseInt(this.notUsable[x].split('-')[0]) &&
                        phrase.tokens[i].index <= parseInt(this.notUsable[x].split('-')[1])
                    ) {
                        found = true
                        break
                    }
                }
                if (!found) {
                    if (phrase.tokens[i + 1] != undefined && typeof phrase.tokens[i + 1].index != 'string') {
                        this.availableStarts.push(phrase.tokens[i].index + '-' + phrase.tokens[i].word)
                    }
                }
                found = false
            }
        }
        this.wait = false
    },
    methods: {
        checkWords() {
            this.invalid = false
            for (let x = 0; x < this.current.length; x++) {
                if (this.current[x].word == '') {
                    this.invalid = true
                    break
                }
            }
        },
        newElement() {
            this.invalid = true
            this.current.push({ start: '', end: '', word: '' })
            this.misc.push('')
        },
        objToString(ph, object) {
            var str = ''
            if (ph != undefined && ph.charAt(ph.length - 1) != '|' && ph != '') {
                str += '|'
            }
            for (var k in object) {
                if (Object.prototype.hasOwnProperty.call(object, k)) {
                    str += k + ':' + object[k] + '|'
                }
            }
            return str
        },
        editData(mode, itemInd) {
            var phrase = this.$store.state.tableData.sentences[this.sentenceIndex]
            let endTmp = []
            if (mode == 'start') {
                for (let i = 0; i < phrase.tokens.length; i++) {
                    if (phrase.tokens[i].index == itemInd.split('-')[0]) {
                        for (let x = i + 1; x < phrase.tokens.length; x++) {
                            if (typeof phrase.tokens[x].index == 'string') {
                                break
                            }
                            endTmp.push(phrase.tokens[x].index + '-' + phrase.tokens[x].word)
                        }
                        this.availableEnds[itemInd.split('-')[0]] = []
                        this.availableEnds[itemInd.split('-')[0]] = endTmp
                        endTmp = []
                    }
                }
            } else if (mode == 'end') {
                for (let i = 0; i < this.availableStarts.length; i++) {
                    for (let x = 0; x < this.current.length; x++) {
                        if (
                            parseInt(this.availableStarts[i].split('-')[0]) >=
                                parseInt(this.current[x].start.split('-')[0]) &&
                            parseInt(this.availableStarts[i].split('-')[0]) <=
                                parseInt(this.current[x].end.split('-')[0])
                        ) {
                            this.availableStarts.splice(i, 1)
                            i--
                        }
                    }
                }
                for (let i = 0; i < this.availableStarts.length; i++) {
                    for (let x = 0; x < this.current.length; x++) {
                        if (this.availableStarts[i + 1] != undefined) {
                            if (
                                parseInt(this.current[x].start.split('-')[0]) ==
                                parseInt(this.availableStarts[i].split('-')[0]) + 1
                            ) {
                                this.availableStarts.splice(i, 1)
                                i--
                            }
                        }
                    }
                }
                let ranges = []
                for (let i = 0; i < this.current.length; i++) {
                    ranges.push(parseInt(this.current[i].start.split('-')[0]))
                }
                ranges.sort(function(a, b) {
                    return a - b
                })
                for (let i = 0; i < ranges.length; i++) {
                    for (let x = 0; x < this.availableEnds[ranges[i]].length; x++) {
                        if (ranges[i + 1] != undefined) {
                            if (parseInt(this.availableEnds[ranges[i]][x].split('-')[0]) >= ranges[i + 1]) {
                                this.availableEnds[ranges[i]].splice(x, 1)
                                x--
                            }
                        }
                    }
                }
                this.current.sort((a, b) => parseInt(a.start.split('-')[0]) - parseInt(b.start.split('-')[0]))
            }
        },
        removeElement(arrInd) {
            this.current.splice(arrInd, 1)
            var phrase = this.$store.state.tableData.sentences[this.sentenceIndex]
            let found = false
            this.availableStarts = []
            var startIndexes = {}
            for (let i = 0; i < phrase.tokens.length; i++) {
                if (typeof phrase.tokens[i].index != 'string') {
                    for (let x = 0; x < this.current.length; x++) {
                        if (
                            phrase.tokens[i].index >= parseInt(this.current[x].start.split('-')[0]) &&
                            phrase.tokens[i].index <= parseInt(this.current[x].end.split('-')[0])
                        ) {
                            found = true
                            break
                        }
                    }
                    if (!found) {
                        if (phrase.tokens[i + 1] != undefined && typeof phrase.tokens[i + 1] != 'string') {
                            this.availableStarts.push(phrase.tokens[i].index + '-' + phrase.tokens[i].word)
                        }
                    }
                    found = false
                }
            }
            for (let i = 0; i < this.availableStarts.length; i++) {
                for (let x = 0; x < this.current.length; x++) {
                    if (this.availableStarts[i + 1] != undefined) {
                        if (
                            parseInt(this.current[x].start.split('-')[0]) ==
                            parseInt(this.availableStarts[i].split('-')[0]) + 1
                        ) {
                            this.availableStarts.splice(i, 1)
                            i--
                        }
                    }
                }
            }
            for (let i = 0; i < phrase.tokens.length; i++) {
                for (let x = 0; x < this.current.length; x++) {
                    if (phrase.tokens[i].index == parseInt(this.current[x].start.split('-')[0])) {
                        startIndexes[i] = {}
                        startIndexes[i].start = parseInt(this.current[x].start.split('-')[0])
                        break
                    }
                }
            }
            this.availableEnds = {}
            let endTmp = []
            Object.keys(startIndexes).forEach(i => {
                for (let x = parseInt(i) + 1; x < phrase.tokens.length; x++) {
                    if (typeof phrase.tokens[x].index == 'string') {
                        break
                    } else if (phrase.tokens[x] != undefined) {
                        endTmp.push(phrase.tokens[x].index + '-' + phrase.tokens[x].word)
                    }
                }
                this.availableEnds[startIndexes[i].start] = []
                this.availableEnds[startIndexes[i].start] = endTmp
                endTmp = []
            })
        },
        save() {
            if (!this.invalid) {
                var phrase1 = this.$store.state.tableData.sentences[this.sentenceIndex]
                var phrase2 = this.$store.state.editableData.sentences[this.sentenceIndex]
                let valid = true
                for (let i = 0; i < this.current.length; i++) {
                    if (this.current[i].start == '' || this.current[i].end == '' || this.current[i].word == '') {
                        valid = false
                    }
                }
                if (valid) {
                    for (let i = 0; i < phrase1.tokens.length; i++) {
                        for (let x = 0; x < this.current.length; x++) {
                            if (
                                typeof phrase1.tokens[i].index != 'string' &&
                                phrase1.tokens[i].index >= parseInt(this.current[x].start.split('-')[0]) &&
                                phrase1.tokens[i].index <= parseInt(this.current[x].end.split('-')[0])
                            ) {
                                phrase1.tokens[i].originalText = this.current[x].word
                                if (phrase1.tokens[i].index == parseInt(this.current[x].start.split('-')[0])) {
                                    phrase1.tokens[i].isMultiwordFirstToken = true
                                    if (
                                        phrase1.tokens[i - 1] != undefined &&
                                        typeof phrase1.tokens[i - 1].index != 'string'
                                    ) {
                                        phrase1.tokens.splice(i, 0, {
                                            index:
                                                this.current[x].start.split('-')[0] +
                                                '-' +
                                                this.current[x].end.split('-')[0],
                                            word: this.current[x].word,
                                            lemma: '_',
                                            upos: '_',
                                            pos: '_',
                                            featuresText: '_',
                                            head: '_',
                                            deprel: '_',
                                            deps: '_',
                                        })
                                        i++
                                    } else if (
                                        phrase1.tokens[i - 1] != undefined &&
                                        typeof phrase1.tokens[i - 1].index == 'string'
                                    ) {
                                        phrase1.tokens.splice(i - 1, 1)
                                        phrase1.tokens.splice(i - 1, 0, {
                                            index:
                                                this.current[x].start.split('-')[0] +
                                                '-' +
                                                this.current[x].end.split('-')[0],
                                            word: this.current[x].word,
                                            lemma: '_',
                                            upos: '_',
                                            pos: '_',
                                            featuresText: '_',
                                            head: '_',
                                            deprel: '_',
                                            deps: '_',
                                        })
                                    } else {
                                        phrase1.tokens.splice(i, 0, {
                                            index:
                                                this.current[x].start.split('-')[0] +
                                                '-' +
                                                this.current[x].end.split('-')[0],
                                            word: this.current[x].word,
                                            lemma: '_',
                                            upos: '_',
                                            pos: '_',
                                            featuresText: '_',
                                            head: '_',
                                            deprel: '_',
                                            deps: '_',
                                        })
                                        i++
                                    }
                                }
                                phrase1.tokens[i].isMultiwordToken = true
                                if (phrase1.tokens[i].isMultiwordFirstToken) {
                                    phrase1.tokens[i].multiwordSpan =
                                        this.current[x].start.split('-')[0] + '-' + this.current[x].end.split('-')[0]
                                }
                                break
                            } else {
                                phrase1.tokens[i].originalText = phrase1.tokens[i].word
                                phrase1.tokens[i].isMultiwordToken = false
                                phrase1.tokens[i].isMultiwordFirstToken = false
                                if (phrase1.tokens[i].multiwordSpan != undefined) {
                                    phrase1.tokens[i].multiwordSpan = ''
                                }
                            }
                        }
                    }
                    let found = false
                    for (let i = 0; i < phrase1.tokens.length; i++) {
                        if (typeof phrase1.tokens[i].index == 'string') {
                            if (this.current.length == 0) {
                                phrase1.tokens.splice(i, 1)
                                i--
                            } else {
                                for (let x = 0; x < this.current.length; x++) {
                                    if (
                                        phrase1.tokens[i].index ==
                                        this.current[x].start.split('-')[0] + '-' + this.current[x].end.split('-')[0]
                                    ) {
                                        found = true
                                    }
                                }
                                if (!found) {
                                    phrase1.tokens.splice(i, 1)
                                    i--
                                }
                            }
                        }
                    }
                    for (let i = 0; i < phrase2.tokens.length; i++) {
                        for (let x = 0; x < this.current.length; x++) {
                            if (
                                phrase2.tokens[i].index >= parseInt(this.current[x].start.split('-')[0]) &&
                                phrase2.tokens[i].index <= parseInt(this.current[x].end.split('-')[0])
                            ) {
                                phrase2.tokens[i].originalText = this.current[x].word
                                if (phrase2.tokens[i].index == parseInt(this.current[x].start.split('-')[0])) {
                                    phrase2.tokens[i].isMultiwordFirstToken = true
                                }
                                phrase2.tokens[i].isMultiwordToken = true
                                if (phrase2.tokens[i].isMultiwordFirstToken) {
                                    phrase2.tokens[i].multiwordSpan =
                                        this.current[x].start.split('-')[0] + '-' + this.current[x].end.split('-')[0]
                                }
                                break
                            } else {
                                phrase2.tokens[i].originalText = phrase2.tokens[i].word
                                phrase2.tokens[i].isMultiwordToken = false
                                phrase2.tokens[i].isMultiwordFirstToken = false
                                if (phrase2.tokens[i].multiwordSpan != undefined) {
                                    phrase2.tokens[i].multiwordSpan = ''
                                }
                            }
                        }
                    }
                    this.$emit('edited')
                    this.toggleModal()
                } else {
                    this.$emit('snack', 'One or more fields are empty')
                }
            }
        },
        toggleModal() {
            this.$emit('close')
            this.wait = true
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
