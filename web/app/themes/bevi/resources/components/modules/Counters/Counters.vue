<template>
  <section
    class="relative bg-blue-100"
  >
    <div class="py-32 md:container flex flex-col items-center gsap-fade-section text-center">
      <p class="uppercase text-blue-500 text-sm font-semibold tracking-wider">
        Our Products
      </p>
      <h3 class="h3 mb-12 text-blue-600 font-semibold">
        Choose your dispenser
      </h3>
      <div class="w-full flex flex-col md:flex-row justify-around">
        <div
          class="mb-6"
          v-for="counter in counters"
          :key="counter.id"
        >
          <h3 class="h3 mb-12 text-blue-600 font-semibold">
            {{ counter.post_title }}
          </h3>
          <a
            :href="counter.permalink"
            class="btn"
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
