<template>
    <div id="app" class="relative mx-auto antialiased  bg-white dark:bg-bgDark min-h-screen dark:text-gray-200">
        <div class="flex flex-col md:flex-row">
            <topBar @changeMode="changeMode" />
            <div
                class="w-full md:w-11/12 p-3 md:p-4 mx-auto  min-h-full dark:shadow-cardShadowDark shadow-cardShadow"
                style="height:min-content"
            >
                <router-view @snack="snack" :sheetMode="sheetMode" />
            </div>
        </div>
        <notification :msg="msg" v-if="notify" @close="notify = false" />
        <footerCustom />
    </div>
</template>

<script>
import footerCustom from './components/footer.vue'
import notification from './components/toast.vue'
import topBar from './components/topBar'
export default {
    components: {
        footerCustom,
        notification,
        topBar,
    },
    data() {
        return {
            msg: '',
            timeout: 3000,
            notify: false,
            sheetMode: 'light',
        }
    },
    created() {
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.theme == undefined) {
            localStorage.theme = 'light'
            this.sheetMode = 'light'
            this.$store.state.theme = 'light'
        } else if (localStorage.theme === 'dark') {
            document.querySelector('html').classList.add('dark')
            this.sheetMode = 'dark'
            this.$store.state.theme = 'dark'
        } else {
            document.querySelector('html').classList.remove('dark')
            this.sheetMode = 'light'
            this.$store.state.theme = 'light'
        }
    },
    methods: {
        changeMode() {
            if (localStorage.theme === 'dark') {
                localStorage.theme = 'light'
                this.$store.state.theme = 'light'
                document.querySelector('html').classList.remove('dark')
                this.sheetMode = 'light'
            } else {
                localStorage.theme = 'dark'
                this.$store.state.theme = 'dark'
                document.querySelector('html').classList.add('dark')
                this.sheetMode = 'dark'
            }
            console.log(this.$store.state.theme)
        },
        snack(msg) {
            this.msg = msg
            this.notify = true
            setTimeout(() => {
                this.notify = false
            }, this.timeout)
        },
    },
}
</script>

<style>
@import url('./css/style.css');
</style>
