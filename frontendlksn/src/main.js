import Vue from 'vue'
import App from './App.vue'
import router from './router'
import VueRouter from 'vue-router';
import './assets/style.css'
import axios from 'axios'

Vue.use(VueRouter);

axios.defaults.baseURL = "http://localhost:8000/api"
Vue.prototype.$axios = axios;

Vue.config.productionTip = false

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    let token = localStorage.getItem('token');
    let role = localStorage.getItem('role');

    if (!token) {
      return next({
        path: '/login',
        replace: true
      });
    }
    
    if (to.matched.some(record => record.meta.isAdmin)) {
      if (role === 'admin') {
        return next();
      }

      return next({
        path: '/user',
        replace: true
      });
    }

    if (to.matched.some(record => record.meta.isUser)) {
      if (role === 'user') {
        return next();
      }
      
      return next({
        path: '/dashboard',
        replace: true
      });
    }

  } else {
    return next();
  }  
});

new Vue({
  render: h => h(App),
  router
}).$mount('#app')