<template>
  <section
    :class="[
      'flex relative',
      {
        wave: block.wave,
      }
    ]"
  >
    <video
      v-if="!isMobile && block.video_source_ph === 'embed'"
      autoplay
      muted
      loop
      class="absolute w-full h-full top-0 left-0 -z-10 object-cover"
    >
      <source
        :src="block.background_video.url"
        type="video/mp4"
      >
    </video>

    <div
        v-if="block.video_embed && block.video_source_ph === 'embed'"
        v-html="block.video_embed"
        id="videos"
        class="absolute w-full h-full top-0 left-0 -z-10 object-cover"
    >

    </div>

    <div class="container flex flex-col lg:flex-row">
      <div class="grid grid-cols-2 xl:grid-cols-8 items-start justify-between pt-20 xs:py-32 w-full lg:gap-20">
        <div class="sm:col-span-1 xl:col-span-3 space-y-4 pb-20">
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
        <div class="xl:col-span-2" />
        <div class="sm:col-span-1 xl:col-span-3">
          <div
            class="pardot-form"
            v-html="block.pardot_form ? block.pardot_form : block.pardot_form_fallback"
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
