<template>
  <section class="container py-6 px-12 mx-auto">
    <div class="flex flex-wrap">
      <div class="lg:w-1/4 sticky top-0 self-start bg-white border-gray-300 border-b-2 lg:border-0 overflow-hidden">
        <ul class="list-none flex overflow-x-scroll lg:flex-col pt-16 lg:pt-0 mb-3 lg:mb-0">
          <li
            v-for="(item, idx) in block.sidenav"
            :key="idx"
            class="flex-shrink-0 md:mb-2 lg:mb-5 link text-gray-600 lg:cursor-pointer mr-5 lg:mr-0 text-lg lg:text-base"
            :class="{'font-bold': (item.title == activeEntry)}"
            @click="goToSection(item.title)"
          >
            {{ item.title }}
          </li>
        </ul>
      </div>

      <div class="mt-8 lg:w-3/4 lg:mt-0">
        <div
          v-for="(item, idx) in block.sidenav"
          class="mb-16"
          :name="item.title"
          :key="idx"
          ref="section"
        >
          <h2
            class="h2 mb-2"
          >
            {{ item.title }}
          </h2>
          <div
            class="block-content smaller list-disc"
            v-html="item.main_content"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  export default {
    props: {
      block: {
        required: true,
        type: Object,
      },
    },
    data() {
      return {
        currentSection: null,
        observer: null,
        activeEntry: '',
      };
    },
    methods: {
      goToSection(sectionName) {
        window.scrollTo({
          top: this.$refs.section.filter((e) => e.attributes.name.value === sectionName)[0].offsetTop,
          behavior: 'smooth',
        });
      },
      observeSections() {
        this.$refs.section.forEach((section) => {
          this.observe(section);
        });
      },
      observe(entry) {
        this.observer.observe(entry);
      },
      setActiveSection(section) {
        this.currentSection = section;
      },
      initObserver() {
        const options = {
          threshold: [0.5],
        };
        this.observer = new IntersectionObserver((entries) => {
          const active = entries.filter((e) => e.isIntersecting);
          if (active.length) {
            this.activeEntry = active[0].target.attributes.name.value;
          }
        }, options);
      },
    },
    mounted() {
      this.initObserver();
      this.observeSections();
    },
    computed: {
      getSectionsRefs() {
        return this.$refs;
      },
    },
  };
</script>

<style>

</style>
