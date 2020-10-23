<template>
  <header
    class="header"
    :class="{ 'nav-open' : isMobileNavOpen }"
  >
    <div class="container">
      <nav class="nav">
        <img
          :src="require('~/assets/images/bevi.svg')"
          alt="Bevi Logo"
          class="w-20"
        >
        <SiteNavigation
          class="flex items-center ml-auto"
          :menu-items="menu"
          :main-header="true"
        />
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
      <SiteNavigation
        v-if="isMobileNavOpen"
        class="mobile-nav-item"
        :menu-items="menu"
      />
    </div>
  </header>
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
    }),
    methods: {
      toggleMobileNav() {
        this.isMobileNavOpen = !this.isMobileNavOpen;
      },
    },
  };
</script>

<style lang="scss" scoped>
.header {
  @apply fixed w-full py-4 transition-colors duration-300 ease-in-out z-10;
  
  @screen lg {
    @apply py-4 items-center;
  }
  .nav {
    @apply flex items-center;
  }
  &.nav-open {
    @apply bg-blue-100 h-full;
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
  .mobile-nav-item {
    @apply absolute flex flex-col text-center justify-center content-around flex-wrap w-full inset-0;
    z-index: -1;
  }
}
</style>
