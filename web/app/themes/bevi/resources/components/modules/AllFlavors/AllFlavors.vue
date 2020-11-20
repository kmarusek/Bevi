<template>
  <section class="all-flavors gsap-fade-section">
    <div class="container text-center">
      <div class="relative mb-4 md:mb-10">
        <div class="title-wrapper gsap-fade">
          <h2 class="h2">
            {{ block.title }}
          </h2>
        </div>
        <div class="mt-2 md:mt-6 block-content smaller md:px-8 gsap-fade">
          <p>
            {{ block.intro_text }}
          </p>
        </div>
      </div>
      <FlavorsFilter
        @filter-list="filterList"
        :tags="tags"
      />
    </div>
    <div class="container flex flex-wrap justify-center">
      <FlavorCard
        v-for="(flavor, index) in flavors"
        :key="index"
        :flavor="flavor"
        @click.native="openTray(flavor.post_name, index)"
      />
      <FlavorTray
        v-if="showTray"
        :flavor="flavors[selectedIndex]"
        @close="closeTray"
      />
    </div>
  </section>
</template>

<script>
  import GSAPFade from '~/mixins/GSAPFade.js';

  export default {
    mixins: [GSAPFade],
    props: {
      block: {
        required: true,
        type: Object,
      },
      flavors: {
        required: true,
        type: Array,
      },
      tags: {
        required: true,
        type: Array,
      },
    },
    data() {
      return {
        selectedTag: '',
        filteredList: [],
        showTray: true,
        selectedIndex: 0,
      };
    },
    // computed: {
    //   filteredList() {
    //     },
    // },
    methods: {
      closeTray() {
        this.showTray = false;
      },
      openTray(item, index) {
        this.showTray = true;
        this.selectedIndex = index;
        console.log(item, index);
      },
      filterList(item) {
        this.selectedTag = item;
        // return this.flavors.filter((tag) => tag.post_tags.slug.includes(this.selectedTag));
        // this.filteredList = this.flavors.flatMap((flavor) => flavor.post_tags.map(() => ({
        //   // result: postTag.name.replace(new RegExp(this.selectedTag, 'gi'), (match) => match),
        //   tag: flavor.post_tags.name,
        // })));
      },
    },
  };
</script>

<style lang="scss" scoped>
.all-flavors {
  @apply relative overflow-x-hidden py-20 bg-gray-100;

  @screen md {
    @apply py-48;
  }
}
</style>
