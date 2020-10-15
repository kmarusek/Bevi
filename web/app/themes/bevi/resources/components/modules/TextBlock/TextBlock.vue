<template>
  <section class="text-block text-center py-32">
    <div class="container max-w-2xl">
      <h2 class="h2 gsap-text-fade">
        {{ block.title }}
      </h2>
      <div
        v-html="block.text"
        class="mt-2 md:mt-6 block-content md:px-8 gsap-text-fade"
      />
    </div>
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
