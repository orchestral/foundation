<template>
  <a href="#" class="btn btn-default navbar-btn navbar-left offcanvas" @click.prevent="toggle">
    <i class="fa fa-bars"></i>
  </a>
</template>

<script>
  import Vue from 'vue'
  import Platform from '../platform'
  import $ from '../../vendor/jquery'

  let container

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
          if (value.lastIndexOf('.', 0) === 0) {
            return value
          } else {
            return `#${value}`
          }
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
        open: false
      }
    },

    ready() {
      const vm = this

      container = $(this.element)
      this.open = ! container.hasClass('alt')

      $('.sidebar__close').click(() => {
        vm.toggle()
        return false
      })

      Platform.watch('t', () => {
        vm.toggle()
      })
    },

    methods: {
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
