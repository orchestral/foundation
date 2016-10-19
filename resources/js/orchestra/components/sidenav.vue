<template>
  <nav>
    <ul class="sidebar__nav">
      <li v-for="item in items" :class="getClassAttributes(item)">
        <a :href="item.link" :id="item.id" v-if="! hasChild(item) && hasLink(item)">
          <faicon :icon="item.icon" :title="item.title"></faicon>
        </a>
        <p v-if="! hasLink(item)">
          <faicon :icon="item.icon" :title="item.title"></faicon>
        </p>
        <a href="#" v-if="hasLink(item) && hasChild(item)">
          <faicon :icon="item.icon" :title="item.title"></faicon>
          <i class="fa fa-angle-down"></i>
        </a>
        <ul class="sidebar-nav__submenu" :style="{display: isActive(item) ? 'block' : 'none'}" v-if="hasChild(item)">
          <li v-if="item.link != '#!'">
            <a :href="item.link" :id="item.id">{{ item.title }}</a>
          </li>
          <li v-for="child in item.childs" :class="{ 'active': isActive(child) }">
            <a :href="child.link">{{ child.title }}</a>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
</template>

<script>
  import Vue from 'vue'

  const _ = require('../../vendor/underscore')
  const jQuery = require('../../vendor/jquery')

  const SideNav = Vue.extend({
    name: 'sidenav',

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
      faicon: require('./faicon.vue')
    },

    methods: {
      bootComponent() {

      },

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
        const sidebar = jQuery('.sidebar')

        jQuery('.sidebar-nav__dropdown > a').click((e) => {
          let li = jQuery(e.toElement).parent('li')

          li.toggleClass('open')
          li.find('.sidebar-nav__submenu').slideToggle(300, () => {
            sidebar.perfectScrollbar('update')
          })

          return false
        })

        sidebar.perfectScrollbar('update')
      }
    },

    mounted() {
      this.bootComponent()
    },

    ready() {
      this.bootComponent()
    }
  })

  export default SideNav
</script>
