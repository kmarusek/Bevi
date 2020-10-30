<template>
  <section class="flavors gsap-fade-section">
    <div class="container text-center max-w-2xl">
      <div class="title-wrapper gsap-fade">
        <h2 class="h2">
          {{ block.title }}
        </h2>
      </div>
      <div class="mt-2 md:mt-6 block-content md:px-8 gsap-fade">
        <p>
          {{ block.intro_text }}
        </p>
      </div>
      <a
        :href="block.cta.url"
        :target="block.cta.target ? block.cta.target : '_self'"
        class="btn mt-12"
      >
        {{ block.cta.title }}
      </a>
      <div class="flavor-wrapper">
        <div
          v-for="(flavor, index) in block.flavor_cards"
          :key="index"
          class="flavor"
        >
          <img
            :src="flavor.image.sizes.large"
            :alt="flavor.image.alt"
          >
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
.flavors {
  background: pink; 
  @apply h-screen flex items-center justify-center relative;

  .title-wrapper {
    @apply relative w-auto inline-flex;

    &:before {
      content: url("../../../assets/images/icons/title_flair_left.svg");
      transform: translateX(-50px);
      width: 5%;
      background-size: 100% 100%;
      @apply absolute left-0 bg-no-repeat h-full;
    }

    &:after {
      content: "";
      background: url("../../../assets/images/icons/title_flair_right.svg");
      transform: translateX(50px);
      width: 5%;
      background-size: 100% 100%;
      @apply absolute right-0 bg-no-repeat h-full;
    }
  }

  .flavor {
    max-width: 230px;
    @apply absolute block flex flex-col justify-center items-center;

    img {
      width: 80%;
    }

  // Lemon
    &:nth-of-type(1) {
      top: 10%;
      left: 10%;
    }

    // Raspberry
    &:nth-of-type(2) {
      bottom: 10%;
      right: 25%;
    }

    // Pomegranate
    &:nth-of-type(3) {
      bottom: 20%;
      left: 20%;
    }

    // Peach
    &:nth-of-type(4) {
      top: 20%;
      right: 20%;
    }

    // Lime
    &:nth-of-type(5) {
      top: 10%;
      left: 45%;
    }

    // Coconut
    &:nth-of-type(6) {
      bottom: 15%;
      right: -5%;
    }

    // Peach Mango
    &:nth-of-type(7) {
      bottom: 40%;
      left: 5%;
    }
  }
}
</style>
