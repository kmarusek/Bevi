<template>
  <div
    class="carousel-dots"
    role="tablist"
  >
    <button
      class="mr-6"
      @click="carouselPrev()"
    >
      <span class="visually-hidden">go to previous slide</span>
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
    </button>
    <ul
      class="inner-dots-container"
      role="tablist"
    >
      <li
        v-for="(item, index) in itemCount"
        :key="index"
        @click="carouselPagination(index)"
        class="carousel-dot"
        :class="{ 'is-active': index === activeSlide }"
        :aria-controls="getDotControlsLabel(item)"
        :aria-selected="index === activeSlide"
        role="tab"
      >
        <span class="visually-hidden">{{ `Go to slide ${index}` }}</span>
      </li>
    </ul>
    <button
      class="ml-6"
      @click="carouselNext()"
    >
      <span class="visually-hidden">go to next slide</span>
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
    </button>
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
      items: {
        type: Array,
        required: false,
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
      getDotControlsLabel(item) {
        const slideID = `slide-${ item }-${ new Date().getTime() }`;

        return slideID;
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

.inner-dots-container {
  display: flex;
  flex-direction: row;
}
</style>
