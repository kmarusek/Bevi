<template>
  <div class="flavor">
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
    },
  };
</script>

<style lang="scss" scoped>
.flavor {
  min-width: 160px;
  @apply block flex flex-col justify-center items-center w-1/5 px-4 mb-16 mx-2 cursor-pointer;

  @screen md {
    min-width: 220px;
    @apply mb-40 mx-0 px-10;
  }

  .image-wrapper {
    width: 160px;
    height: 160px;
    @apply relative;

    @screen md {
      width: 220px;
      height: 220px;
    }

    .badge {
      width: 50px;
      height: 50px;
      @apply absolute top-0 right-0 m-4;
    }

    .callout {
      transform: translate3d(55px, -55px, 0) rotate(5deg);
      @apply absolute top-0 right-0;

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
    @apply text-gray-550 font-semibold leading-tight mt-2;
  }
}
</style>
