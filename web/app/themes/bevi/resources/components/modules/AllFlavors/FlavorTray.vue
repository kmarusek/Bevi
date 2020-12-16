<template>
  <div
    id="tray"
    class="flavor-tray"
  >
    <button
      class="close"
      @click="$emit('close')"
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
          stroke="#E5E5E5"
          stroke-miterlimit="10"
          stroke-width="3"
          fill-rule="evenodd"
        />
      </svg>
      <img :src="require('~/assets/images/icons/close.svg')">
    </button>
    <div
      class="tray-wrapper"
      :class="trayLoading ? 'opacity-0' : 'opacity-100'"
    >
      <div class="flex flex-col md:w-1/2 md:px-10 xl:px-20 h-full">
        <div class="image-wrapper">
          <transition
            name="fade"
            mode="out-in"
          >
            <img
              v-if="swapImage"
              key="1"
              :src="flavor.flavor_calorie_table.sizes.large"
              :alt="flavor.post_title"
              class="flavor-image"
            >
            <img
              v-else
              key="2"
              :src="flavor.featured_image"
              :alt="flavor.post_title"
              class="flavor-image"
            >
          </transition>
        </div>
        <button
          v-if="flavor.flavor_calorie_table"
          class="btn md:w-8/12 md:m-auto rounded-md"
          @click="toggleImage"
        >
          <span v-if="swapImage">
            Hide calorie table
          </span>
          <span v-else>
            View calorie table
          </span>
        </button>
      </div>
      <div class="tray-content-wrapper">
        <h2
          class="h2 title"
          v-html="flavor.post_title"
        />
        <span
          class="accent"
          :class="flavor.flavor_accent_color ? `bg-${ flavor.flavor_accent_color }` : 'bg-blue'"
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
  </div>
</template>

<script>
  export default {
    props: {
      flavor: {
        required: true,
        type: Object,
      },
      trayLoading: {
        required: true,
        type: Boolean,
      },
    },
    data() {
      return {
        swapImage: false,
      };
    },
    methods: {
      toggleImage() {
        this.swapImage = !this.swapImage;
      },
    },
  };
</script>

<style lang="scss" scoped>
.flavor-tray {
  @apply bg-white w-full p-10 relative border-2 border-gray-200 rounded-lg flex h-auto;

  .image-wrapper {
    height: 300px;
    @apply flex items-center justify-center;

    @screen xl {
      height: 500px;
    }
  }

  .flavor-image {
    width: 230px;
    @apply h-auto py-6 m-auto;

    @screen xl {
      width: 400px;
    }
  }

  .tray-wrapper {
    transition: opacity ease-in-out 0.5s;
    @apply flex flex-col h-full;

    @screen md {
      @apply flex flex-row;
    }
  }

  .tray-content-wrapper {
    transition: opacity ease-in-out 0.5s;
    @apply flex flex-col justify-center mt-8;

    @screen md {
      @apply w-1/2 px-10 mt-0;
    }

    @screen xl {
      @apply px-20;
    }
  }

  .accent {
    width: 45px;
    height: 5px;
    @apply block mb-6;
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

// Adding background colors removed by purge css
.bg {
  &-blue {
    background-color: theme("colors.blue.default");
  }

  &-red {
    background-color: theme("colors.red.default");
  }

  &-yellow {
    background-color: theme("colors.yellow.default");
  }

  &-orange {
    background-color: theme("colors.orange.default");
  }

  &-green {
    background-color: theme("colors.green.default");
  }

  &-purple {
    background-color: theme("colors.purple.default");
  }
}

.fade-enter-active {
  transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
