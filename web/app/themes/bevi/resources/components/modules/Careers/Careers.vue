<template>
  <section
    class="bg-gray-200 gsap-fade-section"
    :class="{ wave : block.wave }"
  >
    <div class="py-20 lg:py-32 container">
      <h3 class="text-4xl mb-10 text-blue-600 font-medium gsap-fade">
        Open Positions
      </h3>
      <div
        id="whr_embed_hook"
        class="opprtunities-embed gsap-fade"
      />
    </div>
    <wave
      v-if="block.wave"
      :wave="block.wave"
      wave-id="careers-wave"
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
  clip-path: url(#careers-wave);
  @apply -mb-6 relative;
}
</style>
