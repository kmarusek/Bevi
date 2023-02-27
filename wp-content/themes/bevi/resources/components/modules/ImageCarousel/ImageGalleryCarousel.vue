<template>
  <section class="container py-12 lg:py-24">
    <div>
      <h2
        v-if="block.title"
        class="heading-two max-w-3xl mx-auto text-blue-600 mb-10 text-center"
      >
        {{ block.title }}
      </h2>
    </div>
    <swiper
      ref="carousel"
      :slides-per-view="1"
      :speed="600"
      :loop="true"
      :navigation="{
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      }"
      :keyboard="{
        enabled: true,
      }"
      @swiper="onSwiper"
    >
      <swiper-slide
        v-for="(slide, index) in block.slides"
        :key="index"
      >
        <img
          :src="slide.mobile_image.sizes.large"
          :alt="slide.mobile_image.alt"
          class="gsap-fade w-full h-full rounded-md object-center object-cover block md:hidden"
        >
        <img
          :src="slide.desktop_image.sizes.large"
          :alt="slide.desktop_image.alt"
          class="w-full h-full rounded-md object-center object-cover hidden md:block"
        >
      </swiper-slide>
      <button
        v-if="block.slides.length >= 2"
        class="swiper-button-prev custom-button-prev"
        slot="button-prev"
        @click="carouselPrev"
      >
        <span class="visually-hidden">Go to previous image</span>
      </button>
      <button
        v-if="block.slides.length >= 2"
        class="swiper-button-next custom-button-next"
        slot="button-next"
        @click="carouselNext"
      >
        <span class="visually-hidden">Go to next image</span>
      </button>
    </swiper>
    <div
      v-if="block.show_bullets"
      class="flex flex-col lg:flex-row"
    >
      <div
        v-for="bullet in block.bullets"
        :key="bullet.id"
        class="flex items-start w-full my-4 sm:mt-10"
      >
        <div class="w-1/5 pr-2 md:pr-0">
          <img
            :src="bullet.thumbnail.sizes.thumbnail"
            :alt="bullet.thumbnail.alt"
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
  </section>
</template>

<script>
  import { Swiper, SwiperSlide } from 'swiper/vue';
  import 'swiper/css';
  import GSAPFade from '~/mixins/GSAPFade.js';

  export default {
    mixins: [GSAPFade],
    components: {
      Swiper,
      SwiperSlide,
    },
    props: {
      block: {
        required: true,
        type: Object,
      },
    },
    data: () => ({
      swiper: null,
    }),
    methods: {
      carouselPrev() {
        this.swiper.slidePrev();
      },
      carouselNext() {
        this.swiper.slideNext();
      },
      onSwiper(swiper) {
        this.swiper = swiper;
      },
    },
    mounted() {
      if (this.block.slides.length <= 1) {
        this.swiper?.destroy();
      }
    },
  };
</script>

<style lang="scss">
:deep(.swiper-container) {
  @apply flex;
}

:deep(.swiper-wrapper) {
  @apply items-center h-auto;
}

.fixed-height-container {
  height: 50vh;
  @apply w-full pb-20;

  @screen md {
    @apply px-20 pb-0;
  }

  @screen xl {
    height: 60vh;
    @apply px-28;
  }
}

.custom-button-prev, .custom-button-next {
  top: calc(100% - 30px);
  width: 50px;
  height: 50px;

  @screen md {
    top: 50%;
  }

  &:after {
    content: url('../../../assets/images/icons/circle-arrow.svg');
    width: 50px;
    height: 50px;
  }
}

.custom-button-prev {
  transform: rotate(180deg);
  left: 15px;
}

.custom-button-next {
  right: 15px;
}
</style>
