<template>
  <div class="carousel-dots">
    <div class="mr-6">
      <img
        :src="require('~/assets/images/icons/arrow.svg')"
        class="transform -rotate-90 arrow"
        @click="carouselPrev()"
      >
    </div>
    <button
      v-for="(item, index) in itemCount"
      :key="index"
      @click="carouselPagination(index)"
      class="carousel-dot"
      :class="{ 'is-active': index === activeSlide }"
    />
    <div class="ml-6">
      <img
        :src="require('~/assets/images/icons/arrow.svg')"
        class="transform rotate-90 arrow"
        @click="carouselNext()"
      >
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
  width: 15px;
  &:hover {
    @apply opacity-70;
  }
}
</style>
