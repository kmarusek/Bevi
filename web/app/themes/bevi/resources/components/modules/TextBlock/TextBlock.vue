<template>
  <section
    class="text-block text-center py-32 lg:py-48 gsap-fade-section"
    :class="{ wave : block.wave }"
  >
    <div class="container max-w-3xl">
      <h2 class="h1 gsap-fade">
        {{ block.title }}
      </h2>
      <div
        v-html="block.text"
        class="mt-2 md:mt-6 block-content md:px-8 gsap-fade"
      />
    </div>
    <wave
      v-if="block.wave"
      :wave="block.wave"
    />
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

<style lang="scss" scoped>
.wave {
  clip-path: url(#wave);
  @apply -mb-6;
}
</style>
