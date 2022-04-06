<template>
  <svg
    width="100%"
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 1164 1164"
    :class="small ? 'small-blob' : 'image-blob'"
  >
    <clipPath id="shape">
      <!-- eslint-disable -->
      <path d="M596.15,831.32c318.42-46.18,355.71-416.13,258.57-618-82.24-170.87-271.19-246.11-478-200-306.61,68.37-390.29,310.89-375,465,25.81,259.66,207.74,409,594.42,352.9"/></clipPath>
      <!-- eslint-enable -->
    <image
      width="100%"
      height="100%"
      clip-path="url(#shape)"
      :xlink:href="image"
      :alt="alt"
    /></svg>
</template>

<script>
  export default {
    props: {
      image: {
        required: true,
        type: String,
      },
      alt: {
        required: false,
        type: String,
      },
      small: {
        required: false,
        type: String,
      },
    },
  };
</script>

<style lang="scss" scoped>
.small-blob {
    height: 250px;
}
.image-blob {
  height: 290px;

  @screen md {
    height: 340px;
  }
}

svg path {
  transform: scale(1.25);
}
</style>
