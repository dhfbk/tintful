<template>
    <div id="app" class="relative mx-auto antialiased  bg-white dark:bg-bgDark min-h-screen dark:text-gray-200">
        <div class="flex flex-col md:flex-row">
            <topBar @changeMode="changeMode" @snack="snack" />
            <div class="w-full md:w-11/12 p-3 md:p-4 md:pl-0 mx-auto min-h-full" style="height:min-content">
                <router-view @snack="snack" :sheetMode="sheetMode" />
                <footerCustom :mode="sheetMode" />
            </div>
        </div>
        <snackbar :msg="msg" v-if="notify" @close="notify = false" />
    </div>
</template>

<script>
import footerCustom from './components/footer.vue'
import snackbar from './components/snackbar.vue'
import topBar from './components/topBar.vue'
export default {
    components: {
        footerCustom,
        snackbar,
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
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                localStorage.theme = 'dark'
                document.querySelector('html').classList.add('dark')
                this.sheetMode = 'dark'
                this.$store.state.theme = 'dark'
            } else {
                localStorage.theme = 'light'
                this.sheetMode = 'light'
                this.$store.state.theme = 'light'
            }
        } else if (localStorage.theme === 'dark') {
            document.querySelector('html').classList.add('dark')
            this.sheetMode = 'dark'
            this.$store.state.theme = 'dark'
        } else {
            document.querySelector('html').classList.remove('dark')
            this.sheetMode = 'light'
            this.$store.state.theme = 'light'
        }
        if (localStorage.getItem('processedText') != undefined && localStorage.getItem('processedText') != '') {
            this.$store.state.editableData = JSON.parse(localStorage.getItem('processedText'))
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
            //console.log(this.$store.state.theme)
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
