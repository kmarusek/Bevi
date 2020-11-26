<template>
  <section class="py-12 md:py-28 px-0">
    <div class="container">
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
            class="w-full h-full rounded-md object-center object-cover hidden md:block"
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
    </div>
    <div
      class="wave-wrapper"
      v-if="block.wave"
    >
      <wave
        :wave="block.wave"
        wave-id="comparison-wave"
      />
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

.custom-button-prev,
.custom-button-next {
  top: calc(100% - 30px);
  width: 50px;
  height: 50px;

  @screen md {
    top: 50%;
  }

  &:after {
    content: url("../../../assets/images/icons/circle-arrow.svg");
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

.wave-wrapper {
  z-index: -1;
  max-height: 300px;
  @apply bg-white absolute bottom-0 w-full h-full -mb-6;
  clip-path: url(#machine-wave);

  @screen md {
    max-height: 500px;
  }
}
</style>
