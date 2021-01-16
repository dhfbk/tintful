<template>
    <div class="mt-4">
        <div class="font-bold text-lg">Legend</div>
        <div :class="'grid grid-rows-' + nerDesc.length + 'grid-flow-col mb-6'">
            <div v-for="type in nerDesc" :key="type">
                <div v-if="type != 'O'">
                    <div class="h-3 w-3 rounded-full inline-block" :class="'bg-' + type"></div>
                    {{ type }}
                </div>
            </div>
        </div>
        <div class="divide-y divide-primary divide-opacity-75">
            <div v-for="sen in localData.sentences" :key="sen.index" class="">
                <div class="flex flex-row flex-wrap">
                    <span
                        v-for="(token, cont) in sen.tokens"
                        :key="token.index + token.ner"
                        @click="changeNer(sen.index, cont)"
                        class="flex flex-col place-items-center my-2 cursor-pointer"
                    >
                        <span
                            class="mx-1 px-1 rounded select-none"
                            :class="[
                                token.ner == 'PER'
                                    ? 'text-black bg-PER'
                                    : token.ner == 'ORG' || token.ner == 'LOC'
                                    ? 'text-white bg-' + token.ner
                                    : 'bg-gray-350 dark:bg-gray-600 text-black dark:text-white',
                            ]"
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
            nerDesc: ['O', 'PER', 'ORG', 'LOC'],
            localData: this.$store.state.editableData,
        }
    },
    created() {
        for (var i = 0; i < this.$store.state.editableData.sentences.length; i++) {
            this.ner.i = {}
            for (var x = 0; x < this.$store.state.editableData.sentences[i].tokens.length; x++) {
                this.ner.i.x = this.$store.state.editableData.sentences[i].tokens[x].ner
            }
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
            this.localData.sentences[i].tokens[cont].ner = this.$store.state.editableData.sentences[i].tokens[cont].ner
        },
    },
}
</script>

<style></style>
