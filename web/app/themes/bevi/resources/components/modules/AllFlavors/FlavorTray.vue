<template>
  <div class="flavor-tray">
    <button class="close" @click="$emit('close')">
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
          stroke="#E5E5E5"
          stroke-miterlimit="10"
          stroke-width="3"
          fill-rule="evenodd"
        />
      </svg>
      <img :src="require('~/assets/images/icons/close.svg')">
    </button>
    <div class="flex flex-col md:w-1/2 md:px-10 xl:px-20">
      <img
        :src="flavor.featured_image"
        :alt="flavor.post_title"
        class="flavor-image"
      >
      <button class="btn md:w-8/12 md:m-auto rounded-md">
        View calorie table
      </button>
    </div>
    <div class="flex flex-col justify-center md:w-1/2 md:px-10 xl:px-20 mt-8 md:mt-0">
      <h2
        class="h2 title"
        v-html="flavor.post_title"
      />
      <p
        class="mb-8"
        v-html="flavor.post_content"
      />
      <p
        class="mb-8 text-sm text-gray"
        v-html="flavor.flavor_ingredients"
      />
      <ul
        class="flex"
        v-if="flavor.flavor_icons"
      >
        <li
          v-for="(icon, i) in flavor.flavor_icons"
          :key="i"
          class="flavor-tray-icon"
        >
          <img
            :src="icon.image.sizes.thumbnail"
            :alt="icon.image.alt"
          >
          <span
            v-if="icon.callout"
            class="callout"
          >
            <img
              :src="icon.callout.sizes.large"
              :alt="icon.callout.alt"
            >
          </span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      flavor: {
        required: true,
        type: Object,
      },
    },
    computed: {
      accentColor() {
        return { '--accent-color': `theme('colors.${ this.flavor.flavor_accent_color }.default')` };
      },
    },
  };
</script>

<style lang="scss" scoped>
.flavor-tray {
  @apply block flex flex-col bg-white w-full p-10 relative border-2 border-gray-200 rounded-lg;

  @screen md {
    @apply flex-row;
  }

  .flavor-image {
    max-width: 230px;
    @apply w-full py-6 m-auto;

    @screen md {
      @apply max-w-none;
    }
  }

  .title {
    @apply mb-6;

    &:after {
      content: "";
      width: 45px;
      height: 5px;
      background: var(--accent-color);
      @apply block bg-purple;
    }
  }

  &-icon {
    width: 40px;
    height: 40px;
    @apply relative mr-6;

    .callout {
      transform: translate3d(calc(100% + 10px), 30px, 0);
      @apply absolute right-0 hidden;

      @screen lg {
        @apply block;
      }

      & img {
        @apply max-w-none;
      }

      &:before {
        content: "";
        background: url("../../../assets/images/icons/scribble_arrow_3.svg");
        transform: translateX(-35px) translateY(-32px);
        width: 60px;
        background-size: 100% 100%;
        @apply absolute left-0 bg-no-repeat h-full;
      }
    }
  }

  .close {
    width: 60px;
    height: 60px;
    @apply flex items-center justify-center cursor-pointer absolute top-0 right-0 m-8;

    img {
      height: 12px;
      width: 12px;
      @apply ease-in-out transition-all duration-300;
    }
  }
}
</style>
