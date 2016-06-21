<template>
  <div class="panel panel-{{ type }}">
    <div class="panel-heading" v-if="title">
      <h4>
        {{ title }}
        <small v-if="description != ''">{{ description }}</small>
      </h4>
    </div>
    {{{ html }}}
    <div class="panel-body" v-if="html == ''">
      {{{ content }}}
    </div>
  </div>
</template>

<script>
  import Vue from 'vue'

  const Pane = Vue.extend({
    /**
     * Component name.
     *
     * @type {String}
     */
    name: 'pane',

    /**
     * Component props
     *
     * @type {Object}
     */
    props: {
      content: {
        type: String,
        default: ''
      },
      description: {
        type: String,
        default: ''
      },
      html: {
        type: String,
        default: ''
      },
      title: {
        type: String
      },
      type: {
        type: String,
        default: 'default'
      }
    }
  })

  export default Pane
</script>
