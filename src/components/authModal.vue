<template>
    <transition name="fade-bg" appear>
        <div
            class="flex items-center justify-center fixed left-0 bottom-0 w-full h-full min-h-screen dark:bg-black bg-gray-900 bg-opacity-25 customZ"
            @click="close"
            v-if="!wait"
        >
            <transition name="fade" appear>
                <div
                    class="bg-white dark:bg-bgDark rounded-lg w-5/6 max-w-xl max-h-96 h-auto overflow-y-auto overscroll-none"
                    @click.stop
                >
                    <div class="p-4">
                        <div class="flex w-full">
                            <div class="text-primary dark:text-primaryLight font-bold text-lg">{{ title }}</div>
                            <button
                                class="ripple ml-auto rounded hover:bg-gray-200 dark:hover:bg-gray-600 p-1 focus:outline-none"
                                @click="close"
                            >
                                <svg
                                    class="m-auto fill-current text-gray-700 dark:text-gray-200 w-6 h-6 cursor-pointer"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 18 18"
                                >
                                    <path
                                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                                    />
                                </svg>
                                <span class="sr-only">Close dialog</span>
                            </button>
                        </div>
                        <div class="py-2">
                            <form v-if="id == ''" action="" @submit.prevent class="w-full flex flex-col">
                                <label for="username">Username</label>
                                <input
                                    id="username"
                                    name="username"
                                    type="text"
                                    v-model="username"
                                    class="px-1 border border-primary ring-1 ring-primary bg-gray-100 dark:bg-gray-600 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                                />
                                <label for="password" class="mt-2">Password</label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    v-model="password"
                                    class="px-1 border border-primary ring-1 ring-primary bg-gray-100 dark:bg-gray-600 rounded transition-colors duration-150 hover:border-blue-500 focus:border-blue-500 ease-out focus:outline-none"
                                />
                            </form>
                            <p v-else>ID: {{ id }}</p>
                        </div>
                        <div class="">
                            <button
                                class="font-medium ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent text-primary dark:text-primaryLight py-2 px-4 rounded mr-2"
                                @click="login"
                                v-if="id == ''"
                            >
                                LOG IN
                                <svg
                                    :class="loadBtn ? 'animate-spin ml-1 fill-current block' : 'hidden'"
                                    style="width: 24px; height: 24px"
                                    viewBox="0 0 24 24"
                                >
                                    <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                                </svg>
                            </button>
                            <button
                                class="font-medium ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent text-primary dark:text-primaryLight py-2 px-4 rounded mr-2"
                                @click="logout"
                                v-else
                            >
                                LOG OUT
                            </button>
                            <button
                                class="font-medium ripple transition-colors duration-100 ease-out hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none bg-transparent text-red-400 py-2 px-4 rounded mr-2"
                                @click="close"
                            >
                                CANCEL
                            </button>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </transition>
</template>

<script>
import axios from 'axios'
export default {
    name: 'authModal',
    data() {
        return {
            title: '',
            username: '',
            password: '',
            id: '',
            wait: true,
            loadBtn: false,
        }
    },
    created() {
        if (sessionStorage.getItem('session_id') == undefined) {
            this.title = 'Login'
            this.wait = false
        } else {
            this.title = 'Logout'
            this.id = sessionStorage.getItem('session_id')
            this.wait = false
        }
    },
    methods: {
        close() {
            this.$emit('close')
        },
        login() {
            this.loadBtn = true
            axios({
                method: 'post',
                url: 'https://dh-server.fbk.eu/tint-w/?action=login',
                data: {
                    username: this.username,
                    password: this.password,
                },
            })
                .then(res => {
                    if (res.data.result == 'ERR') {
                        this.$emit('snack', res.data.error)
                    } else {
                        sessionStorage.setItem('session_id', res.data.session_id)
                        this.id = res.data.session_id
                        this.title = 'Logout'
                        this.$emit('snack', 'Login successful')
                    }
                })
                .catch(err => {
                    console.error(err)
                })
                .then(() => {
                    this.loadBtn = false
                })
        },
        logout() {
            sessionStorage.removeItem('session_id')
            this.id = ''
            this.title = 'Login'
            this.$emit('snack', 'Logout successful')
        },
    },
}
</script>

<style scoped>
.customZ {
    z-index: 990;
}
.dark .dark\:bg-black {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>
