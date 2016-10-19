<template>
  <div class="progress">
    <div class="progress-bar progress-bar-{{ type }}" role="progressbar" aria-valuenow="{{ progress }}" aria-valuemin="{{ min }}" aria-valuemax="{{ max }}" :style="{ width: `${progress}%` }"></div>
  </div>
</template>

<script>
  import Vue from 'vue'

  const Progress = Vue.extend({
    name: 'progress',

    props: {
      type: {
        type: String,
        default: 'info'
      },

      min: {
        type: Number,
        default: 0,
        coerce: (value) => {
          return parseInt(value)
        }
      },

      max: {
        type: Number,
        default: 100,
        coerce: (value) => {
          return parseInt(value)
        }
      },

      progress: {
        type: Number,
        default: 0,
        coerce: (value) => {
          return parseInt(value)
        }
      }
    }
  })

  export default Progress
</script>
