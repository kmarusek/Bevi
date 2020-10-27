<template>
  <section class="container text-center py-12 p-0 gsap-fade-section">
    <swiper
      ref="carousel"
      :options="swiperOptions"
    >
      <swiper-slide
        v-for="(slide, index) in block.slides"
        :key="index"
        class="fixed-height-container"
      >
        <img
          :src="slide.mobile_image.sizes.large"
          class="gsap-fade w-full h-full rounded-md object-center object-cover block md:hidden"
        >
        <img
          :src="slide.desktop_image.sizes.large"
          class="gsap-fade w-full h-full rounded-md object-center object-cover hidden md:block"
        >
      </swiper-slide>
      <div
        v-if="block.slides.length >= 2"
        class="gsap-fade swiper-button-prev custom-button-prev"
        slot="button-prev"
        @click="carouselPrev"
      />
      <div
        v-if="block.slides.length >= 2"
        class="gsap-fade swiper-button-next custom-button-next"
        slot="button-next"
        @click="carouselNext"
      />
    </swiper>
  </section>
</template>

<script>
  import GSAPFade from '~/mixins/GSAPFade.js';

  export default {
    mixins: [GSAPFade],
    props: {
      block: {
        required: true,
        type: Object,
      },
    },
    data: () => ({
      swiperOptions: {
        slidesPerView: 1,
        speed: 600,
        loop: true,
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        keyboard: {
          enabled: true,
        },
      },
    }),
    computed: {
      swiper() {
        return this.$refs.carousel.$swiper;
      },
    },
    methods: {
      carouselPrev() {
        this.swiper.slidePrev();
      },
      carouselNext() {
        this.swiper.slideNext();
      },
    },
    mounted() {
      if (this.block.slides.length <= 1) {
        this.swiper.destroy();
      }
    },
  };
</script>

<style lang="scss" scoped>
/deep/ .swiper-wrapper {
  @apply items-center;
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
