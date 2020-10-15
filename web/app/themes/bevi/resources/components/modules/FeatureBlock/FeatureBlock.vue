<template>
  <section>
    <h3>{{ block.title }}</h3>
    <div
      v-html="block.text"
      class="mt-2 md:mt-6 block-content md:px-8 gsap-text-fade"
    />
  </section>
</template>

<script>
  import GSAPTextFade from '~/mixins/GSAPTextFade.js';

  export default {
    mixins: [GSAPTextFade],
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
