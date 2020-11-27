<template>
  <section
    class="py-32 flex flex-wrap flex-col sm:flex-row justify-center gsap-fade-section"
    :class="{ wave : block.wave }"
  >
    <div
      v-for="bullet in block.bullets"
      :key="bullet.id"
      class="sm:flex w-full sm:w-1/2 lg:w-1/3 sm:my-8 container gsap-fade"
    >
      <div class="w-1/4 sm:w-1/5">
        <img
          v-if="bullet.icon"
          :src="bullet.icon.sizes.thumbnail"
          class="mb-2 mt-8 sm:my-0"
        >
      </div>
      <div class="sm:w-4/5 sm:px-6">
        <h5 class="h5">
          {{ bullet.title }}
        </h5>
        <div
          v-html="bullet.content"
          class="block-content smaller"
        />
      </div>
    </div>
    <wave
      v-if="block.wave"
      :wave="block.wave"
      wave-id="bullet-wave"
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
  clip-path: url(#bullet-wave);
  @apply -mb-6 relative bg-white;
}
</style>
