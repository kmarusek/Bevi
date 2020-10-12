<template>
  <ul>
    <li
      v-for="(item, index) in menuItems"
      :key="index"
      :class="mainHeader ? 'hidden lg:block' : 'my-4'"
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
    <li
      class="lg:ml-8 xl:ml-12"
      :class="{ 'hidden sm:block': mainHeader }"
    >
      <a
        href="#"
        class="btn large"
        :class="{ 'w-full': mainHeader }"
      >
        Get a quote
      </a>
    </li>
  </ul>
</template>

<script>
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
    },
  };
</script>

<style lang="scss" scoped>
  .menu-item-link {
    @apply relative text-2xl font-space font-semibold text-blue-600 transition-all duration-300;
    @screen lg {
      @apply ml-8 text-base;
    }
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

</style>
