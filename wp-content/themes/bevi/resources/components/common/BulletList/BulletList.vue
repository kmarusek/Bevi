<template>
  <section
    class="gsap-fade-section"
    :class="[{ wave : block.wave }, block.padding]"
  >
    <div class="pt-2.5 flex flex-wrap flex-col sm:flex-row container justify-center">
      <div
        v-for="bullet in block.bullets"
        :key="bullet.id"
        class="flex items-start w-full sm:w-1/2 lg:w-1/3 my-4 sm:my-8 gsap-fade"
      >
        <div class="w-1/5 pr-2 md:pr-0">
          <img
            alt=""
            v-if="bullet.icon"
            :src="bullet.icon.sizes.thumbnail"
          >
        </div>
        <div class="w-4/5 sm:px-6">
          <h5 class="h5">
            {{ bullet.title }}
          </h5>
          <div
            v-html="bullet.content"
            class="block-content"
          />
        </div>
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
