<template>
  <section
    class="container py-12 p-0 gsap-fade-section"
  >
    <h2 class="h2 text-center mb-20 md:mb-40">
      {{ block.title }}
    </h2>
    <swiper
      ref="gallery"
      :options="swiperOptions"
      class="fixed-height-container"
    >
      <swiper-slide
        v-for="slide in block.gallery"
        :key="slide.image.ID"
        class="w-full"
      >
        <img
          :src="slide.image.sizes.large"
          class="gsap-fade w-full h-full rounded-md object-center object-cover hidden md:block"
        >
      </swiper-slide>
      <div
        v-if="block.gallery.length"
        class="swiper-button-prev custom-button-prev"
        slot="button-prev"
        @click="carouselPrev"
      />
      <div
        v-if="block.gallery.length"
        class="swiper-button-next custom-button-next"
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
        spaceBetween: 20,
        speed: 600,
        loop: false,
        centeredSlides: true,
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        breakpoints: {
          768: {
            slidesPerView: 3,
            centeredSlides: false,
          },
        },
        keyboard: {
          enabled: true,
        },
      },
    }),
    computed: {
      swiper() {
        return this.$refs.gallery.$swiper;
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
      if (this.block.gallery.length <= 1) {
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
