import Vue from 'vue'
import Bootstrap from './bootstrap'
import $ from '../vendor/jquery'

const App = Vue.extend({
  components: {
    btndrop: require('./components/btndrop.vue'),
    dash: require('./components/dash.vue'),
    faicon: require('./components/faicon.vue'),
    offcanvas: require('./components/offcanvas.vue'),
    pane: require('./components/pane.vue'),
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

  created() {
    (new Bootstrap())
      .select2()
      .switcher()
      .restful()
  },

  ready() {
    this.boot()
  },

  methods: {
    boot() {
      let sidebar = $('.sidebar')

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
