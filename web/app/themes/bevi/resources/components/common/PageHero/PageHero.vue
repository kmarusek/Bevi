<template>
  <section class="relative overflow-hidden pb-32 pt-40">
    <div class="container z-10 relative">
      <h2 class="h2">
        {{ wp.pageTitle }}
      </h2>
    </div>
    <img
      :src="require('~/assets/images/backgrounds/page-header.jpg')"
      class="object-cover w-full h-full absolute top-0 lef-0 z-0"
    >
  </section>
</template>

<script>
  export default {
    props: {
      wp: {
        required: true,
        type: Object,
      },
    },
  };
</script>

<style lang="scss" scoped>

</style>
