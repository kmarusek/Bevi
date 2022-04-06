<template>
  <div class="container max-w-xs md:max-w-4xl 2xl:max-w-10xl py-12">
    <h2 class="h1 second-title text-blue-600 text-center">
      {{ block.title }}
    </h2>
    <div class="relative py-4 mt-12 xl:py-32 xl:mt-24">
      <img
        :src="require(`~/assets/images/bubbles/bubble9.svg`).default"
        alt="bubble icon"
        class="hidden lg:block absolute w-24 -left-36 -top-4 opacity-50"
        aria-hidden="true"
      >
      <img
        :src="require(`~/assets/images/bubbles/bubble2.svg`).default"
        alt="bubble icon"
        class="hidden lg:block absolute w-8 -left-30 top-20"
        aria-hidden="true"
      >
      <img
        :src="require(`~/assets/images/bubbles/bubble10.svg`).default"
        alt="bubble icon"
        class="hidden lg:block absolute w-12 -right-20 top-0 opacity-50"
        aria-hidden="true"
      >
      <img
        :src="require(`~/assets/images/bubbles/bubble9.svg`).default"
        alt="bubble icon"
        class="hidden lg:block absolute w-24 top-8 -right-36"
        aria-hidden="true"
      >
      <img
        :src="require(`~/assets/images/bubbles/bubble10.svg`).default"
        alt="bubble icon"
        class="hidden lg:block absolute w-12 -right-8 top-72"
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
      <img
        :src="block.desktop_image.url"
        :alt="block.desktop_image.alt"
        :width="block.desktop_image.width"
        :height="block.desktop_image.height"
        loading="lazy"
        class="hidden xl:block"
      >
      <img
        :src="block.mobile_image.sizes.large"
        :alt="block.mobile_image.alt"
        :width="block.mobile_image.sizes['large-width']"
        :height="block.mobile_image.sizes['large-height']"
        loading="lazy"
        class="xl:hidden"
      >
      <div
        v-if="block.bubbles.length >= 1"
        class="hidden xl:block absolute -top-16 left-0 2xl:-top-8 2xl:left-12"
      >
        <div class="bubble flex w-100 justify-center px-16 py-16 flex-col">
          <h2 class="text-2xl leading-8 font-medium mb-2">
            {{ block.bubbles[0].title }}
          </h2>
          <div v-html="block.bubbles[0].content" />
        </div>
      </div>
      <div
        v-if="block.bubbles.length >= 2"
        class="hidden xl:block absolute -top-16 right-4 2xl:-top-8 2xl:right-32"
      >
        <div class="bubble flex w-108 justify-center px-16 py-16 flex-col">
          <h2 class="text-2xl leading-8 font-medium mb-2">
            {{ block.bubbles[1].title }}
          </h2>
          <div v-html="block.bubbles[1].content" />
        </div>
      </div>
      <div
        v-if="block.bubbles.length >= 3"
        class="hidden xl:block absolute top-72 -left-48 2xl:top-104 2xl:-left-24"
      >
        <div class="bubble flex w-100 justify-center px-16 py-16 flex-col">
          <h2 class="text-2xl leading-8 font-medium mb-2">
            {{ block.bubbles[2].title }}
          </h2>
          <div v-html="block.bubbles[2].content" />
        </div>
      </div>
      <div
        v-if="block.bubbles.length >= 4"
        class="hidden xl:block absolute top-60 -right-44 2xl:top-104 2xl:-right-12"
      >
        <div class="bubble flex w-108 justify-center px-16 py-16 flex-col">
          <h2 class="text-2xl leading-8 font-medium mb-2">
            {{ block.bubbles[3].title }}
          </h2>
          <div v-html="block.bubbles[3].content" />
        </div>
      </div>
      <div
        v-if="block.bubbles.length >= 5"
        class="hidden xl:block absolute top-152 left-0 2xl:left-20 2xl:top-auto 2xl:bottom-90"
      >
        <div class="bubble flex w-100 justify-center px-16 py-16 flex-col">
          <h2 class="text-2xl leading-8 font-medium mb-2">
            {{ block.bubbles[4].title }}
          </h2>
          <div v-html="block.bubbles[4].content" />
        </div>
      </div>
      <div
        v-if="block.bubbles.length >= 6"
        class="hidden xl:block absolute top-152 right-0 2xl:top-auto 2xl:bottom-98 2xl:right-24"
      >
        <div class="bubble flex w-100 justify-center px-16 py-16 flex-col">
          <h2 class="text-2xl leading-8 font-medium mb-2">
            {{ block.bubbles[5].title }}
          </h2>
          <div v-html="block.bubbles[5].content" />
        </div>
      </div>
      <img
        :src="require(`~/assets/images/bubbles/bubble9.svg`).default"
        alt="bubble icon"
        class="absolute w-16 -left-16 opacity-50 lg:hidden"
        aria-hidden="true"
      >
      <img
        :src="require(`~/assets/images/bubbles/bubble2.svg`).default"
        alt="bubble icon"
        class="absolute w-8 -bottom-20 -left-14 lg:hidden"
        aria-hidden="true"
      >
      <img
        :src="require(`~/assets/images/bubbles/bubble10.svg`).default"
        alt="bubble icon"
        class="absolute w-12 -right-8 -bottom-20 opacity-50 lg:hidden"
        aria-hidden="true"
      >
      <img
        :src="require(`~/assets/images/bubbles/bubble2.svg`).default"
        alt="bubble icon"
        class="absolute w-16 -bottom-48 -right-12 lg:hidden"
        aria-hidden="true"
      >
      <img
        :src="require(`~/assets/images/bubbles/bubble9.svg`).default"
        alt="bubble icon"
        class="absolute w-8 -left-12 -bottom-56 opacity-50 lg:hidden"
        aria-hidden="true"
      >
      <img
        :src="require(`~/assets/images/bubbles/bubble2.svg`).default"
        alt="bubble icon"
        class="absolute w-8 -bottom-72 -left-14 lg:hidden"
        aria-hidden="true"
      >
      <img
        :src="require(`~/assets/images/bubbles/bubble10.svg`).default"
        alt="bubble icon"
        class="absolute w-12 right-0 -bottom-90 lg:hidden"
        aria-hidden="true"
      >
    </div>
    <div class="xl:hidden py-4 xl:py-12 space-y-6">
      <div
        v-for="(bubble, key) in block.bubbles"
        :key="key"
      >
        <h2 class="text-blue text-xl font-bold">
          {{ bubble.title }}
        </h2>
        <div v-html="bubble.content" />
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      block: {
        type: Object,
        required: true,
      },
    },
  };
</script>

<style scoped>
.bubble {
  background-color: #e6f3ff;
  border-radius: 94% 85% 79% 82%;
}
</style>
