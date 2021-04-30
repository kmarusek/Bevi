<template>
  <section
    :style="{ 'background-image': 'url(' + block.background_image.url + ')' }"
    :class="[
      'bg-cover bg-no-repeat bg-center flex',
      {
        wave: block.wave,
      }
    ]"
  >
    <div class="container flex flex-col lg:flex-row">
      <div class="grid grid-cols-1 lg:grid-cols-8 items-end pt-20 xs:py-32 w-full md:gap-20">
        <div class="lg:col-span-2 space-y-4 pb-20">
          <h3
            v-if="block.subtitle"
            class="font-space font-medium md:text-lg"
          >
            {{ block.subtitle }}
          </h3>
          <h2
            v-if="block.title"
            class="heading-two"
          >
            {{ block.title }}
          </h2>
          <div
            v-if="block.content"
            v-html="block.content"
          />
        </div>
        <div class="lg:col-span-4 order-last lg:order-2">
          <img
            :src="block.image.sizes.large"
            :width="block.image.sizes['large-width']"
            :height="block.image.sizes['large-height']"
            :alt="block.image.alt"
          >
        </div>
        <div class="lg:col-span-2">
          <div
            class="pardot-form"
            v-html="block.pardot_form"
            :style="[ isMobile ? { 'height' : `${block.form_height.mobile}px` } : { 'height' : `${block.form_height.desktop}px` } ]"
          />
        </div>
      </div>
    </div>
    <wave
      v-if="block.wave"
      :wave="block.wave"
      wave-id="banner-wave"
    />
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
    computed: {
      isMobile() {
        return window.innerWidth <= 768;
      },
    },
  };
</script>

<style lang="scss" scoped>
.wave {
  clip-path: url(#banner-wave);
  @apply -mb-6 relative;
}
</style>
