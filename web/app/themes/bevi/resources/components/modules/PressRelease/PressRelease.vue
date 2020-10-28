<template>
  <section class="press-release gsap-fade-section color-scheme-Red">
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
              v-if="index == 0"
              :src="require('~/assets/images/blob_1.svg')"
              alt="blob_1"
              class="one"
            >
            <img
              v-if="index == 1"
              :src="require('~/assets/images/blob_2.svg')"
              alt="blob_2"
              class="two"
            >
            <img
              v-if="index == 2"
              :src="require('~/assets/images/blob_3.svg')"
              alt="blob_3"
              class="three"
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
  @apply text-center pt-16;

  &.color-scheme-Red {
    @apply bg-red-200;

    .h2 {
      @apply text-red-500;
    }

    .card {
      max-width: 450px;
      margin-bottom: 200px;
      @apply text-red-500 flex flex-col justify-start items-center w-full text-left px-16 relative mx-auto;

      @screen md {
        @apply px-4 mb-0;
      }

      @screen lg {
        @apply px-16;
      }

      .blob {
        @apply absolute;

        & .one {
          transform: translate3d(-20px, -100px, 0);
        }

        & .two {
          transform: translate3d(-45px, -90px, 0);
        }

        & .three {
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
</style>
