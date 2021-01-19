<template>
    <footer class="flex flex-col mt-6 justify-center">
        <div class="flex flex-row flew-wrap content-center items-center justify-center">
            <a href="http://www.simpatico-project.eu/" target="_blank">
                <img src="../assets/logo.png" alt="Simpatico Project Logo" />
            </a>
            <a href="http://dh.fbk.eu/" target="_blank">
                <img src="../assets/logo-dh.png" alt="Digital Humanities Logo"
            /></a>
            <a href="http://www.fbk.eu/" target="_blank"><img src="../assets/logo-fbk.png" alt="FBK Logo"/></a>
        </div>
        <p class="text-center font-light text-sm">This demo uses free software</p>
    </footer>
</template>

<script>
export default {
    name: 'footerCustom',
}
</script>

<style>
footer img {
    height: 30px !important;
    width: auto;
}
</style>
