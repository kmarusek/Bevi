<template>
  <section>
    <div class="py-32 container flex">
      <div class="w-2/5">
        <h3 class="h3">
          {{ block.title }}
        </h3>
        <p v-html="block.content" />
      </div>
      <div class="w-2/5">
        <div v-html="block.pardot_form" />
      </div>
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
