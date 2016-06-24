<template>
  <div class="secret">
    <a href="#" class="btn btn-xs btn-label btn-info" v-show="open" @click.prevent="closeForEdit()">{{ cancel }}</a>
    <div v-else>
      <span>{{ value }}</span>
      <a href="#" class="btn btn-xs btn-label btn-warning" @click.prevent="openForEdit()">{{ title }}</a>
    </div>
    <input type="hidden" name="enable_{{ action }}" value="{{ enabled }}">
  </div>
</template>

<style>
  .secret .btn-label {
    margin-top: 5px;
  }
</style>

<script>
  import Vue from 'vue'
  import ElementSelector from '../plugins/element-selector'
  import $ from '../../vendor/jquery'

  const Secret = Vue.extend({
    /**
     * Component name.
     *
     * @type {String}
     */
    name: 'secret',

    /**
     * Component props.
     */
    props: {
      action: {
        type: String,
        default: null
      },
      cancel: {
        type: String,
        default: 'Cancel'
      },
      element: {
        type: String,
        coerce: (value) => {
          return (new ElementSelector(value)).toString()
        }
      },
      title: {
        type: String
      },
      value: {
        type: String,
        default: ''
      }
    },

    computed: {
      enabled() {
        return this.open ? 'yes' : 'no'
      }
    },

    data() {
      return {
        open: false
      }
    },

    ready() {
      let element = this.getElement()

      if (this.open) {
        element.removeClass('hidden')
      } else {
        element.addClass('hidden')
      }
    },

    methods: {
      openForEdit() {
        this.getElement().removeClass('hidden')
        this.open = true
      },

      closeForEdit() {
        this.getElement().addClass('hidden')
        this.open = false
      },

      getElement() {
        return $(this.element).parent()
      }
    }
  })

  export default Secret
</script>
