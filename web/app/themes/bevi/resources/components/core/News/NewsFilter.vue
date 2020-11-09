<template>
  <section class="py-20 w-full">
    <ul class="flex justify-center flex-wrap">
      <li
        v-for="(category, index) in categories"
        :key="index"
        class="mx-4 mb-4"
      >
        <CategoryButton :category="category" />
      </li>
    </ul>
  </section>
</template>

<script>
  export default {
    props: {
      categories: {
        required: true,
        type: Array,
      },
    },
  };
</script>

<style lang="scss" scoped>
/deep/ .category.uncategorized {
  @apply order-1;
}
</style>
