<template>
    <transition name="fade-bg" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full min-h-screen dark:bg-black bg-gray-900 bg-opacity-25 customZ"
            @click="modal"
        >
            <transition name="fade" appear>
                <div
                    class="bg-white dark:bg-bgDark rounded-lg w-1/2 max-w-3xl max-h-96 h-auto overflow-y-auto overscroll-none p-4"
                    @click.stop
                >
                    <div class="flex w-full">
                        <div class="text-primary dark:text-primaryLight font-bold text-lg">Edit Part of Speech</div>
                        <button
                            class="ripple ml-auto rounded hover:bg-gray-200 dark:hover:bg-gray-600 p-1 focus:outline-none"
                            @click="modal"
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
                    <div>Word: "{{ posToEdit.token.word }}"</div>
                    <span>POS: </span>
                    <div class="relative">
                        <select
                            name="depsSelect"
                            id="depsSelect"
                            v-model="pos"
                            class="w-full block border border-primary appearance-none mr-24 px-1 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                        >
                            <option v-for="(i, x) in posList" :key="x" :value="i.abbr" class="">
                                {{ i.abbr }} ({{ i.full }})
                            </option>
                        </select>
                        <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900">
                            <svg
                                class="h-4 w-4 fill-current text-gray-900 dark:text-gray-200"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                            >
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flow-root mt-4">
                        <div class="float-right">
                            <button
                                class="font-medium ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent text-red-400 py-2 px-4 rounded mr-2"
                                @click="modal"
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
    props: {
        posToEdit: Object,
    },
    created() {
        this.pos = this.posToEdit.token.pos
        //console.log(this.posToEdit)
    },
    data() {
        return {
            pos: '',
            posList: [
                { abbr: 'A', full: 'adjective' },
                { abbr: 'AP', full: 'possessive adjective' },
                { abbr: 'B', full: 'adverb' },
                { abbr: 'BN', full: 'negation adverb' },
                { abbr: 'CC', full: 'coordinative conjunction' },
                { abbr: 'CS', full: 'subordinative conjunction' },
                { abbr: 'DE', full: 'exclamative determiner' },
                { abbr: 'DI', full: 'indefinite determiner' },
                { abbr: 'DQ', full: 'interrogative determiner' },
                { abbr: 'DR', full: 'relative determiner' },
                { abbr: 'DD', full: 'demonstrative determiner' },
                { abbr: 'E', full: 'preposition' },
                { abbr: 'FB', full: '“balanced” punctuation' },
                { abbr: 'FC', full: 'clause boundary punctuation' },
                { abbr: 'FF', full: 'comma, hyphen' },
                { abbr: 'FS', full: 'sentence boundary punctuation' },
                { abbr: 'I', full: 'interjection' },
                { abbr: 'N', full: 'cardinal number' },
                { abbr: 'NO', full: 'ordinal number' },
                { abbr: 'PD', full: 'demonstrative pronoun' },
                { abbr: 'PE', full: 'personal pronoun' },
                { abbr: 'PI', full: 'indefinite pronoun' },
                { abbr: 'PP', full: 'possessive pronoun' },
                { abbr: 'PQ', full: 'interrogative pronoun' },
                { abbr: 'PR', full: 'relative pronoun' },
                { abbr: 'PC', full: 'clitic pronoun' },
                { abbr: 'RD', full: 'determinative article' },
                { abbr: 'RI', full: 'indeterminative article' },
                { abbr: 'S', full: 'common noun' },
                { abbr: 'SA', full: 'abbreviation' },
                { abbr: 'SP', full: 'proper noun' },
                { abbr: 'T', full: 'predeterminer' },
                { abbr: 'VA', full: 'auxiliary verb' },
                { abbr: 'VM', full: 'modal verb' },
                { abbr: 'V', full: 'main verb' },
                { abbr: 'X', full: 'residual class' },
            ],
        }
    },
    methods: {
        modal() {
            this.$emit('closePosModal')
        },
        save() {
            this.$store.state.editableData.sentences[this.posToEdit.senIndex].tokens[
                this.posToEdit.tokenIndex
            ].pos = this.pos
            this.$emit('edited')
            this.$emit('sendID', [this.posToEdit.senIndex, 'token'])
            this.modal()
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
