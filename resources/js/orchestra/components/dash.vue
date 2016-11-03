<template>
  <div class="dashboard-stats__item bg-{{ color }}">
    <faicon :icon="icon"></faicon>
    <h3 class="dashboard-stats__title">
      {{ prefix }}<span class="count-to">{{ count }}</span>{{ suffix }}
      <small>{{ title }}</small>
    </h3>
  </div>
</template>

<script>
  import Vue from 'vue'

  const CountTo = require('../plugins/count-to')

  const Dash = Vue.extend({
    name: 'dash',

    components: {
      faicon: require('./faicon.vue')
    },

    props: {
      color: {
        type: String,
        default: 'primary'
      },
      icon: {
        type: String,
        default: ''
      },
      prefix: {
        type: String,
        default: ''
      },
      suffix: {
        type: String,
        default: ''
      },
      title: {
        type: String,
        default: ''
      },
      value: {
        type: Number,
        default: 0,
        coerce: (value) => {
          return parseInt(value)
        }
      }
    },

    data() {
      return {
        increment: 1,
        interval: null,
        count: 0,
        options: {
          speed: 1000,
          refreshInterval: 100
        }
      }
    },

    methods: {
      bootComponent() {
        if (this.value >= 1000000) {
          this.value = Math.round(this.value / 1000000)
          this.suffix = 'M'
        } else if (this.value >= 100000) {
          this.value = Math.round(this.value / 1000)
          this.suffix = 'K'
        }

        new CountTo(this.value, c => {
          this.count = c.count
        }, this.options).start()
      }
    },

    mounted() {
      this.bootComponent()
    },

    ready() {
      this.bootComponent()
    }
  })

  export default Dash
</script>
