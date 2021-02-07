<template>
    <div id="deps"></div>
</template>

<script>
import { debounce } from 'debounce'
export default {
    name: 'dependencies',
    props: { refresh: Boolean },
    data() {
        return {
            screenWidth: 0,
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
        }
    },
    mounted() {
        this.screenWidth = window.innerWidth
        window.onresize = debounce(this.checkResize, 200)
        this.loadBrat()
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
            let sen = this.$store.state.editableData.sentences
            for (let i = 0; i < sen.length; i++) {
                for (let p = 0; p < sen[i]['basic-dependencies'].length; p++) {
                    if (sen[i]['basic-dependencies'][p].governorGloss == 'ROOT') {
                        for (let l = 0; l < tokens.length; l++) {
                            if (
                                tokens[l].getAttribute('data-span-id') ==
                                'POS_' + i + '_' + (sen[i]['basic-dependencies'][p].dependent - 1)
                            ) {
                                let arrowHeight = tokens[l].parentElement.parentElement.parentElement.getBBox().height
                                tokens[l].parentElement.append(
                                    this.createSvgElement('rect', {
                                        x:
                                            parseFloat(tokens[l].attributes.x.value) +
                                            parseFloat(tokens[l].getAttribute('width')) / 2,
                                        y: parseFloat(tokens[l].attributes.y.value) - arrowHeight + 15,
                                        //'dominant-baseline': 'auto',
                                        // 'text-anchor': 'middle',
                                        class: 'root',
                                        height: arrowHeight - 15,
                                        width: 0.5,
                                    })
                                )
                            }
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
            let phrase = this.$store.state.editableData.sentences[0]
            let x = []
            let y = []
            let tmp = 0
            let length = []
            let rect = []
            let g = []
            let text
            //choose the phrase
            for (let p = 0; p < this.$store.state.editableData.sentences.length; p++) {
                phrase = this.$store.state.editableData.sentences[p]
                for (let i = 0; i < phrase.tokens.length; i++) {
                    if (phrase.tokens[i].isMultiwordFirstToken) {
                        x = []
                        y = []
                        length = []
                        rect = []
                        g = []
                        for (let j = i; j < parseInt(phrase.tokens[i].multiwordSpan.split('-')[1]); j++) {
                            //if there is a multitoken, calculate the coordinates for the tag
                            // prettier-ignore
                            x.push(
                            parseFloat(
                                svg
                                    .getElementsByClassName('text')[0]
                                    .getElementsByTagName('text')[p]
                                    .getElementsByTagName('tspan')[j].attributes.x.value
                            )
                        )
                            // prettier-ignore
                            y.push(
                            parseFloat(
                                svg
                                    .getElementsByClassName('text')[0]
                                    .getElementsByTagName('text')[p]
                                    .getElementsByTagName('tspan')[j].attributes.y.value
                            ) + 6
                        )
                            if (j == parseInt(phrase.tokens[i].multiwordSpan.split('-')[1]) - 1) {
                                // prettier-ignore
                                tmp = svg
                                .getElementsByClassName('text')[0]
                                .getElementsByTagName('text')[p]
                                .getElementsByTagName('tspan')[j]
                                .getComputedTextLength()
                            }
                        }
                        //length of the tag
                        if (
                            svg
                                .getElementsByClassName('text')[0]
                                .getElementsByTagName('text')
                                // eslint-disable-next-line no-unexpected-multiline
                                [p].getElementsByTagName('tspan')[i + x.length - 1] == undefined
                        ) {
                            if (
                                svg
                                    .getElementsByClassName('text')[0]
                                    .getElementsByTagName('text')
                                    // eslint-disable-next-line no-unexpected-multiline
                                    [p].getElementsByTagName('tspan')[i + 1] == undefined
                            ) {
                                // prettier-ignore
                                y[0] == y[y.length-1]
                            ? (length.push(x[x.length-1] + tmp - x[0] - 3)) && (length.push(0))
                            : (length.push(svg.clientWidth - x[0])) &&
                            (length.push(parseFloat(
                                    svg
                                        .getElementsByClassName('text')[0]
                                        .getElementsByTagName('text')[p]
                                        .getElementsByTagName('tspan')[i].attributes.x.value
                                ) + parseFloat(svg
                                        .getElementsByClassName('text')[0]
                                        .getElementsByTagName('text')[p]
                                        .getElementsByTagName('tspan')[i].getComputedTextLength())))
                            } else {
                                // prettier-ignore
                                y[0] == y[y.length-1]
                            ? (length.push(x[x.length-1] + tmp - x[0])) && (length.push(0))
                            : (length.push(svg.clientWidth - x[0])) &&
                            (length.push(parseFloat(
                                    svg
                                        .getElementsByClassName('text')[0]
                                        .getElementsByTagName('text')[p]
                                        .getElementsByTagName('tspan')[i + 1].attributes.x.value
                                ) + parseFloat(svg
                                        .getElementsByClassName('text')[0]
                                        .getElementsByTagName('text')[p]
                                        .getElementsByTagName('tspan')[i + 1].getComputedTextLength())))
                            }
                        } else {
                            // prettier-ignore
                            y[0] == y[y.length-1]
                        ? (length.push(x[x.length-1] + tmp - x[0] - 2)) && (length.push(0))
                        : (length.push(svg.clientWidth - x[0])) &&
                          (length.push(parseFloat(
                                  svg
                                      .getElementsByClassName('text')[0]
                                      .getElementsByTagName('text')[p]
                                      .getElementsByTagName('tspan')[i + x.length - 1].attributes.x.value)
                              + parseFloat(svg
                                        .getElementsByClassName('text')[0]
                                        .getElementsByTagName('text')[p]
                                        .getElementsByTagName('tspan')[i + x.length - 1].getComputedTextLength()) + 3))
                        }
                        g.push(this.createSvgElement('g', { class: 'multiWord' }))
                        rect.push(
                            this.createSvgElement('rect', {
                                x: x[0],
                                y: y[0],
                                width: length[0],
                                height: 5,
                                fill: 'red',
                            })
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
                                    width: length[1] - 40,
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
                            this.createSvgElement('svg', { width: length[0], height: 50, x: x[0], y: y[0] })
                        )
                        g[0].lastChild.appendChild(text)
                        svg.appendChild(g[0])
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
                this.render(JSON.parse(localStorage.getItem('processedText')))
            } else {
                setTimeout(() => {
                    document.getElementById('deps').innerHTML = ''
                    document.getElementById('deps').className = ''
                    this.embed('deps', this.posEntities, this.depsRelations)
                }, 50)
            }
            setTimeout(() => {
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

                /*
                //Adding multiword dependencies
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

                // Lemmas and multiword dependency
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
            // eslint-disable-next-line no-undef
            Util.embed(
                container,
                {
                    entity_types: this.entityTypes,
                    relation_types: this.relationTypes,
                },
                { text: this.currentText, entities: entities, relations: relations }
            )
        },
    },
    watch: {
        refresh() {
            if (this.refresh == true) {
                this.loadBrat()
            }
        },
    },
}
</script>

<style scoped></style>
