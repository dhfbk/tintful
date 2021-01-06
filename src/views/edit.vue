<template>
    <div class="w-full rounded-lg shadow-md p-4 mx-auto bg-white mt-2">
        <deps-modal @closeDepsModal="showDepsModal = false" @edited="editedDep" :dep="depToEdit" v-if="showDepsModal" />
        <features-modal
            @closeFeatsModal="showFeatsModal = false"
            v-if="showFeatsModal"
            :featsToEdit="featsToEdit"
            @edited="editedFeat"
        />
        <modalInfo v-if="modalInfo" @modal="modalInfo = !modalInfo" :type="type" />
        <confirmationModal
            v-if="confirmation"
            @close="confirmation = !confirmation"
            :msg="confirmText"
            @confirm="confirmAction"
        />
        <div class="overflow-x-auto">
            <div class="w-full grid grid-cols-3 text-center min-w-max">
                <div
                    @click="confirmModal('graph')"
                    class="transition-colors ease-out duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t min-w-max px-1"
                    :class="selectedTab == 0 ? 'text-primary' : 'text-gray-500'"
                >
                    Flat graph
                </div>
                <div
                    @click="confirmModal('table')"
                    class="transition-colors ease-out duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t min-w-max px-1"
                    :class="selectedTab == 1 ? 'text-primary' : 'text-gray-500'"
                >
                    Table
                </div>
                <div
                    @click="confirmModal('ner')"
                    class="transition-colors ease-out duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t min-w-max px-1"
                    :class="selectedTab == 2 ? 'text-primary' : 'text-gray-500'"
                >
                    Named Entity Recognition
                </div>
                <div class="col-span-4">
                    <div class="h-1 w-1/3 bg-primary" :class="tabScroll"></div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-3 mt-1">
            <div class="w-full col-span-1 justify-self-start flex content-center items-center justify-start">
                <button
                    @click="modalInfo = true"
                    class="ripple p-2 bg-transparent hover:bg-gray-200 rounded-full focus:outline-none transition-colors duration-100 ease-out"
                >
                    <svg class="fill-current text-primary" style="width: 24px; height: 24px" viewBox="0 0 24 24">
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
                <div
                    @click="confirmModal('prev')"
                    class="rounded h-full flex items-center content-center mr-1 px-2 ripple transition-colors duration-100 ease-out inline-block select-none"
                    :class="
                        sentenceIndex == 0
                            ? 'text-white bg-gray-400 hover:bg-gray-600 cursor-not-allowed'
                            : 'bg-primary hover:bg-primaryDark text-white cursor-pointer'
                    "
                >
                    Prev.
                </div>
                <span class="mx-2">{{ sentenceIndex + 1 }}/{{ sentencesNum }}</span>
                <div
                    @click="confirmModal('next')"
                    class="rounded h-full flex items-center content-center mr-1 px-2 ripple transition-colors duration-100 ease-out inline-block select-none"
                    :class="
                        sentenceIndex == sentencesNum - 1
                            ? 'text-white bg-gray-400 hover:bg-gray-600 cursor-not-allowed'
                            : 'bg-primary hover:bg-primaryDark text-white cursor-pointer'
                    "
                >
                    Next
                </div>
            </div>
            <div
                class="w-full mt-1 sm:mt-0 col-span-3 sm:col-span-1 justify-self-end flex content-center items-center justify-start sm:justify-end"
                :class="selectedTab == 2 ? 'sm:col-span-2' : ''"
            >
                <div
                    :class="
                        isEdited
                            ? 'bg-primary hover:bg-primaryDark cursor-pointer'
                            : 'bg-gray-400 hover:bg-gray-600 cursor-not-allowed'
                    "
                    @click="confirmModal('save')"
                    class="rounded py-1 px-2 ripple transition-colors duration-100 ease-out inline-block select-none text-white"
                >
                    Save changes
                </div>
            </div>
        </div>
        <p v-html="currentData.sentences[sentenceIndex].text" class="my-1"></p>
        <brat-edit
            v-if="selectedTab == 0"
            :sentenceIndex="sentenceIndex"
            :doc="doc"
            @showDepsModal="depsModal"
            @showFeatsModal="featsModal"
            :refresh="refreshBrat"
        />
        <table-edit
            v-else-if="selectedTab == 1"
            :sentenceIndex="sentenceIndex"
            :currentPhrase="sentenceIndex"
            @edited="isEdited = true"
            @editFeats="featsModal"
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
    data() {
        return {
            currentData: JSON.parse(localStorage.getItem('processedText')),
            doc: this.$store.state.editableData,
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
        }
    },
    components: { bratEdit, tableEdit, depsModal, FeaturesModal, nerEdit, modalInfo, confirmationModal },
    created() {
        if (localStorage.getItem('text') == '') {
            this.$router.replace({ name: 'home' })
        }
    },
    mounted() {
        this.sentencesNum = JSON.parse(localStorage.getItem('processedText')).sentences.length
    },
    beforeCreate() {
        this.$store.state.editableData = JSON.parse(localStorage.getItem('processedText'))
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
        confirmModal(mode) {
            this.action = mode
            if (this.isEdited) {
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
        confirmAction() {
            this.isEdited = false
            this.confirmation ? (this.confirmation = !this.confirmation) : ''
            console.log(
                this.currentData.sentences[0].tokens[0].ner,
                this.$store.state.editableData.sentences[0].tokens[0].ner
            )
            this.$store.state.editableData = JSON.parse(localStorage.getItem('processedText'))
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
            console.log(dep)
            var x = this.$store.state.editableData.sentences[this.sentenceIndex]['basic-dependencies']
            console.log(x)

            for (let i = 0; i < x.length; i++) {
                if (dep.governor == x[i].governor && dep.dependent == x[i].dependent) {
                    this.$store.state.editableData.sentences[this.sentenceIndex]['basic-dependencies'][i].dep = dep.dep
                    console.log('###############################################')
                    console.log(
                        this.$store.state.editableData.sentences[this.sentenceIndex]['basic-dependencies'][i].dep
                    )
                    break
                }
            }
            this.refreshBrat = true
            setTimeout(() => {
                this.refreshBrat = false
            }, 200)
        },
        editedFeat(feats) {
            var x = this.$store.state.editableData.sentences[feats.senIndex].tokens[feats.tokIndex]
            console.log(feats)
            x.features = feats.newFeats
            x.pos = feats.newPos
            this.refreshBrat = true
            setTimeout(() => {
                this.refreshBrat = false
            }, 200)
        },
        depsModal(i) {
            this.depToEdit.dep = i.getAttribute('data-arc-role')
            this.depToEdit.governor = parseInt(i.getAttribute('data-arc-origin').split('_')[2]) + 1
            this.depToEdit.dependent = parseInt(i.getAttribute('data-arc-target').split('_')[2]) + 1
            this.showDepsModal = true
        },
        featsModal(info) {
            console.log(info)
            this.featsToEdit = info
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
    },
}
</script>

<style scoped></style>
