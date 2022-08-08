<template>
  <section class="flavors">
    <div class="container text-center max-w-2xl">
      <div class="relative z-10 py-20 md:py-0">
        <div class="title-wrapper">
          <h2 class="h3 font-semibold text-blue-600">
            {{ block.title }}
          </h2>
        </div>
        <div class="mt-2 block-content md:px-24 leading-lose">
          <p>
            {{ block.intro_text }}
          </p>
        </div>
        <a
          v-if="block.cta"
          :href="block.cta.url"
          :target="block.cta.target ? block.cta.target : '_self'"
          class="btn mt-4 md:mt-6"
        >
          {{ block.cta.title }}
        </a>
      </div>
    </div>
    <div class="flavor-wrapper">
      <div
        v-for="(flavor, index) in block.flavor_cards"
        :key="index"
        class="flavor"
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
    @apply h-screen mt-24;
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
      left: 5%;
      @apply absolute bg-no-repeat h-full;

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
      right: 5%;
      @apply absolute bg-no-repeat h-full;

      @screen md {
        transform: translateX(50px);
        width: 5%;
      }
    }
  }

  .flavor-wrapper {
    @apply absolute inset-0 w-full h-full overflow-x-hidden;

    @screen md {
      top: 5%;
    }
  }

  .flavor {
    @apply absolute block flex flex-col justify-center items-center;

    .label {
      line-height: 40px;
      max-width: 200px;
      @apply text-gray-550 font-semibold leading-tight mt-2 text-center text-lg;
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

    &:nth-of-type(1) {
      top: 5%;
      left: 35%;

      @screen sm {
        top: 8%;
        left: 25%;
      }

      @screen md {
        top: 12%;
        left: 10%;
      }
    }

    &:nth-of-type(2) {
      bottom: 16%;
      right: 2%;

      @screen md {
        bottom: 11%;
        right: 21%;
      }
    }

    &:nth-of-type(3) {
      bottom: 5%;
      left: 4%;

      @screen md {
        bottom: 10%;
        left: 23%;
      }
    }

    &:nth-of-type(4) {
      top: 14%;
      right: -2%;

      @screen sm {
        top: 14%;
        right: -13%;
      }

      @screen md {
        top: 14%;
        right: 12%;
      }
    }

    &:nth-of-type(5) {
      top: 15%;
      left: 0%;

      @screen sm {
        top: 15%;
        left: -19%;
      }

      @screen md {
        top: 4%;
        left: 49%;
      }
    }

    &:nth-of-type(6) {
      bottom: 30%;
      @apply hidden right-0;

      @screen md {
        @apply flex;
      }
    }

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
