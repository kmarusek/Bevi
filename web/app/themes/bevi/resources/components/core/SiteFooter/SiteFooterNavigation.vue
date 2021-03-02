<template>
  <ul class="font-space">
    <li class="mb-5">
      <a
        class="h6"
        :href="heading ? heading.url : ''"
      >
        {{ heading ? heading.title : '' }}
      </a>
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
        <span v-html="item.label ? item.label : item.name" />
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
      heading: {
        required: false,
        type: [Object, String],
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
