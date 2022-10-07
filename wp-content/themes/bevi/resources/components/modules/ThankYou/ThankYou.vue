<template>

  <section class="quiz w-full bg-gray-200 py-24">
    <div class="relative flex flex-col overflow-hidden">
      <div class="block-content">
        <div class="thankyou--body my-4 text-center">
          <img :src="block.image" />
          <div class="thankyou-title w-full mt-16">{{ block.title }}</div>
          <div class="desctption w-full">{{ block.description }}</div>
          <a v-bind:href="block.linkedin_url" class="social mx-2">ICON</a>
          <a v-bind:href="block.instagram_url" class="social mx-2">ICON</a>
          <a v-bind:href="block.facebook_url" class="social mx-2">ICON</a>
          <a v-bind:href="block.twitter_url" class="social mx-2">ICON</a>

        </div>
      </div>

      <div class="bubbles">
        <img
            :src="require(`~/assets/images/bubbles/bubble9.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-24 -left-36 -top-40 opacity-50"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble2.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-8 -left-30 top-64"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble10.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-12 -right-20 top-20 opacity-50"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble9.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-24 top-40 -right-12"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble10.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-12 -right-8 top-98"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble9.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-12 -left-30 bottom-36 opacity-50"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble10.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-16 -left-16 bottom-24 opacity-50"
            aria-hidden="true"
        >
      </div>

    </div>
  </section>

</template>

<style>

.thankyou--body {
  max-width: 736px;
  min-height: 400px;
  margin: auto;
  border-radius: 24px;
  background-color: #fff;
  padding: 0 96px 48px 96px;
}

.thankyou--body {
  background-image: url("/wp-content/themes/bevi/dist/images/quiz/thankyou.svg");
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}

.thankyou--body .thankyou-title {
  max-width: 544px;
  margin: 32px 0 24px;
  font-size: 40px;
  font-weight: 600;
  font-stretch: expanded;
  font-style: normal;
  line-height: normal;
  letter-spacing: 2px;
  text-align: left;
  color: #174cbb;
}

.thankyou--body .desctption {
  max-width: 544px;
  margin: 24px 0;
  font-size: 16px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.88;
  letter-spacing: 0.64px;
  text-align: left;
  color: #787878;
}

.thankyou--body .desctption.w-full {
  width: 100%;
}

@media screen and (max-width: 425px) {
  .thankyou--body {
    background-image: none !important;
    padding: 24px;
    margin: 20px;
  }
  .thankyou--body .thankyou-title {
    font-size: 30px;
    font-weight: 600;
    font-stretch: expanded;
    font-style: normal;
    line-height: normal;
    letter-spacing: 1.5px;
    text-align: left;
    color: #00223e;
  }
}

.quiz .bubbles {
  z-index: -1;
}
.thankyou--body .social {
  width: 48px;
  height: 48px;
}

</style>

<script>
export default {
  props: {
    block: {
      required: true,
      type: Object,
    },
  },
  mounted() {
    jQuery(document).ready(function () {

    });
  },
};
</script>