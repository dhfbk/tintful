<template>
    <transition name="fade-bg" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800  bg-opacity-25 customZ"
            @click="modal"
        >
            <transition name="fade" appear>
                <div
                    class="bg-white dark:bg-bgDark rounded-lg w-5/6 max-w-3xl max-h-96 h-auto overflow-y-auto overscroll-none"
                    @click.stop
                >
                    <div class="flex flex-col p-4">
                        <div class="flex w-full">
                            <div class="text-primary font-medium text-lg">Help</div>
                            <span
                                class="ripple ml-auto rounded hover:bg-gray-200 dark:hover:bg-dark01dp p-1"
                                @click="modal"
                            >
                                <svg
                                    class="m-auto fill-current text-gray-700 dark:text-gray-200 w-6 h-6 cursor-pointer"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 18 18"
                                >
                                    <path
                                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                                    />
                                </svg>
                                <span class="sr-only">Close dialog</span>
                            </span>
                        </div>
                        <div class="py-2" v-if="type == 'results'">
                            <p>About the colors:</p>
                            <ul class="px-4">
                                <li class="list-disc" v-for="(key, value, count) in colors" :key="key">
                                    If the color is <span :style="'color:' + key">{{ value }}</span
                                    >, it means that {{ meanings[count] }}
                                </li>
                            </ul>
                            <p class="mt-2">About the values' meaning:</p>
                            <ul class="px-4" v-if="mode == 'readability'">
                                <li class="list-disc" v-for="(key, value, count) in readabilityInfo" :key="count">
                                    <b>{{ value }}</b
                                    >: {{ key }}
                                </li>
                            </ul>
                            <ul class="px-4" v-else>
                                <li class="list-disc" v-for="(key, value, count) in difficultyInfo" :key="count">
                                    <b>{{ value }}</b
                                    >: {{ key }}
                                </li>
                            </ul>
                        </div>
                        <div class="py-2" v-else v-html="text"></div>
                    </div>
                </div>
            </transition>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'modalInfo',
    props: { mode: String, type: String },
    data() {
        return {
            colors: {
                green: '#15ff00',
                yellow: '#ffbb00',
                red: '#ff0000',
            },
            meanings: [
                'the value is in the norm',
                'the value is slightly out of the norm',
                'the value is out of the norm',
            ],
            readabilityInfo: {
                'Subordinate ratio': 'relationship between subordinate and coordinated propositions',
                'Semantic richness':
                    'multidimensional construct reflecting the extent of variability in the information associated with a word’s meaning',
                'Lexical density':
                    'estimates the linguistic complexity in a written or spoken composition from the functional words (grammatical units) and content words (lexical units, lexemes)',
            },
            difficultyInfo: {
                Gulpease:
                    'readability index for the Italian language. It does not take into consideration the content of the text, but technical information such as the length of sentences and words: less than 80 means difficult for a person at elementary school; less than 60 means difficult for a person at medium school; less than 40 means difficult for a person at high school',
                'Level 1':
                    'how difficult the text is for a user knowing only the 500 easiest words in Italian (elementary school)',
                'Level 2':
                    'how difficult the text is for a user knowing only the 2500 easiest words in Italian (middle school)',
                'Level 3':
                    'how difficult the text is for a user knowing only the 5000 easiest words in Italian (high school)',
            },
            text: '',
        }
    },
    methods: {
        modal() {
            this.$emit('modal')
        },
    },
    created() {
        if (this.type == 'graph') {
            this.text =
                '<p class="font-medium">Dependencies actions:</p>' +
                '<ul class="list-disc px-4">' +
                '<li>to change the head of a current dependency, right click on the dependent and then left click on the new head</li>' +
                '<li>to add a new dependency, right click on the element that should become the dependent and then left click on the new head</li>' +
                "<li>to edit the type of a dependency, double click on the dependency's type in the middle of the arrow and use the modal to choose the new one</li>" +
                '</ul>' +
                '<br/><p class="font-medium">Features\' actions:</p>' +
                '<ul class="list-disc px-4">' +
                "<li>to edit a token's features double click on the token's Part Of Speech label and use the modal to do your editing</li>" +
                '</ul>'
        } else if (this.type == 'table') {
            this.text =
                "To edit values in the table, simply change the text where the field is an open one, select another option in the dropdown fields, or use the modal where it's needed"
        } else {
            this.text =
                'To change the NER value of a certain token, simply click on it and the color will change according to the new value. You can check the legend to see what value each color refers to.'
        }
    },
}
</script>

<style scoped>
.customZ {
    z-index: 990;
}
</style>
