<template>
  <section class="relative pb-20 overflow-hidden">
    <NewsFilter :categories="categories" />
    <div class="container grid">
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
      :disabled="loading"
      v-if="showLoadMore"
    >
      <span v-if="loading">Loading...</span>
      <span v-else>View more</span>
    </button>
    <single-bubble
      class="bubble parallax"
      data-speed="1"
      stroke-color="light-gray"
    />
    <single-bubble
      class="bubble parallax"
      data-speed="1.4"
      stroke-color="light-gray"
    />
    <single-bubble
      class="bubble parallax"
      data-speed="1.2"
      stroke-color="light-gray"
    />
    <single-bubble
      class="bubble parallax"
      data-speed="1.4"
      stroke-color="light-gray"
    />
  </section>
</template>

<script>
  import GSAPParallax from '~/mixins/GSAPParallax.js';

  export default {
    mixins: GSAPParallax,
    props: {
      categories: {
        required: true,
        type: Array,
      },
      categoryId: {
        type: Number,
        required: false,
      },
    },
    data() {
      return {
        posts: [],
        postsNumber: null,
        queryOptions: {
          per_page: 6,
          page: 1,
          categories: this.categoryId,
          _embed: true,
        },
        loading: false,
      };
    },
    mounted() {
      this.getPosts();
      this.getAllPosts();
    },
    computed: {
      showLoadMore() {
        return !!(this.postsShown < this.postsNumber);
      },
      postsShown() {
        return this.posts.length;
      },
    },
    methods: {
      getAllPosts() {
        this.axios.get('/wp-json/wp/v2/posts', { params: { categories: this.categoryId } }).then((response) => {
          this.postsNumber = response.data.length;
        });
      },
      getPosts() {
        this.axios.get('/wp-json/wp/v2/posts', { params: this.queryOptions }).then((response) => {
          this.posts = response.data;
        });
      },
      loadMore() {
        if (window.innerWidth <= 768) {
          this.queryOptions.per_page += 4;
        } else {
          this.queryOptions.per_page += 6;
        }
        this.loading = true;

        setTimeout(() => {
          this.getPosts();
          this.loading = false;
        }, 500);
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
    column-gap: 40px;
    row-gap: 20px;
    grid-template-columns: repeat(2, 1fr);
  }

  @screen xl {
    column-gap: 60px;
    row-gap: 40px;
    grid-template-columns: repeat(3, 1fr);
  }

  &-item {
    height: 25rem;
    @apply ease-in-out transition-all duration-300;

    @screen md {
      &:nth-child(4n + 1) {
        height: 35rem;
      }

      &:nth-child(4n + 2) {
        height: 25rem;
      }

      &:nth-child(4n + 3) {
        height: 25rem;
      }

      &:nth-child(4n + 4) {
        height: 35rem;
        margin-top: -10rem;
      }
    }

    @screen xl {
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
        margin-top: 0;
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

.bubble {
  @apply absolute;

  &:nth-of-type(1) {
    top: 12%;
    right: 3%;
  }

  &:nth-of-type(2) {
    top: 50%;
    right: -1%;
  }

  &:nth-of-type(3) {
    bottom: 0;
    right: 7%;
  }

  &:nth-of-type(4) {
    top: 40%;
    left: -2%;
  }
}
</style>
