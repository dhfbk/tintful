<template>
    <div
        class="dark:bg-dark01dp shadow-md rounded-lg col-span-8 p-3 pb-4 md:p-4 md:pb-5"
        :class="id == '' ? 'w-2/5 m-auto mt-4' : ''"
        v-if="!wait"
    >
        <div class="w-full">
            <div class="text-primary dark:text-primaryLight font-bold text-lg">{{ title }}</div>
            <div class="py-2">
                <form v-if="id == ''" action="" @submit.prevent="login" class="w-full flex flex-col">
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
                    <button
                        class="w-max mt-2 bg-primary flex dark:bg-primaryLight dark:hover:bg-blue-500 hover:bg-primaryDark text-white dark:text-black dark:hover:text-white rounded py-1 px-2 ripple transition-colors duration-100 ease-out inline-block select-none focus:outline-none"
                        @click="login"
                        type="submit"
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
                </form>
                <p v-else>ID: {{ id }}</p>
                <button
                    class="font-medium ripple transition-colors duration-100 ease-out hover:bg-red-600 focus:outline-none bg-red-500 text-black py-1 px-2 rounded mr-2 mt-2"
                    @click="logout"
                    v-if="id != ''"
                >
                    LOG OUT
                </button>
            </div>
        </div>
        <div class="mt-4" v-if="id != ''">
            <p class="text-primary dark:text-primaryLight font-bold text-lg">Edits history</p>
            <div class="grid xl:grid-cols-6 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2" v-if="records.length != ''">
                <div
                    v-for="(row, n) in records"
                    :key="n"
                    class="dark:bg-dark02dp shadow-md rounded-lg p-2 m-2 flex flex-col justify-between"
                    :class="n != records.length - 1 ? 'mb-2' : ''"
                >
                    <span class="flex flex-col h-full justify-between">
                        <p class="w-full overflow-ellipsis overflow-hidden"><b>Text:</b> {{ row.sentence }}</p>
                        <span>
                            <p class="w-full overflow-ellipsis overflow-hidden"><b>Last saved:</b> {{ row.ts }}</p>
                            <p class="w-full overflow-ellipsis overflow-hidden"><b>Hash:</b> {{ row.hash }}</p>
                        </span>
                    </span>
                    <div class="flex gap-x-2">
                        <button
                            class="bg-transparent ripple py-1 px-1 rounded focus:outline-none w-max flex flex-row content-center items-center text-primary dark:text-primaryLight transition-colors duration-150 ease-out hover:bg-gray-200 dark:hover:bg-gray-600"
                        >
                            Load
                            <svg class="fill-current ml-2" style="width: 24px; height: 24px" viewBox="0 0 24 24">
                                <path
                                    d="M8,13H10.55V10H13.45V13H16L12,17L8,13M19.35,10.04C21.95,10.22 24,12.36 24,15A5,5 0 0,1 19,20H6A6,6 0 0,1 0,14C0,10.91 2.34,8.36 5.35,8.04C6.6,5.64 9.11,4 12,4C15.64,4 18.67,6.59 19.35,10.04M19,18A3,3 0 0,0 22,15C22,13.45 20.78,12.14 19.22,12.04L17.69,11.93L17.39,10.43C16.88,7.86 14.62,6 12,6C9.94,6 8.08,7.14 7.13,8.97L6.63,9.92L5.56,10.03C3.53,10.24 2,11.95 2,14A4,4 0 0,0 6,18H19Z"
                                />
                            </svg>
                        </button>
                        <button
                            class="bg-transparent ripple py-1 px-1 rounded focus:outline-none w-max flex flex-row content-center items-center text-red-500 transition-colors duration-150 ease-out hover:bg-gray-200 dark:hover:bg-gray-600"
                        >
                            Delete
                            <svg class="fill-current ml-2" style="width: 24px; height: 24px" viewBox="0 0 24 24">
                                <path
                                    d="M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19M8,9H16V19H8V9M15.5,4L14.5,3H9.5L8.5,4H5V6H19V4H15.5Z"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div v-else class="w-full">
                <svg
                    class="animate-spin fill-current text-primary dark:text-primaryLight block mx-auto"
                    style="width: 5rem; height: 5rem"
                    viewBox="0 0 24 24"
                >
                    <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                </svg>
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
            records: [],
        }
    },
    created() {
        if (sessionStorage.getItem('session_id') == undefined) {
            this.title = 'Login'
            this.wait = false
        } else {
            this.title = 'Logout'
            this.id = sessionStorage.getItem('session_id')
            this.getHistory()
            this.wait = false
        }
    },
    methods: {
        close() {
            this.$emit('close')
        },
        getHistory() {
            axios({
                method: 'post',
                url: 'http://dh-server.fbk.eu/tint-w/api/?action=list',
                data: {
                    session_id: this.id,
                },
            })
                .then(res => {
                    if (res.data.result == 'ERR') {
                        setTimeout(() => {
                            this.$emit('snack', res.data.error)
                        }, 4000)
                    } else {
                        this.records = res.data.records
                    }
                })
                .catch(err => {
                    console.error(err)
                })
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
                .then(() => {
                    this.getHistory()
                })
        },
        logout() {
            sessionStorage.removeItem('session_id')
            this.id = ''
            this.title = 'Login'
            this.records = []
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
