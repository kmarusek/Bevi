<template>
  <section
    class="relative bg-blue-100"
    :class="{ wave : block.wave }"
  >
    <div class="py-20 lg:py-32 md:container flex flex-col items-center text-center">
      <p class="uppercase font-space text-blue-500 text-sm font-bold tracking-wider mb-1">
        Our Products
      </p>
      <h3 class="h3 mb-4 text-blue-600 font-semibold">
        Our office water dispensers
      </h3>
      <div class="w-full flex flex-col sm:flex-row justify-around">
        <div
          class="mb-6 w-full sm:w-1/2 md:w-2/5 lg:w-30 flex flex-col"
          v-for="counter in counters"
          :key="counter.id"
        >
          <div class="relative">
            <single-bubble
              class="bubble parallax"
              data-speed="1"
              stroke-color="light-blue"
            />
            <single-bubble
              class="bubble parallax"
              data-speed="1.6"
              stroke-color="light-blue"
            />
            <single-bubble
              class="bubble parallax"
              data-speed="1.2"
              stroke-color="light-blue"
            />
            <single-bubble
              class="bubble parallax"
              data-speed="1.4"
              stroke-color="light-blue"
            />
            <img
              v-if="counter.counter_thumb"
              class="h-70 lg:h-80 mx-auto relative"
              :src="counter.counter_thumb.sizes.large"
            >
          </div>
          <div class="mt-10">
            <h3 class="h3 text-blue-600 font-semibold mt-2">
              {{ counter.post_title }}
            </h3>
            <div
              class="mt-2 px-4 md:px-12 text-body smaller"
              v-html="counter.short_description"
            />
            <a
              :href="counter.counter_link.url"
              :target="counter.counter_link.target"
              class="btn mt-3"
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

  export default {
    mixins: GSAPParallax,
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
  };
</script>

<style lang="scss" scoped>
.bubble {
  @apply absolute;

  &:nth-of-type(1) {
    top: 4%;
    left: 10%;
    transform: scale(0.4);

    @screen md {
      top: 30%;
      left: 5%;
    }
  }

  &:nth-of-type(2) {
    top: 15%;
    right: 15%;
    transform: scale(0.6);

    @screen md {
      top: 45%;
      right: 5%;
    }
  }

  &:nth-of-type(3) {
    bottom: 15%;
    right: 15%;

    @screen md {
      bottom: -15%;
      right: 0;
    }
  }

  &:nth-of-type(4) {
    bottom: 10%;
    left: 10%;
    transform: scale(0.5);

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
