<template>
  <section class="container">
    <div class="flex relative pt-8 md:pt-20">
      <div
        class="post-content w-full lg:w-8/12 mt-2 md:mt-6 md:px-8 mx-auto "
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

  .post-content /deep/ .wp-block-quote {
    background-image: url('../../../assets/images/icons/quote.svg');
    @apply font-semibold not-italic w-full leading-tight text-sm bg-contain bg-no-repeat px-8 py-4 my-4;

    @screen md {
      @apply px-16 py-16 my-12 text-xl;
    }

    p {
      @apply mb-3;
    }

    cite {
      @apply mt-4 not-italic text-xs uppercase tracking-wide;
      color: theme("colors.blue.default");
    }
  }
</style>
