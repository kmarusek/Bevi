<template>
  <ul class="lg:space-x-8 xl:space-x-12">
    <li
      v-for="(item, index) in menuItems"
      :key="index"
      :class="mainHeader ? 'hidden lg:block' : 'my-4'"
    >
      <Menu
        v-if="item.children.length"
        v-slot="{ open }"
        as="div"
        class="relative inline-block"
      >
        <MenuButton
          :class="[
            'menu-item-link',
            open ? 'active z-50' : 'z-30',
          ]"
        >
          <span v-html="item.label ? item.label : item.name" />
          <img
            :src="require('~/assets/images/icons/chevron.svg').default"
            :class="[
              'w-3 h-3 ml-2',
              open ? '' : 'transform rotate-180',
            ]"
          >
        </MenuButton>
        <MenuItems
          :class="[
            open ? 'z-40' : '',
            'menu-items absolute text-left z-20 -left-4 -top-4 pb-2 pt-12 lg:pt-8 min-w-40 mt-2 bg-opacity-90 bg-white divide-y divide-gray-100 rounded-xl ring-1 ring-black ring-opacity-5 focus:outline-none'
          ]"
          style="width: 120%;"
        >
          <div>
            <MenuItem
              v-for="(childItem, childIndex) in item.children"
              :key="childIndex"
            >
              <a
                class="font-space p-4 flex flex-col w-full"
                :href="childItem.url"
              >
                <span class="text-sm font-semibold text-blue-600">
                  {{ childItem.label ? childItem.label : childItem.name }}
                </span>
                <span
                  v-if="childItem.description"
                  class="text-gray-600 pt-2 text-xs"
                >
                  {{ childItem.description }}
                </span>
              </a>
            </MenuItem>
          </div>
        </MenuItems>
      </Menu>
      <a
        v-else
        :href="item.url"
        :class="[
          'menu-item-link',
        ]"
      >
        <span v-html="item.label ? item.label : item.name" />
      </a>
    </li>
    <li
      v-if="cta"
      :class="[
        'lg:ml-8 xl:ml-12',
        { 'hidden sm:block': mainHeader },
      ]"
    >
      <a
        :href="cta.url"
        :class="[
          'btn',
          { 'w-full': mainHeader },
        ]"
        :target="cta.target ?? '_self'"
      >
        {{ cta.title }}
      </a>
    </li>
  </ul>
</template>

<script>
  import {
    Menu,
    MenuButton,
    MenuItems,
    MenuItem,
  } from '@headlessui/vue';

  export default {
    props: {
      menuItems: {
        required: true,
        type: Array,
      },
      mainHeader: {
        required: false,
        type: Boolean,
      },
      cta: {
        required: false,
        type: Object,
      },
    },
    components: {
      Menu,
      MenuButton,
      MenuItems,
      MenuItem,
    },
    mounted () {
      // set active menu item
      var loc = window.location;
      jQuery('.menu-item-link').each(function() {
        jQuery(this).toggleClass('active', jQuery(this).attr('href') == loc);
      });
    },
  };
</script>

<style lang="scss" scoped>
  @supports (-moz-appearance:none) {
    .menu-items {
      @apply border border-blue;
      --bg-opacity: 1;
    }
  }

  .menu-items {
    backdrop-filter: blur(5px);
  }

  .menu-item-link {
    @apply relative text-2xl font-space font-semibold text-blue-600 transition-all duration-300 flex items-center;
    @screen lg {
      @apply text-sm;
    }
    span {
      @apply z-10;
    }
    &:after {
      @apply absolute w-full left-0 bg-blue transition-all duration-300;
      content: "";
      height: 3px;
      top: 100%;
      transform: scaleX(0) translateY(-2px);
      transform-origin: left;
      z-index: -1;
    }
    &:hover,
    &.active {
      &:after {
        transform: scaleX(1) translateY(-2px);
      }
    }
  }

</style>
