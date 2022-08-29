<template>
  <section>
    <div
      class="overflow-hidden bg-cover bg-no-repeat bg-center mt-0"
      :style="{ 'background-image': 'url(' + backgroundImage() + ')' }"
    >
      <video
        v-if="block.video.url"
        id="videos"
        muted
        loop
        autoplay
        controls
        class="h-full w-full object-cover"
      >
        <source
          :src="block.video.url"
          type="video/mp4"
        >
      </video>
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
    methods: {
      backgroundImage() {
        if (this.block.mobile_background_image && this.windowWidth <= 767) {
          return this.block.mobile_background_image.sizes.medium_large;
        }
        return '';
      },
    },
  };
</script>
