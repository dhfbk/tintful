<template>
    <div class="mt-4">
        <div class="font-bold text-lg">Legend</div>
        <div :class="'grid grid-rows-' + nerDesc.length + 'grid-flow-col mb-6'">
            <div v-for="(i, x) in Object.keys(legend)" :key="x">
                <div v-if="i != 'O'">
                    <div
                        class="h-3 w-3 rounded-full inline-block"
                        :style="{ background: Object.values(legend)[x] }"
                    ></div>
                    {{ i }}
                </div>
            </div>
        </div>
        <div class="divide-y divide-primary divide-opacity-75">
            <div v-for="sen in $store.state.editableData.sentences" :key="sen.index" class="">
                <div class="flex flex-row flex-wrap">
                    <span
                        v-for="(token, cont) in sen.tokens"
                        :key="token.index"
                        @click="changeNer(sen.index, cont)"
                        class="flex flex-col place-items-center my-2 cursor-pointer"
                    >
                        <span
                            class="mx-1 px-1 rounded select-none"
                            :class="[
                                token.ner == 'PER'
                                    ? 'text-black'
                                    : token.ner == 'ORG' || token.ner == 'LOC'
                                    ? 'text-white'
                                    : 'bg-gray-350 dark:bg-gray-600 text-black dark:text-white',
                            ]"
                            :style="{
                                background:
                                    token.ner == 'PER' || token.ner == 'ORG' || token.ner == 'LOC'
                                        ? legend[token.ner]
                                        : '',
                            }"
                        >
                            {{ token.word }}
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ner',
    data() {
        return {
            keys: [],
            values: [],
            ner: {},
            legend: {},
            nerDesc: ['O', 'PER', 'ORG', 'LOC'],
            colors: ['rgba(243, 244, 246, 1)', 'ffbf00', 'e83f6f', '2274a5'],
        }
    },
    created() {
        for (var i = 0; i < this.$store.state.editableData.sentences.length; i++) {
            this.ner.i = {}
            for (var x = 0; x < this.$store.state.editableData.sentences[i].tokens.length; x++) {
                this.ner.i.x = this.$store.state.editableData.sentences[i].tokens[x].ner
            }
        }
        for (let i = 0; i < this.nerDesc.length; i++) {
            this.legend[this.nerDesc[i]] = '#' + this.colors[i]
        }
    },
    methods: {
        changeNer(i, cont) {
            switch (this.$store.state.editableData.sentences[i].tokens[cont].ner) {
                case 'O':
                    this.$store.state.editableData.sentences[i].tokens[cont].ner = 'PER'
                    this.$emit('edited')
                    break
                case 'PER':
                    this.$store.state.editableData.sentences[i].tokens[cont].ner = 'ORG'
                    this.$emit('edited')
                    break
                case 'ORG':
                    this.$store.state.editableData.sentences[i].tokens[cont].ner = 'LOC'
                    this.$emit('edited')
                    break
                case 'LOC':
                    this.$store.state.editableData.sentences[i].tokens[cont].ner = 'O'
                    this.$emit('edited')
                    break
            }
        },
    },
}
</script>

<style></style>
