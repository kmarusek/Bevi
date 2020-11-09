<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    width="66.877"
    height="67.268"
    viewBox="0 0 66.877 67.268"
    class="bubble"
    :class="strokeColor"
  >
    <path
      id="Path_148630"
      data-name="Path 148630"
      d="M-440.228,564.667c-.148,12.217-11.52,21.556-23.758,21.556s-25.384-9.045-23.575-21.858c1.711-12.118,11.337-21.707,23.575-21.707S-440.053,550.2-440.228,564.667Z"
      transform="translate(155.025 754.115) rotate(131)"
      fill="none"
      stroke="#dbdbdb"
      stroke-miterlimit="10"
      stroke-width="2"
      fill-rule="evenodd"
    />
  </svg>
</template>

<script>
  export default {
    props: {
      strokeColor: {
        required: false,
        type: String,
      },
    },
  };
</script>

<style lang="scss" scoped>
.bubble {
  &.blue {
    path {
      @apply stroke-blue;
    }
  }
  &.light-blue {
    path {
      @apply stroke-blue opacity-60;
    }
  }
  &.white {
    path {
      @apply stroke-white;
    }
  }
  &.gray {
    path {
      @apply opacity-80;
    }
  }
  &.light-gray {
    path {
      @apply opacity-30;
    }
  }
}
</style>
