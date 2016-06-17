<template>
  <a href="#" class="btn btn-default navbar-btn navbar-left offcanvas" @click.prevent="toggle">
    <i class="fa fa-bars"></i>
  </a>
</template>

<script>
  import Vue from 'vue'
  import Platform from '../platform'
  import $ from '../../vendor/jquery'

  let wrapper

  const OffCanvas = Vue.extend({
    /**
     * Component name.
     *
     * @type {String}
     */
    name: 'offcanvas',

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

      wrapper = $('.wrapper')
      this.open = ! wrapper.hasClass('alt')

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

        if (this.open)
          wrapper.removeClass('alt')
        else
          wrapper.addClass('alt')
      }
    }
  })

  export default OffCanvas
</script>
