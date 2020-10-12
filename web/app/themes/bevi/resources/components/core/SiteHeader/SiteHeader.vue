<template>
  <nav
    class="header"
    :class="{ ' bg-blue-100' : isMobileNavOpen }"
  >
    <img
      :src="require('~/assets/images/bevi.svg')"
      alt="Bevi Logo"
      class="w-auto"
    >
    <ul class="flex items-center ml-auto">
      <li
        v-for="(item, index) in menu"
        :key="index"
        :item="item"
        class="hidden lg:block"
      >
        <a
          :href="item.url"
          class="menu-item-link"
          :class="{ 'is-active': item.pageId === item.pageNavId }"
        >
          <span>
            {{ item.name }}
          </span>
        </a>
      </li>
      <li class="hidden sm:block ml-8 xl:ml-12">
        <a
          href="#"
          class="btn large w-full"
        >
          Get a quote
        </a>
      </li>
    </ul>
    <button
      @click="toggleMobileNav"
      class="mobile-hamburger flex lg:hidden"
      :class="{ active : isMobileNavOpen }"
    >
      <span />
      <span />
      <span />
    </button>
  </nav>
</template>

<script>
  export default {
    props: {
      menu: {
        required: true,
        type: Array,
      },
    },
    data: () => ({
      isMobileNavOpen: false,
      windowWidth: window.innerWidth,
    }),
    mounted() {
      window.onresize = () => {
        this.windowWidth = window.innerWidth;
      };
    },
    methods: {
      toggleMobileNav() {
        this.isMobileNavOpen = !this.isMobileNavOpen;
      },
      isMobile() {
        return this.windowWidth <= 1024;
      },
    },
  };
</script>

<style lang="scss" scoped>
.header {
  @apply fixed w-full flex items-center px-6 py-4 transition-colors duration-300 ease-in-out;
  
  @screen lg {
    @apply px-8 py-4;
  }
  .menu-item-link {
    @apply relative font-space ml-8 font-semibold text-blue-600 transition-all duration-300;
    
    @screen xl {
      @apply ml-12;
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
    &:hover {
      &:after {
        transform: scaleX(1) translateY(-2px);
      }
    }
  }
  .mobile-hamburger {
    @apply ml-8 relative flex flex-col justify-center items-center cursor-pointer w-8 ;
    height: 30px;

    @screen lg {
      @apply hidden;
    }
    span {
      @apply bg-blue-600 block w-8 rounded-full absolute right-0;
      height: 6px;
      transition: all .5s ease-in-out;

      &:nth-of-type(1) {
        @apply top-0 m-auto;
      }
      &:nth-of-type(2) {
        @apply m-auto;
        top: calc(50% - 3px);
      }
      &:nth-of-type(3) {
        @apply bottom-0 m-auto;
      }
    }
    &.active {
      span {
        &:nth-of-type(1) {
          @apply top-0 opacity-0;
          transform: translateY(10px) scale(.5);
          opacity: 0;
          transition-duration: .25s;
        }

        &:nth-of-type(2) {
          @apply top-0 bottom-0 m-auto;
          transform: rotate(135deg);
        }

        &:nth-of-type(3) {
          @apply top-0 bottom-0 m-auto;
          transform: rotate(-135deg);
        }
      }
    }
  }
}
</style>
