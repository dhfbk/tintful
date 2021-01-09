<template>
    <div class="w-full md:w-1/12 md:min-h-full flex md:flex-col content-center items-center mb-4 md:mb-0">
        <img
            src="../assets/logoTint.png"
            alt="Logo Tint"
            class="inline md:block w-24 sm:w-36 md:w-2/3 mx-2 md:mx-auto"
            @click="$router.push({ path: '/' }).catch(() => {})"
        />
        <div class="flex md:flex-col w-max content-center items-center flex-grow justify-end md:flex-grow-0">
            <button
                class="group md:mx-auto md:mt-4 p-2 mr-4 md:mr-0 rounded-full shadow-buttonShadow dark:shadow-buttonShadowDark focus:outline-none float-right"
            >
                <svg class="w-8 h-8 fill-current text-primary group-hover:text-primaryDark dark:group-hover:text-primaryLight" viewBox="0 0 24 24">
                    <path
                        d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z"
                    />
                </svg>
            </button>
            <button
                class="group md:mx-auto md:mt-4 p-2 mr-4 md:mr-0 rounded-full shadow-buttonShadow dark:shadow-buttonShadowDark focus:outline-none float-right"
                @click="$router.push({ path: '/edit' }).catch(() => {})"
                v-if="isLogged"
            >
                <svg class="w-8 h-8 fill-current text-primary group-hover:text-primaryDark dark:group-hover:text-primaryLight" viewBox="0 0 24 24">
                    <path
                        d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"
                    />
                </svg>
            </button>
            <div
                class="group md:mx-auto md:mt-4 shadow-microInsetShadow dark:shadow-microInsetShadowDark h-8 w-14 rounded-full p-1 focus:outline-none cursor-pointer"
                @click="$emit('changeMode')"
            >
                <div
                    class="shadow-microShadow dark:shadow-microShadowDark h-6 w-6 rounded-full my-auto transition-transform transform p-1"
                    :class="$store.state.theme == 'dark' ? 'translate-x-5' : ''"
                >
                    <svg
                        class="w-4 h-4 fill-current text-primary group-hover:text-primaryDark"
                        viewBox="0 0 24 24"
                        v-if="$store.state.theme == 'light'"
                    >
                        <path
                            d="M12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,2L14.39,5.42C13.65,5.15 12.84,5 12,5C11.16,5 10.35,5.15 9.61,5.42L12,2M3.34,7L7.5,6.65C6.9,7.16 6.36,7.78 5.94,8.5C5.5,9.24 5.25,10 5.11,10.79L3.34,7M3.36,17L5.12,13.23C5.26,14 5.53,14.78 5.95,15.5C6.37,16.24 6.91,16.86 7.5,17.37L3.36,17M20.65,7L18.88,10.79C18.74,10 18.47,9.23 18.05,8.5C17.63,7.78 17.1,7.15 16.5,6.64L20.65,7M20.64,17L16.5,17.36C17.09,16.85 17.62,16.22 18.04,15.5C18.46,14.77 18.73,14 18.87,13.21L20.64,17M12,22L9.59,18.56C10.33,18.83 11.14,19 12,19C12.82,19 13.63,18.83 14.37,18.56L12,22Z"
                        />
                    </svg>
                    <svg v-else class="w-4 h-4 fill-current text-primary group-hover:text-primaryLight" viewBox="0 0 24 24">
                        <path d="M2 12A10 10 0 0 0 15 21.54A10 10 0 0 1 15 2.46A10 10 0 0 0 2 12Z" />
                    </svg>
                </div>
            </div>
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
    methods: {},
}
</script>

<style></style>
