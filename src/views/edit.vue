<template>
    <div>
        <depsModal @closeDepsModal="showDepsModal = false" @edited="editedDep" :dep="depToEdit" v-if="showDepsModal" />

        <div
            @click="sentenceIndex < sentencesNum - 1 ? sentenceIndex++ : false"
            class="rounded m-1 p-1 ripple bg-gray-200 hover:bg-gray-300 inline-block select-none cursor-pointer"
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
        <div id="deps"></div>
    </div>
</template>

<script>
import { debounce } from 'debounce'
import depsModal from '../components/depsModal.vue'
export default {
    components: { depsModal },

    data() {
        return {
            showDepsModal: false,
            depToEdit: {},
            sonId: '',
            newFatherId: '',
            isEditMode: false,
            sentenceIndex: 0,
            sentencesNum: 0,
            //selectedTab: 0,
            //tabScroll: "transition-transform ease-out transform translate-x-0",
            //bratLocation: "http://hlt-services7.fbk.eu/mysql/js/brat",
            tokensMap: {
                '=LRB=': '(',
                '=RRB=': ')',
                '=LSB=': '[',
                '=RSB=': ']',
                '=LCB=': '{',
                '=RCB=': '}',
                '``': '"',
                "''": '"',
            },
            /**
             * Register an entity type (a tag) for Brat
             */
            entityTypesSet: {},
            entityTypes: [],
            /**
             * Register a relation type (an arc) for Brat
             */
            relationTypesSet: {},
            relationTypes: [],
            //
            // Construct text of annotation
            //
            currentText: [], // GLOBAL
            sentI: 0,
            tokens: [],
            //
            // Shared variables
            // These are what we'll render in BRAT
            //
            // (pos)
            posEntities: [],
            // (lemma)
            lemmaEntities: [],
            // (ner)
            nerEntities: [],
            // (sentiment)
            sentimentEntities: [],
            // (entitylinking)
            linkEntities: [],
            // (dependencies)
            depsRelations: [],
            deps2Relations: [],
            // (openie)
            openieEntities: [],
            openieEntitiesSet: {},
            openieRelations: [],
            openieRelationsSet: {},
            // (kbp)
            kbpEntities: [],
            kbpEntitiesSet: [],
            kbpRelations: [],
            kbpRelationsSet: [],
            currentSentences: [],
            doc: this.$store.state.editableData,
            dispatcher: undefined,
            visualizer: undefined,
        }
    },
    // head: function() {
    //   return {
    //     script: [
    //       {
    //         type: "text/javascript",
    //         src: this.bratLocation + "/client/lib/jquery.svg.min.js",
    //         async: true,
    //         body: false,
    //       },
    //       {
    //         type: "text/javascript",
    //         src: this.bratLocation + "/client/src/configuration.js",
    //         async: true,
    //         body: false,
    //       },
    //       {
    //         type: "text/javascript",
    //         src: this.bratLocation + "/client/src/util.js",
    //         async: true,
    //         body: false,
    //       },
    //       {
    //         type: "text/javascript",
    //         src: this.bratLocation + "/client/src/annotation_log.js",
    //         async: true,
    //         body: false,
    //       },
    //       {
    //         type: "text/javascript",
    //         src: this.bratLocation + "/client/lib/webfont.js",
    //         async: true,
    //         body: false,
    //       },
    //       {
    //         type: "text/javascript",
    //         src: this.bratLocation + "/client/src/dispatcher.js",
    //         async: true,
    //         body: false,
    //       },
    //       {
    //         type: "text/javascript",
    //         src: this.bratLocation + "/client/src/url_monitor.js",
    //         async: true,
    //         body: false,
    //       },
    //     ],
    //   };
    // },

    mounted() {
        this.sentencesNum = JSON.parse(localStorage.getItem('processedText')).sentences.length
        window.onresize = debounce(this.loadBrat, 200)
        this.loadBrat()
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

            document.getElementById('deps').innerHTML = ''
            document.getElementById('deps').className = ''
            this.resetVariables()
            this.loadBrat()
        },
        resetVariables() {
            this.entityTypesSet = {}
            this.entityTypes = []
            /**
             * Register a relation type (an arc) for Brat
             */
            this.relationTypesSet = {}
            this.relationTypes = []
            //
            // Construct text of annotation
            //
            this.currentText = [] // GLOBAL
            this.sentI = 0
            this.tokens = []
            //
            // Shared variables
            // These are what we'll render in BRAT
            //
            // (pos)
            this.posEntities = []
            // (lemma)
            this.lemmaEntities = []
            // (ner)
            this.nerEntities = []
            // (sentiment)
            this.sentimentEntities = []
            // (entitylinking)
            this.linkEntities = []
            // (dependencies)
            this.depsRelations = []
            this.deps2Relations = []
            // (openie
            this.openieEntities = []
            this.openieEntitiesSet = {}
            this.openieRelations = []
            this.openieRelationsSet = {}
            // (kbp)
            this.kbpEntities = []
            this.kbpEntitiesSet = []
            this.kbpRelations = []
            this.kbpRelationsSet = []
            this.currentSentences = []
        },
        isGovernor(el) {
            return el.index == this.newFatherId
        },
        sheet(arr) {
            this.$emit('sheet', arr)
        },
        handleRight(e) {
            if (!this.isEditMode) {
                let i = e.target
                i.parentNode.children[1].setAttribute('fill', 'white')
                i.parentNode.children[0].setAttribute('fill', '#688e26')
                var infos = i.getAttribute('data-span-id').split('_')
                this.sonId = parseInt(infos[2]) + 1
                console.log(this.isEditMode)
                this.isEditMode = true
            } else {
                this.isEditMode = false
                document.getElementById('deps').innerHTML = ''
                document.getElementById('deps').className = ''
                this.resetVariables()
                this.loadBrat()
            }
        },
        handleClick(e) {
            console.log("siamo dentro l'evento click")

            //console.log(e.target);
            var i = e.target
            i.parentNode.children[1].setAttribute('fill', 'white')
            i.parentNode.children[0].setAttribute('fill', '#688e26')
            var infos = i.getAttribute('data-span-id').split('_')
            this.newFatherId = parseInt(infos[2]) + 1
            var d = this.$store.state.editableData

            var dep = d.sentences[this.sentenceIndex]['basic-dependencies']
            for (let i = 0; i < dep.length; i++) {
                if (dep[i].dependent == this.sonId) {
                    dep[i].governor = this.newFatherId
                    dep[i].governorGloss = d.sentences[this.sentenceIndex].tokens.find(this.isGovernor).word
                    console.log(dep[i].governorGloss)
                    console.log(
                        'new father of ' +
                            dep[i].dependent +
                            ' (' +
                            dep[i].dependentGloss +
                            ')' +
                            ' is ' +
                            dep[i].governor +
                            ' (' +
                            dep[i].governorGloss +
                            ')'
                    )
                    break
                }
            }
            this.$store.state.editableData = d

            document.getElementById('deps').innerHTML = ''
            document.getElementById('deps').className = ''
            this.resetVariables()
            this.loadBrat()
            this.isEditMode = false
            //this.currentText = [];

            console.log('son: ' + this.sonId, 'new father: ' + this.newFatherId)
        },
        addEvents() {
            console.log('siamo dentro la funzione che inserisce gli eventi')
            var x = document.getElementById('deps')
            var pos = x.getElementsByClassName('span_default')
            if (this.isEditMode) {
                pos.forEach(el => {
                    el.onclick = this.handleClick
                })
            } else {
                pos.forEach(el => {
                    el.onclick = function(e) {
                        console.log(e.target)
                    }
                })
            }
        },
        // checkRedraw() {
        //   if (this.selectedTab == 3) {
        //     this.loadBrat();
        //   }
        // },
        loadBrat() {
            //console.log(this.currentText.length);
            if (this.currentText.length == 0) {
                /**
                 * Add the startsWith function to the String class
                 */
                if (typeof String.prototype.startsWith != 'function') {
                    // see below for better implementation!
                    String.prototype.startsWith = function(str) {
                        return this.indexOf(str) === 0
                    }
                }
                // document.getElementById("deps").innerHTML = "";
                //document.getElementById("deps").className = "";
                // if (this.sentenceIndex > 0) {
                //   doc.sentences[this.sentenceIndex].index = 0;
                //   doc.sentences[this.sentenceIndex].characterOffsetEnd -= parseInt(
                //     doc.sentences[this.sentenceIndex - 1].characterOffsetEnd + 1
                //   );
                //   doc.sentences[this.sentenceIndex].characterOffsetBegin = 0;
                //   // doc.sentences[this.sentenceIndex].characterOffsetEnd -=
                //   //   doc.sentences[this.sentenceIndex - 1].characterOffsetBegin + 1;
                //   doc.sentences[this.sentenceIndex].tokens.forEach((el) => {
                //     console.log(
                //       el.characterOffsetBegin +
                //         "-" +
                //         parseInt(
                //           doc.sentences[this.sentenceIndex - 1].characterOffsetEnd + 1
                //         )
                //     );
                //     el.characterOffsetBegin -= parseInt(
                //       doc.sentences[this.sentenceIndex - 1].characterOffsetEnd + 1
                //     );
                //     el.characterOffsetEnd -= parseInt(
                //       doc.sentences[this.sentenceIndex - 1].characterOffsetEnd + 1
                //     );
                //   });
                // }
                // doc.sentences[this.sentenceIndex].tokens.forEach((el) => {
                //   console.log(el.characterOffsetBegin, el.characterOffsetEnd);
                // });
                // console.log(this.sentenceIndex);
                /* console.log({
          sentences: [this.doc.sentences[this.sentenceIndex]],
        }); */
                let doc = this.$store.state.editableData
                this.render({
                    sentences: [doc.sentences[this.sentenceIndex]],
                })
                console.log(this.doc.sentences[this.sentenceIndex])
                var x = document.getElementById('deps')
                var pos = x.getElementsByClassName('span_default')
                var depend = x.getElementsByClassName('arcs')

                x.addEventListener(
                    'contextmenu',
                    function(e) {
                        e.preventDefault()
                    },
                    true
                )
                // x.children.forEach((el) => {
                //   el.oncontextmenu = "return false;";
                // });
                //console.log(x);
                //console.log(pos.length);
                setTimeout(() => {
                    pos.forEach(el => {
                        //console.log(el);
                        el.oncontextmenu = this.handleRight

                        //function(e) {
                        // let i = e.target;
                        // this.isEditMode = true;
                        // i.parentNode.children[1].setAttribute("fill", "white");
                        // i.parentNode.children[0].setAttribute("fill", "#688e26");
                        // var infos = i.getAttribute("data-span-id").split("_");
                        // this.sonId = infos[2];
                        // console.log(this.isEditMode);
                        //let i = e.target;
                        // console.log(i.getAttribute("data-span-id").split("_"));
                        // console.log(i.className.baseVal.split(" "));
                        // console.log(el.parentNode.children); //.setAttribute("fill", "#688e26");
                        // if (this.isEditMode) {
                        //  }
                        // var infos = i.getAttribute("data-span-id").split("_");
                        // //console.log(infos[2]);
                        // this.isEditMode = !this.isEditMode;
                        // if (!this.isEditMode) {
                        //   this.sonId = infos[2];
                        //   //console.log(this.sonId);
                        // } else {
                        //   this.newFatherId = infos[2];
                        //   // this.sonId=''
                        //   // this.newFatherId=''
                        // }
                        // console.log(this.sonId);
                        // console.log(this.newFatherId);
                        // this.render(JSON.parse(localStorage.getItem("processedText")));
                        //document.getElementById("demo").innerHTML = "Hello World";
                        // };
                        // el.addEventListener("click", function(e) {
                        //   var self = this;
                        //   let i = e.target;
                        //   console.log(i.getAttribute("data-span-id").split("_"));
                        //   console.log(i.className.baseVal.split(" "));
                        //   console.log(el.parentNode.children); //.setAttribute("fill", "#688e26");
                        //   // if (this.isEditMode) {
                        //   el.parentNode.children[1].setAttribute("fill", "white");
                        //   el.parentNode.children[0].setAttribute("fill", "#688e26");
                        //   //  }
                        //   var infos = i.getAttribute("data-span-id").split("_");
                        //   console.log(infos[2]);
                        //   if (!self.isEditMode) {
                        //     self.sonId = infos[2];
                        //     console.log(self.sonId);
                        //   } else {
                        //     self.newFatherId = infos[2];
                        //     // this.sonId=''
                        //     // this.newFatherId=''
                        //   }
                        //   self.isEditMode = !self.isEditMode;
                        //   // this.render(JSON.parse(localStorage.getItem("processedText")));
                        //   //document.getElementById("demo").innerHTML = "Hello World";
                        // });
                    })
                    //console.log(depend);
                    depend.forEach(el => {
                        //console.log(el.children);
                        el.children.forEach(p => {
                            //console.log(p.children[0]);
                            p.children[0].ondblclick = e => {
                                let i = e.target
                                this.depToEdit.dep = i.getAttribute('data-arc-role')
                                this.depToEdit.governor = parseInt(i.getAttribute('data-arc-origin').split('_')[2]) + 1
                                this.depToEdit.dependent = parseInt(i.getAttribute('data-arc-target').split('_')[2]) + 1
                                this.showDepsModal = true

                                console.log(i.getAttribute('data-arc-role'))
                                console.log(i.getAttribute('data-arc-origin').split('_'))
                                console.log(i.getAttribute('data-arc-target').split('_'))
                            }
                        })
                    })
                }, 200)
            } else {
                setTimeout(() => {
                    document.getElementById('deps').innerHTML = ''
                    document.getElementById('deps').className = ''
                    // this.render({
                    //   sentences: [
                    //     JSON.parse(localStorage.getItem("processedText")).sentences[
                    //       this.sentenceIndex
                    //     ],
                    //   ],
                    // });
                    this.embed('deps', this.posEntities, this.depsRelations)
                }, 50)
            }
        },
        isInt(value) {
            return (
                !isNaN(value) &&
                (function(x) {
                    return (x | 0) === x
                })(parseFloat(value))
            )
        },
        posColor(posTag) {
            if (posTag.startsWith('R')) {
                return '#A4BCED'
            } else if (posTag.startsWith('V') || posTag.startsWith('M')) {
                return '#ADF6A2'
            } else if (posTag.startsWith('A')) {
                return '#CCDAF6'
            } else if (posTag.startsWith('E')) {
                return '#FFE8BE'
            } else if (posTag.startsWith('N') || posTag.startsWith('W')) {
                return '#FFFDA8'
            } else if (posTag.startsWith('D') || posTag == 'CD') {
                return '#CCADF6'
            } else if (posTag.startsWith('J')) {
                return '#FFFDA8'
            } else if (posTag.startsWith('T')) {
                return '#FFE8BE'
            } else if (posTag.startsWith('CC') || posTag.startsWith('S')) {
                return '#E4CBF6'
            } else if (posTag.startsWith('I')) {
                return '#FFFFFF'
            } else if (posTag == 'LS' || posTag == 'FW') {
                return '#FFFFFF'
            } else {
                return '#E3E3E3'
            }
        },
        nerColor(nerTag) {
            if (nerTag == 'PER') {
                return '#FFCCAA'
            } else if (nerTag == 'ORG') {
                return '#8FB2FF'
            } else if (nerTag == 'MISC') {
                return '#F1F447'
            } else if (nerTag == 'LOC') {
                return '#95DFFF'
            } else if (nerTag == 'DATE' || nerTag == 'TIME' || nerTag == 'SET') {
                return '#9AFFE6'
            } else if (nerTag == 'MONEY') {
                return '#FFFFFF'
            } else if (nerTag == 'PERCENT') {
                return '#FFA22B'
            } else {
                return '#E3E3E3'
            }
        },
        sentimentColor(sentiment) {
            if (sentiment == 'VERY POSITIVE') {
                return '#00FF00'
            } else if (sentiment == 'POSITIVE') {
                return '#7FFF00'
            } else if (sentiment == 'NEUTRAL') {
                return '#FFFF00'
            } else if (sentiment == 'NEGATIVE') {
                return '#FF7F00'
            } else if (sentiment == 'VERY NEGATIVE') {
                return '#FF0000'
            } else {
                return '#E3E3E3'
            }
        },
        annotators() {
            return 'tokenize, ssplit, ml, pos, ita_morpho, ita_lemma, ner, depparse'
        },
        addEntityType(name, type) {
            // Don't add duplicates
            if (this.entityTypesSet[type]) return
            this.entityTypesSet[type] = true
            // Get the color of the entity type
            var color = '#ffccaa'
            if (name == 'POS') {
                color = this.posColor(type)
            } else if (name == 'NER') {
                color = this.nerColor(type)
            } else if (name == 'COREF') {
                color = '#FFE000'
            } else if (name == 'ENTITY') {
                color = this.posColor('NN')
            } else if (name == 'RELATION') {
                color = this.posColor('VB')
            } else if (name == 'LEMMA') {
                color = '#FFFFFF'
            } else if (name == 'SENTIMENT') {
                color = this.sentimentColor(type)
            } else if (name == 'LINK') {
                color = '#FFFFFF'
            } else if (name == 'KBP_ENTITY') {
                color = '#FFFFFF'
            }
            // Register the type
            this.entityTypes.push({
                type: type,
                labels: [type],
                bgColor: color,
                borderColor: 'darken',
            })
        },
        addRelationType(type, symmetricEdge) {
            // Prevent adding duplicates
            if (this.relationTypesSet[type]) return
            this.relationTypesSet[type] = true
            // Default arguments
            if (typeof symmetricEdge == 'undefined') {
                symmetricEdge = false
            }
            // Add the type
            this.relationTypes.push({
                type: type,
                labels: [type],
                dashArray: symmetricEdge ? '3,3' : undefined,
                arrowHead: symmetricEdge ? 'none' : undefined,
            })
        },
        posID(i) {
            return 'POS_' + this.sentI + '_' + i
        },
        processDeps(name, deps) {
            var relations = []
            // Format: [${ID}, ${TYPE}, [[${ARGNAME}, ${TARGET}], [${ARGNAME}, ${TARGET}]]]
            for (let i = 0; i < deps.length; i++) {
                var dep = deps[i]
                var governor = dep.governor - 1
                var dependent = dep.dependent - 1
                if (governor == -1) continue
                this.addRelationType(dep.dep)
                relations.push([
                    name + '_' + this.sentI + '_' + i,
                    dep.dep,
                    [
                        ['governor', this.posID(governor)],
                        ['dependent', this.posID(dependent)],
                    ],
                ])
            }
            return relations
        },
        openieID(span) {
            return 'OPENIEENTITY' + '_' + this.sentI + '_' + span[0] + '_' + span[1]
        },
        addEntity(span, role) {
            // Don't add duplicate entities
            if (this.openieEntitiesSet[[this.sentI, span, role]]) return
            this.openieEntitiesSet[[this.sentI, span, role]] = true
            // Add the entity
            this.openieEntities.push([
                this.openieID(span),
                role,
                [[this.tokens[span[0]].characterOffsetBegin, this.tokens[span[1] - 1].characterOffsetEnd]],
            ])
        },
        addRelation(gov, dep, role) {
            // Don't add duplicate relations
            if (this.openieRelationsSet[[this.sentI, gov, dep, role]]) return
            this.openieRelationsSet[[this.sentI, gov, dep, role]] = true
            // Add the relation
            this.openieRelations.push([
                'OPENIESUBJREL_' + this.sentI + '_' + gov[0] + '_' + gov[1] + '_' + dep[0] + '_' + dep[1],
                role,
                [
                    ['governor', this.openieID(gov)],
                    ['dependent', this.openieID(dep)],
                ],
            ])
        },
        kbpEntity(span) {
            return 'KBPENTITY' + '_' + this.sentI + '_' + span[0] + '_' + span[1]
        },
        addKBPEntity(span, role) {
            // Don't add duplicate entities
            if (this.kbpEntitiesSet[[this.sentI, span, role]]) return
            this.kbpEntitiesSet[[this.sentI, span, role]] = true
            // Add the entity
            this.kbpEntities.push([
                this.kbpEntity(span),
                role,
                [[this.tokens[span[0]].characterOffsetBegin, this.tokens[span[1] - 1].characterOffsetEnd]],
            ])
        },
        addKBPRelation(gov, dep, role) {
            // Don't add duplicate relations
            if (this.kbpRelationsSet[[this.sentI, gov, dep, role]]) return
            this.kbpRelationsSet[[this.sentI, gov, dep, role]] = true
            // Add the relation
            this.kbpRelations.push([
                'KBPRELATION_' + this.sentI + '_' + gov[0] + '_' + gov[1] + '_' + dep[0] + '_' + dep[1],
                role,
                [
                    ['governor', this.kbpEntity(gov)],
                    ['dependent', this.kbpEntity(dep)],
                ],
            ])
        },
        render(data) {
            // Error checks
            if (typeof data.sentences == 'undefined') {
                return
            }
            this.currentSentences = data.sentences // GLOBAL
            data.sentences.forEach(sentence => {
                for (let i = 0; i < sentence.tokens.length; ++i) {
                    let token = sentence.tokens[i]
                    var word = token.word
                    if (!(typeof this.tokensMap[word] == 'undefined')) {
                        word = this.tokensMap[word]
                    }
                    if (i > 0) {
                        this.currentText.push(' ')
                    }
                    token.characterOffsetBegin = this.currentText.length
                    for (let j = 0; j < word.length; ++j) {
                        this.currentText.push(word[j])
                    }
                    token.characterOffsetEnd = this.currentText.length
                }
                this.currentText.push('\n')
            })
            this.currentText = this.currentText.join('')

            //
            // Loop over sentences.
            // This fills in the variables above.
            //
            for (this.sentI = 0; this.sentI < data.sentences.length; ++this.sentI) {
                var sentence = data.sentences[this.sentI]
                this.tokens = sentence.tokens
                var deps = sentence['basic-dependencies']
                var deps2 = sentence['enhanced-plus-plus-dependencies']

                // POS tags
                /**
                 * Generate a POS tagged token id
                 */
                if (this.tokens.length > 0 && typeof this.tokens[0].pos != 'undefined') {
                    for (let i = 0; i < this.tokens.length; i++) {
                        let token = this.tokens[i]
                        var pos = token.pos
                        var begin1 = parseInt(token.characterOffsetBegin)
                        let end = parseInt(token.characterOffsetEnd)
                        this.addEntityType('POS', pos)
                        this.posEntities.push([this.posID(i), pos, [[begin1, end]]])
                    }
                }

                // Dependency parsing
                // Actually add the dependencies
                if (typeof deps != 'undefined') {
                    this.depsRelations = this.depsRelations.concat(this.processDeps('dep', deps))
                }
                if (typeof deps2 != 'undefined') {
                    this.deps2Relations = this.deps2Relations.concat(this.processDeps('dep2', deps2))
                }

                // Lemmas
                if (this.tokens.length > 0 && typeof this.tokens[0].lemma != 'undefined') {
                    for (let i = 0; i < this.tokens.length; i++) {
                        let token = this.tokens[i]
                        var lemma = token.lemma
                        let begin2 = parseInt(token.characterOffsetBegin)
                        let end = parseInt(token.characterOffsetEnd)
                        this.addEntityType('LEMMA', lemma)
                        this.lemmaEntities.push(['LEMMA_' + this.sentI + '_' + i, lemma, [[begin2, end]]])
                    }
                }

                // NER tags
                // Assumption: contiguous occurrence of one non-O is a single entity
                if (this.tokens.length > 0 && typeof this.tokens[0].ner != 'undefined') {
                    for (let i = 0; i < this.tokens.length; i++) {
                        var ner = this.tokens[i].ner
                        if (ner == 'O') continue
                        let j = i
                        while (j < this.tokens.length - 1 && this.tokens[j + 1].ner == ner) j++
                        this.addEntityType('NER', ner)
                        this.nerEntities.push([
                            'NER_' + this.sentI + '_' + i,
                            ner,
                            [[this.tokens[i].characterOffsetBegin, this.tokens[j].characterOffsetEnd]],
                        ])
                        i = j
                    }
                }

                // Sentiment
                if (typeof sentence.sentiment != 'undefined') {
                    var sentiment = sentence.sentiment.toUpperCase().replace('VERY', 'VERY ')
                    this.addEntityType('SENTIMENT', sentiment)
                    this.sentimentEntities.push([
                        'SENTIMENT_' + this.sentI,
                        sentiment,
                        [[this.tokens[0].characterOffsetBegin, this.tokens[this.tokens.length - 1].characterOffsetEnd]],
                    ])
                }

                // Entity Links
                // Carries the same assumption as NER
                if (this.tokens.length > 0) {
                    for (let i = 0; i < this.tokens.length; i++) {
                        var link = this.tokens[i].entitylink
                        if (link == 'O' || typeof link == 'undefined') continue
                        let j = i
                        while (j < this.tokens.length - 1 && this.tokens[j + 1].entitylink == link) j++
                        this.addEntityType('LINK', link)
                        this.linkEntities.push([
                            'LINK_' + this.sentI + '_' + i,
                            link,
                            [[this.tokens[i].characterOffsetBegin, this.tokens[j].characterOffsetEnd]],
                        ])
                        i = j
                    }
                }

                // Render OpenIE
                if (typeof sentence.openie != 'undefined') {
                    // Register the entities + relations we'll need
                    this.addEntityType('ENTITY', 'Entity')
                    this.addEntityType('RELATION', 'Relation')
                    this.addRelationType('subject')
                    this.addRelationType('object')
                    // Loop over triples
                    for (let i = 0; i < sentence.openie.length; ++i) {
                        let subjectSpan = sentence.openie[i].subjectSpan
                        var relationSpan = sentence.openie[i].relationSpan
                        let objectSpan = sentence.openie[i].objectSpan
                        if (parseInt(relationSpan[0]) < 0 || parseInt(relationSpan[1]) < 0) {
                            continue // This is a phantom relation
                        }
                        //let begin = parseInt(token.characterOffsetBegin);
                        // Add the entities
                        this.addEntity(subjectSpan, 'Entity')
                        this.addEntity(relationSpan, 'Relation')
                        this.addEntity(objectSpan, 'Entity')
                        // Add the relations
                        this.addRelation(relationSpan, subjectSpan, 'subject')
                        this.addRelation(relationSpan, objectSpan, 'object')
                    }
                } // End OpenIE block

                //
                // KBP
                //
                if (typeof sentence.kbp != 'undefined') {
                    console.log('CI SONO')
                    // Register the entities + relations we'll need
                    this.addRelationType('subject')
                    this.addRelationType('object')
                    // Loop over triples
                    for (let i = 0; i < sentence.kbp.length; ++i) {
                        let subjectSpan = sentence.kbp[i].subjectSpan
                        var subjectLink = 'Entity'
                        for (let k = subjectSpan[0]; k < subjectSpan[1]; ++k) {
                            if (
                                subjectLink == 'Entity' &&
                                typeof this.tokens[k] != 'undefined' &&
                                this.tokens[k].entitylink != 'O' &&
                                typeof this.tokens[k].entitylink != 'undefined'
                            ) {
                                subjectLink = this.tokens[k].entitylink
                            }
                        }
                        this.addEntityType('KBP_ENTITY', subjectLink)
                        let objectSpan = sentence.kbp[i].objectSpan
                        var objectLink = 'Entity'
                        for (let k = objectSpan[0]; k < objectSpan[1]; ++k) {
                            if (
                                objectLink == 'Entity' &&
                                typeof this.tokens[k] != 'undefined' &&
                                this.tokens[k].entitylink != 'O' &&
                                typeof this.tokens[k].entitylink != 'undefined'
                            ) {
                                objectLink = this.tokens[k].entitylink
                            }
                        }
                        this.addEntityType('KBP_ENTITY', objectLink)
                        var relation = sentence.kbp[i].relation
                        //let begin = parseInt(token.characterOffsetBegin);
                        // Add the entities
                        this.addKBPEntity(subjectSpan, subjectLink)
                        this.addKBPEntity(objectSpan, objectLink)
                        // Add the relations
                        this.addKBPRelation(subjectSpan, objectSpan, relation)
                    }
                } // End KBP block
            } // End sentence loop

            //
            // Coreference
            //
            var corefEntities = []
            var corefRelations = []
            if (typeof data.corefs != 'undefined') {
                this.addRelationType('coref', true)
                this.addEntityType('COREF', 'Mention')
                var clusters = Object.keys(data.corefs)
                clusters.forEach(clusterId => {
                    var chain = data.corefs[clusterId]
                    if (chain.length > 1) {
                        for (let i = 0; i < chain.length; ++i) {
                            var mention = chain[i]
                            var id = 'COREF' + mention.id
                            var tokens = data.sentences[mention.sentNum - 1].tokens
                            corefEntities.push([
                                id,
                                'Mention',
                                [
                                    [
                                        tokens[mention.startIndex - 1].characterOffsetBegin,
                                        tokens[mention.endIndex - 2].characterOffsetEnd,
                                    ],
                                ],
                            ])
                            if (i > 0) {
                                var lastId = 'COREF' + chain[i - 1].id
                                corefRelations.push([
                                    'COREF' + chain[i - 1].id + '_' + chain[i].id,
                                    'coref',
                                    [
                                        ['governor', lastId],
                                        ['dependent', id],
                                    ],
                                ])
                            }
                        }
                    }
                })
            } // End coreference block

            //
            // Actually render the elements
            //
            this.embed('deps', this.posEntities, this.depsRelations)
        },
        embed(container, entities, relations) {
            this.utilEmbed(
                container,
                {
                    entity_types: this.entityTypes,
                    relation_types: this.relationTypes,
                },
                { text: this.currentText, entities: entities, relations: relations }
            )
        },
        unregisterHandlers(element) {
            // eslint-disable-next-line no-undef
            element.off()
            //console.log(element);
            /* console.log($("#deps").event);
      var tmp = {};
      if (element.jQuery351050590575794885741 != undefined) {
        element.jQuery351050590575794885741.forEach((el) => {
          tmp = el[el.length - 1];
          el = tmp;
        });
      } else if (element.jQuery351099887161471225451 != undefined) {
        element.jQuery351099887161471225451.forEach((el) => {
          tmp = el[el.length - 1];
          el = tmp;
        });
      } */
        },
        utilEmbed(container, collData, docData, webFontURLs) {
            // eslint-disable-next-line no-undef
            this.dispatcher = new Dispatcher()
            // eslint-disable-next-line no-undef
            this.visualizer = new Visualizer(this.dispatcher, container, webFontURLs)
            docData.collection = null
            this.dispatcher.post('collectionLoaded', [collData])
            this.dispatcher.post('requestRenderData', [docData])
            return this.dispatcher
        },
    },
    watch: {
        // selectedTab: function() {
        //   if (this.selectedTab == 0) {
        //     this.tabScroll =
        //       "transition-transform ease-out transform translate-x-0";
        //   } else if (this.selectedTab == 1) {
        //     this.tabScroll =
        //       "transition-transform ease-out transform translate-x-full";
        //   } else {
        //     this.tabScroll =
        //       "transition-transform ease-out transform translate-x-" +
        //       this.selectedTab.toString() +
        //       "full";
        //     if (this.selectedTab == 3) {
        //       setTimeout(() => {
        //         this.loadBrat();
        //       }, 100);
        //     }
        //   }
        // },
        isEditMode: function() {
            this.addEvents()
        },
        sentenceIndex: function() {
            // var doc = JSON.parse(localStorage.getItem("processedText"));

            // var pos = document.getElementsByClassName("span_default");
            // pos.forEach((el) => {
            //   //console.log(el.onmouseover);
            //   el.onclick.clear();
            //   el.ondblclick.clear();
            //   el.onmouseover.clear();
            // });

            // eslint-disable-next-line no-undef
            this.unregisterHandlers($('#deps'))
            // eslint-disable-next-line no-undef
            //this.unregisterHandlers($(document));
            // eslint-disable-next-line no-undef
            //this.unregisterHandlers($(window));
            document.getElementById('deps').innerHTML = ''
            document.getElementById('deps').className = ''
            this.resetVariables()

            this.loadBrat()
        },
    },
    beforeCreate() {
        this.$store.state.editableData = JSON.parse(localStorage.getItem('processedText'))
    },
}
</script>

<style scoped>
@import url('../css/brat.css');
</style>
