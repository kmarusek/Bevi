<template>
  <section>
    <Disclosure
      as="div"
      class="py-12 container xl:max-w-4xl"
      v-slot="{ open }"
    >
      <h3 class="text-3xl mb-4 text-gray-600 font-semibold leading-tight">
        {{ block.title }}
      </h3>
      <div v-show="!open">
        <div class="relative">
          <div class="gradient absolute w-full h-full inset-0" />
          <div
            class="content"
            v-html="`${block.content.split('</p>')[0]}${block.content.split('</p>')[1]}`"
          />
        </div>
        <DisclosureButton class="btn outline-blue flex mx-auto">
          <img
            :src="require('~/assets/images/icons/plus.svg').default"
            class="w-4 h-4 mr-3"
          >
          {{ block.button_text }}
        </DisclosureButton>
      </div>
      <div v-show="open">
        <DisclosurePanel static>
          <div
            class="content"
            v-html="block.content"
          />
        </DisclosurePanel>
        <DisclosureButton class="btn outline-blue flex mx-auto">
          <img
            :src="require('~/assets/images/icons/minus.svg').default"
            class="w-4 h-4 mr-3"
          >
          Close
        </DisclosureButton>
      </div>
    </Disclosure>
  </section>
</template>

<script>
  import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
  } from '@headlessui/vue';

  export default {
    components: {
      Disclosure,
      DisclosureButton,
      DisclosurePanel,
    },
    props: {
      block: {
        type: Object,
        required: true,
      },
    },
  };
</script>

<style lang="scss" scoped>
.content:deep(p) {
  @apply text-gray-700 text-base leading-7 font-space mb-6;

  a {
    @apply text-primary underline font-bold;
  }
}

.gradient {
  background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 100%);
}
</style>
