<template>
  <section class="py-10 md:pt-10 border-t-2 border-gray-200 w-full">
    <ul class="pl-4 md:pl-0 flex whitespace-no-wrap justify-start md:justify-center overflow-x-scroll md:overflow-x-visible md:flex-wrap">
      <li class="mr-4 md:mx-4 md:mb-4">
        <button
          class="tag"
          :class="{ selected : selectedTag === 'all flavors'}"
          @click="selectAll"
        >
          All Flavors
        </button>
      </li>
      <li
        v-for="(tag, index) in tags"
        :key="index"
        class="mr-4 md:mx-4 md:mb-4"
      >
        <button
          class="tag"
          :class="{ selected : selectedTag === tag.name}"
          @click="selectTag(tag.name)"
        >
          {{ tag.name }}
        </button>
      </li>
    </ul>
  </section>
</template>

<script>
  export default {
    props: {
      tags: {
        required: true,
        type: Array,
      },
    },
    data() {
      return {
        selectedTag: 'all flavors',
      };
    },
    methods: {
      selectAll() {
        this.selectedTag = 'all flavors';
        this.$emit('filter-list', this.selectedTag);
      },
      selectTag(item) {
        this.selectedTag = item;
        this.$emit('filter-list', item);
      },
    },
  };
</script>

<style lang="scss" scoped>
.tag {
  @apply font-space bg-cover bg-center bg-gray-400 bg-no-repeat text-blue-600 rounded-full py-3 px-6 text-sm leading-none font-medium text-center inline-flex flex-no-wrap transition-all duration-300 no-underline outline-none capitalize;

  &.selected {
    @apply text-white bg-blue-500;
  }

  &:hover {
    opacity: 0.8;
  }
}
</style>
