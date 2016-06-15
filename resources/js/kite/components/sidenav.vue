<template>
    <nav>
      <ul class="sidebar__nav">
        <li v-for="item in items" :class="getClassAttributes(item)">
          <a :href="item.link" :id="item.id" v-if="! hasChild(item) && hasLink(item)">
            <fa :icon="item.icon" :title="item.title"></fa>
          </a>
          <p v-if="! hasLink(item)">
            <fa :icon="item.icon" :title="item.title"></fa>
          </p>
          <a href="#" v-if="hasLink(item) && hasChild(item)">
            <fa :icon="item.icon" :title="item.title"></fa>
            <i class="fa fa-angle-down"></i>
          </a>
          <ul class="sidebar-nav__submenu" :style="{display: isActive(item) ? 'block' : 'none'}" v-if="hasChild(item)">
            <li v-if="item.link != '#!'">
              <a :href="item.link" :id="item.id">{{ item.title }}</a>
            </li>
            <li v-for="child in item.childs">
              <a :href="child.link">{{ child.title }}</a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
</template>

<script>
  import Vue from 'vue'
  import $ from '../../vendor/jquery'
  import _ from '../../vendor/underscore'

  let SideNav = Vue.extend({
    /**
     * Component name.
     *
     * @type {String}
     */
    name: 'sidenav',

    /**
     * Component props.
     */
    props: {
      active: {
        type: String,
        default: null
      },
      items: {
        default: []
      }
    },

    components: {
      fa: require('./fa.vue')
    },

    ready() {
      let vm = this

      $(function() {
        vm.boot()
      })
    },

    methods: {
      getClassAttributes(item) {
        return {
          'sidebar-nav__dropdown': this.hasChild(item),
          'sidebar-nav__heading': ! this.hasLink(item),
          'open': this.isActive(item)
        }
      },

      hasChild(item) {
        return _.size(item.childs) >=1
      },

      hasIcon(item) {
        return item.icon != ''
      },

      hasLink(item) {
        return item.link != '#'
      },

      isActive(item) {
        if (item.id == this.active) {
          return true
        }

        return _.indexOf(_.keys(item.childs), this.active) >= 0
      },

      boot() {
        const sidebar = $('.sidebar')

        $('.sidebar-nav__dropdown > a').click(function () {
          let li = $(this).parent('li')

          li.toggleClass('open')
          li.find('.sidebar-nav__submenu').slideToggle(300, function() {
            sidebar.perfectScrollbar('update')
          })

          return false
        })

        sidebar.perfectScrollbar('update')
      }
    }
  })

  export default SideNav
</script>
