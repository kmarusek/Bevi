<template>
  <section class="container py-6 px-12 mx-auto">
     <h2
      class="mb-6 md:mb-8 font-semibold text-xl md:text-3xl leading-tight text-center capitalize"
    >
      Frequently Asked Questions
    </h2>
    <div class="flex flex-wrap">
      <div class="lg:w-1/4 sticky top-0 self-start bg-white border-gray-300 border-b-2 lg:border-0 overflow-hidden">
        <ul class="list-none flex overflow-x-scroll lg:flex-col pt-16 lg:pt-0 mb-3 lg:mb-0">
          <li
            v-for="faq in faqs"
            :key="faq.ID"
            class="flex-shrink-0 md:mb-2 lg:mb-5 link text-gray-600 lg:cursor-pointer mr-5 lg:mr-0 text-lg lg:text-base"
            :class="{'font-bold': (faq.post_title === activeEntry)}"
            @click="goToSection(faq.post_title)"
          >
            {{ faq.post_title }}
          </li>
        </ul>
      </div>

      <div class="mt-8 lg:w-3/4 lg:mt-0">
        <div
          v-for="faq in faqs"
          class="mb-16"
          :name="faq.post_title"
          :key="faq.ID"
          ref="section"
        >
          <h2
            class=" font-semibold text-2xl mb-2 leading-tight"
          >
            {{ faq.post_title }}
          </h2>
          <div
            class="block-content smaller"
            v-html="faq.post_content"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script>
  export default {
    props: {
      faqs: {
        type: Array,
        required: true,
      },
    },
    data() {
      return {
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
      initObserver() {
        const options = {
          threshold: [0.3],
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

<style lang="scss" scoped>

  .block-content /deep/ p {
    margin-bottom: 1rem;
  }

  .faq-content {
    @apply overflow-y-hidden;
    transition: all 0.35s ease-in-out;
    max-height: 0;
    opacity: 0;

    &.open {
      @apply mt-4 pt-4 max-h-full;
      border-top: 1px solid #ccc;
      opacity: 1;
    }
  }

   .icon {
    background-image: url('../../../assets/images/icons/chevron.svg');
    @apply border-gray-400 bg-no-repeat absolute right-0 bg-center transform rotate-180;
    background-size: 10px;
    transition: all 0.3s ease;

    &.active {
      @apply rotate-0;
    }
  }

</style>
