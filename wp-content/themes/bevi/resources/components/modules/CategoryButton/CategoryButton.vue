<template>
  <a
    class="category order-0"
    :class="category.color"
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
  background-image: url("../../../assets/images/backgrounds/blue.jpg");
  @apply font-space bg-cover bg-center bg-no-repeat text-blue-600 rounded-full py-3 px-6 text-sm leading-none font-medium text-center inline-flex flex-no-wrap transition-all duration-300 no-underline;

  &:hover {
    opacity: 0.8;
  }

  &.orange {
    background-image: url("../../../assets/images/backgrounds/orange.jpg");
  }

  &.purple {
    background-image: url("../../../assets/images/backgrounds/purple.jpg");
  }

  &.yellow {
    background-image: url("../../../assets/images/backgrounds/yellow.jpg");
  }

  &.red {
    background-image: url("../../../assets/images/backgrounds/red.jpg");
  }

  &.green {
    background-image: url("../../../assets/images/backgrounds/green.jpg");
  }
}
</style>
