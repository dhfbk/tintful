<template>
    <div class="dark:bg-dark01dp shadow-md rounded-lg col-span-8 p-3 md:p-4">
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
        <confirmationModal
            v-if="confirmation"
            @close="confirmation = !confirmation"
            :msg="confirmText"
            @confirm="confirmAction"
            :mode="confirmationMode"
        />
        <div class="overflow-x-auto w-full">
            <div class="w-full grid grid-cols-3 text-center min-w-max p-2">
                <div
                    @click="confirmModal('graph')"
                    class="transition-colors rounded-t duration-100 cursor-pointer py-2 min-w-max px-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                    :class="
                        selectedTab == 0 ? 'text-primary dark:text-primaryLight' : 'dark:text-gray-300 text-gray-500'
                    "
                >
                    Flat graph
                </div>
                <div
                    @click="confirmModal('table')"
                    class="transition-colors rounded-t duration-100 cursor-pointer py-2 min-w-max px-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                    :class="
                        selectedTab == 1 ? 'text-primary dark:text-primaryLight' : 'dark:text-gray-300 text-gray-500'
                    "
                >
                    Table
                </div>
                <div
                    @click="confirmModal('ner')"
                    class="transition-colors rounded-t duration-100 cursor-pointer py-2 min-w-max px-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                    :class="
                        selectedTab == 2 ? 'text-primary dark:text-primaryLight' : 'dark:text-gray-300 text-gray-500'
                    "
                >
                    Named Entity Recognition
                </div>
                <div class="col-span-4">
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
                v-if="selectedTab == 0 || selectedTab == 1"
                class="flex content-center items-center col-span-2 sm:col-span-1 justify-end sm:justify-center"
            >
                <button
                    @click="confirmModal('prev')"
                    class="rounded h-5/6 flex items-center content-center mr-1 px-2 ripple transition-colors duration-100 ease-out focus:outline-none select-none"
                    :class="
                        sentenceIndex == 0
                            ? 'bg-gray-400 text-black hover:text-white hover:bg-gray-600 cursor-not-allowed'
                            : 'bg-primary dark:bg-primaryLight dark:hover:bg-primary hover:bg-primaryDark text-white dark:text-black dark:hover:text-white cursor-pointer'
                    "
                >
                    Prev.
                </button>
                <span class="mx-2">{{ sentenceIndex + 1 }}/{{ sentencesNum }}</span>
                <button
                    @click="confirmModal('next')"
                    class="rounded h-5/6 flex items-center content-center mr-1 px-2  ripple transition-colors duration-100 ease-out select-none focus:outline-none"
                    :class="
                        sentenceIndex == sentencesNum - 1
                            ? 'bg-gray-400 text-black hover:text-white hover:bg-gray-600 cursor-not-allowed'
                            : 'bg-primary dark:bg-primaryLight dark:hover:bg-primary hover:bg-primaryDark text-white dark:text-black dark:hover:text-white cursor-pointer'
                    "
                >
                    Next
                </button>
            </div>
            <div
                class="w-full mt-1 sm:mt-0 col-span-3 sm:col-span-1 justify-self-end flex content-center items-center justify-start sm:justify-end"
                :class="selectedTab == 2 ? 'sm:col-span-2' : ''"
            >
                <button
                    :class="
                        isEdited && !noRoot
                            ? 'bg-primary dark:bg-primaryLight dark:hover:bg-primary hover:bg-primaryDark text-white dark:text-black dark:hover:text-white cursor-pointer'
                            : 'bg-gray-400 text-black hover:text-white hover:bg-gray-600 cursor-not-allowed'
                    "
                    @click="confirmModal('save')"
                    class="rounded py-1 px-2 ripple transition-colors duration-100 ease-out inline-block select-none focus:outline-none"
                >
                    Save changes
                </button>
            </div>
        </div>
        <span class="my-2 inline-block" v-if="selectedTab != 2">
            Sentence:
            <span class="font-bold">{{ currentData.sentences[sentenceIndex].text }}</span>
        </span>
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
            :doc="doc"
            @showDepsModal="depsModal"
            @showFeatsModal="featsModal"
            :refresh="refreshBrat"
            @edited="isEdited = true"
            @snack="snack"
            @noRoot="checkRoot"
        />
        <table-edit
            v-else-if="selectedTab == 1"
            :sentenceIndex="sentenceIndex"
            @edited="isEdited = true"
            @editFeats="featsModal"
            @snack="snack"
            @misc="setMisc"
            @noRoot="checkRoot"
        />
        <nerEdit v-else-if="selectedTab == 2" @edited="isEdited = true" />
    </div>
