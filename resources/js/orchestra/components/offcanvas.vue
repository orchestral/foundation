<template>
  <a href="#" class="btn btn-default navbar-btn navbar-left offcanvas" @click.prevent="toggle" v-if="enabled">
    <i class="fa fa-bars"></i>
  </a>
</template>

<script>
  import Vue from 'vue'

  let container
  const Platform = require('../platform').default
  const ElementSelector = require('../plugins/element-selector.js').default

  const OffCanvas = Vue.extend({
    /**
     * Component name.
     *
     * @type {String}
     */
    name: 'offcanvas',

    /**
     * Component props
     */
    props: {
      element: {
        type: String,
        default: 'wrapper',
        coerce: (value) => {
          return (new ElementSelector(value)).toString()
        }
      }
    },

    /**
     * Component data.
     *
     * @return {Object}
     */
    data() {
      return {
        enabled: true,
        open: false
      }
    },

    ready() {
      container = $(this.element)

      this.enabled = container.size() > 0

      if (this.enabled) {
        this.open = ! container.hasClass('alt')
        this.boot()
      }
    },

    methods: {
      /**
       * Boot the component.
       *
       * @return void
       */
      boot() {
        jQuery('.sidebar__close').click(() => {
          this.toggle()
          return false
        })

        Platform.watch('t', () => this.toggle())
      },

      /**
       * Toggle off-canvas state.
       *
       * @return void
       */
      toggle() {
        this.open = (this.open != true)

        if (this.open) {
          container.removeClass('alt')
        } else {
          container.addClass('alt')
        }
      }
    }
  })

  export default OffCanvas
</script>
