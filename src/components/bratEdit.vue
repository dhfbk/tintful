<template>
    <div>
        <div id="deps" class="select-none"></div>
    </div>
</template>

<script>
import { debounce } from 'debounce'
export default {
    name: 'bratEdit',
    props: { sentenceIndex: Number, doc: Object, refresh: Boolean },
    data() {
        return {
            screenWidth: 0,
            editRoot: false,
            isEditMode: false,
            dispatcher: undefined,
            visualizer: undefined,
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
            //
            // Construct text of annotation
            //
            currentText: [], // GLOBAL
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
            sonId: '',
            newFatherId: '',
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
        }
    },
    mounted() {
        this.screenWidth = window.innerWidth
        window.onresize = debounce(this.checkResize, 200)
        setTimeout(() => {
            this.loadBrat()
        }, 20)
    },
    beforeDestroy() {
        window.onresize = null
    },
    methods: {
        checkResize() {
            if (this.screenWidth != window.innerWidth) {
                this.screenWidth = window.innerWidth
                this.loadBrat()
            }
        },
        addRoot() {
            let svg = document.getElementById('deps').children[0]
            let tokens = svg.getElementsByClassName('span_default')
            let sen = this.$store.state.editableData.sentences[this.sentenceIndex]
            for (let p = 0; p < sen['basic-dependencies'].length; p++) {
                if (sen['basic-dependencies'][p].governorGloss == 'ROOT') {
                    for (let l = 0; l < tokens.length; l++) {
                        if (
                            tokens[l].getAttribute('data-span-id') ==
                            'POS_0' + '_' + (sen['basic-dependencies'][p].dependent - 1)
                        ) {
                            let arrowHeight = tokens[l].parentElement.parentElement.parentElement.getBBox().height

                            tokens[l].parentElement.append(
                                this.createSvgElement('rect', {
                                    x:
                                        parseFloat(tokens[l].attributes.x.value) +
                                        parseFloat(tokens[l].getAttribute('width')) / 2,
                                    y: parseFloat(tokens[l].attributes.y.value) - arrowHeight + 15,
                                    'dominant-baseline': 'middle',
                                    'text-anchor': 'middle',
                                    class: 'root',
                                    height: arrowHeight - 15,
                                    width: 0.5,
                                })
                            )
                        }
                    }
                }
            }
        },
        addMultiWord() {
            //get the brat svg
            let svg = document.getElementById('deps').children[0]
            //remove 4 px to the y of every background and add 2px to the last background height
            svg.getElementsByClassName('background')[0].children.forEach(el => {
                el.setAttribute('y', parseFloat(el.getAttribute('y')) - 4)
            })
            svg.getElementsByClassName('background')[0].lastChild.setAttribute(
                'height',
                parseFloat(svg.getElementsByClassName('background')[0].lastChild.getAttribute('height')) + 2
            )
            //choose the phrase
            let phrase = this.$store.state.editableData.sentences[this.sentenceIndex]
            let x = []
            let y = []
            let tmp = 0
            let length = []
            let rect = []
            let g = []
            let text
            let n = 0
            let p = 0
            let f = 0
            let id = 0
            for (let i = 0; i < phrase.tokens.length; i++) {
                if (phrase.tokens[i].isMultiwordFirstToken) {
                    x = []
                    y = []
                    length = []
                    rect = []
                    g = []
                    id = parseInt(phrase.tokens[i].multiwordSpan.split('-')[0])
                    for (let j = i; j < parseInt(phrase.tokens[i].multiwordSpan.split('-')[1]); j++) {
                        //if there is a multitoken, calculate the coordinates for the tag
                        //the number removed from the x is the same as the width in the visualizer for the span
                        // prettier-ignore
                        x.push(
                            parseFloat(
                                svg
                                    .getElementsByClassName('text')[0]
                                    .getElementsByTagName('text')[0]
                                    .getElementsByTagName('tspan')[j].attributes.x.value
                            ) - 8
                        )
                        // prettier-ignore
                        y.push(
                            parseFloat(
                                svg
                                    .getElementsByClassName('text')[0]
                                    .getElementsByTagName('text')[0]
                                    .getElementsByTagName('tspan')[j].attributes.y.value
                            ) + 6
                        )
                        if (j == parseInt(phrase.tokens[i].multiwordSpan.split('-')[1]) - 1) {
                            // prettier-ignore
                            tmp = svg
                                .getElementsByClassName('text')[0]
                                .getElementsByTagName('text')[0]
                                .getElementsByTagName('tspan')[j]
                                .getComputedTextLength()
                        }
                    }
                    //length of the tag
                    if (
                        svg
                            .getElementsByClassName('text')[0]
                            .getElementsByTagName('text')[0]
                            .getElementsByTagName('tspan')[i + x.length] == undefined
                    ) {
                        n = 16
                        p = x.length - 1
                        f = 13
                    } else {
                        n = 13
                        p = x.length - 1
                        f = 10
                    }
                    // prettier-ignore
                    y[0] == y[y.length-1]
                    ? (length.push(x[x.length-1] + tmp - x[0] + n)) && (length.push(0))
                    : (length.push(svg.clientWidth - x[0])) &&
                        (length.push(parseFloat(
                                svg
                                    .getElementsByClassName('text')[0]
                                    .getElementsByTagName('text')[0]
                                    .getElementsByTagName('tspan')[i + p].attributes.x.value)
                            + parseFloat(svg
                                    .getElementsByClassName('text')[0]
                                    .getElementsByTagName('text')[0]
                                    .getElementsByTagName('tspan')[i + p].getComputedTextLength()) + f))
                    g.push(this.createSvgElement('g', { class: 'multiWord' }))
                    rect.push(
                        this.createSvgElement('rect', { x: x[0], y: y[0], width: length[0], height: 5, fill: 'red' })
                    )
                    //word under the red line
                    text = this.createSvgElement('text', {
                        x: '50%',
                        y: 15,
                        'dominant-baseline': 'middle',
                        'text-anchor': 'middle',
                    })
                    text.textContent = phrase.tokens[i].originalText
                    if (length[1] !== 0) {
                        g.push(this.createSvgElement('g', { class: 'multiWord' }))
                        rect.push(
                            this.createSvgElement('rect', {
                                x: 21,
                                y: y[y.length - 1],
                                width: length[1] - 26,
                                height: 5,
                                fill: 'red',
                            })
                        )
                        g[1].appendChild(rect[1])
                        svg.appendChild(g[1])
                    }
                    //append everything
                    g[0].appendChild(rect[0])
                    g[0].appendChild(
                        this.createSvgElement('svg', {
                            width: length[0],
                            height: 25,
                            x: x[0],
                            y: y[0],
                            class: 'textSvg',
                            'first-id': id,
                        })
                    )
                    g[0].lastChild.appendChild(text)
                    svg.appendChild(g[0])
                    this.addMWEvents(svg.parentElement, id, i, x.length)
                }
            }
        },
        addMWEvents(svg, id, arrPos, len) {
            var txt = svg.getElementsByClassName('textSvg')
            var vm = this
            var emitArr = [arrPos, len]
            for (let i = 0; i < txt.length; i++) {
                if (txt[i].getAttribute('first-id') == id) {
                    txt[i].onclick = function() {
                        vm.$emit('mwModal', emitArr)
                    }
                }
            }
        },
        createSvgElement(tag, attrs) {
            var el = document.createElementNS('http://www.w3.org/2000/svg', tag)
            for (var k in attrs) {
                el.setAttribute(k, attrs[k])
            }
            return el
        },
        editedDep() {
            document.getElementById('deps').innerHTML = ''
            document.getElementById('deps').className = ''
            this.resetVariables()
            this.loadBrat()
        },
        resetVariables() {
            this.entityTypesSet = {}
            this.entityTypes = []
            this.editRoot = false
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
            this.isEditMode = false
        },
        isGovernor(el) {
            return el.index == this.newFatherId
        },
        handleDbl(e) {
            var i = e.target
            var infos = i.getAttribute('data-span-id').split('_')
            var d = this.$store.state.editableData
            var feat = d.sentences[this.sentenceIndex].tokens[infos[2]]

            let infoToEdit = {}
            infoToEdit.senIndex = this.sentenceIndex
            infoToEdit.tokIndex = infos[2]
            infoToEdit.feats = feat.features
            infoToEdit.lemma = feat.lemma
            infoToEdit.pos = feat.pos
            infoToEdit.word = feat.word
            this.$emit('showFeatsModal', infoToEdit, 'brat')
            //console.log(infoToEdit)
        },
        dblRoot(e) {
            var i = e.target
            var infos = parseInt(i.getAttribute('data-chunk-id'))
            // eslint-disable-next-line no-unused-vars
            var text = i.textContent
            var dep = this.$store.state.editableData.sentences[this.sentenceIndex]['basic-dependencies']
            // for (let x = 0; x < dep.length; x++) {
            //     if (dep[x].dep == 'ROOT') {
            dep.unshift({
                dep: 'ROOT',
                dependent: infos + 1,
                dependentGloss: text.slice(0, -1),
                governor: 0,
                governorGloss: 'ROOT',
            })
            // dep[x].dependent = infos + 1
            // dep[x].dependentGloss = text.slice(0, -1)
            //         break
            //     }
            // }
            for (let x = 0; x < dep.length; x++) {
                if (dep[x].dependent == infos + 1 && dep[x].dep != 'ROOT') {
                    dep.splice(x, 1)
                }
            }
            document.getElementById('deps').innerHTML = ''
            document.getElementById('deps').className = ''
            this.resetVariables()
            this.loadBrat()
            this.isEditMode = false
            this.$emit('edited')
            this.$emit('noRoot', 'false')
        },
        handleRight(e) {
            if (!this.isEditMode) {
                let i = e.target
                i.parentNode.children[1].setAttribute('fill', 'white')
                i.parentNode.children[0].setAttribute('fill', '#688e26')
                var infos = i.getAttribute('data-span-id').split('_')
                this.sonId = parseInt(infos[2]) + 1
                //('sono nel primo')
                this.isEditMode = true
            } else {
                //this.isEditMode = false
                document.getElementById('deps').innerHTML = ''
                document.getElementById('deps').className = ''
                this.resetVariables()
                this.loadBrat()
                //console.log('sono nel secondo')
            }
        },
        handleClick(e) {
            // console.log("siamo dentro l'evento click")

            var i = e.target
            i.parentNode.children[1].setAttribute('fill', 'white')
            i.parentNode.children[0].setAttribute('fill', '#688e26')
            var infos = i.getAttribute('data-span-id').split('_')
            this.newFatherId = parseInt(infos[2]) + 1
            var d = this.$store.state.editableData
            var dep = d.sentences[this.sentenceIndex]['basic-dependencies']

            //checking loop
            let msg = ''
            let gov = []
            let cont = 0
            let found = false
            let stop = false
            // for (let i = 0; i < dep.length; i++) {
            //     if (dep[i].dependent == this.sonId && dep[i].dep == 'ROOT') {
            //         cont = -1
            //         msg = "A ROOT element can't have a head"
            //         break
            //     }
            // }
            while (cont != -1 && !stop) {
                if (!found) {
                    if (dep[cont].dependent == this.newFatherId) {
                        gov.push(dep[cont].governor)
                        found = true
                        cont = 0
                    } else {
                        cont++
                    }
                } else {
                    if (this.sonId == gov[gov.length - 1]) {
                        stop = true
                        cont = -1
                    } else {
                        gov.forEach(el => {
                            cont = 0
                            if (cont != -1) {
                                while (cont < dep.length && cont != -1) {
                                    if (el == dep[cont].dependent) {
                                        if (dep[cont].governor == this.sonId) {
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
                for (let x = 0; x < dep.length; x++) {
                    if (dep[x].dependent == this.sonId) {
                        if (dep[x].dep != 'ROOT') {
                            dep[x].governor = this.newFatherId
                            dep[x].governorGloss = d.sentences[this.sentenceIndex].tokens.find(this.isGovernor).word
                            //console.log(dep[x].dep)
                        } else {
                            dep[x].governor = this.newFatherId
                            dep[x].governorGloss = d.sentences[this.sentenceIndex].tokens.find(this.isGovernor).word
                            dep[x].dep = 'choosing...'
                            this.$emit('showDepsModal', {
                                gov: this.newFatherId,
                                dep: dep[x].dependent,
                            })
                        }
                        break
                    }
                }
                //this.$store.state.editableData = d
                this.$emit('edited')
            } else {
                msg == '' ? (msg = 'Loop detected. Choose another head') : null
                this.$emit('snack', msg)
            }

            document.getElementById('deps').innerHTML = ''
            document.getElementById('deps').className = ''
            this.resetVariables()
            this.loadBrat()
            this.isEditMode = false
            // if (dep[x].dep == 'ROOT') {
            //     this.$emit('snack', "Can't change head of a ROOT element")
            // } else {
            // }
            d = this.$store.state.editableData.sentences[this.sentenceIndex]['basic-dependencies']
            let hasRoot = 0
            for (let x = 0; x < d.length; x++) {
                if (d[x].dep == 'ROOT') {
                    hasRoot++
                }
            }
            hasRoot == 0 ? this.$emit('noRoot', 'true') : ''
        },
        addEvents() {
            //console.log('siamo dentro la funzione che inserisce gli eventi')
            var x = document.getElementById('deps')
            var pos = x.getElementsByClassName('span_default')
            if (this.isEditMode) {
                pos.forEach(el => {
                    el.onclick = this.handleClick
                })
            }
        },
        loadBrat() {
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

                let doc = this.$store.state.editableData
                this.render({
                    sentences: [doc.sentences[this.sentenceIndex]],
                })
            } else {
                setTimeout(() => {
                    document.getElementById('deps').innerHTML = ''
                    document.getElementById('deps').className = ''
                    this.embed('deps', this.posEntities, this.depsRelations)
                }, 50)
            }
            var x = document.getElementById('deps')
            var pos = x.getElementsByClassName('span_default')
            var depend = x.getElementsByClassName('arcs')
            var words = x.getElementsByTagName('tspan')

            x.addEventListener(
                'contextmenu',
                function(e) {
                    e.preventDefault()
                },
                true
            )
            setTimeout(() => {
                pos.forEach(el => {
                    //console.log(el);
                    el.oncontextmenu = this.handleRight
                    el.ondblclick = this.handleDbl
                })
                words.forEach(el => {
                    el.ondblclick = this.dblRoot
                })
                //console.log(depend);
                depend.forEach(el => {
                    //console.log(el.children);
                    el.children.forEach(p => {
                        //console.log(p.children[0]);
                        p.children[0].ondblclick = e => {
                            let i = e.target
                            this.$emit('showDepsModal', i)

                            //console.log(i.getAttribute('data-arc-role'))
                            //console.log(i.getAttribute('data-arc-origin').split('_'))
                            //console.log(i.getAttribute('data-arc-target').split('_'))
                        }
                    })
                })
                this.addMultiWord()
                this.addRoot()
            }, 200)
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

                //Adding multiword dependencies
                /*
                for (let i = 0; i < this.tokens.length; i++) {
                    if (this.tokens[i].isMultiwordFirstToken) {
                        sentence['basic-dependencies'].push({
                            dep: 'mwe',
                            dependent: this.tokens[i].multiwordSpan.split('-')[0],
                            dependentGloss: this.tokens[i].lemma,
                            governor: this.tokens[i].multiwordSpan.split('-')[1],
                            governorGloss: this.tokens[this.tokens[i].multiwordSpan.split('-')[1] - 1].lemma,
                        })
                    }
                }
                */

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
                    //console.log('CI SONO')
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
            element.off()
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
        sentenceIndex: function() {
            // eslint-disable-next-line no-undef
            this.unregisterHandlers($('#deps'))
            document.getElementById('deps').innerHTML = ''
            document.getElementById('deps').className = ''
            this.resetVariables()

            this.loadBrat()
        },
        isEditMode: function() {
            this.addEvents()
        },
        refresh() {
            if (this.refresh == true) {
                this.editedDep()
            }
        },
    },
}
</script>

<style scoped>
@import url('../css/brat.css');
</style>
