<template>
  <a
    class="category order-0"
    :class="category.slug"
    :href="category.link"
  >
    {{ category.name }}
  </a>
</template>

<script>
  export default {
    props: {
      category: {
        required: true,
        type: Object,
      },
    },
  };
</script>

<style lang="scss" scoped>
.category {
  @apply font-space bg-green-100 text-blue-600 rounded-full py-3 px-6 text-sm leading-none font-medium text-center inline-flex transition-all duration-300 no-underline;

  &:hover {
    opacity: 0.8;
  }

  // TODO: add background assets for each category
  &.culture {
    @apply bg-orange;
  }
}
</style>
