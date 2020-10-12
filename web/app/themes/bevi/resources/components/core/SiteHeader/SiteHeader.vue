<template>
  <nav class="header">
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
          {{ item.name }}
        </a>
      </li>
      <li class="ml-8 xl:ml-12">
        <a
          href="#"
          class="btn large w-full"
        >
          Get a quote
        </a>
      </li>
    </ul>
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
  };
</script>

<style lang="scss" scoped>
.header {
  @apply fixed w-full flex px-10 py-8;
  
  @screen lg {
    @apply p-10;
  }
}
.menu-item-link {
  @apply font-space ml-8 font-semibold text-blue-600;
  
  @screen xl {
    @apply ml-12;
  }
}
</style>
