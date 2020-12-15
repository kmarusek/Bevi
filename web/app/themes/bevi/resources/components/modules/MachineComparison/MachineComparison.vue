<template>
  <section class="relative bg-blue-150">
    <div class="py-20 lg:py-32 md:container flex flex-col items-center text-center">
      <h2 class="h2 mb-4 lg:mb-10 text-blue-600 font-semibold">
        Choose your dispenser
      </h2>
      <div class="w-full flex flex-col sm:flex-row justify-around flex-wrap">
        <div
          class="dispenser-item w-full sm:w-1/2 lg:px-16 xl:px-32 flex flex-col sm:border-b border-r border-blue-200 pb-8"
          v-for="(counter, index) in counters"
          :key="counter.id"
        >
          <div class="relative mt-auto">
            <img
              v-if="counter.counter_image"
              class="mx-auto relative z-10"
              :src="counter.counter_image.sizes.large"
            >
            <img
              :src="require(`~/assets/images/blob_${index}.svg`)"
              :alt="`blob_${index}`"
              :class="`blob-${index}`"
              class="blob"
            >
          </div>
          <h4 class="hidden sm:block text-2.5xl text-blue-600 font-semibold mt-6">
            {{ counter.post_title }}
          </h4>
          <machine-details
            class="sm:hidden"
            :counters="counter"
            :details="counter.counter_details"
          />
        </div>
        <div
          class="dispenser-item w-full sm:w-1/2 lg:px-16 xl:px-32 sm:border-r border-blue-200 pt-8"
          v-for="counter in counters"
          :key="counter.id"
        >
          <machine-details
            class="hidden sm:block"
            :counters="counter"
            :details="counter.counter_details"
          />
        </div>
      </div>
    </div>
    <div
      class="wave-wrapper"
      v-if="block.wave"
    >
      <wave
        :wave="block.wave"
        wave-id="comparison-wave"
      />
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
      block: {
        required: true,
        type: Object,
      },
    },
  };
</script>

<style lang="scss" scoped>
.blob {
  @apply absolute z-0;
  top: 50%;
  transform: translateY(-50%);
}
.dispenser-item {
  &:nth-of-type(even) {
    border-right: 0;
  }
}
.wave-wrapper {
  z-index: -1;
  max-height: 300px;
  @apply bg-blue-150 absolute bottom-0 w-full h-full -mb-6;
  clip-path: url(#machine-wave);

  @screen md {
    max-height: 500px;
  }
}
</style>
