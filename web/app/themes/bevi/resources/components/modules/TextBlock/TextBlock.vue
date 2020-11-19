<template>
  <section class="text-block text-center py-32 lg:py-48 gsap-fade-section">
    <div class="container max-w-3xl">
      <h2 class="h1 gsap-fade">
        {{ block.title }}
      </h2>
      <div
        v-html="block.text"
        class="mt-2 md:mt-6 block-content md:px-8 gsap-fade"
      />
    </div>
  </section>
</template>

<script>
  import GSAPFade from '~/mixins/GSAPFade.js';

  export default {
    mixins: [GSAPFade],
    props: {
      block: {
        required: true,
        type: Object,
      },
    },
  };
</script>
