import Vue from 'vue'
import $ from '../vendor/jquery'

let App = Vue.extend({
  components: {
    btndrop: require('./components/btndrop.vue'),
    dash: require('./components/dash.vue'),
    faicon: require('./components/faicon.vue'),
    offcanvas: require('./components/offcanvas.vue'),
    progress: require('./components/progress.vue'),
    secret: require('./components/secret.vue'),
    sidenav: require('./components/sidenav.vue')
  },

  data() {
    return {
      dropmenu: {},
      sidebar: {
        menu: [],
        active: null
      },
      user: null
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
    },

    nav(name) {
      this.$set('sidebar.active', name)

      return this
    }
  }
})

export default App
