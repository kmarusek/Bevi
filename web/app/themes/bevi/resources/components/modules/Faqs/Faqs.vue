<template>
  <section class="container">
    <div class="flex">
      <div
        v-for="(faq, index) in block.selected_faq"
        :key="index"
      >
        <h3>{{ faq.post_title }}</h3>
        <div
          v-html="faq.post_content"
        />
      </div>
    </div>
  </section>
</template>

<script>
  export default {
    props: {
      block: {
        type: Object,
        required: true,
      }
    },
    created() {
      console.log(this);
    }
  };
</script>

<style>

</style>