</template>

<script>
import bratEdit from '../components/bratEdit.vue'
import tableEdit from '../components/tableEdit.vue'
import depsModal from '../components/depsModal.vue'
import FeaturesModal from '../components/featuresModal.vue'
import nerEdit from '../components/nerEdit.vue'
import modalInfo from '../components/modalInfo.vue'
import confirmationModal from '../components/confirmationModal.vue'
export default {
    props: {
        sheetMode: String,
    },
    data() {
        return {
            currentData: JSON.parse(localStorage.getItem('processedText')),
            featsMode: '',
            doc: this.$store.state.editableData,
            startNerPages: 0,
            endNerPages: 1,
            nerPhrases: {},
            showDepsModal: false,
            showFeatsModal: false,
            sentenceIndex: 0,
            sentencesNum: 0,
            selectedTab: 0,
            tabScroll: 'transition-transform ease-out transform translate-x-0',
            isEdited: false,
            depToEdit: {},
            featsToEdit: {},
            refreshBrat: false,
            type: 'graph',
            modalInfo: false,
            confirmText: '',
            confirmation: false,
            action: '',
            confirmationMode: '',
            misc: {},
            tableMisc: false,
            noRoot: false,
        }
    },
    created() {
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
    },
    components: { bratEdit, tableEdit, depsModal, FeaturesModal, nerEdit, modalInfo, confirmationModal },
    mounted() {
        this.sentencesNum = JSON.parse(localStorage.getItem('processedText')).sentences.length
    },
    beforeCreate() {
        if (localStorage.getItem('text') === '' || localStorage.getItem('text') == undefined) {
            this.$router.replace({ name: 'home' })
        } else {
            this.$store.state.editableData = JSON.parse(localStorage.getItem('processedText'))
        }
    },
    beforeRouteLeave(to, from, next) {
        if (this.isEdited) {
            const answer = window.confirm('Do you really want to leave? Your changes will be discarded!')
            if (answer) {
                this.$store.state.editableData = {}
                next()
            } else {
                next(false)
            }
        } else {
            next()
        }
    },
    methods: {
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
        confirmAction(mode) {
            this.isEdited = false
            this.confirmation ? (this.confirmation = !this.confirmation) : ''
            if (mode == 'save') {
                if (this.tableMisc) {
                    for (
                        let i = 0;
                        i < this.$store.state.editableData.sentences[this.sentenceIndex].tokens.length;
                        i++
                    ) {
                        this.$store.state.editableData.sentences[this.sentenceIndex].tokens[i] = Object.assign(
                            this.$store.state.editableData.sentences[this.sentenceIndex].tokens[i],
                            this.misc[i + 1]
                        )
                    }
                }
                localStorage.setItem('processedText', '')
                localStorage.setItem('processedText', JSON.stringify(this.$store.state.editableData))
            } else if (!this.noRoot) {
                this.$store.state.editableData = JSON.parse(localStorage.getItem('processedText'))
            }
            this.tableMisc = false
            this.noRoot = false
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
                case 'ner':
                    if (this.selectedTab != 2) {
                        this.selectedTab = 2
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
            this.refreshBrat = true
            setTimeout(() => {
                this.refreshBrat = false
            }, 200)
        },
        editedFeat(feats) {
            if (feats == 'noBrat') {
                this.isEdited = true
            } else {
                var x = this.$store.state.editableData.sentences[feats.senIndex].tokens[feats.tokIndex]
                //console.log(feats)
                x.features = feats.newFeats
                x.pos = feats.newPos
                this.refreshBrat = true
                setTimeout(() => {
                    this.refreshBrat = false
                }, 200)
            }
        },
        depsModal(i) {
            if (typeof i != 'object') {
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
            this.refreshBrat = true
            setTimeout(() => {
                this.refreshBrat = false
            }, 200)
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

<style scoped></style>
