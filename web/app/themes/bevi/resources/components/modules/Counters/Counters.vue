<template>
  <section
    class="relative bg-blue-100 gsap-fade-section"
    :class="{ wave : block.wave }"
  >
    <div class="py-20 lg:py-32 md:container flex flex-col items-center text-center">
      <p class="uppercase font-space text-blue-500 text-sm font-bold tracking-wider mb-1 gsap-fade">
        Our Products
      </p>
      <h3 class="h3 font-semibold mb-4 text-blue-600 gsap-fade">
        Our office water dispensers
      </h3>
      <div class="w-full flex flex-col sm:flex-row justify-around">
        <div
          class="mb-6 w-full sm:w-1/2 md:w-2/5 lg:w-30 flex flex-col gsap-fade"
          v-for="(counter, index) in counters"
          :key="counter.id"
          @mouseover="isHovered = index"
          @mouseout="isHovered = null"
        >
          <div
            class="bubble-wrap relative"
            :class="{ hovering: isHovered === index }"
          >
            <single-bubble
              class="bubble gsap-float"
              stroke-color="light-blue"
            />
            <single-bubble
              class="bubble gsap-float"
              stroke-color="light-blue"
            />
            <single-bubble
              class="bubble gsap-float"
              stroke-color="light-blue"
            />
            <single-bubble
              class="bubble gsap-float"
              stroke-color="light-blue"
            />
            <img
              v-if="counter.counter_thumb"
              class="h-70 xl:h-80 mx-auto relative"
              :src="counter.counter_thumb.sizes.large"
            >
          </div>
          <div class="mt-10">
            <h3 class="h3 text-blue-600 font-semibold leading-none mt-2">
              {{ counter.post_title }}
            </h3>
            <div
              class="mt-2 px-4 md:px-12 text-body"
              v-html="counter.short_description"
            />
            <a
              :href="counter.counter_link.url"
              :target="counter.counter_link.target"
              class="btn mt-4"
            >
              {{ counter.counter_link.title }}
            </a>
          </div>
        </div>
      </div>
    </div>
    <wave
      v-if="block.wave"
      :wave="block.wave"
      wave-id="counters-wave"
    />
  </section>
</template>

<script>
  import GSAPParallax from '~/mixins/GSAPParallax.js';
  import GSAPFade from '~/mixins/GSAPFade.js';
  import GSAPFloat from '~/mixins/GSAPFloat.js';

  export default {
    mixins: [GSAPParallax, GSAPFade, GSAPFloat],
    props: {
      counters: {
        required: true,
        type: Array,
      },
      block: {
        required: true,
        type: Object,
      },
    },
    data: () => ({
      isHovered: null,
    }),
  };
</script>

<style lang="scss" scoped>
.bubble-wrap {
  @apply relative;

  &.hovering {
    & .bubble {
      @apply opacity-100;
    }
  }
}

.bubble {
  transition: opacity ease 0.5s;
  @apply absolute opacity-0;

  &:nth-of-type(1) {
    top: 4%;
    left: 10%;
    width: 20px;

    @screen md {
      top: -10%;
      left: 8%;
    }
  }

  &:nth-of-type(2) {
    top: 15%;
    right: 15%;
    width: 30px;

    @screen md {
      top: 3%;
      right: 5%;
    }
  }

  &:nth-of-type(3) {
    bottom: 15%;
    right: 15%;

    @screen md {
      bottom: 10%;
      right: -5%;
    }
  }

  &:nth-of-type(4) {
    bottom: 10%;
    left: 10%;
    width: 25px;

    @screen md {
      bottom: -10%;
      left: 5%;
    }
  }
}

.wave {
  clip-path: url(#counters-wave);
  @apply -mb-6 relative;
}
</style>
