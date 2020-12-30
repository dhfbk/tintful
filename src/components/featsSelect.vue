<template>
    <div class="my-2">
        <span>{{ label }}: </span>
        <select
            class="appearance-none h-full border-b-2 float-right inline-block focus:bg-gray-200 bg-gray-200 rounded-t border-gray-400 text-gray-700 py-1 pl-2 pr-12 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
            :name="opt[0]"
            :id="opt[0]"
            @change="send"
            v-model="md"
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
