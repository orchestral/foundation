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

  const Dash = Vue.extend({
    /**
     * Component name.
     *
     * @type {String}
     */
    name: 'dash',

    /**
     * Component props
     */
    props: {
      color: {
        type: String,
        default: 'orange'
      },
      icon: {
        type: String,
        default: 'pie-chart'
      },
      value: {
        type: Number,
        default: 0,
        coerce: (value) => {
          return parseInt(value)
        }
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

    components: {
      faicon: require('./faicon.vue')
    },

    ready() {
      if (this.value >= 1000000) {
        this.value = Math.round(this.value / 1000000)
        this.suffix = 'M'
      } else if (this.value >= 100000) {
        this.value = Math.round(this.value / 100000)
        this.suffix = 'K'
      }

      let loops = Math.ceil(this.options.speed / this.options.refreshInterval)
      this.increment = (this.value / loops)

      if (this.increment < 1) {
        this.increment = 1
      }

      this.interval = setInterval(this.update, 100)
    },

    methods: {
      update() {
        if (this.count < this.value) {
          this.count = this.count + this.increment
        } else {
          this.count = this.value
          clearInterval(this.inteval)
        }
      }
    }
  })

  export default Dash
</script>
