<template>
  <div class="dropdown">
    <button class="btn btn-{{ type }} dropdown-toggle" type="button" id="{{ id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      {{ selected }}
      <span class="caret"></span>
    </button>
    <ul :class="getClassAttributes()" aria-labelledby="{{ id }}">
      <li v-for="item in items">
        <a href="{{ item.link }}">{{ item.title }}</a>
      </li>
    </ul>
  </div>
</template>

<script>
  import Vue from 'vue'

  const BtnDrop = Vue.extend({
    /**
     * Component name.
     *
     * @type {String}
     */
    name: 'btndrop',

    /**
     * Component props.
     *
     * @return {Object}
     */
    props: {
      id: {
        type: String
      },
      current: {
        type: String
      },
      type: {
        type: String,
        default: 'default'
      },
      pull: {
        type: String,
        default: 'left'
      },
      items: {
        default: {}
      }
    },

    computed: {
      selected() {
        if (_.has(this.items, this.current)) {
          return this.items[this.current].title
        }

        return 'Dropdown'
      }
    },

    methods: {
      getClassAttributes() {
        if (this.pull == 'right') {
          return 'dropdown-menu dropdown-menu-right'
        }

        return 'dropdown-menu'
      }
    }
  })

  export default BtnDrop
</script>
