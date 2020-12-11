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
        <span v-html="item.label ? item.label : item.name" />
      </a>
    </li>
    <li
      v-if="cta"
      class="lg:ml-8 xl:ml-12"
      :class="{ 'hidden sm:block': mainHeader }"
    >
      <a
        :href="cta.url"
        class="btn"
        :class="{ 'w-full': mainHeader }"
        :target="cta.target ? cta.target : '_self'"
      >
        {{ cta.title }}
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
      cta: {
        required: false,
        type: Object,
      },
    },
  };
</script>

<style lang="scss" scoped>
  .menu-item-link {
    @apply relative text-2xl font-space font-semibold text-blue-600 transition-all duration-300;
    @screen lg {
      @apply ml-8 text-sm;
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
