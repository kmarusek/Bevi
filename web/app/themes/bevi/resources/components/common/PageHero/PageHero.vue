<template>
  <section
    class="relative overflow-hidden flex items-center main-banner small-banner gsap-fade-section"
    :class="{ wave : block.wave || block.hero.wave }"
  >
    <div
      class="bubbles"
      v-if="block.show_bubbles || block.hero.show_bubbles"
    >
      <span
        v-for="(n, index) in 6"
        :key="index"
        class="bubble"
        :class="`bubble${index}`"
      >
        <img
          :src="require(`~/assets/images/bubbles/bubble${index}.svg`)"
          alt="bubble icon"
        >
      </span>
    </div>
    <div class="container xl:max-w-6xl z-10 relative text-center lg:text-left">
      <h1 class="h1 gsap-fade">
        {{ category || block.title || block.hero.title }}
      </h1>
    </div>
    <img
      :src="require('~/assets/images/backgrounds/page-header.jpg')"
      class="object-cover w-full h-full absolute top-0 left-0 z-0"
    >
    <wave
      v-if="block.wave || block.hero.wave"
      :wave="block.wave || block.hero.wave"
      wave-id="hero-wave"
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
      category: {
        required: false,
        type: String,
      },
    },
  };
</script>

<style lang="scss" scoped>
</style>
