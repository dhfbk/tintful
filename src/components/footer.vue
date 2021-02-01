<template>
    <footer class="flex flex-col mt-6 justify-center">
        <div class="flex flex-row flew-wrap content-center items-center justify-center">
            <a href="https://simpatico-project.com/" target="_blank">
                <img src="../assets/logoDark.png" alt="Simpatico Project Logo" v-if="mode == 'dark'"/>
                <img src="../assets/logo.png" alt="Simpatico Project Logo" v-else/>
            </a>
            <a href="http://dh.fbk.eu/" target="_blank">
                <img src="../assets/logo-dh.png" alt="Digital Humanities Logo"
            /></a>
            <a href="http://www.fbk.eu/" target="_blank"><img src="../assets/logo-fbk.png" alt="FBK Logo"/></a>
        </div>
        <p class="text-center font-light text-sm mt-1">This demo uses free software</p>
    </footer>
</template>

<script>
export default {
    name: 'footerCustom',
    props:{
        mode: String
    },
}
</script>

<style>
footer img {
    height: 30px !important;
    width: auto;
}
</style>
