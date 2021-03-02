<template>
  <section
    class="text-block text-center gsap-fade-section"
    :class="[{ wave : block.wave }, block.padding]"
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
      wave-id="text-wave"
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
    clip-path: url(#text-wave);
    @apply -mb-6 relative bg-white;
  }

  .block-content /deep/ ul {
    @apply list-disc text-gray-700;
    list-style-position: inside;
  }

  .block-content /deep/ ol {
    @apply list-decimal text-gray-700;
    list-style-position: inside;
  }
</style>
