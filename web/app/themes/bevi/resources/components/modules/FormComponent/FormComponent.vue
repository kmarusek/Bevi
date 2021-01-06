<template>
  <section :class="{ 'bg-blue-100' : block.show_blue_background }">
    <div class="py-20 lg:py-32 container xl:max-w-6xl flex flex-col lg:flex-row relative">
      <div class="w-full lg:w-5/12 lg:pr-16">
        <h3 class="text-3xl md:text-4xl mb-4 text-blue-600 font-semibold leading-tight">
          {{ block.title }}
        </h3>
        <div v-html="block.content" />
        <div v-if="block.show_bullets === true">
          <div
            v-for="bullet in block.bullets"
            :key="bullet.id"
            class="flex w-full my-4 sm:my-10"
          >
            <div class="w-1/5 pr-2 md:pr-0">
              <img
                :src="bullet.icon.sizes.thumbnail"
                alt=""
              >
            </div>
            <div class="w-4/5 sm:px-6">
              <h5 class="font-semibold text-lg tracking-wide text-blue-600">
                {{ bullet.title }}
              </h5>
              <div
                v-html="bullet.content"
                class="block-content smallest"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="lg:w-7/12">
        <div
          class="pardot-form"
          v-html="block.pardot_form"
          :style="[ isMobile ? { 'height' : `${block.form_height.mobile}px` } : { 'height' : `${block.form_height.desktop}px` } ]"
        />
      </div>
    </div>
  </section>
</template>

<script>
  import GSAPParallax from '~/mixins/GSAPParallax.js';

  export default {
    mixins: [GSAPParallax],
    props: {
      block: {
        required: true,
        type: [Object, Array],
      },
    },
    computed: {
      isMobile() {
        if (window.innerWidth <= 768) {
          return true;
        }
        return false;
      },
    },
  };
</script>

<style lang="scss" scoped>
.pardot-form {
  /deep/ iframe.pardotform {
    min-height: 1000px;
    @apply h-full;

    @screen lg {
      min-height: 550px;
    }
  }
}
.bubble-one {
  top: 25%;
  left: 5%;
}
.bubble-two {
  bottom: 35%;
  left: 35%;
}
.bubble-three {
  bottom: 5%;
  left: 3%;
  transform: scale(1.5);
}
</style>
