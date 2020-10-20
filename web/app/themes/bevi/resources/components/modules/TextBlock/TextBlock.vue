<template>
  <section class="text-block text-center py-32 gsap-fade-section">
    <div class="container max-w-2xl">
      <h2 class="h2 gsap-fade">
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
