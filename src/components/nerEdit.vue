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
        <div class="divide-y divide-primary divide-opacity-75">
            <div v-for="sen in localData.sentences" :key="sen.index" class="">
                <p class="mt-3 mb-1">{{sen.text}}</p>
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
                            ]"
                        >
                            {{ token.word }}
                        </span>
                    </span>
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
            totalPages: 0,
            page: 1,
            senPerPage: 10,
            sentencesToShow: [],
        }
    },
    created() {
        this.totalPages = Math.ceil(this.localData.sentences.length / this.senPerPage)
        for (let i = 0; i < this.senPerPage; i++) {
            this.sentencesToShow.push(this.localData.sentences[i].text)
            //console.log(i)
        }
        for (var i = 0; i < this.$store.state.editableData.sentences.length; i++) {
            this.ner.i = {}

            for (var x = 0; x < this.$store.state.editableData.sentences[i].tokens.length; x++) {
                this.ner.i.x = this.$store.state.editableData.sentences[i].tokens[x].ner
            }
        }
    },
    methods: {
        changeNer(i, cont) {
            switch (this.$store.state.editableData.sentences[i].tokens[cont].ner) {
                case 'O':
                    this.$store.state.editableData.sentences[i].tokens[cont].ner = 'PER'
                    this.$emit('edited')
                    break
                case 'PER':
                    this.$store.state.editableData.sentences[i].tokens[cont].ner = 'ORG'
                    this.$emit('edited')
                    break
                case 'ORG':
                    this.$store.state.editableData.sentences[i].tokens[cont].ner = 'LOC'
                    this.$emit('edited')
                    break
                case 'LOC':
                    this.$store.state.editableData.sentences[i].tokens[cont].ner = 'O'
                    this.$emit('edited')
                    break
            }
            this.localData.sentences[i].tokens[cont].ner = this.$store.state.editableData.sentences[i].tokens[cont].ner
        },
    },
    watch: {
        page() {
            this.sentencesToShow = []

            for (let i = (this.page - 1) * this.senPerPage; i < (this.page - 1) * this.senPerPage + 10; i++) {
                //console.log(i)

                if (this.localData.sentences[i] != undefined) {
                    this.sentencesToShow.push(this.localData.sentences[i].text)
                }
            }
        },
    },
}
</script>

<style></style>
