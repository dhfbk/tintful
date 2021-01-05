<template>
    <div id="app" class="relative mx-auto antialiased pr-6 pt-6 bg-gray-200">
        <div class="flex flex-row ">
            <topBar />
            <div class="w-11/12 p-8 ml-2 cardShadow bg-gray-200 min-h-full" style="height:min-content">
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
