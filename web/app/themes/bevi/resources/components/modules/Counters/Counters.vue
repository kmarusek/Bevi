<template>
  <section
    class="relative overflow-hidden flex items-center main-banner"
  >
    <div class="container xl:max-w-6xl z-10 relative text-center lg:text-left">
      <h1 class="h1">
        {{ wp.pageTitle }}
      </h1>
    </div>
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
