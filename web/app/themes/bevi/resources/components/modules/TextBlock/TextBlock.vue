<template>
  <section class="text-center py-12">
    <div class="container max-w-2xl">
      <h2 class="h2">
        {{ block.title }}
      </h2>
      <div v-html="block.text" />
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
