<template>
  <section
    id="image-carousel"
    class="gsap-fade-section flex"
    :class="[{ wave : block.wave }, block.padding]"
    :style="{ 'background-image': `url('${block.background.sizes.large}')` }"
    tabIndex="0"
  >
    <div class="container text-center">
      <h3 class="font-space text-sm font-semibold uppercase text-gray-700 tracking-wide">
        {{ block.title }}
      </h3>
      <swiper
        ref="content"
        :slides-per-view="2"
        :centered-slides="true"
        :loop="false"
        :breakpoints="{
          768: {
            slidesPerView: 6,
            centeredSlides: false,
          },
        }"
        @swiper="onSwiper"
        class="mt-10"
      >
        <swiper-slide
          v-for="slide in block.carousel"
          :key="slide.id"
          class="h-auto"
          role="tabpanel"
          :id="getDotControlsId(slide)"
        >
          <img
            :src="slide.logo.sizes.large"
            :alt="slide.logo.alt"
            class="mx-auto gsap-fade carousel-image"
          >
        </swiper-slide>
      </swiper>
      <carousel-dots
        @carousel-item-active="carouselUpdateSlide"
        @carousel-prev="carouselPrev"
        @carousel-next="carouselNext"
        :item-count="block.carousel.length"
        :active-slide="activeSlide"
        :items="block.carousel"
        class=" md:hidden pt-10"
      />
    </div>
    <wave
      v-if="block.wave"
      :wave="block.wave"
      wave-id="carousel-wave"
    />
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
      activeSlide: 0,
      swiper: null,
    }),
    methods: {
      onSwiper(swiper) {
        this.swiper = swiper;
      },
      carouselPrev() {
        this.swiper.slidePrev();
      },
      carouselNext() {
        this.swiper.slideNext();
      },
      carouselUpdateSlide(index) {
        this.swiper.slideTo(index);
        this.activeSlide = index;
      },
      carouselPaginationUpdate() {
        this.activeSlide = this.swiper.activeIndex;
      },
      getDotControlsId(item) {
        const slideID = `slide-${ item }-${ new Date().getTime() }`;
        return slideID;
      },
    },
    mounted() {
      document.addEventListener('keydown', (e) => {
        const imageCarousel = document.getElementById('image-carousel');

        if (document.activeElement === imageCarousel) {
          switch (e.keyCode) {
          case 37:
            this.carouselPrev();
            break;
          case 39:
            this.carouselNext();
            break;
          default:
          }
        }
      });
      this.swiper.on('slideChange', () => {
        this.carouselPaginationUpdate();
      });
    },
  };
</script>

<style lang="scss" scoped>
:deep(swiper-wrapper) {
  @apply items-center h-auto;
}

.wave {
  clip-path: url(#carousel-wave);
  @apply -mb-6 relative bg-white;
}

.carousel-image {
  height: 4rem;
  max-width: 200px;
}
</style>
