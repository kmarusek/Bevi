<template>
  <section
    class="lg:min-h-screen bg-cover bg-no-repeat flex relative"
    :style="{ 'background-image': 'url(' + block.background_image.sizes.large + ')' }"
  >
    <div
      class="container flex"
      :class="{
        'text-center md:text-left flex-col md:flex-row' : block.text_position === 'Right',
        'text-center md:text-left flex-col md:flex-row-reverse' : block.text_position === 'Left',
        'flex-col text-center' : block.text_position === 'Center'
      }"
    >
      <div
        :class="{
          'pt-20 xs:py-32 lg:w-2/4' : block.text_position === 'Left' && block.feature_image || block.text_position === 'Right' && block.feature_image,
          'py-32 lg:w-2/4' : block.text_position === 'Left' && !block.feature_image || 'Right' && !block.feature_image,
          'md:flex-1 py-32 lg:w-full' : block.text_position === 'Center' && !block.feature_image,
          'md:flex-1 pt-20 lg:pt-32 lg:w-full' : block.text_position === 'Center' && block.feature_image,
        }"
        class="flex items-center"
      >
        <div
          class="w-full"
          :class="[{'md:pl-20' : block.text_position === 'Left' && block.feature_image, 'md:pr-20' : block.text_position === 'Right' && block.feature_image}, block.text_color.value ]"
        >
          <h6
            v-if="block.small_title"
            class="font-space font-medium md:text-lg"
          >
            {{ block.small_title }}
          </h6>
          <h1
            v-if="block.large_title"
            :class="block.text_position === 'Center' ? 'heading-one' : 'my-2 heading-two'"
          >
            {{ block.large_title }}
          </h1>

          <div
            v-if="block.main_text && block.text_position != 'Center'"
            v-html="block.main_text"
            class="post-content"
          />

          <a
            v-if="block.link"
            :href="block.link.url"
            :target="block.link.target"
            class="btn mt-4"
            :class="{'center-bottom' : block.text_position === 'Center'}"
          >
            {{ block.link.title }}
          </a>
        </div>
      </div>
      <div
        v-if="block.feature_image"
        class="flex-1 flex"
        :class="block.text_position === 'Center' ? '' : ' mt-10 md:mt-20 lg:mt-auto'"
      >
        <img
          v-if="block.feature_image"
          :src="block.feature_image.sizes.large"
          :alt="block.feature_image.alt"
          class="h-auto mx-auto mt-auto"
          :class="block.text_position === 'Center' ? 'hero-image-small' : ' hero-image'"
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
