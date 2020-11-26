<template>
  <section class="container py-6 px-12 mx-auto">
    <div class="flex flex-wrap">
      <div class="lg:w-1/4 sticky top-0 self-start bg-white border-gray-300 border-b-2 lg:border-0 overflow-hidden">
        <ul class="list-none flex overflow-x-scroll lg:flex-col pt-16 lg:pt-0 mb-3 lg:mb-0">
          <li
            v-for="(item, idx) in block.sidenav"
            :key="idx"
            class="flex-shrink-0 md:mb-2 lg:mb-5 link text-gray-600 lg:cursor-pointer mr-5 lg:mr-0 text-lg lg:text-base"
            :class="{'font-bold': (idx === activeEntry)}"
            @click="goToSection(idx)"
          >
            {{ item.title }}
          </li>
        </ul>
      </div>

      <div class="mt-8 lg:w-3/4 lg:mt-0">
        <div
          v-for="(item, idx) in block.sidenav"
          class="mb-16"
          :key="idx"
          :id="idx"
          :ref="idx"
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
        activeEntry: 0,
      };
    },
    methods: {
      goToSection(refName) {
        const element = this.$refs[refName][0];
        this.activeEntry = +this.$refs[refName][0].id;
        element.scrollIntoView({ behavior: 'smooth' });
      },
    },
  };
</script>

<style>

</style>
