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
        <manageRoot
            v-if="manageRootModal"
            :sentenceIndex="sentenceIndex"
            @close="manageRootModal = false"
            @snack="snack"
            @edited="editedRoot"
        />
        <div class="tooltip relative w-max float-right">
            <button
                class="bg-red-500 dark:bg-red-400 hover:bg-red-600 dark:hover:bg-red-600 text-white dark:text-black dark:hover:text-white cursor-pointer flex rounded py-1 px-2 ripple transition-colors duration-100 ease-out select-none focus:outline-none"
                @click="confirmAction('restoreAll')"
            >
                <svg style="width: 24px; height: 24px" class="fill-current" viewBox="0 0 24 24">
                    <path
                        d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2M18 20H6V4H13L18 9V20M17 13.24C17 15.86 14.87 18 12.24 18C10.29 18 8.61 16.82 7.88 15.14H9.5C10.11 16 11.11 16.57 12.24 16.57C14.08 16.57 15.57 15.07 15.57 13.24S14.08 9.9 12.24 9.9C10.95 9.9 9.86 10.65 9.29 11.71L10.81 13.24H7V9.43L8.24 10.67C9.09 9.35 10.55 8.5 12.24 8.5C14.87 8.47 17 10.61 17 13.24Z"
                    />
                </svg>
                <span class="sr-only">Restore everything</span>
            </button>
            <span
                class="tooltip-text-right bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1"
            >
                Restore everything
            </span>
        </div>
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
                    <svg class="fill-current" style="width: 24px; height: 24px" viewBox="0 0 24 24">
                        <path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" />
                    </svg>
                    <span class="sr-only">Previous phrase</span>
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
                    <svg class="fill-current" style="width: 24px; height: 24px" viewBox="0 0 24 24">
                        <path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" />
                    </svg>
                    <span class="sr-only">Next phrase</span>
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
                <div class="tooltip relative">
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
                        <span v-if="loadBtn">Wait...</span>
                        <span v-else-if="isEdited">
                            <svg class="fill-current" style="width: 24px; height: 24px" viewBox="0 0 24 24">
                                <path
                                    d="M17 3H5C3.89 3 3 3.9 3 5V19C3 20.1 3.89 21 5 21H19C20.1 21 21 20.1 21 19V7L17 3M19 19H5V5H16.17L19 7.83V19M12 12C10.34 12 9 13.34 9 15S10.34 18 12 18 15 16.66 15 15 13.66 12 12 12M6 6H15V10H6V6Z"
                                />
                            </svg>
                            <span class="sr-only">Save changes</span>
                        </span>
                        <span v-else>
                            <svg class="fill-current" style="width: 24px; height: 24px" viewBox="0 0 24 24">
                                <path
                                    d="M19.78,2.2L24,6.42L8.44,22L0,13.55L4.22,9.33L8.44,13.55L19.78,2.2M19.78,5L8.44,16.36L4.22,12.19L2.81,13.55L8.44,19.17L21.19,6.42L19.78,5Z"
                                />
                            </svg>
                            <span class="sr-only">Mark as correct</span>
                        </span>
                        <svg
                            :class="loadBtn ? 'animate-spin ml-1 fill-current block' : 'hidden'"
                            style="width: 24px; height: 24px"
                            viewBox="0 0 24 24"
                        >
                            <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                        </svg>
                    </button>
                    <span
                        v-if="!loadBtn"
                        class="tooltip-text-right bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light mt-1"
                    >
                        {{ isEdited ? 'Save changes' : 'Mark as correct' }}
                    </span>
                </div>
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
            <button
                @click="manageRootModal = !manageRootModal"
                class="ml-2 bg-transparent dark:hover:bg-gray-600 hover:bg-gray-200 text-primary dark:text-primaryLight rounded py-1 px-2 ripple transition-colors duration-100 ease-out inline-block select-none focus:outline-none border-2 border-primary dark:border-primaryLight"
            >
                Edit root
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
            :reset="reset"
            :checkProp="checkProp"
        />
        <nerEdit v-else-if="selectedTab == 3" @edited="isEdited = true" @sendID="addToID" :refresh="refresh" />
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
import manageRoot from '../components/manageRoot.vue'
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
            manageRootModal: false,
            sentenceIndex: 0,
            sentencesNum: 0,
            selectedTab: 0,
            tabScroll: 'transition-transform ease-out transform translate-x-0',
            isEdited: false,
            depToEdit: {},
            featsToEdit: {},
            posToEdit: {},
            refresh: false,
            reset: false,
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
        manageRoot,
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
                    senMtPiece = JSON.parse(localStorage.getItem('tableData')).sentences[senInd]
                    this.$store.state.editableData.sentences[senInd] = senPiece
                    this.$store.state.tableData.sentences[senInd] = senMtPiece
                    this.refresh = true
                    setTimeout(() => {
                        this.refresh = false
                    }, 100)
                } else {
                    senPiece = JSON.parse(localStorage.getItem('processedText'))
                    senMtPiece = JSON.parse(localStorage.getItem('tableData'))
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
                if (mode == 'restoreAll') {
                    let senPiece = JSON.parse(localStorage.getItem('processedText'))
                    let senMtPiece = JSON.parse(localStorage.getItem('tableData'))
                    this.$store.state.editableData = senPiece
                    this.$store.state.tableData = senMtPiece
                    this.selectedTab != 2 ? (this.refresh = true) : (this.reset = true)
                    setTimeout(() => {
                        this.selectedTab != 2 ? (this.refresh = false) : (this.reset = false)
                    }, 100)
                    this.save(senInd, this.type)
                    this.isEdited = false
                } else {
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
                        this.isEdited ? this.save(senInd, prevType) : null
                    }
                    this.isEdited = false
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
        editedRoot() {
            this.isEdited = true
            this.refresh = true
            setTimeout(() => {
                this.refresh = false
            }, 100)
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
.tooltip .tooltip-text,
.tooltip .tooltip-text-right {
    visibility: hidden;
    text-align: center;
    padding: 4px 8px;
    z-index: 100;
    left: 0;
    transition: opacity 0.15s ease-out !important;
    opacity: 0;
    transition-delay: 0.15s;
}

.dark .tooltip-text,
.dark .tooltip-text-right {
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

.tooltip-text-right {
    font-weight: 500;
    width: max-content;
    left: 100% !important;
    -webkit-transform: translateX(-100%) !important;
    transform: translateX(-100%) !important;
}

.tooltip:hover .tooltip-text,
.tooltip:hover .tooltip-text-right {
    visibility: visible;
    transition: opacity 0.2s ease-out !important;
    opacity: 100%;
}
@media (max-width: 460px) {
    .tooltip-text,
    .tooltip-text-right {
        visibility: hidden !important;
    }
}
</style>
