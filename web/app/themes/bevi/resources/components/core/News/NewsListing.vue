<template>
  <section class="container pb-20">
    <NewsFilter :categories="categories" />
    <div class="grid">
      <div
        v-for="(post, index) in posts"
        :key="index"
        class="grid-item"
      >
        <PostCard :post="post" />
      </div>
    </div>
    <button
      @click="loadMore"
      class="btn flex mx-auto mt-10"
    >
      View more
    </button>
  </section>
</template>

<script>
  export default {
    props: {
      categories: {
        required: true,
        type: Array,
      },
      posts: {
        required: true,
        type: Array,
      },
    },
    methods: {
      loadMore() {
        console.log('LOAD');
      },
    },
  };
</script>

<style lang="scss" scoped>
.grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  column-gap: 0;
  row-gap: 20px;

  @screen md {
    column-gap: 60px;
    row-gap: 40px;
    grid-template-columns: repeat(3, 1fr);
  }

  &-item {
    height: 20rem;
    @apply ease-in-out transition-all duration-300;

    @screen md {
      &:nth-child(6n + 1) {
        height: 35rem;
      }

      &:nth-child(6n + 2) {
        height: 25rem;
      }

      &:nth-child(6n + 3) {
        height: 30rem;
      }

      &:nth-child(6n + 4) {
        height: 25rem;
      }

      &:nth-child(6n + 5) {
        height: 35rem;
        margin-top: -10rem;
      }

      &:nth-child(6n + 6) {
        height: 30rem;
        margin-top: -5rem;
      }
    &:hover {
      transform: translateY(-5px);

      /deep/ .post-card .details {
        transform: translateY(10px);
      }
    }
    }

  }
}
</style>
