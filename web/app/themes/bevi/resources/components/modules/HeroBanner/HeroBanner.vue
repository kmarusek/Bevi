<template>
  <section
    class="min-h-screen bg-cover bg-no-repeat flex"
    :style="{ 'background-image': 'url(' + block.background_image.sizes.large + ')' }"
  >
    <div
      class="container flex"
      :class="block.image_position === 'Center' ? 'flex-col text-center' : 'flex-row'"
    >
      <div class="flex-1 py-20 lg:py-32">
        <h6 class="font-space font-medium text-lg text-blue-600">
          {{ block.small_title }}
        </h6>
        <h1 class="heading-one">
          {{ block.large_title }}
        </h1>

        <div
          v-if="block.main_text && block.image_position != 'Center'"
          v-html="block.main_text"
        />

        <a
          v-if="block.link"
          :href="block.link.url"
          :target="block.link.target"
          class="btn"
          :class="block.image_position === 'Center' ? 'center-bottom' : ''"
        >
          {{ block.link.title }}
        </a>
      </div>
      <div class="flex-1 mt-auto">
        <img
          :src="block.feature_image.sizes.thumbnail"
          :alt="block.feature_image.alt"
          class="mx-auto"
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

<style>
.center-bottom {
  @apply absolute;
  bottom: 10%;
  left: 50%;
  transform: translateX(-50%);
}

</style>
