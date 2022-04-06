<template>
  <section class="container pt-24 lg:pt-36 flex">
    <swiper
      ref="carousel"
      :slides-per-view="1"
      :speed="600"
      :loop="true"
      :auto-height="true"
      :space-between="20"
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
        v-for="(slide, index) in block"
        :key="index"
        class="lg:px-24"
      >
        <div class="flex flex-wrap lg:-mx-2 items-center overflow-hidden">
          <div class="relative lg:pr-6 lg:w-60 overflow-hidden">
            <img
              :src="slide.featured_image"
              class="gsap-fade rounded object-cover h-75 w-full mb-4 lg:mb-0"
              alt=""
            >
            <CategoryButton
              :category="slide.post_category[0]"
              class="absolute top-0 left-0 m-4"
            />
          </div>
          <div class="my-2 px-2 lg:w-40 lg:pl-10 overflow-hidden">
            <h3 class="h4 font-semibold leading-tight mb-4 text-blue-600">
              {{ slide.post_title }}
            </h3>
            <div
              v-html="slide.post_content"
              class="block-content"
            />
            <a
              :href="slide.permalink"
              class="mt-6 hover:underline text-gray-800 font-semibold inline-block read-more"
            >
              Read more
            </a>
          </div>
        </div>
      </swiper-slide>
      <div
        class="gsap-fade swiper-button-prev custom-button-prev left-0"
        slot="button-prev"
        @click="carouselPrev"
      />
      <div
        class="gsap-fade swiper-button-next custom-button-next right-0"
        slot="button-next"
        @click="carouselNext"
      />
    </swiper>
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
        type: Array,
      },
    },
    data: () => ({
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
    },
    mounted() {
      if (this.block.length <= 1) {
        this.swiper?.destroy();
      }
    },
  };
</script>

<style lang="scss" scoped>
:deep(.swiper-container) {
  @apply pb-16;

  @screen md {
    @apply pb-0;
  }
}

:deep(.swiper-wrapper) {
  @apply items-center;
}

:deep(.category.uncategorized) {
  @apply order-1;
}

.read-more {
  color: theme("colors.blue.default");
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
</style>
