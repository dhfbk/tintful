<template>
    <transition name="fade-bg" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-30 z-40 transition-opacity"
        >
            <transition name="fade" appear>
                <div
                    class=" bg-white rounded w-2/3 max-w-3xl max-h-80 overflow-y-auto focus:outline-none p-4 shadow-lg z-50"
                    v-if="showDialog"
                >
                    <div>
                        <div class="text-lg mb-2 font-medium">Edit token features</div>
                        <span class="">{{ featsToEdit }} </span>
                        <select
                            class="appearance-none font-medium h-full border-b  inline-block appearance-none bg-white border-gray-400 text-gray-700 py-1 pl-2 pr-12 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            name="depsSelect"
                            id="depsSelect"
                            @change="gender"
                            v-model="gen"
                        >
                            <option v-for="(gen, i) in params.Gender" :key="i" :value="gen" class="">
                                {{ gen }}
                            </option>
                        </select>
                    </div>
                    <div class="float-right">
                        <button
                            class="font-medium ripple transition duration-100 ease-out hover:bg-gray-200 focus:outline-none mt-2 xs2:mt-0 xs2:ml-2 bg-transparent text-text-primary  py-2 px-4 rounded mr-2"
                            @click="toggleModal()"
                        >
                            CANCEL
                        </button>
                        <button
                            class="font-medium text-blue-700 ripple transition duration-100 ease-out hover:bg-gray-200 focus:outline-none mt-2 xs2:mt-0 xs2:ml-2 bg-transparent py-2 px-4 rounded"
                            @click="save()"
                        >
                            SAVE
                        </button>
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
            params: {
                Gender: ['Femm', 'Masc'],
                Number: ['Sing', 'Plur'],
                Tense: ['Present', 'Future', 'Imperfect', 'Past'],
                VerbForm: ['Inf', 'Part', 'Fin'],
                Person: ['1', '2', '3'],
                Mood: ['Ind'],
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
        gender(bro) {
            console.log(bro)
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
    },
}
</script>

<style></style>
