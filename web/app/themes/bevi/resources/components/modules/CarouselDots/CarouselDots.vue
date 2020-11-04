<template>
  <div class="carousel-dots">
    <div
      class="mr-6"
      @click="carouselPrev()"
    >
      <svg
        viewBox="0 0 22.136 8.483"
        class="transform arrow"
      >
        <path
          id="Path_148162"
          data-name="Path 148162"
          d="M22.136,4.236,17.886,0,16.474,1.417,18.31,3.243H0v2H18.3L16.479,7.071,17.9,8.483Z"
          transform="translate(22.136 8.483) rotate(180)"
        />
      </svg>
    </div>
    <button
      v-for="(item, index) in itemCount"
      :key="index"
      @click="carouselPagination(index)"
      class="carousel-dot"
      :class="{ 'is-active': index === activeSlide }"
    />
    <div
      class="ml-6"
      @click="carouselNext()"
    >
      <svg
        viewBox="0 0 22.136 8.483"
        class="transform rotate-180 arrow"
      >
        <path
          id="Path_148162"
          data-name="Path 148162"
          d="M22.136,4.236,17.886,0,16.474,1.417,18.31,3.243H0v2H18.3L16.479,7.071,17.9,8.483Z"
          transform="translate(22.136 8.483) rotate(180)"
        />
      </svg>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      itemCount: {
        type: Number,
        required: true,
      },
      activeSlide: {
        type: Number,
        required: true,
      },
    },
    methods: {
      carouselPagination(index) {
        this.$emit('carousel-item-active', index);
      },
      carouselPrev() {
        this.$emit('carousel-prev');
      },
      carouselNext() {
        this.$emit('carousel-next');
      },
    },
  };
</script>

<style lang="scss" scoped>
.arrow {
  @apply opacity-50 cursor-pointer transition-opacity duration-200;
  width: 22px;

  &:hover {
    @apply opacity-70;
  }
}
</style>
