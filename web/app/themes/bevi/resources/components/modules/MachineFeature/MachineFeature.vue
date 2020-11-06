<template>
  <section>
    <h1>Helo world</h1>
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
    created() {
      // console.log('show me something!');
    },
  };
</script>

<style>

</style>
