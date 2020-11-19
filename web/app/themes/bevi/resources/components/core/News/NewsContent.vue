<template>
  <section class="container">
    <div class="flex flex-col relative pt-24 md:pt-40">
      <div
        class="post-content w-full lg:w-9/12 mt-2 md:mt-6 md:px-8 gsap-fade"
        v-html="postData.post_content"
      />
    </div>
  </section>
</template>

<script>
  export default {
    props: {
      postData: {
        type: Object,
        required: true,
      },
    },
    created() {
      console.log(this);
    },
  };
</script>

<style lang="scss" scoped>

</style>
