<template>
  <section
    class="press-release gsap-fade-section color-scheme-Red"
    :class="[{ wave : block.wave }, block.padding]"
  >
    <div class="overflow-wrapper">
      <div class="container">
        <h2 class="h3 font-semibold mb-20 md:mb-32 gsap-fade">
          {{ block.title }}
        </h2>
        <div class="flex flex-col md:flex-row justify-between md:-mx-4 lg:-mx-16">
          <div
            v-for="(card, index) in block.cards"
            :key="card.id"
            class="card"
          >
            <span class="blob">
              <img
                :src="require(`~/assets/images/blobs_${index}.svg`)"
                :alt="`blob_${index}`"
                :class="`blob-${index}`"
              >
            </span>
            <div class="img-wrapper">
              <img
                :src="card.logo.sizes.large"
                :alt="card.logo.alt"
                class="gsap-fade"
              >
            </div>
            <p class="gsap-fade">
              {{ card.text }}
            </p>
          </div>
        </div>
      </div>
    </div>
    <div
      class="wave-wrapper"
      v-if="block.wave"
    >
      <wave
        :wave="block.wave"
        wave-id="press-wave"
      />
    </div>
  </section>
</template>

<script>
  import GSAPFade from '~/mixins/GSAPFade.js';

  export default {
    mixins: [GSAPFade],
    props: {
      block: {
        required: true,
        type: Object,
      },
    },
  };
</script>

<style lang="scss" scoped>
.press-release {
  @apply text-center relative;

  .overflow-wrapper {
    @apply min-h-full overflow-hidden;

    @screen lg {
      min-height: 540px;
    }
  }

  &.color-scheme-Red {
    @apply bg-red-100;

    .h3 {
      @apply text-red-500;
    }

    .card {
      max-width: 450px;
      margin-bottom: 200px;
      @apply text-red-500 flex flex-col justify-start items-center w-full text-left relative mx-auto;

      &:last-of-type {
        @apply mb-0;
      }

      @screen md {
        @apply px-4 mb-0;
      }

      @screen lg {
        @apply px-16;
      }

      .blob {
        @apply absolute;

        &-0 {
          transform: translate3d(-20px, -100px, 0);
        }

        &-1 {
          transform: translate3d(-45px, -90px, 0);
        }

        &-2 {
          transform: translate3d(20px, -90px, 0);
        }
      }

      @screen md {
        @apply w-4/12;
      }

      .img-wrapper {
        height: 105px;
        @apply flex justify-center items-center mb-4;
      }
    }
  }
}

.wave-wrapper {
  z-index: -1;
  max-height: 300px;
  @apply bg-red-100 absolute bottom-0 w-full h-full -mb-6;
  clip-path: url(#press-wave);

  @screen md {
    max-height: 500px;
  }
}
</style>
