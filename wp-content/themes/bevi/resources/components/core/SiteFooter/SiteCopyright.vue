<template>
  <div class="bg-purple-200 py-4 font-space text-xs font-semibold text-blue-600">
    <div class="container flex flex-col md:flex-row md:items-center">
      <p class="md:mr-auto order-last md:order-first">
        Bevi &#169; {{ new Date().getFullYear() }} All rights reserved.
      </p>
      <ul class="font-space md:mx-auto md:my-6">
        <li
          v-for="(item, index) in navigation"
          :key="index"
          class="inline-block mr-6"
        >
          <a
            :href="item.url"
            class="link"
          >
            <span>
              {{ item.name }}
            </span>
          </a>
        </li>
      </ul>
      <button
        @click="scrollToTop()"
        class="md:ml-auto flex items-center cursor-pointer"
      >
        <p>Back to top</p>
        <div class="icon ml-auto md:ml-6">
          <img
            :src="require('~/assets/images/icons/arrow.svg').default"
            alt=""
          >
        </div>
      </button>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      navigation: {
        required: true,
        type: Array,
      },
    },
    methods: {
      scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      },
    },
  };
</script>

<style lang="scss" scoped>
.link {
  @apply relative;

  span {
    @apply z-10;
  }

  &:after {
    @apply absolute w-full left-0 bg-gray-700 transition-all duration-300;
    content: "";
    height: 1px;
    top: 100%;
    transform: scaleX(1) translateY(-2px);
    transform-origin: left;
  }
  &:hover {
    &:after {
      transform: scaleX(0) translateY(-2px);
    }
  }
}
</style>
