<template>
  <ul class="font-space">
    <li class="mb-5">
      <h4 class="h4">
        {{ title }}
      </h4>
    </li>
    <li
      v-for="(item, index) in navigation"
      :key="index"
      class="mb-5"
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
</template>
<script>
  export default {
    props: {
      navigation: {
        required: true,
        type: Array,
      },
      title: {
        required: false,
        type: String,
      },
    },
  };
</script>
<style lang="scss" scoped>
.link {
  @apply text-gray-700 text-sm font-medium relative;
  @screen lg {
    @apply text-gray;
  }

  span {
    @apply z-10;
  }
  
  &:after {
    @apply absolute w-full left-0 bg-blue transition-all duration-300;
    content: "";
    height: 1px;
    top: 100%;
    transform: scaleX(0) translateY(-2px);
    transform-origin: left;
  }
  &:hover {
    &:after {
      transform: scaleX(1) translateY(-2px);
    }
  }
}
</style>
