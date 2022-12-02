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
    <div class="container flex flex-col lg:flex-row mb-36">
      <div class="grid grid-cols-1 xl:grid-cols-8 items-start justify-between pt-20 xs:py-32 w-full lg:gap-20">
        <div class="xl:col-span-2 space-y-4 pb-20 py-10">
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
        <div class="xl:col-span-3 order-last xl:order-none">
          <img
            :src="block.image.sizes.large"
            :width="block.image.sizes['large-width']"
            :height="block.image.sizes['large-height']"
            :alt="block.image.alt"
          >
        </div>
        <div class="xl:col-span-3">
          <div
            ref="pardot"
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
  import iframeResize from 'iframe-resizer/js/iframeResizer';

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
    mounted() {
      const iframe = this.$refs.pardot.querySelector('iframe');
      iframeResize({'checkOrigin': false, 'heightCalculationMethod': 'taggedElement'}, iframe);
    }
  };
</script>

<style lang="scss" scoped>
.wave {
  clip-path: url(#banner-wave);
  @apply -mb-6 relative;
}
</style>
