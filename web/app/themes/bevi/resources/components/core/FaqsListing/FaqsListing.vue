<template>
  <section class="container pt-24 lg:pt-32 px-4 lg:px-12 mx-auto">
    <div class="flex flex-wrap">
      <div class="hidden lg:block lg:w-1/4 sticky top-0 self-start bg-white border-gray-300 border-b-2 lg:border-0 overflow-hidden">
        <ul class="list-none flex overflow-x-scroll lg:flex-col pt-16 lg:pt-0 mb-3 lg:mb-0 lg:pr-4">
          <li
            v-for="faq in faqs"
            :key="faq.ID"
            class="flex-shrink-0 md:mb-2 lg:mb-5 link text-gray-600 lg:cursor-pointer mr-5 lg:mr-0 text-lg lg:text-base px-2"
            :class="{'font-bold text-blue-600': (faq.ID === activeEntry)}"
            @click="goToSection(faq.ID)"
            v-html="faq.post_title"
          />
        </ul>
      </div>

      <div class="mt-8 lg:w-3/4 lg:mt-0">
        <div
          v-for="faq in faqs"
          class="mb-16"
          :key="faq.ID"
          :id="faq.ID"
          :ref="faq.ID"
        >
          <h2
            class=" font-semibold text-2xl mb-2 leading-tight text-blue-600"
            v-html="faq.post_title"
          />
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
        activeEntry: null,
      };
    },
    methods: {
      goToSection(refName) {
        const element = this.$refs[refName][0];
        this.activeEntry = +this.$refs[refName][0].id;
        element.scrollIntoView({ behavior: 'smooth' });
      },
    },
    mounted() {
      this.activeEntry = this.faqs[0].ID;
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
