<template>
  <span class="fa fa-{{ icon }}" v-if="hasIcon"></span>{{ text }}
</template>

<script>
  import Vue from 'vue'

  const Faicon = Vue.extend({
    name: 'faicon',

    props: {
      title: {
        type: String,
        default: ''
      },
      icon: {
        type: String,
        default: ''
      }
    },

    computed: {
      hasIcon() {
        return this.icon != ''
      },

      text() {
        if (this.title != '') {
          return ` ${this.title}`
        }
      }
    }
  })

  export default Faicon
</script>
