<template>
  <section
    class="press-release gsap-fade-section color-scheme-Red"
    :class="{ wave : block.wave }"
  >
    <div class="container">
      <h2 class="h2 mb-20 md:mb-40 gsap-fade">
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
    <wave
      v-if="block.wave"
      :wave="block.wave"
      wave-id="press-wave"
    />
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
  padding-bottom: 200px;
  @apply text-center pt-16 overflow-hidden;

  &.color-scheme-Red {
    @apply bg-red-200;

    .h2 {
      @apply text-red-500;
    }

    .card {
      max-width: 450px;
      margin-bottom: 200px;
      @apply text-red-500 flex flex-col justify-start items-center w-full text-left px-16 relative mx-auto;

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

.wave {
  clip-path: url(#press-wave);
  @apply -mb-6 relative bg-white;
}
</style>
