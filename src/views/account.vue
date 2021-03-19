<template>
    <div class="dark:bg-dark01dp shadow-md rounded-lg col-span-8 p-3 pb-4 md:p-4 md:pb-5" v-if="!wait">
        <div class="max-w-xs mx-auto">
            <div class="text-primary dark:text-primaryLight font-bold text-lg">{{ title }}</div>
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
                    class="bg-primary flex dark:bg-primaryLight dark:hover:bg-blue-500 hover:bg-primaryDark text-white dark:text-black dark:hover:text-white rounded py-1 px-2 ripple transition-colors duration-100 ease-out inline-block select-none focus:outline-none"
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
                    class="font-medium ripple transition-colors duration-100 ease-out hover:bg-red-600 focus:outline-none bg-red-500 text-black py-1 px-2 rounded mr-2"
                    @click="logout"
                    v-else
                >
                    LOG OUT
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
export default {
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
                url: 'https://dh-server.fbk.eu/tint-w/api/?action=login',
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
