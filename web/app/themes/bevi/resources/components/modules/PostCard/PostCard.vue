<template>
  <a :href="post.permalink">
    <div class="post-card">
      <CategoryButton
        :category="post.post_category[0]"
        class="absolute top-0 left-0 m-4"
      />
      <div
        class="post-card-image"
        :style="{ 'background-image': 'url(' + post.featured_image + ')' }"
      />
      <div class="details">
        <h5 class="h5">
          {{ post.post_title }}
        </h5>
        <a
          :href="post.permalink"
          class="text-primary underline text-sm"
        >
          Read more
        </a>
      </div>
    </div>
  </a>
</template>

<script>
  export default {
    props: {
      post: {
        required: true,
        type: Object,
      },
    },
  };
</script>

<style lang="scss" scoped>
.post-card {
  @apply h-full w-full relative flex flex-col;

  &-image {
    @apply w-full h-full rounded-md bg-cover bg-center bg-no-repeat;
  }

  & .details {
    @apply flex flex-col mt-4 ease-in-out transition-all duration-300;
  }
}
</style>
