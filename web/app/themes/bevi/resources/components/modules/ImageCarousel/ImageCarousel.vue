<template>
  <section
    class="container text-center py-32 p-0 gsap-fade-section"
    :style="{ 'background-image': 'url(' + block.background.sizes.large + ')' }"
  >
    <h3 class="font-space text-sm font-semibold uppercase text-gray-700 tracking-wide">
      {{ block.title }}
    </h3>
    <swiper
      ref="content"
      :options="swiperOptions"
      class="mt-10"
    >
      <swiper-slide
        v-for="slide in block.carousel"
        :key="slide.id"
        class="w-full"
      >
        <img
          :src="slide.logo.sizes.thumbnail"
          class="mx-auto gsap-fade"
        >
      </swiper-slide>
    </swiper>
    <carousel-dots
      @carousel-item-active="carouselUpdateSlide"
      @carousel-prev="carouselPrev"
      @carousel-next="carouselNext"
      :item-count="block.carousel.length"
      :active-slide="activeSlide"
      class=" md:hidden pt-10"
    />
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
        slidesPerView: 2,
        centeredSlides: true,
        loop: false,
        breakpoints: {
          768: {
            slidesPerView: 5,
            centeredSlides: false,
          },
        },
      },
      activeSlide: 0,
    }),
    computed: {
      swiper() {
        return this.$refs.content.$swiper;
      },
    },
    methods: {
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
    },
    mounted() {
      this.swiper.on('slideChange', () => {
        this.carouselPaginationUpdate();
      });
    },
  };
</script>

<style lang="scss" scoped>
/deep/ .swiper-wrapper {
  @apply items-center;
}
</style>
