<template>
  <section
    class="information-module bg-green-100"
    :class="[{ wave : block.wave }, block.padding]"
  >
    <div class="md:container flex flex-col items-center gsap-fade-section">
      <h3 class="h3 font-bold mb-4 gsap-fade">
        {{ block.title }}
      </h3>
      <swiper
        ref="info"
        :options="swiperOptions"
        class="w-full relative py-2 md:py-0 md:overflow-visible"
      >
        <swiper-slide
          v-for="(card, index) in block.information_cards"
          :key="index"
          class="gsap-fade"
        >
          <div
            class="flex flex-col items-center text-center"
            @mouseover="isHovered = index"
            @mouseout="isHovered = null"
          >
            <div
              class="image-wrapper"
              :class="{hovering: isHovered === index || activeSlide === index }"
            >
              <ImageBlob
                class="image"
                :image="card.image.sizes.large"
                small="true"
              />
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="49.139"
                height="46.672"
                viewBox="0 0 49.139 46.672"
                class="bubble gsap-float"
              >
                <g
                  data-name="Group 7231"
                  transform="translate(-545.894 -345)"
                >
                  <path
                    data-name="Path 148158"
                    d="M-446.867,564.017c1.737,10.692-10.266,24.579-21.116,24.579s-21.25-10.278-19.646-21.638c1.517-10.744,8.8-17.654,19.646-17.654S-448.607,553.307-446.867,564.017Z"
                    transform="translate(1040.698 -197.924)"
                    fill="none"
                    stroke="#105128"
                    stroke-miterlimit="10"
                    stroke-width="2"
                    fill-rule="evenodd"
                  />
                  <path
                    data-name="Path 148159"
                    d="M-470.474,555.53c.735,4.525-4.345,10.4-8.937,10.4s-8.993-4.35-8.314-9.157c.642-4.547,3.723-7.471,8.314-7.471S-471.21,551-470.474,555.53Z"
                    transform="translate(1034.698 -203.303)"
                    fill="none"
                    stroke="#105128"
                    stroke-miterlimit="10"
                    stroke-width="2"
                    fill-rule="evenodd"
                  />
                </g>
              </svg>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="76.192"
                height="76.639"
                viewBox="0 0 76.192 76.639"
                class="bubble gsap-float"
              >
                <g
                  data-name="Group 7232"
                  transform="translate(-738.853 -164.325)"
                >
                  <path
                    data-name="Path 148156"
                    d="M-433.32,567.867c-.169,13.993-13.2,24.691-27.213,24.691s-29.075-10.36-27-25.036c1.96-13.88,12.985-24.864,27-24.864S-433.12,551.291-433.32,567.867Z"
                    transform="translate(903.19 922.598) rotate(131)"
                    fill="none"
                    stroke="#fdfdfd"
                    stroke-miterlimit="10"
                    stroke-width="2"
                    fill-rule="evenodd"
                  />
                  <path
                    data-name="Path 148157"
                    d="M-468.635,551.5c-.059,4.911-4.631,8.665-9.55,8.665s-10.2-3.636-9.476-8.786a9.784,9.784,0,0,1,9.476-8.725C-473.266,542.658-468.565,545.688-468.635,551.5Z"
                    transform="translate(898.182 907.052) rotate(131)"
                    fill="none"
                    stroke="#105128"
                    stroke-miterlimit="10"
                    stroke-width="2"
                    fill-rule="evenodd"
                  />
                </g>
              </svg>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="28.57"
                height="28.727"
                viewBox="0 0 28.57 28.727"
                class="bubble gsap-float"
              >
                <path
                  id="Bubble"
                  d="M-468.635,551.5c-.059,4.911-4.631,8.665-9.55,8.665s-10.2-3.636-9.476-8.786a9.784,9.784,0,0,1,9.476-8.725C-473.266,542.658-468.565,545.688-468.635,551.5Z"
                  transform="translate(116.724 737.015) rotate(131)"
                  fill="none"
                  stroke="#fdfdfd"
                  stroke-miterlimit="10"
                  stroke-width="2"
                  fill-rule="evenodd"
                />
              </svg>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="96.316"
                height="92.164"
                viewBox="0 0 96.316 92.164"
                class="bubble gsap-float"
              >
                <path
                  data-name="Single Bubble"
                  d="M-394.921,582.692c3.942,24.262-23.3,55.775-47.918,55.775s-48.221-23.324-44.582-49.1c3.442-24.38,19.96-40.061,44.582-40.061S-398.87,558.389-394.921,582.692Z"
                  transform="translate(489.344 -547.803)"
                  fill="none"
                  stroke="#eff8fc"
                  stroke-miterlimit="10"
                  stroke-width="3"
                  fill-rule="evenodd"
                />
              </svg>
            </div>
            <h4 class="font-body text-3xl font-semibold antialiased text-green-600">
              {{ card.title }}
            </h4>
            <div
              v-if="card.intro_text"
              v-html="card.intro_text"
              class="mt-2 block-content smaller text-center max-w-md px-2 sm:max-w-none sm:p-0"
            />
            <a
              v-if="card.cta"
              :href="card.cta.url"
              :target="card.cta.target ? card.cta.target : '_self'"
              class="btn mt-4 md:mt-6 bg-green-600"
            >
              {{ card.cta.title }}
            </a>
          </div>
        </swiper-slide>
      </swiper>
      <carousel-dots
        @carousel-item-active="carouselUpdateSlide"
        @carousel-prev="carouselPrev"
        @carousel-next="carouselNext"
        :item-count="block.information_cards.length"
        :active-slide="activeSlide"
        class=" md:hidden pt-10"
      />
    </div>
    <wave
      v-if="block.wave"
      :wave="block.wave"
      wave-id="info-wave"
    />
  </section>
