<template>
    <div class="dark:bg-dark01dp shadow-md rounded-lg col-span-8 p-3 pb-4 md:p-4 md:pb-5" v-if="ready">
        <pos-modal
            v-if="showPosModal"
            @closePosModal="showPosModal = false"
            :posToEdit="posToEdit"
            @edited="editedPos"
            @sendID="addToCheck"
        />
        <deps-modal
            @closeDepsModal="showDepsModal = false"
            @edited="editedDep"
            @snack="snack"
            :dep="depToEdit"
            v-if="showDepsModal"
            :checkRoot="noRoot"
        />
        <features-modal
            @closeFeatsModal="showFeatsModal = false"
            v-if="showFeatsModal"
            :featsToEdit="featsToEdit"
            @edited="editedFeat"
            :mode="featsMode"
        />
        <modalInfo v-if="modalInfo" @modal="modalInfo = !modalInfo" :type="type" />
        <!--
        <confirmationModal
            v-if="confirmation"
            @close="confirmation = !confirmation"
            :msg="confirmText"
            @confirm="confirmAction"
            :mode="confirmationMode"
        />
        -->
        <mwModal
            v-if="showMwModal"
            :arrPos="arrPos"
            @close="showMwModal = false"
            :sentenceIndex="sentenceIndex"
            @edited="editedMW"
        />
        <manageMultiword
            v-if="manageMW"
            :sentenceIndex="sentenceIndex"
            @close="manageMW = false"
            @snack="snack"
            @edited="editedMW"
        />
        <div class="overflow-x-auto w-full">
            <div class="w-full grid grid-cols-4 text-center min-w-max py-2">
                <div
                    @click="confirmAction('graph')"
                    class="transition-colors rounded-t duration-100 cursor-pointer py-2 min-w-max px-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                    :class="
                        selectedTab == 0 ? 'text-primary dark:text-primaryLight' : 'dark:text-gray-300 text-gray-500'
                    "
                >
                    Flat graph
                </div>
                <div
                    @click="confirmAction('table')"
                    class="transition-colors rounded-t duration-100 cursor-pointer py-2 min-w-max px-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                    :class="
                        selectedTab == 1 ? 'text-primary dark:text-primaryLight' : 'dark:text-gray-300 text-gray-500'
                    "
                >
                    Table
                </div>
                <div
                    @click="confirmAction('pos')"
                    class="transition-colors rounded-t duration-100 cursor-pointer py-2 min-w-max px-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                    :class="
                        selectedTab == 2 ? 'text-primary dark:text-primaryLight' : 'dark:text-gray-300 text-gray-500'
                    "
                >
                    Part of Speech
                </div>
                <div
                    @click="confirmAction('ner')"
                    class="transition-colors rounded-t duration-100 cursor-pointer py-2 min-w-max px-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                    :class="
                        selectedTab == 3 ? 'text-primary dark:text-primaryLight' : 'dark:text-gray-300 text-gray-500'
                    "
                >
                    Named Entity Recognition
                </div>
                <div class="col-span-3">
                    <div class="h-1 w-1/3 bg-primary dark:bg-primaryLight" :class="tabScroll"></div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-3 mt-1">
            <div class="w-full col-span-1 justify-self-start flex content-center items-center justify-start">
                <button
                    @click="modalInfo = true"
                    class="ripple p-2 bg-transparent hover:bg-gray-200 dark:hover:bg-gray-600 rounded-full focus:outline-none transition-colors duration-100 ease-out"
                    title="Info"
                >
                    <svg
                        class="fill-current text-primary dark:text-primaryLight"
                        style="width: 24px; height: 24px"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11V17Z"
                        />
                    </svg>
                    <span class="sr-only">Open information dialog</span>
                </button>
            </div>
            <div
                v-if="selectedTab != 2 && selectedTab != 3"
                class="flex content-center items-center col-span-2 sm:col-span-1 justify-end sm:justify-center"
            >
                <button
                    @click="confirmAction('prev')"
                    class="rounded h-5/6 flex items-center content-center mr-1 px-2 ripple transition-colors duration-100 ease-out focus:outline-none select-none"
                    :class="
                        sentenceIndex == 0
                            ? 'bg-gray-500 dark:bg-gray-400 hover:bg-gray-600 dark:hover:bg-gray-500 text-white dark:text-black hover:text-white dark:hover:text-white cursor-not-allowed'
                            : 'bg-primary dark:bg-primaryLight dark:hover:bg-blue-500 hover:bg-primaryDark text-white dark:text-black dark:hover:text-white cursor-pointer'
                    "
                >
                    Prev.
                </button>
                <span class="mx-2">{{ sentenceIndex + 1 }}/{{ sentencesNum }}</span>
                <button
                    @click="confirmAction('next')"
                    class="rounded h-5/6 flex items-center content-center mr-1 px-2 ripple transition-colors duration-100 ease-out select-none focus:outline-none"
                    :class="
                        sentenceIndex == sentencesNum - 1
                            ? 'bg-gray-500 dark:bg-gray-400 hover:bg-gray-600 dark:hover:bg-gray-500 text-white dark:text-black hover:text-white dark:hover:text-white cursor-not-allowed'
                            : 'bg-primary dark:bg-primaryLight dark:hover:bg-blue-500 hover:bg-primaryDark text-white dark:text-black dark:hover:text-white cursor-pointer'
                    "
                >
                    Next
                </button>
            </div>
            <div
                class="w-full mt-1 sm:mt-0 col-span-3 sm:col-span-1 justify-self-end flex content-center items-center justify-start sm:justify-end"
                :class="selectedTab == 2 || selectedTab == 3 ? 'sm:col-span-2' : ''"
            >
                <div class="tooltip mr-2 relative">
                    <button
                        :class="
                            editedBtn
                                ? 'bg-red-500 dark:bg-red-400 hover:bg-red-600 dark:hover:bg-red-600 text-white dark:text-black dark:hover:text-white cursor-pointer'
                                : 'bg-gray-500 dark:bg-gray-400 hover:bg-gray-600 dark:hover:bg-gray-500 text-white dark:text-black hover:text-white dark:hover:text-white cursor-not-allowed'
                        "
                        class="flex rounded py-1 px-2 ripple transition-colors duration-100 ease-out select-none focus:outline-none"
                        @click="confirmAction('restoreSen')"
                    >
                        <svg style="width: 24px; height: 24px" class="fill-current" viewBox="0 0 24 24">
                            <path
                                d="M13,3A9,9 0 0,0 4,12H1L4.89,15.89L4.96,16.03L9,12H6A7,7 0 0,1 13,5A7,7 0 0,1 20,12A7,7 0 0,1 13,19C11.07,19 9.32,18.21 8.06,16.94L6.64,18.36C8.27,20 10.5,21 13,21A9,9 0 0,0 22,12A9,9 0 0,0 13,3Z"
                            />
                        </svg>
                        <span class="sr-only">Restore from last save</span>
                    </button>
                    <span
                        class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1"
                    >
                        Restore from last save
                    </span>
                </div>
                <button
                    :class="
                        loadBtn
                            ? 'bg-gray-500 dark:bg-gray-400 hover:bg-gray-600 dark:hover:bg-gray-500 text-white dark:text-black hover:text-white dark:hover:text-white cursor-pointer'
                            : isEdited
                            ? 'bg-yellow-500 dark:bg-yellow-400 hover:bg-yellow-600 dark:hover:bg-yellow-600 text-black hover:text-white cursor-pointer'
                            : 'bg-green-500 dark:bg-green-400 hover:bg-green-600 dark:hover:bg-green-600 text-white dark:text-black dark:hover:text-white cursor-pointer'
                    "
                    @click="confirmAction('save')"
                    class="flex rounded py-1 px-2 ripple transition-colors duration-100 ease-out select-none focus:outline-none"
                >
                    {{ loadBtn ? 'Wait...' : isEdited ? 'Save changes' : 'Mark as correct' }}
                    <svg
                        :class="loadBtn ? 'animate-spin ml-1 fill-current block' : 'hidden'"
                        style="width: 24px; height: 24px"
                        viewBox="0 0 24 24"
                    >
                        <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                    </svg>
                </button>
            </div>
        </div>
        <span class="my-2 inline-block" v-if="selectedTab != 2 && selectedTab != 3">
            Sentence:
            <span class="font-bold">{{ $store.state.editableData.sentences[sentenceIndex].text }}</span>
        </span>
        <div class="my-2" v-if="selectedTab != 2 && selectedTab != 3">
            <button
                @click="manageMW = !manageMW"
                class="bg-primary dark:bg-primaryLight dark:hover:bg-blue-500 hover:bg-primaryDark text-white dark:text-black dark:hover:text-white rounded py-1 px-2 ripple transition-colors duration-100 ease-out inline-block select-none focus:outline-none"
            >
                Edit multiwords
            </button>
        </div>
        <!--inserire paginazione-->
        <!-- <span class="my-2 inline-block w-full" v-else>
            Sentences:<br />
            <span class="font-bold" v-for="(phrase, n) in nerPhrases" :key="n"> {{ n }}. {{ phrase }}<br /> </span>
            <div class="flex flex-row content-center items-center justify-center mt-2" v-if="this.$store.state.editableData.sentences.length > 10">
                <button
                    class="rounded flex items-center content-center mr-1 px-2 py-1 ripple transition-colors duration-100 ease-out select-none focus:outline-none"
                    :class="
                        startNerPages == 0
                            ? 'bg-gray-400 text-black hover:text-white hover:bg-gray-600 cursor-not-allowed'
                            : 'bg-primary dark:bg-primaryLight dark:hover:bg-primary hover:bg-primaryDark text-white dark:text-black dark:hover:text-white cursor-pointer'
                    "
                    @click="startNerPages--"
                >
                    Prev.
                </button>
                <span class="mx-2">{{ startNerPages + 1 }}/{{ endNerPages }}</span>
                <button
                    class="rounded flex items-center content-center mr-1 px-2 py-1 ripple transition-colors duration-100 ease-out select-none focus:outline-none"
                    :class="
                        startNerPages == endNerPages - 1
                            ? 'bg-gray-400 text-black hover:text-white hover:bg-gray-600 cursor-not-allowed'
                            : 'bg-primary dark:bg-primaryLight dark:hover:bg-primary hover:bg-primaryDark text-white dark:text-black dark:hover:text-white cursor-pointer'
                    "
                    @click="startNerPages++"
                >
                    Next
                </button>
            </div>
        </span> -->
        <p class="my-1 text-red-500 dark:text-red-400" v-if="noRoot && (action == 'graph' || action == '')">
            No ROOT element, please choose the new ROOT by double clicking/tapping on the word
        </p>
        <p class="my-1 text-red-500 dark:text-red-400" v-if="noRoot && action == 'table'">
            No ROOT element, please choose one
        </p>

        <brat-edit
            v-if="selectedTab == 0"
            :sentenceIndex="sentenceIndex"
            :doc="$store.state.editableData"
            @showDepsModal="depsModal"
            @showFeatsModal="featsModal"
            :refresh="refresh"
            @edited="isEdited = true"
            @snack="snack"
            @noRoot="checkRoot"
            @mwModal="multiwordModal"
        />
        <table-edit
            v-else-if="selectedTab == 1"
            :sentenceIndex="sentenceIndex"
            :refresh="refresh"
            @edited="isEdited = true"
            @editFeats="featsModal"
            @snack="snack"
            @misc="setMisc"
            @noRoot="checkRoot"
        />
        <pos-edit
            v-else-if="selectedTab == 2"
            @showPosModal="posModal"
            @sendID="addToID"
            :refresh="refresh"
            :checkProp="checkProp"
        />
        <nerEdit v-else-if="selectedTab == 3" @edited="isEdited = true" @sendID="addToID" />
    </div>
