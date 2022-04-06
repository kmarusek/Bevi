<template>
  <section class="container">
    <div class="flex relative pt-8 md:pt-20">
      <div
        class="post-content w-full lg:w-10/12 mt-2 md:mt-6 mx-auto"
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
  .post-content:deep(h1){
    @apply font-body text-4.5xl text-blue-600 font-semibold leading-none mb-6;
    @screen md {
      @apply text-5.75xl;
    }
  }

  .post-content:deep(h2) {
    @apply font-body text-3xl text-blue-600 font-semibold  mb-6;
    @screen md {
      @apply text-4xl leading-tight;
    }
    @screen lg {
      @apply text-5xl font-bold;
    }
  }

  .post-content:deep(h3) {
    @apply font-body text-3xl font-semibold leading-tight mb-6;
    @screen md {
      @apply text-3xl font-medium;
    }
  }

  .post-content:deep(h4) {
    @apply font-body text-3xl text-blue-600 mb-6;
  }

  .post-content:deep(h5) {
    @apply font-semibold text-xl text-blue-600 mb-6;
  }

  .post-content:deep(h6) {
    @apply font-extrabold text-blue-600 uppercase tracking-widest text-xs mb-6;
  }

  .post-content:deep(p) {
    @apply text-gray-700 text-base leading-7 font-space mb-6;

    a {
      @apply text-primary underline font-bold;
    }

    &.smaller {
      @apply text-sm leading-normal;
    }

    &.smallest {
      @apply text-xs leading-normal;
    }
  }

  .post-content:deep(img),
  .img {
    @apply rounded-md mx-auto;
  }

  .post-content:deep(figcaption),
  .figcaption {
    @apply text-center text-sm mb-12 mx-auto;
  }

  .post-content:deep(.wp-block-quote) {
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
