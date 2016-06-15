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
  import $ from '../../vendor/jquery'

  let Secret = Vue.extend({
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
      field: {
        type: String
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
      const field = this.getField()

      if (this.open) {
        field.removeClass('hidden')
      } else {
        field.addClass('hidden')
      }
    },

    methods: {
      openForEdit() {
        this.getField().removeClass('hidden')
        this.open = true
      },

      closeForEdit() {
        this.getField().addClass('hidden')
        this.open = false
      },

      getField() {
        return $(`#${this.field}`).parent()
      }
    }
  })

  export default Secret
</script>
