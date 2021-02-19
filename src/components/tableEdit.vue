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
                    v-for="(d, index) in $store.state.tableData.sentences[sentenceIndex].tokens"
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
                            class="px-1 border border-primary bg-gray-100 dark:bg-gray-700 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none w-max"
                            v-model="d.lemma"
                            @change="$emit('edited')"
                            :disabled="d.lemma == '_'"
                            :class="d.lemma == '_' ? 'cursor-not-allowed' : ''"
                        />
                    </td>
                    <td class="p-1 px-2 border-r border-gray-300 uppercase dark:border-gray-500">
                        <div class="flex content-center items-center h-full w-max">
                            <button
                                class="mr-1 text-primary dark:text-primaryLight bg-transparent hover:bg-gray-300 dark:hover:bg-gray-800 transition-colors duration-100 ease-out ripple py-1 px-1 rounded focus:outline-none w-max"
                                @click="editFeats(d, 'upos')"
                                v-if="d.upos != '_'"
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
                                    ? d.upos == '_'
                                        ? d.upos
                                        : upos[d.pos]
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
                                v-if="d.pos != '_'"
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
                            v-if="d.head == '_'"
                            :name="'head' + d.index"
                            :id="'head' + d.index"
                            disabled
                            class="cursor-not-allowed w-full block border border-primary appearance-none mr-8 px-1 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                        >
                            <option value="_" selected>_</option>
                        </select>
                        <select
                            v-else
                            :name="'head' + d.index"
                            :id="'head' + d.index"
                            v-model="headsEditable[d.index]"
                            @change="editData('heads', d.index)"
                            class="w-full block border border-primary appearance-none mr-8 px-1 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                        >
                            <option
                                v-for="(n, c) in $store.state.tableData.sentences[sentenceIndex]['basic-dependencies']
                                    .length + 1"
                                :key="c"
                                :value="c"
                            >
                                {{ c }}
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
                    </td>
                    <td class="relative p-1 px-2 border-r border-gray-300 dark:border-gray-500">
                        <select
                            v-if="d.deprel == '_'"
                            :name="'dep' + d.index"
                            :id="'dep' + d.index"
                            disabled
                            class="cursor-not-allowed w-full block border border-primary appearance-none mr-24 px-1 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                        >
                            <option value="_" selected>_</option>
                        </select>
                        <select
                            v-else
                            :name="'dep' + d.index"
                            :id="'dep' + d.index"
                            v-model="deprelsEditable[d.index]"
                            @change="editData('deprels', d.index, d.word)"
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
                        <input
                            :name="'space' + d.index"
                            :id="'space' + d.index"
                            class="px-1 border border-primary bg-gray-100 dark:bg-gray-700 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none w-max"
                            @blur="editData('spaceAfter', d.index)"
                            :value="miscInitial[d.index]"
                        />
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
            misc: {},
            miscInitial: {},
            spaceAfter: {}, //object with the index of the token as the key and the spaceAfter as the value
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
        }
    },
    created() {
        this.setInitialData()
        this.loading = false
    },
    methods: {
        setInitialData() {
            var phrase = this.$store.state.tableData.sentences[this.sentenceIndex]
            for (let i = 0; i < phrase['basic-dependencies'].length; i++) {
                this.headsRef[phrase['basic-dependencies'][i].dependent] = phrase['basic-dependencies'][i].governor
                this.deprelsRef[phrase['basic-dependencies'][i].dependent] = phrase['basic-dependencies'][i].dep
            }
            this.deprelsEditable = JSON.parse(JSON.stringify(this.deprelsRef))
            this.headsEditable = JSON.parse(JSON.stringify(this.headsRef))
            for (let i = 0; i < phrase.tokens.length; i++) {
                this.misc[phrase.tokens[i].index] = {}
                if (phrase.tokens[i].spaceAfter != undefined || phrase.tokens[i].spaceAfter != null) {
                    this.misc[phrase.tokens[i].index].spaceAfter = phrase.tokens[i].spaceAfter
                    this.miscInitial[phrase.tokens[i].index] =
                        'spaceAfter:' + this.misc[phrase.tokens[i].index].spaceAfter + '|'
                } else {
                    this.miscInitial[phrase.tokens[i].index] = ''
                }
                this.misc[phrase.tokens[i].index].misc = {}
                if (
                    phrase.tokens[i].misc != undefined &&
                    phrase.tokens[i].misc != null &&
                    Object.keys(phrase.tokens[i].misc).length !== 0
                ) {
                    this.miscInitial[phrase.tokens[i].index] += this.objToString(
                        this.miscInitial[phrase.tokens[i].index],
                        phrase.tokens[i].misc
                    )
                }
            }
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
        editFeats(token, mode) {
            var index = token.index - 1
            var d = this.$store.state.tableData
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
        editData(mode, index, word) {
            let dep = this.$store.state.tableData.sentences[this.sentenceIndex]['basic-dependencies']
            if (mode == 'deprels') {
                let value = document.getElementById('dep' + index).value
                if (value == 'ROOT') {
                    dep.unshift({
                        dep: 'ROOT',
                        dependent: index,
                        dependentGloss: word,
                        governor: 0,
                        governorGloss: 'ROOT',
                    })
                    this.headsEditable[index] = 0
                    for (let x = 0; x < dep.length; x++) {
                        if (dep[x].dependent == index && dep[x].dep != 'ROOT') {
                            dep.splice(x, 1)
                        }
                    }
                }
                this.deprelsEditable[index] = value
                for (let i = 0; i < dep.length; i++) {
                    if (this.deprelsRef[dep[i].dependent] != this.deprelsEditable[dep[i].dependent]) {
                        dep[i].dep = this.deprelsEditable[dep[i].dependent]
                    }
                }
                this.$emit('edited')
            } else if (mode == 'heads') {
                let value = document.getElementById('head' + index).value
                //checking loop
                let msg = ''
                let gov = []
                let cont = 0
                let found = false
                let stop = false
                for (let i = 0; i < dep.length; i++) {
                    if (dep[i].dependent == index && dep[i].dep == 'ROOT') {
                        cont = -1
                        msg = "A ROOT element can't have a head"
                        break
                    }
                }
                while (cont != -1 && !stop) {
                    if (!found) {
                        if (dep[cont].dependent == value) {
                            gov.push(dep[cont].governor)
                            found = true
                            cont = 0
                        } else {
                            cont++
                        }
                    } else {
                        if (index == gov[gov.length - 1]) {
                            stop = true
                            cont = -1
                        } else {
                            gov.forEach(el => {
                                cont = 0
                                if (cont != -1) {
                                    while (cont < dep.length && cont != -1) {
                                        if (el == dep[cont].dependent) {
                                            if (dep[cont].governor == index) {
                                                cont = -1
                                                break
                                            } else {
                                                if (dep[cont].governor != 0) {
                                                    gov.push(dep[cont].governor)
                                                    break
                                                } else {
                                                    stop = true
                                                    cont = dep.length
                                                }
                                            }
                                        } else {
                                            cont++
                                        }
                                    }
                                }
                            })
                        }
                    }
                }
                if (cont != -1) {
                    this.headsEditable[index] = value
                    for (let i = 0; i < dep.length; i++) {
                        if (this.headsRef[dep[i].dependent] != this.headsEditable[dep[i].dependent]) {
                            dep[i].governor = this.headsEditable[dep[i].dependent]
                        }
                    }
                    this.$emit('edited')
                } else {
                    this.headsEditable[index] = this.headsRef[index]
                    document.getElementById('head' + index).value = this.headsRef[index]
                    msg == '' ? (msg = 'Loop detected. Choose another head') : null
                    this.$emit('snack', msg)
                }
            } else {
                let value = document.getElementById('space' + index).value.split('|')
                for (let i = 0; i < value.length; i++) {
                    if (value[i].split(':')[0] != undefined && value[i].split(':')[0] != '') {
                        if (value[i].split(':')[1] != undefined && value[i].split(':')[1] != '') {
                            if (value[i].split(':')[0] == 'spaceAfter') {
                                this.misc[index][value[i].split(':')[0]] = value[i].split(':')[1]
                            } else {
                                this.misc[index].misc[value[i].split(':')[0]] = value[i].split(':')[1]
                            }
                        }
                        this.$emit('edited')
                    }
                }
                this.$emit('misc', this.misc)
                console.log(this.misc)
            }
            let hasRoot = 0
            for (let x = 0; x < dep.length; x++) {
                if (dep[x].dep == 'ROOT') {
                    hasRoot++
                }
            }
            hasRoot == 0 ? this.$emit('noRoot', 'true') : this.$emit('noRoot', 'false')
        },
    },
    watch: {
        sentenceIndex() {
            this.setInitialData()
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
