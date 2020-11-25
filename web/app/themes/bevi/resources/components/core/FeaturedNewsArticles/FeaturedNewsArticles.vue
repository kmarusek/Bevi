<template>
  <section class="container pt-24 lg:pt-36 ">
    <swiper
      ref="carousel"
      :options="swiperOptions"
    >
      <swiper-slide
        v-for="(slide, index) in block"
        :key="index"
        class="lg:px-24"
      >
        <div
          class="flex flex-wrap -mx-2 items-center overflow-hidden"
        >
          <div
            class="relative lg:pr-6 lg:w-2/4 overflow-hidden"
          >
            <img
              :src="slide.featured_image"
              class="gsap-fade rounded object-cover h-70 w-full mb-4 lg:mb-0"
            >
            <CategoryButton
              :category="slide.post_category[0]"
              class="absolute top-0 left-0 m-4"
            />
          </div>
          <div
            class="my-2 px-2 lg:w-2/4 overflow-hidden"
          >
            <span class="mb-2 block">Posted on: {{ slide.post_date | moment("from", "now") }}</span>
            <h3
              class="h3 mb-4"
            >
              {{ slide.post_title }}
            </h3>
            <div
              v-html="slide.post_content"
              class="block-content smaller"
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
        v-if="block.length >= 2"
        class="gsap-fade swiper-button-prev custom-button-prev relative lg:absolute mt-4 lg:mt-0"
        slot="button-prev"
        @click="carouselPrev"
      />
      <div
        v-if="block.length >= 2"
        class="gsap-fade swiper-button-next custom-button-next right-0"
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
        type: Array,
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
      if (this.block.length <= 1) {
        this.swiper.destroy();
      }
    },
  };
</script>

<style lang="scss" scoped>
  /deep/ .swiper-wrapper {
  @apply items-center;
}

/deep/ .category.uncategorized {
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
