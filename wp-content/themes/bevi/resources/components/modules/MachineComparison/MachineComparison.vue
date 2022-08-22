<template>
  <section class="relative bg-blue-150 gsap-fade-section">
    <div class="py-20 lg:py-32 md:container flex flex-col items-center text-center">
      <h2 class="h2 mb-4 lg:mb-10 text-blue-600 font-semibold gsap-fade">
        {{ block.title }}
      </h2>
      <div class="max-w-full overflow-x-auto">
        <table class="border-collapse min-w-full table-fixed w-full">
          <tr class="align-bottom">
            <td
              v-for="counter in counters"
              :key="counter.id"
              class="lg:px-16 px-12 sm:px-3 xl:px-32 pb-8 gsap-fade border-blue-200 border"
            >
              <div class="relative mt-auto">
                <img
                  v-if="counter.counter_image"
                  class="mx-auto relative z-10"
                  :src="counter.counter_image.sizes.large"
                  alt=""
                >
              </div>
              <h4 class="text-2.5xl text-blue-600 font-semibold mt-6">
                {{ counter.post_title }}
              </h4>
            </td>
          </tr>
          <tr class="align-top">
            <td
              v-for="counter in counters"
              :key="counter.id"
              class="lg:px-16 xl:px-32 pt-8 border-blue-200 border"
            >
              <machine-details
                :counters="counter"
                :details="counter.counter_details"
              />
            </td>
          </tr>
        </table>
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
  import GSAPFade from '~/mixins/GSAPFade.js';

  export default {
    mixins: [GSAPFade],
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
table tr {
  &:first-child td {
    border-top: 0;
  }

  &:last-child td {
    border-bottom: 0;
  }

  td {
    &:first-child {
      border-left: 0;
    }

    &:last-child {
      border-right: 0;
    }
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
