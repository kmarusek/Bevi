<template>
  <section :class="{ 'bg-blue-100' : block.show_blue_background }">
    <div class="py-20 lg:py-32 container xl:max-w-6xl flex flex-col md:flex-row">
      <div class="md:w-5/12 lg:pr-16">
        <h3 class="text-3xl md:text-4xl mb-4 text-blue-600 font-semibold leading-tight">
          {{ block.title }}
        </h3>
        <div v-html="block.content" />
        <div v-if="block.bullets">
          <div
            v-for="bullet in block.bullets"
            :key="bullet.id"
            class="flex w-full my-4 sm:my-10"
          >
            <div class="w-1/5 pr-2 md:pr-0">
              <img
                :src="bullet.icon.sizes.thumbnail"
                alt=""
              >
            </div>
            <div class="w-4/5 sm:px-6">
              <h5 class="font-semibold text-lg tracking-wide text-blue-600">
                {{ bullet.title }}
              </h5>
              <div
                v-html="bullet.content"
                class="block-content smallest"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="md:w-7/12">
        <div v-html="block.pardot_form" />
      </div>
    </div>
  </section>
</template>

<script>
  export default {
    props: {
      block: {
        required: true,
        type: Object,
      },
    },
  };
</script>

<style>

</style>
