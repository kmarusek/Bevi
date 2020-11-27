<template>
  <section class="flavors gsap-fade-section">
    <div class="container text-center max-w-2xl">
      <div class="relative z-10 py-20 md:py-0">
        <div class="title-wrapper gsap-fade">
          <h2 class="h2">
            {{ block.title }}
          </h2>
        </div>
        <div class="mt-2 md:mt-6 block-content smaller md:px-8 gsap-fade">
          <p>
            {{ block.intro_text }}
          </p>
        </div>
        <a
          v-if="block.cta"
          :href="block.cta.url"
          :target="block.cta.target ? block.cta.target : '_self'"
          class="btn mt-4 md:mt-12 gsap-fade"
        >
          {{ block.cta.title }}
        </a>
      </div>
    </div>
    <div class="flavor-wrapper gsap-float-section">
      <div
        v-for="(flavor, index) in block.flavor_cards"
        :key="index"
        class="flavor gsap-float"
      >
        <img
          :src="flavor.image.sizes.large"
          :alt="flavor.image.alt"
          class="img"
        >
        <p
          class="label"
          v-html="flavor.label"
        />
        <span
          v-if="flavor.callout"
          class="callout"
        >
          <img
            :src="flavor.callout.sizes.large"
            :alt="flavor.callout.alt"
          >
        </span>
      </div>
    </div>
    <div
      v-if="block.wave"
      class="wave-wrapper"
    >
      <wave
        :wave="block.wave"
        wave-id="flavors-wave"
      />
    </div>
  </section>
</template>

<script>
  import GSAPFade from '~/mixins/GSAPFade.js';
  import GSAPFloat from '~/mixins/GSAPFloat.js';

  export default {
    mixins: [GSAPFade, GSAPFloat],
    props: {
      block: {
        required: true,
        type: Object,
      },
    },
  };
</script>

<style lang="scss" scoped>
.flavors {
  @apply flex items-center justify-center relative py-48;

  @screen md {
    @apply h-screen py-10;
  }

  .title-wrapper {
    max-width: 200px;
    @apply relative w-auto inline-flex;

    @screen md {
      @apply max-w-none;
    }

    &:before {
      content: "";
      background: url("../../../assets/images/icons/title_flair_left.svg");
      transform: translateX(-20px);
      width: 10%;
      background-size: 100% 100%;
      @apply absolute left-0 bg-no-repeat h-full;

      @screen md {
        transform: translateX(-50px);
        width: 5%;
      }
    }

    &:after {
      content: "";
      background: url("../../../assets/images/icons/title_flair_right.svg");
      transform: translateX(20px);
      width: 10%;
      background-size: 100% 100%;
      @apply absolute right-0 bg-no-repeat h-full;

      @screen md {
        transform: translateX(50px);
        width: 5%;
      }
    }
  }

  .flavor-wrapper {
    @apply absolute inset-0 w-full h-full overflow-x-hidden;
  }

  .flavor {
    @apply absolute block flex flex-col justify-center items-center;

    .label {
      line-height: 40px;
      @apply text-gray-550 font-semibold leading-tight;
    }

    .img {
      width: 35vw;
      max-width: 150px;
      min-width: 100px;

      @screen md {
        width: 10vw;
      }
     
      @screen 2xl {
        width: 15vw;
        max-width: 200px;
      }
    }

    .callout {
      transform: translate3d(calc(100% + 40px), 30px, 0);
      width: 60%;
      @apply absolute right-0;

      &:before {
        content: "";
        background: url("../../../assets/images/icons/scribble_arrow.svg");
        transform: translateX(-50px) translateY(-30px) rotate(-30deg);
        width: 80px;
        background-size: 100% 100%;
        @apply absolute left-0 bg-no-repeat h-full;
      }
    }

    // Lemon
    &:nth-of-type(1) {
      top: 8%;
      left: 25%;

      @screen md {
        top: 12%;
        left: 10%;
      }
    }

    // Raspberry
    &:nth-of-type(2) {
      bottom: 16%;
      right: -8%;

      @screen md {
        bottom: 11%;
        right: 21%;
      }
    }

    // Pomegranate
    &:nth-of-type(3) {
      bottom: 5%;
      left: -17%;

      @screen md {
        bottom: 10%;
        left: 23%;
      }
    }

    // Peach
    &:nth-of-type(4) {
      top: 14%;
      right: -13%;

      @screen md {
        top: 19%;
        right: 7%;
      }
    }

    // Lime
    &:nth-of-type(5) {
      top: 15%;
      left: -19%;

      @screen md {
        top: 4%;
        left: 49%;
      }
    }

    // Coconut
    &:nth-of-type(6) {
      bottom: 25%;
      right: -4%;
      @apply hidden;

      @screen md {
        @apply flex;
      }
    }

    // Peach Mango
    &:nth-of-type(7) {
      bottom: 34%;
      left: 2%;
      @apply hidden;

      @screen md {
        @apply flex;
      }
    }
  }
}

.wave-wrapper {
  z-index: -1;
  max-height: 300px;
  @apply bg-white absolute bottom-0 w-full h-full -mb-6;
  clip-path: url(#flavors-wave);

  @screen md {
    max-height: 500px;
  }
}
</style>