</template>

<script>
  import GSAPFade from '~/mixins/GSAPFade.js';
  import GSAPFloat from '~/mixins/GSAPFloat.js';

  export default {
    mixins: [GSAPFade, GSAPFloat],
    props: {
      block: {
        required: true,
        type: Object,
      },
    },
    data: () => ({
      isHovered: null,
      activeSlide: 1,
      swiperOptions: {
        initialSlide: 1,
        slidesPerView: 1.2,
        centeredSlides: true,
        spaceBetween: 20,
        speed: 600,
        loop: false,
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        breakpoints: {
          375: {
            spaceBetween: 30,
            slidesPerView: 2,
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 50,
            centeredSlides: false,
            allowSlidePrev: false,
            allowSlideNext: false,
          },
        },
      },
    }),
    created() {
      window.addEventListener('resize', this.updateActiveSlide);
    },
    destroyed() {
      window.removeEventListener('resize', this.updateActiveSlide);
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
      updateActiveSlide() {
        if (window.innerWidth >= 768) {
          this.activeSlide = -1;
        } else {
          this.activeSlide = 1;
        }
      },
    },
    mounted() {
      this.swiper.on('slideChange', () => {
        this.carouselPaginationUpdate();
      });
      this.updateActiveSlide();
    },
    computed: {
      swiper() {
        return this.$refs.info.$swiper;
      },
    },
  };
</script>

<style lang="scss" scoped>
.information-module {
  @apply text-green-600;

  & /deep/ .block-content p {
    @apply text-green-600;
  }

  .image-wrapper {
    @apply flex justify-center relative py-4 w-full;

    @screen md {
      @apply px-10;
    }

    & .bubble {
      @apply absolute opacity-0;
      transition: opacity ease 1s;

      &:nth-child(2) {
        bottom: 6%;
        left: 9%;

        @screen md {
          bottom: 18%;
          left: 9%;
        }
      }

      &:nth-child(3) {
        top: -1%;
        right: 9%;

        @screen md {
          top: -10%;
          right: 50%;
        }
      }

      &:nth-child(4) {
        bottom: 5%;
        right: 7%;

        @screen md {
          path {
            stroke: theme("colors.green.600");
          }
        }
      }

      &:nth-child(5) {
        @apply hidden;

        @screen md {
          bottom: 40%;
          right: -7%;
          @apply block;
        }
      }
    }

    &.hovering {
      & .bubble {
        @apply opacity-100;
      }
    }
  }

  /deep/ .carousel-dot {
    @apply bg-green-600 opacity-50;

    &.is-active {
      @apply opacity-100;
    }
  }

  /deep/ .arrow {
    fill: theme("colors.green.600");
    @apply opacity-100;
  }
}

.wave {
  clip-path: url(#info-wave);
  @apply -mb-6 relative;
}
</style>
