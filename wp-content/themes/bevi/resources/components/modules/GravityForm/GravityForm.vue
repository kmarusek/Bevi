<template>

  <section class="w-full bg-gray-100 comparison">
    <div class="relative flex min-h-screen flex-col overflow-hidden">

      <div class="block-content py-16 pb-4 px-0 lg:px-48 text-center">
        <h1 class="gsap-fades my-4 heading-two text-blue-500">DDDDDDDDDDDD</h1>
        <div v-html="block.the_form" class="my-4"></div>
      </div>

    </div>
  </section>

</template>

<style>


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
  },
};
</script>