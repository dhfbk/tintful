import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

function lazyLoad(view) {
  return () => import(`@/views/${view}.vue`)
}

const routes = [
  {
    path: '*',
    redirect: '/',
  },
  {
    path: '/',
    name: 'home',
    component: lazyLoad('Home'),
  },
  {
    path: '/result',
    name: 'result',
    component: lazyLoad('result'),
  },
  {
    path: '/edit',
    name: 'edit',
    component: lazyLoad('edit'),
  },
]

const router = new VueRouter({
  saveScrollPosition: false,
  mode: 'history',
  base: process.env.BASE_URL,
  routes: routes,
})

export default router
