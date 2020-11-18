<template>
  <section class="container">
      <p>It Begins</p>
    <!-- <div class="flex flex-col container relative pt-24 md:pt-40">
      <div class="flex items-center flex-wrap">
        <CategoryButton
          :category="postData.post_category[0]"
          class="mr-4 md:mr-6"
        />
        <span class="text-black mt-2">Posted: {{ postData.post_date | moment("from", "now") }}</span>
      </div>
      <h2 class="h2 mt-6 md:mt-10 mb-8 md:w-2/3">
        {{ postData.post_title }}
      </h2>
      <div class="flex mb-8 md:mb-24">
        <img
          v-if="postData.post_author_avatar"
          :src="postData.post_author_avatar"
          alt="avatar"
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
    </div>
    <div
      class="hero-image"
      :style="{ 'background-image' : `url(${ postData.featured_image })` }"
    /> -->
  </section>
</template>

<script>
  export default {
    props: {
      secret: {
        type: String,
        required: true,
      },
    },
  };
</script>

<style lang="scss" scoped>

</style>
