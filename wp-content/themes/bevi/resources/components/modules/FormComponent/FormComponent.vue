<template>
  <section
    :id="block.id"
    :class="{ 'bg-blue-100' : block.show_blue_background }"
  >
    <div class="py-20 lg:py-32 lg:pb-0 container xl:max-w-6xl flex flex-col lg:flex-row relative">
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
            <div class="pr-2 md:pr-0 w-1/5 md:w-1/6 lg:w-1/5">
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
          ref="pardot"
          class="pardot-form"
          v-html="block.pardot_form ? block.pardot_form : block.pardot_form_fallback"
        />
      </div>
    </div>
  </section>
</template>

<script>
  import GSAPParallax from '~/mixins/GSAPParallax.js';
  import iframeResize from 'iframe-resizer/js/iframeResizer';

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
        return window.innerWidth <= 768;
      },
    },
    mounted() {
      const iframe = this.$refs.pardot.querySelector('iframe');
      const form_class = this.block.pardot_class;
      iframe.classList.add(form_class);
      iframeResize({'checkOrigin': false, 'heightCalculationMethod': 'taggedElement'}, iframe);
    }
  };
</script>

<style lang="scss" scoped>

.pardot-form {
  height: 100%;
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
