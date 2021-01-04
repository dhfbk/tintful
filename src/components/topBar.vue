<template>
    <div class="w-1/12 min-h-screen flex flex-col">
        <img
            src="../assets/logoTint.png"
            alt="Logo Tint"
            class="w-2/3 mx-auto"
            @click="$router.push({ path: '/' }).catch(() => {})"
        />
        <div class="mx-auto mt-4 p-2 rounded-full buttonShadow ripple">
            <svg class="w-8 h-8 fill-current text-gray-800" viewBox="0 0 24 24">
                <path
                    d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z"
                />
            </svg>
        </div>
        <div
            class="mx-auto mt-4 p-2 rounded-full buttonShadow ripple"
            @click="$router.push({ path: '/edit' }).catch(() => {})"
            v-if="isLogged"
        >
            <svg class="w-8 h-8 fill-current  text-gray-800" viewBox="0 0 24 24">
                <path
                    d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"
                />
            </svg>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLogged: true,
        }
    },
}
</script>

<style></style>
