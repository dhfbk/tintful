<template>
    <div class="overflow-x-auto" v-if="!loading">
        <table class="xs2:min-w-5/6 bg-transparent mx-auto border border-gray-300 dark:border-gray-500">
            <thead>
                <tr class="text-left text-white bg-primary border-b border-gray-300 dark:border-gray-500 uppercase">
                    <th class="px-2" v-for="h in headers" :key="h">{{ h }}</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(d, index) in $store.state.editableData.sentences[currentPhrase].tokens"
                    :key="index"
                    class="border-b border-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-500"
                >
                    <td
                        class="p-1 px-2 border-r border-gray-300 dark:border-gray-500 overflow-x-auto"
                        v-html="d.index"
                    ></td>
                    <td class="p-1 px-2 border-r border-gray-300 dark:border-gray-500" v-html="d.word"></td>
                    <td class="p-1 px-2 border-r border-gray-300 dark:border-gray-500">
                        <input
                            type="text"
                            class="px-1 border border-primary bg-gray-100 dark:bg-gray-700 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none w-full"
                            v-model="d.lemma"
                            @change="$emit('edited')"
                        />
                    </td>
                    <td class="p-1 px-2 border-r border-gray-300 uppercase dark:border-gray-500">
                        <div class="flex content-center items-center h-full w-max">
                            <button
                                class="mr-1 text-primary dark:text-primaryLight bg-transparent hover:bg-gray-300 dark:hover:bg-gray-800 transition-colors duration-100 ease-out ripple py-1 px-1 rounded focus:outline-none w-max"
                                @click="editFeats(d, 'upos')"
                            >
                                <svg class="fill-current" style="width:24px;height:24px" viewBox="0 0 24 24">
                                    <path
                                        fill="currentColor"
                                        d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"
                                    />
                                </svg>
                            </button>
                            {{
                                d.pos.indexOf('+') == -1
                                    ? upos[d.pos]
                                    : upos[d.pos.split('+')[0]] + ' + ' + upos[d.pos.split('+')[1]]
                            }}
                        </div>
                    </td>
                    <td class="p-1 px-2 border-r border-gray-300 dark:border-gray-500" v-html="d.pos"></td>
                    <td class="p-1 px-2 border-r border-gray-300 dark:border-gray-500">
                        <div class="flex content-center items-center h-full">
                            <button
                                class="mr-1 text-primary dark:text-primaryLight bg-transparent hover:bg-gray-300 dark:hover:bg-gray-800 transition-colors duration-100 ease-out ripple py-1 px-1 rounded focus:outline-none w-max"
                                @click="editFeats(d, 'feats')"
                            >
                                <svg class="fill-current" style="width:24px;height:24px" viewBox="0 0 24 24">
                                    <path
                                        fill="currentColor"
                                        d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"
                                    />
                                </svg>
                            </button>
                            {{ d.featuresText }}
                        </div>
                    </td>
                    <td class="relative p-1 px-2 border-r border-gray-300 dark:border-gray-500">
                        <select
                            :name="'head' + d.index"
                            :id="'head' + d.index"
                            v-model="headsEditable[d.index]"
                            @change="editData('heads', d.index)"
                            class="w-full block border border-primary appearance-none mr-8 px-1 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                        >
                            <option
                                v-for="(n, c) in $store.state.editableData.sentences[currentPhrase][
                                    'basic-dependencies'
                                ].length + 1"
                                :key="c"
                                :value="c"
                                >{{ c }}</option
                            >
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
                    </td>
                    <td class="relative p-1 px-2 border-r border-gray-300 dark:border-gray-500">
                        <select
                            :name="'dep' + d.index"
                            :id="'dep' + d.index"
                            v-model="deprelsEditable[d.index]"
                            @change="editData('deprels', d.index)"
                            class="w-full block border border-primary appearance-none mr-24 px-1 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                        >
                            <option v-for="i in deps" :key="i" :value="i">{{ i }}</option>
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
                    </td>
                    <td class="p-1 px-2 border-r border-gray-300 dark:border-gray-500">-</td>
                    <td class="p-1 px-2">
                        spaceAfter:
                        <div class="relative">
                            <select
                                :name="'space' + d.index"
                                :id="'space' + d.index"
                                v-model="spaceAfterEditable[d.index]"
                                @change="editData('spaceAfter', d.index)"
                                class="w-full block border border-primary appearance-none mr-24 px-1 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                            >
                                <option>Yes</option>
                                <option>No</option>
                            </select>
                            <div class="pointer-events-none absolute pin-y pin-r flex items-center pl-2 text-gray-900">
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
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: 'tableEdit',
    props: {
        currentPhrase: Number,
        sentenceIndex: Number,
    },
    data() {
        return {
            headers: ['id', 'form', 'lemma', 'upos', 'xpos', 'feats', 'head', 'deprel', 'deps', 'misc'],
            upos: {
                A: 'adjective',
                AP: 'possessive adjective',
                B: 'adverb',
                BN: 'negation adverb',
                CC: 'coordinative conjunction',
                CS: 'subordinative conjunction',
                DE: 'exclamative determiner',
                DI: 'indefinite determiner',
                DQ: 'interrogative determiner',
                DR: 'relative determiner',
                DD: 'demonstrative determiner',
                E: 'preposition',
                EA: 'articulated preposition',
                FB: '“balanced” punctuation',
                FC: 'clause boundary punctuation',
                FF: 'comma, hyphen',
                FS: 'sentence boundary punctuation',
                I: 'interjection',
                N: 'cardinal number',
                NO: 'ordinal number',
                PD: 'demonstrative pronoun',
                PE: 'personal pronoun',
                PI: 'indefinite pronoun',
                PP: 'possessive pronoun',
                PQ: 'interrogative pronoun',
                PR: 'relative pronoun',
                PC: 'clitic pronoun',
                RD: 'determinative article',
                RI: 'indeterminative article',
                S: 'common noun',
                SA: 'abbreviation',
                SP: 'proper noun',
                T: 'predeterminer',
                VA: 'auxiliary verb',
                VM: 'modal verb',
                V: 'main verb',
                X: 'residual class',
            },
            headsRef: {}, //object with the index of the token as the key and the head as the values
            headsEditable: {},
            deprelsRef: {}, //object with the index of the token as the key and the deprel as the value
            deprelsEditable: {},
            spaceAfterRef: {}, //object with the index of the token as the key and the spaceAfter as the value
            spaceAfterEditable: {},
            deps: [
                'ROOT', //aggiunta a mano
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
                'dobj', //aggiunta a mano
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
            loading: true,
            showData: this.$store.state.editableData.sentences
        }
    },
    created() {
        var phraseDependencies = this.$store.state.editableData.sentences[this.currentPhrase]['basic-dependencies']
        for (let i = 0; i < phraseDependencies.length; i++) {
            this.headsRef[phraseDependencies[i].dependent] = phraseDependencies[i].governor
            this.deprelsRef[phraseDependencies[i].dependent] = phraseDependencies[i].dep
        }
        this.deprelsEditable = JSON.parse(JSON.stringify(this.deprelsRef))
        this.headsEditable = JSON.parse(JSON.stringify(this.headsRef))
        for (let i = 0; i < this.$store.state.editableData.sentences[this.currentPhrase].tokens.length; i++) {
            if (this.$store.state.editableData.sentences[this.currentPhrase].tokens[i].spaceAfter != undefined) {
                this.spaceAfterRef[
                    this.$store.state.editableData.sentences[this.currentPhrase].tokens[i].index
                ] = this.$store.state.editableData.sentences[this.currentPhrase].tokens[i].spaceAfter
            } else {
                this.spaceAfterRef[this.$store.state.editableData.sentences[this.currentPhrase].tokens[i].index] = "Yes"
            }
        }
        this.spaceAfterEditable = JSON.parse(JSON.stringify(this.spaceAfterRef))
        this.loading = false
    },
    methods: {
        editFeats(token, mode) {
            var index = token.index - 1
            var d = this.$store.state.editableData
            var feat = d.sentences[this.sentenceIndex].tokens[index]

            //console.log(token)
            let infoToEdit = {}
            infoToEdit.senIndex = this.sentenceIndex
            infoToEdit.tokIndex = index
            infoToEdit.feats = feat.features
            infoToEdit.lemma = feat.lemma
            infoToEdit.pos = feat.pos
            infoToEdit.word = feat.word
            this.$emit('editFeats', infoToEdit, mode)
        },
        editData(mode, index) {
            if (mode == 'deprels') {
                let value = document.getElementById('dep' + index).value
                this.deprelsEditable[index] = value
                for (
                    let i = 0;
                    i < this.$store.state.editableData.sentences[this.currentPhrase]['basic-dependencies'].length;
                    i++
                ) {
                    if (
                        this.deprelsRef[
                            this.$store.state.editableData.sentences[this.currentPhrase]['basic-dependencies'][i]
                                .dependent
                        ] !=
                        this.deprelsEditable[
                            this.$store.state.editableData.sentences[this.currentPhrase]['basic-dependencies'][i]
                                .dependent
                        ]
                    ) {
                        this.$store.state.editableData.sentences[this.currentPhrase]['basic-dependencies'][
                            i
                        ].dep = this.deprelsEditable[
                            this.$store.state.editableData.sentences[this.currentPhrase]['basic-dependencies'][
                                i
                            ].dependent
                        ]
                    }
                }
                this.$emit('edited')
            } else if (mode == 'heads') {
                let value = document.getElementById('head' + index).value
                this.headsEditable[index] = value
                for (
                    let i = 0;
                    i < this.$store.state.editableData.sentences[this.currentPhrase]['basic-dependencies'].length;
                    i++
                ) {
                    if (
                        this.headsRef[
                            this.$store.state.editableData.sentences[this.currentPhrase]['basic-dependencies'][i]
                                .dependent
                        ] !=
                        this.headsEditable[
                            this.$store.state.editableData.sentences[this.currentPhrase]['basic-dependencies'][i]
                                .dependent
                        ]
                    ) {
                        this.$store.state.editableData.sentences[this.currentPhrase]['basic-dependencies'][
                            i
                        ].governor = this.headsEditable[
                            this.$store.state.editableData.sentences[this.currentPhrase]['basic-dependencies'][
                                i
                            ].dependent
                        ]
                    }
                }
                this.$emit('edited')
            } else {
                let value = document.getElementById('space' + index).value
                if (this.spaceAfterRef[index] != value) {
                    this.spaceAfterEditable[index] = value
                    this.$store.state.editableData.sentences[this.currentPhrase].tokens[index - 1].spaceAfter = value
                    this.$emit('edited')
                }
            }
        },
    },
}
</script>

<style scoped>
.pin-r {
    right: 0;
}

.pin-y {
    top: 0;
    bottom: 0;
}
</style>
