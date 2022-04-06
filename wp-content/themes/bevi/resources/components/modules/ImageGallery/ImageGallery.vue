<template>
  <section :class="block.padding">
    <div class="gallery-container">
      <h2 class="h2 text-left mb-8 md:mb-20">
        {{ block.title }}
      </h2>
    </div>
    <swiper
      ref="gallery"
      :slides-per-view="1.2"
      :space-between="20"
      :speed="600"
      :loop="false"
      :centered-slides="false"
      :navigation="{
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      }"
      :breakpoints="{
        768: {
          spaceBetween: 40,
          slidesPerView: 3.2,
        },
        1024: {
          spaceBetween: 60,
          slidesPerView: 3.4,
        },
      }"
      :keyboard="{
        enabled: true,
      }"
      class="px-20 gallery-container"
    >
      <swiper-slide
        v-for="slide in block.gallery"
        :key="slide.ID"
        class="w-full"
      >
        <img
          :src="slide.sizes.large"
          :alt="slide.alt"
          class="gsap-fade w-full rounded-md"
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
  import { Swiper, SwiperSlide } from 'swiper/vue';
  import 'swiper/css';

  export default {
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
      if (this.block.gallery.length <= 1) {
        this.swiper?.destroy();
      }
    },
  };
</script>

<style lang="scss" scoped>
:deep(.swiper-wrapper) {
  @apply items-start;
}

.gallery-container {
  @apply px-10;

  @screen md {
    padding-left: 13%;
  }
}

.custom-button-prev,
.custom-button-next {
  top: 50%;
  width: 50px;
  height: 50px;
  transition: opacity ease 0.5s;
  @apply hidden opacity-70;

  &:hover {
    @apply opacity-100;
  }

  @screen md {
    @apply block;
  }

  &:after {
    content: url("../../../assets/images/icons/circle-arrow-2.svg");
    width: 50px;
    height: 50px;
  }
}

.custom-button-prev {
  transform: rotate(180deg);
  left: 8%;
}

.custom-button-next {
  right: 8%;
}

.wave-wrapper {
  z-index: -1;
  max-height: 300px;
  @apply bg-white absolute bottom-0 w-full h-full -mb-6;
  clip-path: url(#gallery-wave);

  @screen md {
    max-height: 500px;
  }
}
</style>
