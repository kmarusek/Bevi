<template>
  <div
    class="flavor"
    :class="{ showTray : 'tray-open' }"
  >
    <div class="image-wrapper">
      <img
        :src="flavor.featured_image"
        :alt="flavor.post_title"
        class="w-full"
      >
      <div v-if="flavor.flavor_badge">
        <img
          v-if="flavor.flavor_badge.image"
          :src="flavor.flavor_badge.image.sizes.thumbnail"
          :alt="flavor.flavor_badge.image.alt"
          class="badge"
        >
        <span
          v-if="flavor.flavor_badge.callout"
          class="callout"
        >
          <img
            :src="flavor.flavor_badge.callout.sizes.large"
            :alt="flavor.flavor_badge.callout.alt"
          >
        </span>
      </div>
    </div>
    <p
      class="label"
      v-html="flavor.post_title"
    />
  </div>
</template>

<script>
  export default {
    props: {
      flavor: {
        required: true,
        type: Object,
      },
      showTray: {
        required: true,
        type: Boolean,
      },
    },
  };
</script>

<style lang="scss" scoped>
.flavor {
  @apply block flex flex-col justify-center items-center w-1/2 px-4 my-12 cursor-pointer;

  @screen md {
    padding: 1%;
    @apply w-1/3;
  }

  @screen xl {
    padding: 2%;
    @apply mx-0 w-1/5;
  }

  .image-wrapper {
    max-width: 160px;
    max-height: 160px;
    @apply relative;

    @screen md {
      max-width: 220px;
      max-height: 220px;
    }

    .badge {
      width: 40px;
      height: 40px;
      @apply absolute top-0 right-0 m-2;

      @screen md {
        width: 50px;
        height: 50px;
      }
    }

    .callout {
      transform: translate3d(55px, -55px, 0) rotate(5deg);
      @apply absolute top-0 right-0 hidden;

      @screen md {
        @apply block;
      }

      &:before {
        content: "";
        background: url("../../../assets/images/icons/scribble_arrow_2.svg");
        transform: translate3d(-35px, 38px, 0);
        width: 60px;
        background-size: 100% 100%;
        @apply absolute left-0 bg-no-repeat h-full;
      }
    }
  }

  .label {
    line-height: 40px;
    @apply text-gray-550 font-semibold leading-tight mt-2 text-center;
  }

  &.selected-flavour {
    @apply relative;

    &:after {
      content:'';
      width: 30px;
      height: 30px;
      bottom: -65px;
      transform: rotate(-45deg);
      @apply border-2 border-b-0 border-l-0 border-gray-200 bg-white absolute;
    }
  }
}
</style>
