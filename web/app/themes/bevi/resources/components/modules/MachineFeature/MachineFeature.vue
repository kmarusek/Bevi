<template>
  <section class="machine-feature gsap-fade-section">
    <div
      class="md:my-24 pb-20 container text-center"
    >
      <img
        :src="block.image_desktop.sizes.large"
        :alt="block.image_desktop.alt"
        class="img sm:hidden lg:block mx-auto"
      >

      <img
        :src="block.image_mobile.sizes.large"
        :alt="block.image_mobile.alt"
        class="img sm:visible lg:hidden mx-auto"
      >

      <div class="feature-list flex flex-wrap lg:h-0">
        <div
          v-for="(feature, index) in block.feature_list"
          :key="index"
          class="sm:w-1/1 md:w-1/2 mt-8 lg:invisible "
        >
          <h5 class="h5">
            {{ feature.title }}
          </h5>
          <div
            v-html="feature.text"
          />
        </div>
      </div>

      <div
        v-html="block.text"
        class="mt-5 w-2/4 mx-auto"
      />

      <a
        :href="block.cta.url"
        :target="block.cta.target ? block.cta.target : '_self'"
        class="btn mt-4 md:mt-12 gsap-fade"
      >
        {{ block.cta.title }}
      </a>
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

<style>

</style>
