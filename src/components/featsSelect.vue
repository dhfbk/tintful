<template>
    <div class="flex content-center items-center justify-between my-2">
        <span class="mr-2">{{ label }}: </span>
        <div class="relative">
            <select
                :name="opt[0]"
                :id="opt[0]"
                @change="send"
                v-model="md"
                required
                :disabled="unselectable"
                :class="unselectable ? 'cursor-not-allowed' : ''"
                class="w-full block border border-primary appearance-none mr-24 px-1 py-1 rounded bg-gray-100 dark:bg-gray-700 transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
            >
                <option value="" class="appearance-none hover:bg-gray-300 text-gray-700" disabled selected hidden>
                    Choose {{ label.toLowerCase() }}
                </option>
                <option v-for="(i, x) in opt" :key="x" :value="i">
                    {{ i }}
                </option>
            </select>
            <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900">
                <svg
                    class="h-4 w-4 fill-current text-gray-900 dark:text-gray-200"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                >
                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        opt: Array,
        model: String,
        label: String,
        unselectable: Boolean,
    },
    data() {
        return {
            md: '',
        }
    },
    created() {
        this.md = this.model
    },
    methods: {
        send() {
            this.$emit('updateData', { info: this.md, type: this.label })
        },
    },
}
</script>

<style></style>