</template>

<script>
import bratEdit from '../components/bratEdit.vue'
import tableEdit from '../components/tableEdit.vue'
import posEdit from '../components/posEdit.vue'
import depsModal from '../components/depsModal.vue'
import posModal from '../components/posModal.vue'
import mwModal from '../components/mwModal.vue'
import FeaturesModal from '../components/featuresModal.vue'
import nerEdit from '../components/nerEdit.vue'
import modalInfo from '../components/modalInfo.vue'
//import confirmationModal from '../components/confirmationModal.vue'
import manageMultiword from '../components/manageMultiword.vue'
import axios from 'axios'
export default {
    props: {
        sheetMode: String,
    },
    data() {
        return {
            featsMode: '',
            startNerPages: 0,
            endNerPages: 1,
            nerPhrases: {},
            showDepsModal: false,
            showFeatsModal: false,
            showPosModal: false,
            showMwModal: false,
            manageMW: false,
            sentenceIndex: 0,
            sentencesNum: 0,
            selectedTab: 0,
            tabScroll: 'transition-transform ease-out transform translate-x-0',
            isEdited: false,
            depToEdit: {},
            featsToEdit: {},
            posToEdit: {},
            refresh: false,
            type: 'graph',
            modalInfo: false,
            confirmText: '',
            confirmation: false,
            action: '',
            confirmationMode: '',
            misc: {},
            tableMisc: false,
            noRoot: false,
            arrPos: 0,
            ready: false,
            loadBtn: false,
            idList: '',
            editedBtn: false,
            checkProp: [],
        }
    },
    created() {
        if (
            localStorage.getItem('processedText') != null ||
            localStorage.getItem('processedText') != undefined ||
            localStorage.getItem('processedText') != ''
        ) {
            if (this.$store.state.editableData.sentences.length <= 10) {
                for (let i = 0; i < this.$store.state.editableData.sentences.length; i++) {
                    this.nerPhrases[i + 1] = this.$store.state.editableData.sentences[i].text
                }
                this.endNerPages = 1
            } else {
                for (let i = 0; i < 10; i++) {
                    this.nerPhrases[i + 1] = this.$store.state.editableData.sentences[i].text
                }
                this.endNerPages = this.$store.state.editableData.sentences.length % 10
            }
            this.ready = true
        }
    },
    components: {
        bratEdit,
        tableEdit,
        depsModal,
        posEdit,
        FeaturesModal,
        nerEdit,
        modalInfo,
        //confirmationModal,
        posModal,
        mwModal,
        manageMultiword,
    },
    mounted() {
        if (
            localStorage.getItem('processedText') != null ||
            localStorage.getItem('processedText') != undefined ||
            localStorage.getItem('processedText') != ''
        ) {
            this.sentencesNum = JSON.parse(localStorage.getItem('processedText')).sentences.length
        }
    },
    beforeCreate() {
        if (localStorage.getItem('text') === '' || localStorage.getItem('text') == undefined) {
            this.$router.replace({ name: 'home' })
        }
    },
    async beforeRouteLeave(to, from, next) {
        await this.confirmAction('save')
        next()
    },
    methods: {
        addToCheck(ind) {
            let arr = []
            for (let i = 0; i < this.sentencesNum; i++) {
                if (this.idList.indexOf(i.toString()) == -1) {
                    arr.push(false)
                } else {
                    arr.push(true)
                }
            }
            arr[ind] = true
            this.checkProp = arr
            this.refresh = true
            setTimeout(() => {
                this.refresh = false
            }, 100)
            this.addToID(arr)
        },
        addToID(ids) {
            this.idList = ''
            for (let i = 0; i < ids.length; i++) {
                if (ids[i] == true) {
                    this.idList += i + ','
                }
            }
            this.idList != '' ? (this.idList = this.idList.substring(0, this.idList.length - 1)) : ''
            this.isEdited = true
        },
        multiwordModal(arrPos) {
            this.arrPos = arrPos
            this.showMwModal = true
        },
        setMisc(obj) {
            this.misc = obj
            this.tableMisc = true
        },
        checkRoot(value) {
            value == 'true' ? (this.noRoot = true) : (this.noRoot = false)
        },
        snack(msg) {
            this.$emit('snack', msg)
        },
        /*
        confirmModal(mode) {
            this.action = mode
            if (this.isEdited && !this.noRoot) {
                this.confirmationMode = mode
                switch (mode) {
                    case 'save':
                        this.confirmText = 'Are you sure you want to save?'
                        this.confirmation = true
                        break
                    default:
                        this.confirmText = 'Doing this will cause you to lose your latest changes. Proceed?'
                        this.confirmation = true
                        break
                }
            } else {
                this.confirmAction()
            }
        },
        */
        async save(senInd, prevType) {
            let ind = ''
            let type = ''
            if (prevType == 'graph' || prevType == 'table') {
                type = 'dep'
                ind += senInd
            } else {
                type = prevType
                ind = this.idList
            }
            if (ind != '') {
                this.loadBtn = true
                let sen = this.$store.state.editableData
                let senMt = this.$store.state.tableData
                let sentences = []
                for (let i = 0; i < sen.sentences.length; i++) {
                    this.createData(sen, senMt, sentences, i)
                }
                let toSend = { user: '', sentences: sentences }
                //console.log(toSend, senInd)
                let sessID = ''
                if (sessionStorage.getItem('session_id') != undefined) {
                    sessID = sessionStorage.getItem('session_id')
                }
                let hashTxt = this.$store.state.hash
                await axios({
                    method: 'post',
                    url: 'https://dh-server.fbk.eu/tint-w/api/?action=submit',
                    data: {
                        session_id: sessID,
                        type: type,
                        hash: hashTxt,
                        sentences: ind,
                        data: JSON.stringify(toSend),
                    },
                })
                    .then(res => {
                        if (res.data.result == 'ERR') {
                            this.snack(res.data.error)
                        } else {
                            this.snack('Saved successfully')
                            localStorage.setItem('processedText', '')
                            localStorage.setItem('processedText', JSON.stringify(sen))
                            localStorage.setItem('tableData', '')
                            localStorage.setItem('tableData', JSON.stringify(senMt))
                        }
                    })
                    .catch(err => {
                        console.error(err)
                    })
                    .then(() => {
                        this.loadBtn = false
                    })
            }
        },
        confirmAction(mode) {
            this.action = mode
            let senInd = this.sentenceIndex
            if (mode == 'restoreSen' && this.editedBtn) {
                let senPiece, senMtPiece
                if (this.selectedTab == 0 || this.selectedTab == 1) {
                    senPiece = JSON.parse(localStorage.getItem('processedText')).sentences[senInd]
                    senMtPiece = JSON.parse(localStorage.getItem('processedText')).sentences[senInd]
                    this.$store.state.editableData.sentences[senInd] = senPiece
                    this.$store.state.tableData.sentences[senInd] = senMtPiece
                    this.refresh = true
                    setTimeout(() => {
                        this.refresh = false
                    }, 100)
                } else {
                    senPiece = JSON.parse(localStorage.getItem('processedText'))
                    senMtPiece = JSON.parse(localStorage.getItem('processedText'))
                    let property = ''
                    this.selectedTab == 2 ? (property = 'pos') : (property = 'ner')
                    this.idList = ''
                    for (let i = 0; i < senPiece.sentences.length; i++) {
                        this.idList += i + ','
                        for (let x = 0; x < senPiece.sentences[i].tokens.length; x++) {
                            this.$store.state.editableData.sentences[i].tokens[x][property] =
                                senPiece.sentences[i].tokens[x][property]
                        }
                        for (let x = 0; x < senMtPiece.sentences[i].tokens.length; x++) {
                            this.$store.state.tableData.sentences[i].tokens[x][property] =
                                senMtPiece.sentences[i].tokens[x][property]
                        }
                    }
                    this.idList != '' ? (this.idList = this.idList.substring(0, this.idList.length - 1)) : ''
                }

                this.isEdited = true
                this.editedBtn = false
            } else if (mode != 'restoreSen') {
                this.isEdited = false
                mode == 'save' ? null : (this.editedBtn = false)
                let prevType = this.type
                switch (this.action) {
                    case 'graph':
                        if (this.selectedTab != 0) {
                            this.selectedTab = 0
                            this.type = 'graph'
                        }
                        break
                    case 'table':
                        if (this.selectedTab != 1) {
                            this.selectedTab = 1
                            this.type = 'table'
                        }
                        break
                    case 'pos':
                        if (this.selectedTab != 2) {
                            this.selectedTab = 2
                            this.type = 'pos'
                        }
                        break
                    case 'ner':
                        if (this.selectedTab != 3) {
                            this.selectedTab = 3
                            this.type = 'ner'
                        }
                        break
                    case 'next':
                        this.sentenceIndex < this.sentencesNum - 1 ? this.sentenceIndex++ : false
                        break
                    case 'prev':
                        this.sentenceIndex > 0 ? this.sentenceIndex-- : false
                        break
                }
                if (!((this.action == 'next' || this.action == 'prev') && this.sentenceIndex == senInd)) {
                    this.save(senInd, prevType)
                }
            }
            this.noRoot = false
            this.tableMisc = false
            this.misc = {}
        },
        createData(sen, senMt, sentences, index) {
            let mT = {}
            for (let x = 0; x < sen.sentences[index].tokens.length; x++) {
                if (this.misc[sen.sentences[index].tokens[x].index] != undefined) {
                    sen.sentences[index].tokens[x] = Object.assign(
                        sen.sentences[index].tokens[x],
                        this.misc[sen.sentences[index].tokens[x].index]
                    )
                }
            }
            sentences.push({
                tokens: sen.sentences[index].tokens,
                multiTokens: [],
                deps: sen.sentences[index]['basic-dependencies'],
            })
            for (let x = 0; x < senMt.sentences[index].tokens.length; x++) {
                if (this.tableMisc) {
                    if (this.misc[senMt.sentences[index].tokens[x].index] != undefined) {
                        senMt.sentences[index].tokens[x] = Object.assign(
                            senMt.sentences[index].tokens[x],
                            this.misc[senMt.sentences[index].tokens[x].index]
                        )
                    }
                }
                if (typeof senMt.sentences[index].tokens[x].index == 'string') {
                    mT = {
                        index: senMt.sentences[index].tokens[x].index,
                        start: parseInt(senMt.sentences[index].tokens[x].index.split('-')[0]),
                        end: parseInt(senMt.sentences[index].tokens[x].index.split('-')[1]),
                        form: senMt.sentences[index].tokens[x].word,
                        misc: senMt.sentences[index].tokens[x].misc,
                    }
                    sentences[index].multiTokens.push(mT)
                }
            }
        },
        editedMW() {
            this.isEdited = true
            this.refresh = true
            setTimeout(() => {
                this.refresh = false
            }, 100)
        },
        editedDep(dep) {
            var x = this.$store.state.editableData.sentences[this.sentenceIndex]['basic-dependencies']
            this.isEdited = true

            for (let i = 0; i < x.length; i++) {
                if (dep.governor == x[i].governor && dep.dependent == x[i].dependent) {
                    this.$store.state.editableData.sentences[this.sentenceIndex]['basic-dependencies'][i].dep = dep.dep
                    break
                }
            }
            this.refresh = true
            setTimeout(() => {
                this.refresh = false
            }, 100)
        },
        editedFeat(feats) {
            if (feats != 'noBrat') {
                var x = {}
                var y = []
                if (this.type == 'graph') {
                    x = this.$store.state.editableData.sentences[feats.senIndex].tokens[feats.tokIndex]
                    y = this.$store.state.tableData.sentences[feats.senIndex].tokens
                } else {
                    x = this.$store.state.tableData.sentences[feats.senIndex].tokens[feats.tokIndex]
                    y = this.$store.state.editableData.sentences[feats.senIndex].tokens
                }
                for (let i = 0; i < y.length; i++) {
                    if (y[i].index == x.index) {
                        y[i].features = feats.newFeats
                        y[i].pos = feats.newPos
                        y[i].word = feats.newWord
                        y[i].lemma = feats.newLemma
                    }
                }
                x.features = feats.newFeats
                x.pos = feats.newPos
                x.word = feats.newWord
                x.lemma = feats.newLemma
                this.refresh = true
                setTimeout(() => {
                    this.refresh = false
                }, 100)
            }
            this.isEdited = true
        },
        editedPos() {
            this.isEdited = true
        },
        depsModal(i) {
            if (i.dep == undefined || i.dep == null) {
                this.depToEdit.dep = i.getAttribute('data-arc-role')
                this.depToEdit.governor = parseInt(i.getAttribute('data-arc-origin').split('_')[2]) + 1
                this.depToEdit.dependent = parseInt(i.getAttribute('data-arc-target').split('_')[2]) + 1
                this.showDepsModal = true
            } else {
                this.depToEdit.dep = 'ROOT'
                this.depToEdit.governor = i.gov
                this.depToEdit.dependent = i.dep
                this.showDepsModal = true
            }
        },
        featsModal(info, mode) {
            //console.log(info)
            this.featsToEdit = info
            this.featsMode = mode
            this.showFeatsModal = true
        },
        posModal(info) {
            this.posToEdit = {
                senIndex: info.senIndex,
                tokenIndex: info.tokenIndex,
                token: this.$store.state.editableData.sentences[info.senIndex].tokens[info.tokenIndex],
            }
            //console.log(this.$store.state.editableData.sentences[info.senIndex].tokens[info.tokenIndex])
            this.showPosModal = true
        },
    },
    watch: {
        selectedTab: function() {
            if (this.selectedTab == 0) {
                this.tabScroll = 'transition-transform ease-out transform translate-x-0'
            } else if (this.selectedTab == 1) {
                this.tabScroll = 'transition-transform ease-out transform translate-x-full'
            } else {
                this.tabScroll =
                    'transition-transform ease-out transform translate-x-' + this.selectedTab.toString() + 'full'
            }
        },
        sheetMode() {
            this.refresh = true
            setTimeout(() => {
                this.refresh = false
            }, 100)
        },
        isEdited() {
            this.isEdited ? (this.editedBtn = true) : null
        },
        // startNerPages() {
        //     this.nerPhrases = {}
        //     for (let i = ((this.startNerPages + 1) * 10) - 10; i < (this.startNerPages + 1) * 10; i++) {
        //         if (this.$store.state.editableData.sentences[i] != undefined) {
        //             this.nerPhrases[i + 1] = this.$store.state.editableData.sentences[i].text
        //         }
        //     }
        // },
    },
}
</script>

<style scoped>
.tooltip .tooltip-text {
    visibility: hidden;
    text-align: center;
    padding: 4px 8px;
    z-index: 100;
    left: 0;
    transition: opacity 0.15s ease-out !important;
    opacity: 0;
    transition-delay: 0.15s;
}

.dark .tooltip-text {
    background-color: white;
    color: #000;
}

.tooltip-text {
    font-weight: 500;
    width: max-content;
    left: 50% !important;
    -webkit-transform: translateX(-50%) !important;
    transform: translateX(-50%) !important;
}

.tooltip:hover .tooltip-text {
    visibility: visible;
    transition: opacity 0.2s ease-out !important;
    opacity: 85%;
}
@media (max-width: 460px) {
    .tooltip-text {
        visibility: hidden !important;
    }
}
</style>
