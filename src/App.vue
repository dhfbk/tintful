<template>
    <div id="app" class="container relative mx-auto antialiased px-4">
        <notification :msg="msg" v-if="notify" @close="notify = false" />
        <img src="./assets/logoTint.png" alt="Logo Tint" class="mx-auto w-24 sm:w-1/6 my-4" />
        <router-view @snack="snack" />
        <footerCustom />
    </div>
</template>

<script>
import footerCustom from './components/footer.vue'
import notification from './components/toast.vue'
export default {
    components: {
        footerCustom,
        notification,
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
