<template>
  <section class="container py-6 px-12 mx-auto">
    <div class="flex flex-wrap -m-4">
      <div class="lg:w-1/4 lg:sticky top-0 self-start">
        <nav class="list-none">
          <li
            v-for="(item, idx) in block.sidenav"
            :key="idx"
            class="mb-3link text-gray-600 hover:text-gray-800 lg:cursor-pointer"
            :class="{'font-bold': (item.title == activeEntry)}"
            @click="goToSection(item.title)"
          >
            {{ item.title }}
          </li>
        </nav>
      </div>

      <div class="lg:w-3/4 /4">
        <div
          v-for="(item, idx) in block.sidenav"
          class="mb-10"
          :name="item.title"
          :key="idx"
          ref="section"
        >
          <h2
            class="h2 title-font font-medium text-black mb-3"
          >
            {{ item.title }}
          </h2>
          <div
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
