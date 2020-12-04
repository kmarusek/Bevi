<template>
  <section class="machine-feature gsap-fade-section">
    <div class="pb-20 md:py-24 container lg:text-center">
      <img
        :src="block.image_desktop.sizes.large"
        :alt="block.image_desktop.alt"
        class="img hidden lg:block mx-auto"
      >

      <img
        :src="block.image_mobile.sizes.large"
        :alt="block.image_mobile.alt"
        class="img sm:visible lg:hidden mx-auto"
      >

      <div
        v-html="block.text"
        class="mt-5 lg:w-2/4 mx-auto"
      />

      <a
        v-if="block.cta"
        :href="block.cta.url"
        :target="block.cta.target ? block.cta.target : '_self'"
        class="btn mt-4 md:mt-12 gsap-fade"
      >
        {{ block.cta.title }}
      </a>
    </div>
    <div
      v-if="block.wave"
      class="wave-wrapper"
    >
      <wave
        :wave="block.wave"
        wave-id="machine-wave"
      />
    </div>
  </section>
</template>

<script>
  export default {
    props: {
      block: {
        required: true,
        type: Object,
      },
    },
  };
</script>

<style lang="scss" scoped>
.wave-wrapper {
  z-index: -1;
  max-height: 300px;
  @apply bg-white absolute bottom-0 w-full h-full -mb-6;
  clip-path: url(#machine-wave);

  @screen md {
    max-height: 500px;
  }
}
</style>
