<template>
  <section class="text-center py-32">
    <div class="container max-w-2xl">
      <h2 class="h2">
        {{ block.title }}
      </h2>
      <div
        v-html="block.text"
        class="mt-2 md:mt-6 block-content"
      />
    </div>
  </section>
</template>

<script>
  export default {
    props: {
      block: {
        required: true,
        type: Object,
      },
    },
  };
</script>

<style>

</style>
