<template>
  <section class="pb-10 md:pb-20 w-full">
    <ul class="pl-4 md:pl-0 flex whitespace-no-wrap justify-start md:justify-center overflow-x-scroll md:overflow-x-visible md:flex-wrap">
      <li
        v-for="(category, index) in categories"
        :key="index"
        class="mr-4 md:mx-4 mb-4"
        :class="{ 'order-1' : category.slug === 'uncategorized' }"
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
        type: Object,
      },
    },
  };
</script>

<style lang="scss" scoped>
/deep/ .category.uncategorized {
  @apply order-1;
}
</style>
