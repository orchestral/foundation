<template>
  <span :class="['fa', `fa-${icon}`]" v-if="hasIcon"></span> {{ title }}
</template>

<script>
  import Vue from 'vue'

  let Fa = Vue.extend({
    /**
     * Component name.
     *
     * @type {String}
     */
    name: 'fa',

    /**
     * Component props
     */
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
      }
    }
  })

  export default Fa
</script>
