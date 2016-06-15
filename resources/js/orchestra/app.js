import Vue from 'vue'
import $ from '../vendor/jquery'

let App = Vue.extend({
  components: {
    btndrop: require('./components/btndrop.vue'),
    fa: require('./components/fa.vue'),
    offcanvas: require('./components/offcanvas.vue'),
    progress: require('./components/progress.vue'),
    secret: require('./components/secret.vue'),
    sidenav: require('./components/sidenav.vue')
  },

  data() {
    return {
      user: null,
      sidebar: {
        menu: [],
        active: null
      }
    }
  },

  ready() {
    this.boot()
  },

  methods: {
    boot() {
      const sidebar = $('.sidebar')

      if (sidebar.size() < 1) {
        return null
      }

      sidebar.perfectScrollbar({ suppressScrollX: true })

      $('.sidebar-user__info').click(() => {
        $('.sidebar-user__nav').slideToggle(300, () => {
          sidebar.perfectScrollbar('update')
        })

        return false
      })
    }
  }
})

export default App
