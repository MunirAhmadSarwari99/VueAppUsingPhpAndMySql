import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import NewView from '../views/NewView.vue'
import EditView from '../views/EditView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/NewPost',
      name: 'NewPost',
      component: NewView
    },
    {
      path: '/EditPost',
      name: 'EditPost',
      component: EditView
    }
  ]
})

export default router
