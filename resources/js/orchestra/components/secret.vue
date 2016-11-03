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

<script>
  import Vue from 'vue'

  const jQuery = require('../../vendor/jquery')
  const ElementSelector = require('../plugins/element-selector')

  const Secret = Vue.extend({
    name: 'secret',

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

    methods: {
      bootComponent() {
        let element = this.getElement()

        if (this.open) {
          element.removeClass('hidden')
        } else {
          element.addClass('hidden')
        }
      },

      openForEdit() {
        this.getElement().removeClass('hidden')
        this.open = true
      },

      closeForEdit() {
        this.getElement().addClass('hidden')
        this.open = false
      },

      getElement() {
        return jQuery(this.element).parent()
      }
    },

    mounted() {
      this.bootComponent()
    },

    ready() {
      this.bootComponent()
    }
  })

  export default Secret
</script>
