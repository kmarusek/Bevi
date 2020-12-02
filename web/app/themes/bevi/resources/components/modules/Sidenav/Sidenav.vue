<template>
  <section class="container py-8 lg:py-32 lg:px-12 mx-auto">
    <div class="flex flex-wrap">
      <aside class="sidebar lg:w-1/4 sticky self-start bg-white border-gray-300 border-b-2 lg:border-0 overflow-hidden">
        <ul class="list-none flex overflow-x-scroll lg:overflow-x-auto lg:flex-col pt-16 lg:pt-0 mb-3 lg:mb-0">
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
      </aside>

      <div class="mt-8 lg:w-3/4 lg:mt-0">
        <div
          v-for="(item, idx) in block.sidenav"
          class="mb-16 scroll-margin"
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
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
      },
    },
  };
</script>

<style lang="scss" scoped>

.scroll-margin {
  scroll-margin-top: 120px;

  @screen lg {
    scroll-margin-top: 80px;
  }
}
.sidebar {
  top: 0;

  @screen lg {
    top: 80px;
  }
}

.block-content /deep/ div,
.block-content /deep/ p,
 {
  @apply text-gray-700 text-base leading-7 font-space mb-6;

  a {
    @apply text-primary underline font-bold;
  }
}

.block-content /deep/ ol,
.block-content /deep/ ul,
 {
  @apply pl-4 text-gray-700 ;

  ::marker {
    @apply font-bold text-blue-600;
  }

  li {
    @apply mb-2 pl-2;
  }
}

.block-content /deep/ ul {
  @apply list-disc;
}

.block-content /deep/ ol {
  @apply list-decimal;
}

</style>
