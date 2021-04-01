<template>
    <transition name="fade-bg" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full min-h-screen dark:bg-black bg-gray-900 bg-opacity-25 customZ"
            @click="toggleModal"
        >
            <transition name="fade" appear>
                <div
                    class="bg-white dark:bg-bgDark rounded-lg w-5/6 max-w-xl max-h-96 h-auto overflow-y-auto overscroll-none"
                    @click.stop
                    v-if="!wait"
                >
                    <div class="p-4">
                        <div class="flex w-full mb-2">
                            <div class="text-primary dark:text-primaryLight font-bold text-lg">
                                Manage multiword tokens
                            </div>
                            <button
                                class="ripple ml-auto rounded hover:bg-gray-200 dark:hover:bg-gray-600 p-1 focus:outline-none"
                                @click="toggleModal()"
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
                            </button>
                        </div>
                        <div v-if="current.length != 0">
                            <div
                                class="flex flex-col sm:flex-row items-center content-center mb-1 justify-between"
                                v-for="(mw, i) in current"
                                :key="i"
                            >
                                <div
                                    class="flex flex-col justify-center md:flex-row md:justify-between items-center content-center gap-1 flex-wrap w-full"
                                >
                                    <span
                                        class="flex flex-row items-center content-center gap-1 flex-wrap justify-center md:justify-start mx-auto md:mx-0"
                                    >
                                        <span class="flex flex-row items-center content-center gap-1 mt-1 sm:mt-0">
                                            <p>Start</p>
                                            <div class="relative" v-if="mw.start == '' || mw.start == undefined">
                                                <select
                                                    :name="'start' + mw.start"
                                                    :id="'start' + mw.start"
                                                    v-model="mw.start"
                                                    @change="editData('start', mw.start)"
                                                    class="w-max inline-block border border-primary appearance-none pl-1 pr-4 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                                                >
                                                    <option
                                                        v-for="(s, i) in availableStarts"
                                                        :key="'start' + i"
                                                        :value="s"
                                                    >
                                                        {{ s }}
                                                    </option>
                                                </select>
                                                <div
                                                    class="pointer-events-none absolute pin-y pin-r flex items-center pl-2 text-gray-900"
                                                >
                                                    <svg
                                                        class="h-4 w-4 fill-current text-gray-900 dark:text-gray-200"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                                        />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div v-else>
                                                <input
                                                    type="text"
                                                    class="w-28 cursor-not-allowed px-1 border border-primary bg-gray-100 dark:bg-gray-700 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                                                    v-model="mw.start"
                                                    disabled
                                                />
                                            </div>
                                        </span>
                                        <span class="flex flex-row items-center content-center gap-1 mt-1 sm:mt-0">
                                            <p>End</p>
                                            <div class="relative">
                                                <select
                                                    :name="'end' + mw.end"
                                                    :id="'end' + mw.end"
                                                    v-model="mw.end"
                                                    @change="editData('end')"
                                                    class="w-max inline-block border border-primary appearance-none pl-1 pr-4 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                                                >
                                                    <option
                                                        v-for="(e, i) in availableEnds[mw.start.split('-')[0]]"
                                                        :key="'end' + i"
                                                        :value="e"
                                                    >
                                                        {{ e }}
                                                    </option>
                                                </select>
                                                <div
                                                    class="pointer-events-none absolute pin-y pin-r flex items-center pl-2 text-gray-900"
                                                >
                                                    <svg
                                                        class="h-4 w-4 fill-current text-gray-900 dark:text-gray-200"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                                        />
                                                    </svg>
                                                </div>
                                            </div>
                                        </span>
                                        <span class="flex flex-row items-center content-center gap-1 sm:mt-0">
                                            <p>Form</p>
                                            <div class="relative">
                                                <input
                                                    :name="'form' + mw.start"
                                                    :id="'form' + mw.start"
                                                    class="px-1 border border-primary bg-gray-100 dark:bg-gray-700 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none w-28"
                                                    v-model="mw.word"
                                                    @keydown="checkWords"
                                                />
                                            </div>
                                        </span>
                                    </span>
                                    <span class="flex flex-row ml-2 mt-1 sm:mt-0" v-if="!invalid">
                                        <button
                                            class="p-2 ripple rounded-full focus:outline-none text-red-500 transition duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600"
                                            @click="removeElement(i)"
                                        >
                                            <svg
                                                style="width: 24px; height: 24px"
                                                viewBox="0 0 24 24"
                                                class="fill-current"
                                            >
                                                <path
                                                    fill="currentColor"
                                                    d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"
                                                />
                                            </svg>
                                            <span class="sr-only">Remove multiword</span>
                                        </button>
                                        <button
                                            v-if="i == current.length - 1"
                                            class="p-2 ripple rounded-full transition text-primary dark:text-primaryLight duration-100 ease-out focus:outline-none hover:bg-gray-200 dark:hover:bg-gray-600"
                                            @click="newElement()"
                                        >
                                            <svg
                                                style="width: 24px; height: 24px"
                                                viewBox="0 0 24 24"
                                                class="fill-current"
                                            >
                                                <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                                            </svg>
                                            <span class="sr-only">Add multiword</span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="float-right pb-4">
                                <button
                                    class="font-medium ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent text-red-400 py-2 px-4 rounded mr-2"
                                    @click="toggleModal()"
                                >
                                    CANCEL
                                </button>
                                <button
                                    class="font-medium text-primary dark:text-primaryLight ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent py-2 px-4 rounded"
                                    @click="save()"
                                >
                                    SAVE
                                </button>
                            </div>
                        </div>
                        <div v-else>
                            <p class="text-center italic">No multiword token found</p>
                            <div class="flex items-center justify-center content-center">
                                <button
                                    class="p-2 ripple rounded-full transition text-primary dark:text-primaryLight duration-100 ease-out focus:outline-none hover:bg-gray-200 dark:hover:bg-gray-600"
                                    @click="newElement()"
                                >
                                    <svg style="width: 24px; height: 24px" viewBox="0 0 24 24" class="fill-current">
                                        <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                                    </svg>
                                    <span class="sr-only">Add multiword</span>
                                </button>
                            </div>
                            <div class="float-right pb-4">
                                <button
                                    class="font-medium ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent text-red-400 py-2 px-4 rounded mr-2"
                                    @click="toggleModal()"
                                >
                                    CANCEL
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'manageRoot',
    props: { sentenceIndex: Number },
    data() {
        return {
            current: [],
            availableRoots: [],
            wait: true,
            invalid: false,
        }
    },
    created() {
        //incomplete
        this.wait = false
    },
    methods: {
        //incomplete
        toggleModal() {
            this.$emit('close')
            this.wait = true
        },
    },
}
</script>

<style scoped>
.customZ {
    z-index: 990;
}
.dark .dark\:bg-black {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
