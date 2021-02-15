<template>
    <div class="shadow-md rounded-lg dark:bg-dark01dp p-3 md:p-4 flex flex-col w-full h-full">
        <div class="w-full flex flex-row content-center items-center mb-4">
            <div class="text-primary dark:text-primaryLight text-xl tracking-wide w-full">{{title}}</div>
            <button
                @click="$emit('modal', title)"
                class="ripple p-2 bg-transparent hover:bg-gray-200 dark:hover:bg-gray-600 rounded-full focus:outline-none transition-colors duration-100 ease-out"
                title="Info"
            >
                <svg
                    class="fill-current text-primary dark:text-primaryLight"
                    style="width: 24px; height: 24px"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11V17Z"
                    />
                </svg>
                <span class="sr-only">Open information dialog</span>
            </button>
        </div>
        <div class="flex flex-row my-auto">
            <div class="w-1/3 flex flex-col place-items-center mx-2" v-for="(i, x) in series" :key="x">
                <div class="inline-block h-40 w-4 sm:w-6 relative rounded-lg dark:bg-dark02dp bg-gray-200">
                    <div :style="{ height: i + '%' }" class="absolute bottom-0 w-4 sm:w-6">
                        <div
                            class="heightTrns h-full  bg-gradient-to-tl w-full absolute bottom-0 rounded-lg	"
                            style="border-radius:10px"
                            :class="options.colors[x]"
                        ></div>
                    </div>
                </div>
                <p
                    class="text-md dark:font-thin min-w-min text-center text-gray-600 tracking-wider dark:text-gray-300 mt-2 overflow-ellipsis block overflow-hidden"
                >
                    {{ options.labels[x] }}
                </p>
                <p
                    class="w-2/3 text-center tracking-widest dark:text-gray-300 inline mt-1 text-lg font-semibold"
                    style="font-family: 'Barlow', sans-serif"
                >
                    {{ i.toFixed(2) }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "levelsCard",
    props:{
        series: Array,
        options: Object,
        title: String
    }
}
</script>

<style></style>
