<template>
  <a :href="post.link">
    <div class="post-card">
      <CategoryButton
        :category="post._embedded['wp:term'][0][0]"
        class="absolute top-0 left-0 m-4"
      />
      <div
        class="post-card-image"
        :style="{ 'background-image' : `url(${ backgroundImage })` }"
      />
      <div class="details">
        <h5 class="h5">
          {{ post.title.rendered }}
        </h5>
        <a
          :href="post.link"
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
    computed: {
      backgroundImage() {
        return this.post._embedded['wp:featuredmedia']
          ? this.post._embedded['wp:featuredmedia'][0].source_url
          // eslint-disable-next-line
          : `${ require('../../../assets/images/placeholder.jpg') }`;
      },
    },
  };
</script>

<style lang="scss" scoped>
.post-card {
  @apply h-full w-full relative flex flex-col cursor-pointer;

  &-image {
    @apply w-full h-full rounded-md bg-cover bg-center bg-no-repeat;
  }

  & .details {
    @apply flex flex-col mt-4 ease-in-out transition-all duration-300;
  }

  /deep/ .category {
    @apply z-10;
  }
}
</style>
