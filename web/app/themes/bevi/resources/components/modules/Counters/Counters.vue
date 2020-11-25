<template>
  <section
    class="relative bg-blue-100"
  >
    <div class="py-20 lg:py-32 md:container flex flex-col items-center text-center">
      <p class="uppercase font-space text-blue-500 text-sm font-bold tracking-wider mb-1">
        Our Products
      </p>
      <h3 class="h3 mb-4 text-blue-600 font-semibold">
        Choose your dispenser
      </h3>
      <div class="w-full flex flex-col sm:flex-row justify-around">
        <div
          class="mb-6 w-full sm:w-1/2 md:w-2/5 lg:w-30"
          v-for="counter in counters"
          :key="counter.id"
        >
          <div class="relative">
            <single-bubble
              class="bubble"
              stroke-color="light-blue"
            />
            <single-bubble
              class="bubble"
              stroke-color="light-blue"
            />
            <single-bubble
              class="bubble"
              stroke-color="light-blue"
            />
            <single-bubble
              class="bubble"
              stroke-color="light-blue"
            />
            <img
              v-if="counter.counter_thumb"
              class="h-70 sm:h-auto w-auto sm:w-full mx-auto relative"
              :src="counter.counter_thumb.sizes.large"
            >
          </div>
          <h3 class="h3 text-blue-600 font-semibold mt-2">
            {{ counter.post_title }}
          </h3>
          <div
            class="mt-2 px-4 md:px-12 text-body smaller"
            v-html="counter.short_description"
          />
          <a
            :href="counter.permalink"
            class="btn mt-3"
          >
            View dispenser
          </a>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  export default {
    props: {
      counters: {
        required: true,
        type: Array,
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
      left: 5%;
    }
  }

  &:nth-of-type(2) {
    top: 15%;
    right: 15%;
    transform: scale(0.6);

    @screen md {
      right: 5%;
    }
  }

  &:nth-of-type(3) {
    bottom: 15%;
    right: 15%;
    
    @screen md {
      right: 0;
    }
  }
  
  &:nth-of-type(4) {
    bottom: 10%;
    left: 10%;
    transform: scale(0.5);

    @screen md {
      left: 5%;
    }
  }
}
</style>
