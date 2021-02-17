<template>
    <div class="flex content-center items-center justify-between my-2">
        <span>{{ label }}: </span>
        <select
            class="appearance-none font-medium h-full border-b-2 inline-block bg-transparent border-gray-400 text-gray-700 dark:text-gray-200 py-1 pl-2 leading-tight focus:outline-none focus:bg-white dark:focus:bg-gray-800 focus:border-gray-500"
            :name="opt[0]"
            :id="opt[0]"
            @change="send"
            v-model="md"
            required
            :disabled="unselectable"
            :class="unselectable ? 'cursor-not-allowed' : ''"
        >
            <option value="" class="appearance-none hover:bg-gray-300 text-gray-700" disabled selected hidden
                >Choose {{ label.toLowerCase() }}</option
            >
            <option v-for="(i, x) in opt" :key="x" :value="i" class="appearance-none hover:bg-gray-300">
                {{ i }}
            </option>
        </select>
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
