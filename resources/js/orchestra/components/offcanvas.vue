<template>
  <a href="#" class="btn btn-default navbar-btn navbar-left offcanvas" @click.prevent="toggle" v-if="enabled">
    <i class="fa fa-bars"></i>
  </a>
</template>

<script>
  import Vue from 'vue'

  let container

  const jQuery = require('../../vendor/jquery')
  const Platform = require('../platform')
  const ElementSelector = require('../plugins/element-selector')

  const OffCanvas = Vue.extend({
    name: 'offcanvas',

    props: {
      element: {
        type: String,
        default: 'wrapper',
        coerce: (value) => {
          return (new ElementSelector(value)).toString()
        }
      }
    },

    data() {
      return {
        enabled: true,
        open: false
      }
    },

    methods: {
      bootComponent() {
        container = $(this.element)

        this.enabled = container.size() > 0

        if (this.enabled) {
          this.open = ! container.hasClass('alt')
          this.boot()
        }
      },

      boot() {
        jQuery('.sidebar__close').click(() => {
          this.toggle()
          return false
        })

        Platform.watch('t', () => this.toggle())
      },

      toggle() {
        this.open = (this.open != true)

        if (this.open) {
          container.removeClass('alt')
        } else {
          container.addClass('alt')
        }
      }
    },

    mounted() {
      this.bootComponent()
    },

    ready() {
      this.bootComponent()
    }
  })

  export default OffCanvas
</script>
