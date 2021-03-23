<template>
    <div class="mt-4">
        <!--
        <span class="mt-2 mb-4 inline-block w-full">
            <div
                class="flex flex-row content-center items-center justify-center"
                v-if="this.$store.state.editableData.sentences.length > 10"
            >
                <button
                    class="rounded flex items-center content-center mr-1 px-2 py-1 ripple transition-colors duration-100 ease-out select-none focus:outline-none"
                    :class="
                        page == 1
                            ? 'bg-gray-400 text-black hover:text-white hover:bg-gray-600 cursor-not-allowed'
                            : 'bg-primary dark:bg-primaryLight dark:hover:bg-blue-500 hover:bg-primaryDark text-white dark:text-black dark:hover:text-white cursor-pointer'
                    "
                    @click="page > 1 ? page-- : (page = page)"
                >
                    Prev.
                </button>
                <span class="mx-2">{{ page }}/{{ totalPages }}</span>
                <button
                    class="rounded flex items-center content-center mr-1 px-2 py-1 ripple transition-colors duration-100 ease-out select-none focus:outline-none"
                    :class="
                        page == totalPages
                            ? 'bg-gray-400 text-black hover:text-white hover:bg-gray-600 cursor-not-allowed'
                            : 'bg-primary dark:bg-primaryLight dark:hover:bg-blue-500 hover:bg-primaryDark text-white dark:text-black dark:hover:text-white cursor-pointer'
                    "
                    @click="page < totalPages ? page++ : (page = page)"
                >
                    Next
                </button>
            </div>
            Sentences:<br />
            <span class="font-bold" v-for="(sen, n) in sentencesToShow" :key="n">
                {{ n + 1 + (page - 1) * senPerPage }}. {{ sen }}<br />
            </span>
        </span>
        -->
        <div class="font-bold text-lg">Legend</div>
        <div :class="'grid grid-rows-' + nerDesc.length + 'grid-flow-col mb-6'">
            <div v-for="type in nerDesc" :key="type">
                <div v-if="type != 'O'">
                    <div class="h-3 w-3 rounded-full inline-block" :class="'bg-' + type"></div>
                    {{ type }}
                </div>
            </div>
        </div>
        <p class="font-bold text-primary dark:text-primaryLight">Check<br /> to save</p>
        <div class="divide-y divide-primary divide-opacity-75">
            <div v-for="sen in localData.sentences" :key="sen.index" class="flex">
                <!--TOGGLE-->
                <!--
                <label class="flex items-center relative cursor-pointer select-none mr-2">
                    <input
                        v-model="checked[sen.index]"
                        type="checkbox"
                        class="appearance-none transition-colors cursor-pointer w-14 h-7 rounded-full focus:outline-none"
                    />
                    <span class="w-7 h-7 absolute rounded-full transition-all bg-gray-200" />
                </label>
                -->
                <div
                    class="mt-4 flex items-center content-center flex-shrink-0 justify-center bg-white dark:bg-gray-800 border-2 rounded border-gray-400 w-6 h-6 focus-within:border-blue-500 mx-4"
                >
                    <input type="checkbox" class="opacity-0 absolute w-6 h-6" v-model="checked[sen.index]" />
                    <svg
                        class="fill-current hidden w-4 h-4 text-primary dark:text-primaryLight pointer-events-none"
                        viewBox="0 0 20 20"
                    >
                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
                    </svg>
                </div>
                <div>
                    <p class="mt-3 mb-1">{{ sen.text }}</p>
                    <div class="flex flex-row flex-wrap">
                        <span
                            v-for="(token, cont) in sen.tokens"
                            :key="token.index + token.ner"
                            @click="changeNer(sen.index, cont)"
                            class="flex flex-col place-items-center my-2 cursor-pointer"
                        >
                            <span
                                class="mx-1 px-1 rounded select-none"
                                :class="[
                                    token.ner == 'PER'
                                        ? 'text-black bg-PER'
                                        : token.ner == 'ORG' || token.ner == 'LOC'
                                        ? 'text-white bg-' + token.ner
                                        : 'bg-gray-350 dark:bg-gray-600 text-black dark:text-white',
                                    checked[sen.index] ? 'ring-1 ring-green-400' : '',
                                ]"
                            >
                                {{ token.word }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ner',
    data() {
        return {
            keys: [],
            values: [],
            ner: {},
            nerDesc: ['O', 'PER', 'ORG', 'LOC'],
            localData: this.$store.state.editableData,
            backup: JSON.parse(localStorage.getItem('processedText')),
            totalPages: 0,
            page: 1,
            senPerPage: 10,
            sentencesToShow: [],
            checked: [],
        }
    },
    created() {
        this.totalPages = Math.ceil(this.localData.sentences.length / this.senPerPage)
        /*
        for (let i = 0; i < this.senPerPage; i++) {
            this.sentencesToShow.push(this.localData.sentences[i].text)
            //console.log(i)
        }
        */
        for (var i = 0; i < this.$store.state.editableData.sentences.length; i++) {
            this.ner.i = {}
            this.checked[i] = false
            for (var x = 0; x < this.$store.state.editableData.sentences[i].tokens.length; x++) {
                this.ner.i.x = this.$store.state.editableData.sentences[i].tokens[x].ner
            }
        }
    },
    methods: {
        changeNer(i, cont) {
            if (!this.checked[i]) {
                switch (this.$store.state.editableData.sentences[i].tokens[cont].ner) {
                    case 'O':
                        this.$store.state.editableData.sentences[i].tokens[cont].ner = 'PER'
                        break
                    case 'PER':
                        this.$store.state.editableData.sentences[i].tokens[cont].ner = 'ORG'
                        break
                    case 'ORG':
                        this.$store.state.editableData.sentences[i].tokens[cont].ner = 'LOC'
                        break
                    case 'LOC':
                        this.$store.state.editableData.sentences[i].tokens[cont].ner = 'O'
                        break
                }
                this.$emit('edited')
                this.$emit('sendID', [i, 'token'])
                this.localData.sentences[i].tokens[cont].ner = this.$store.state.editableData.sentences[i].tokens[
                    cont
                ].ner
            }
        },
    },
    watch: {
        page() {
            this.sentencesToShow = []
            for (let i = (this.page - 1) * this.senPerPage; i < (this.page - 1) * this.senPerPage + 10; i++) {
                if (this.localData.sentences[i] != undefined) {
                    this.sentencesToShow.push(this.localData.sentences[i].text)
                }
            }
        },
        checked() {
            for (let i = 0; i < this.checked.length; i++) {
                if (this.checked[i] == true) {
                    for (let x = 0; x < this.$store.state.editableData.sentences[i].tokens.length; x++) {
                        this.$store.state.editableData.sentences[i].tokens[x].ner = this.backup.sentences[i].tokens[x].ner
                    }
                }
                this.$emit('sendID', [i, 'check', this.checked[i]])
            }
        },
    },
}
</script>

<style scoped>
input:checked + svg {
    display: block;
}
/**toggle */
/*
input {
    background-color: #ef4444;
}

input:checked {
    background-color: #22c55e;
}

input ~ span:last-child {
    right: 1.75rem;
}

input:checked ~ span:last-child {
    right: 0;
}
*/
</style>
