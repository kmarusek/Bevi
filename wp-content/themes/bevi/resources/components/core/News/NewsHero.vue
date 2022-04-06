<template>
  <section class="relative">
    <div class="flex flex-col container relative pt-24 md:pt-40">
      <div class="w-full lg:w-10/12 mx-auto">
        <div class="flex items-center flex-wrap">
          <CategoryButton
            :category="category"
            class="mr-4 md:mr-6"
          />
        </div>
        <h1 class="h2 mt-6 md:mt-10 mb-8 md:w-2/3">
          {{ postData.post_title }}
        </h1>
        <div class="flex mb-8 md:mb-24">
          <img
            v-if="postData.post_author_avatar"
            :src="postData.post_author_avatar"
            alt="Photo of author"
            class="avatar "
          >
          <div class="flex flex-col justify-center">
            <div class="capitalize h5">
              {{ postData.post_author }}
            </div>
            <div
              v-if="postData.post_author_role"
              class="text-xs text-gray-700"
            >
              {{ postData.post_author_role }}
            </div>
          </div>
        </div>
        <div class="aspect-w-16 aspect-h-9">
          <img
            :src="postData.featured_image.src"
            :alt="postData.featured_image.alt"
            :width="postData.featured_image.width"
            :height="postData.featured_image.height"
            class="object-cover"
          >
        </div>
      </div>
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
      categories: {
        required: true,
        type: Object,
      },
    },
    computed: {
      category() {
        const categoryList = Object.values(this.categories);
        return categoryList.filter((category) => category.cat_ID === this.postData.post_category[0].cat_ID)[0];
      },
    },
  };
</script>

<style lang="scss" scoped>
.avatar {
  width: 60px;
  height: 60px;
  @apply rounded-full mr-4;

  @screen md {
    @apply mr-6;
  }
}
</style>
