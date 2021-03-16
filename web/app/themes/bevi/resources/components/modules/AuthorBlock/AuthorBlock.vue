<template>
  <div class="container flex items-center relative">
    <div class="w-full lg:w-10/12 md:space-x-4 flex items-center mx-auto">
      <div
        @click="scrollToTop()"
        class="scroll-top hidden md:flex"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 96.316 92.164"
          class="absolute inset-0 -z-10"
        >
          <path
            id="Path_148631"
            data-name="Path 148631"
            d="M-394.921,582.692c3.942,24.262-23.3,55.775-47.918,55.775s-48.221-23.324-44.582-49.1c3.442-24.38,19.96-40.061,44.582-40.061S-398.87,558.389-394.921,582.692Z"
            transform="translate(489.344 -547.803)"
            fill="none"
            stroke="#00223E"
            stroke-miterlimit="10"
            stroke-width="3"
            fill-rule="evenodd"
          />
        </svg>
        <img :src="require('~/assets/images/icons/arrow.svg')">
      </div>
      <div class="w-full border-t-2 border-b-2 md:border-b-0 border-gray-200 py-10 flex items-center flex-wrap mx-auto">
        <img
          v-if="postData.post_author_avatar"
          :src="postData.post_author_avatar"
          alt="avatar"
          class="avatar"
        >
        <div class="flex flex-col">
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
        <div
          @click="scrollToTop()"
          class="scroll-top flex md:hidden ml-auto"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 96.316 92.164"
            class="absolute inset-0 -z-10"
          >
            <path
              id="Path_148631"
              data-name="Path 148631"
              d="M-394.921,582.692c3.942,24.262-23.3,55.775-47.918,55.775s-48.221-23.324-44.582-49.1c3.442-24.38,19.96-40.061,44.582-40.061S-398.87,558.389-394.921,582.692Z"
              transform="translate(489.344 -547.803)"
              fill="none"
              stroke="#00223E"
              stroke-miterlimit="10"
              stroke-width="3"
              fill-rule="evenodd"
            />
          </svg>
          <img :src="require('~/assets/images/icons/arrow.svg')">
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      postData: {
        type: Object,
        required: true,
      },
    },
    methods: {
      scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      },
    },
  };
</script>

<style lang="scss" scoped>
.scroll-top {
  width: 60px;
  height: 60px;
  @apply items-center justify-center cursor-pointer relative;

  &:hover > img {
    transform: translateY(-3px);
  }

  svg {
    z-index: -1;
  }

  img {
    height: 12px;
    width: 12px;
    @apply ease-in-out transition-all duration-300;
  }
}

.avatar {
  width: 60px;
  height: 60px;
  @apply rounded-full mr-6;
}
</style>
