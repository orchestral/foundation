<template>
  <a href="#" class="btn btn-default navbar-btn navbar-left offcanvas" @click.prevent="toggle">
    <i class="fa fa-bars"></i>
  </a>
</template>

<script>
  import Vue from 'vue'
  import $ from '../../vendor/jquery'

  const wrapper = $('.wrapper')

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
        open: ! wrapper.hasClass('alt')
      }
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
