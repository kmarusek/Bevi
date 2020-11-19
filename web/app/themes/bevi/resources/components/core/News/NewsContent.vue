<template>
  <section class="container">
    <div class="flex relative pt-24 md:pt-40">
      <div class="w-full lg:w-2/12 md:mb-6 md:py-8 gsap-fade">
        Share
      </div>
      <div
        class="post-content w-full lg:w-8/12 mt-2 md:mt-6 md:px-8 gsap-fade mx-auto "
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
  .post-content /deep/ h1,
  .h1 {
    @apply mb-8 leading-none;
  }

  .post-content /deep/ h2,
  .h2 {
    @apply mb-8 leading-none;
  }

  .post-content /deep/ h3,
  .h3 {
    @apply mb-8 leading-none;
  }

  .post-content /deep/ h4,
  .h34{
    @apply mb-8 leading-none;
  }

  .post-content /deep/ h5,
  .h5 {
    @apply mb-8 leading-none;
  }

  .post-content /deep/ p,
  .p {
    @apply mb-6;
  }

  .post-content /deep/ img,
  .img {
    @apply rounded-md;
  }

  .post-content /deep/ figcaption,
  .figcaption {
    @apply text-center text-sm mb-12;
  }
</style>
