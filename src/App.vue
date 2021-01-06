<template>
    <div id="app" class="relative mx-auto antialiased p-2 md:p-4 bg-gray-200 min-h-screen">
        <div class="flex flex-col md:flex-row">
            <topBar />
            <div class="w-full md:w-11/12 p-3 md:p-4 md:p-8 mx-auto md:ml-2 cardShadow min-h-full" style="height:min-content">
                <router-view @snack="snack" />
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
        }
    },
    methods: {
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
