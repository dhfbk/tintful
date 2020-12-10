<template>
  <div id="app" class="container mx-auto antialiased px-4">
    <img
      src="./assets/logoTint.png"
      alt="Logo Tint"
      class="mx-auto w-1/6 my-4"
    />
    <router-view />
    <footerCustom />
  </div>
</template>

<script>
import footerCustom from "./components/footer.vue";
export default {
  components: {
    footerCustom,
  },
};
</script>

<style>
@import url("./css/style.css");
</style>
