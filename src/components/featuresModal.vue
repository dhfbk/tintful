<template>
    <transition name="fade-bg" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-25 customZ"
            @click="toggleModal"
        >
            <transition name="fade" appear>
                <div
                    class="bg-white rounded-lg w-5/6 max-w-3xl max-h-96 h-auto overflow-y-auto overscroll-none"
                    @click.stop
                    v-if="showDialog"
                >
                    <div class="p-4">
                        <div class="flex w-full">
                            <div class="text-primary font-bold text-lg text-primary">Edit token features</div>
                            <span class="ripple ml-auto rounded hover:bg-gray-200 p-1" @click="toggleModal()">
                                <svg
                                    class="m-auto fill-current text-gray-700 w-6 h-6 cursor-pointer"
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
                        <div class="py-2">
                            <span class="">{{ featsToEdit }} </span>
                            <transition name="zoom">
                                <select
                                    class="appearance-none font-medium h-full border-b   block appearance-none bg-white border-gray-400 text-gray-700 py-1 pl-2 pr-12 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    name="depsSelect"
                                    id="depsSelect"
                                    @change="gender"
                                    v-model="gen"
                                    v-if="showGen"
                                >
                                    <option v-for="(gen, i) in params.Gender" :key="i" :value="gen" class="">
                                        {{ gen }}
                                    </option>
                                </select>
                            </transition>
                            <transition name="zoom">
                                <select
                                    class="appearance-none font-medium h-full border-b  block appearance-none bg-white border-gray-400 text-gray-700 py-1 pl-2 pr-12 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    name="depsSelect"
                                    id="depsSelect"
                                    @change="number"
                                    v-model="num"
                                    v-if="showNum"
                                >
                                    <option v-for="(num, i) in params.Number" :key="i" :value="num" class="">
                                        {{ num }}
                                    </option>
                                </select>
                            </transition>
                            <transition name="zoom">
                                <select
                                    class="appearance-none font-medium h-full border-b  block appearance-none bg-white border-gray-400 text-gray-700 py-1 pl-2 pr-12 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    name="depsSelect"
                                    id="depsSelect"
                                    @change="tense"
                                    v-model="ten"
                                    v-if="showTen"
                                >
                                    <option v-for="(ten, i) in params.Tense" :key="i" :value="ten" class="">
                                        {{ ten }}
                                    </option>
                                </select>
                            </transition>
                            <div @click="showGen = true">show</div>
                        </div>
                        <div class="float-right pb-4">
                            <button
                                class="font-medium ripple transition-colors duration-100 ease-out hover:bg-gray-200 focus:outline-none bg-transparent text-text-primary py-2 px-4 rounded mr-2"
                                @click="toggleModal()"
                            >
                                CANCEL
                            </button>
                            <button
                                class="font-medium text-primary ripple transition-colors duration-100 ease-out hover:bg-gray-200 focus:outline-none bg-transparent py-2 px-4 rounded"
                                @click="save()"
                            >
                                SAVE
                            </button>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </transition>
</template>

<script>
export default {
    props: {
        featsToEdit: Object,
    },
    data() {
        return {
            showDialog: false,
            gen: '',
            num: '',
            ten: '',
            form: '',
            per: '',
            mood: '',
            deg: '',
            pro: '',
            showGen: false,
            showNum: false,
            showTen: false,
            showForm: false,
            showPer: false,
            showMood: false,
            showDeg: false,
            showPronType: false,
            showPoss: false,
            showClic: false,
            params: {
                Gender: ['Fem', 'Masc'],
                Number: ['Sing', 'Plur'],
                Tense: ['Pres', 'Fut', 'Imp', 'Past'],
                VerbForm: ['Fin', 'Inf', 'Part', 'Ger'],
                Person: ['1', '2', '3'],
                Mood: ['Ind', 'Imp', 'Cnd', 'Sub'],
                Degree: ['Cmp', 'Sup', 'Abs'],
                PronType: ['Prs', 'Art', 'Int', 'Rel', 'Dem', 'Tot', 'Neg', 'Ind', 'Exc', 'Predet', 'Ord'],
                Clitic: ['Yes', 'No'],
                Poss: ['Yes', 'No'],
                Definite: ['Ind', 'Def'],
            },
        }
    },
    methods: {
        toggleModal() {
            this.$emit('closeFeatsModal')
        },
        save() {
            this.$emit('closeFeatsModal')
        },
        gender(i) {
            console.log(i)
        },
        number(i) {
            console.log(i)
        },
        tense(i) {
            console.log(i)
        },
    },
    created() {
        setTimeout(() => {
            this.showDialog = true
        }, 1)
        this.gen = this.featsToEdit.feats.Gender == undefined ? '' : this.featsToEdit.feats.Gender[0]
        this.num = this.featsToEdit.feats.Number == undefined ? '' : this.featsToEdit.feats.Number[0]
        this.ten = this.featsToEdit.feats.Tense == undefined ? '' : this.featsToEdit.feats.Tense[0]
        this.form = this.featsToEdit.feats.VerbForm == undefined ? '' : this.featsToEdit.feats.VerbForm[0]
        this.per = this.featsToEdit.feats.Person == undefined ? '' : this.featsToEdit.feats.Person[0]
        this.mood = this.featsToEdit.feats.Mood == undefined ? '' : this.featsToEdit.feats.Mood[0]
        this.deg = this.featsToEdit.feats.Degree == undefined ? '' : this.featsToEdit.feats.Degree[0]

        switch (this.featsToEdit.pos) {
            case 'A':
                this.showGen = true
                this.showNum = true
                if (this.deg != '') this.showDeg = true
                break
            case 'AP':
                this.showGen = true
                this.showNum = true
                this.showPronType = true
                this.showPoss = true
        }
    },
}
</script>

<style scoped>
.customZ {
    z-index: 990;
}
</style>
