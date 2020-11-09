<template>
  <section
    class="relative bg-blue-100"
  >
    <div class="py-32 md:container flex flex-col items-center gsap-fade-section text-center">
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
          <img
            v-if="counter.counter_thumb"
            class="h-70 sm:h-auto w-auto sm:w-full mx-auto"
            :src="counter.counter_thumb.sizes.large"
          >
          <h3 class="h3 text-blue-600 font-semibold mt-2">
            {{ counter.post_title }}
          </h3>
          <p class="mt-2 px-4 text-sm md:px-12">
            {{ counter.short_description }}
          </p>
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
</style>
