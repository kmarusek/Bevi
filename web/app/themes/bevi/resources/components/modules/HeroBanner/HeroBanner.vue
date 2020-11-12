<template>
  <section
    class="lg:min-h-screen bg-cover bg-no-repeat flex relative"
    :style="{ 'background-image': 'url(' + block.background_image.sizes.large + ')' }"
  >
    <div
      class="container flex"
      :class="[block.image_position === 'Center' ? 'flex-col text-center' : 'text-center md:text-left flex-col md:flex-row', block.image_position === 'Left' ? 'md:flex-row-reverse' : '']"
    >
      <div
        :class="block.image_position === 'Center' ? 'pt-20 md:pt-32' : 'md:flex-1 pt-20 md:py-20 lg:py-32'"
        class="flex items-center"
      >
        <div
          class="w-full"
          :class="{'md:pl-20' : block.image_position === 'Left', 'md:pr-20' : block.image_position === 'Right'}"
        >
          <h6
            v-if="block.small_title"
            class="font-space font-medium md:text-lg text-blue-600"
          >
            {{ block.small_title }}
          </h6>
          <h1
            v-if="block.large_title"
            :class="block.image_position === 'Center' ? 'heading-one text-blue-600' : 'text-blue-600 my-2 heading-two'"
          >
            {{ block.large_title }}
          </h1>

          <div
            v-if="block.main_text && block.image_position != 'Center'"
            v-html="block.main_text"
            class="post-content text-blue-600"
          />

          <a
            v-if="block.link"
            :href="block.link.url"
            :target="block.link.target"
            class="btn mt-4"
            :class="{'center-bottom' : block.image_position === 'Center'}"
          >
            {{ block.link.title }}
          </a>
        </div>
      </div>
      <div
        class="flex-1 flex"
        :class="block.image_position === 'Center' ? '' : ' mt-10 md:mt-20 lg:mt-auto'"
      >
        <img
          :src="block.feature_image.sizes.large"
          :alt="block.feature_image.alt"
          class="h-auto mx-auto mt-auto"
          :class="block.image_position === 'Center' ? 'hero-image-small' : ' hero-image'"
        >
      </div>
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
.center-bottom {
  @apply absolute;
  bottom: 5%;
  left: 50%;
  transform: translateX(-50%);
}

.hero-image-small {
  max-height: 350px;
  
  @screen md {
    max-height: 65vh;
  }
}
.hero-image {
  max-height: 350px;
  
  @screen md {
    max-height: 65vh;
  }

  @screen lg {
    max-height: 80vh;
  }
}
</style>
