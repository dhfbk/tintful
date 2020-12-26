<template>
    <div class="w-full rounded-lg shadow-md p-4 mx-auto bg-white mt-2">
        <deps-modal @closeDepsModal="showDepsModal = false" @edited="editedDep" :dep="depToEdit" v-if="showDepsModal" />
        <features-modal @closeFeatsModal="showFeatsModal = false" v-if="showFeatsModal" :featsToEdit="featsToEdit" />
        <div class="overflow-x-auto">
            <div class="w-full grid grid-cols-3 text-center min-w-max">
                <div
                    @click="selectedTab = 0"
                    class="transition-colors duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t min-w-max px-1"
                    :class="selectedTab == 0 ? 'text-primary' : 'text-gray-500'"
                >
                    Flat graph
                </div>
                <div
                    @click="selectedTab = 1"
                    class="transition-colors duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t min-w-max px-1"
                    :class="selectedTab == 1 ? 'text-primary' : 'text-gray-500'"
                >
                    Table
                </div>
                <div
                    @click="selectedTab = 2"
                    class="transition-colors duration-150 hover:bg-gray-100 cursor-pointer py-2 rounded-t min-w-max px-1"
                    :class="selectedTab == 2 ? 'text-primary' : 'text-gray-500'"
                >
                    Named Entity Recognition
                </div>
                <div class="col-span-4">
                    <div class="h-1 w-1/3 bg-primary" :class="tabScroll"></div>
                </div>
            </div>
        </div>
        <div v-if="selectedTab == 0 || selectedTab == 1" class="flex content-center items-center">
            <div
                @click="sentenceIndex < sentencesNum - 1 ? sentenceIndex++ : false"
                class="rounded m-1 ml-0 p-1 ripple bg-gray-200 hover:bg-gray-300 inline-block select-none cursor-pointer"
                :class="sentenceIndex == sentencesNum - 1 ? 'text-gray-500' : ''"
            >
                Next
            </div>
            <span>{{ sentenceIndex + 1 }}/{{ sentencesNum }}</span>
            <div
                @click="sentenceIndex > 0 ? sentenceIndex-- : false"
                class="rounded m-1 p-1 ripple bg-gray-200 hover:bg-gray-300 inline-block select-none cursor-pointer"
                :class="sentenceIndex == 0 ? 'text-gray-500' : ''"
            >
                Previous
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
        <table-edit v-else-if="selectedTab == 1" :currentPhrase="sentenceIndex" @edited="isEdited = true" />
        <nerEdit v-else-if="selectedTab == 2" @edited="isEdited = true" />
    </div>
</template>

<script>
import bratEdit from '../components/bratEdit.vue'
import tableEdit from '../components/tableEdit.vue'
import depsModal from '../components/depsModal.vue'
import FeaturesModal from '../components/featuresModal.vue'
import nerEdit from '../components/nerEdit.vue'
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
        }
    },
    components: { bratEdit, tableEdit, depsModal, FeaturesModal, nerEdit },
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
    methods: {
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
        depsModal(i) {
            console.log('ci arrivo')
            this.depToEdit.dep = i.getAttribute('data-arc-role')
            this.depToEdit.governor = parseInt(i.getAttribute('data-arc-origin').split('_')[2]) + 1
            this.depToEdit.dependent = parseInt(i.getAttribute('data-arc-target').split('_')[2]) + 1
            this.showDepsModal = true
        },
        featsModal(info) {
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
