<template>
  <section class="container">
    <h2
      class="mb-6 md:mb-8 font-semibold text-xl md:text-3xl leading-tight text-center capitalize"
    >
      {{ block.faq_title }}
    </h2>
    <div class="flex flex-wrap -m-2">
      <div
        class="p4-4 md:w-1/2"
        v-for="faq in faqList"
        :key="faq.id"
      >
        <div class="rounded-md border border-gray-400 p-4 m-3">
          <div
            @click="handleClick(faq.id)"
            class="h-12 flex pr-12 items-center relative cursor-pointer"
          >
            <span
              class="font-semibold text-sm md:text-x1 leading-tight w-full"
            >
              {{ faq.title }}
            </span>
            <i
              class="icon"
              :class="{active: faq.active}"
            />
          </div>
          <div
            class="faq-content block-content text-sm"
            :class="{open: faq.active}"
            v-html="faq.content"
          />
        </div>
      </div>
    </div>

    <div class="block-content lg:w-1/2 mx-auto my-6 md:my-8 text-center">
      <p
        v-html="block.text"
      />
      <a
        class="btn mt-8"
        :href="block.cta"
      >
        Contact Us
      </a>
    </div>
  </section>
</template>

<script>
  export default {
    props: {
      block: {
        type: Object,
        required: true,
      },
    },
    data() {
      return {
        faqList: [],
      };
    },
    methods: {
      handleClick(curIndex) {
        this.faqList[curIndex].active = !this.faqList[curIndex].active;

        for (let idx = 0; idx < this.faqList.length; idx += 1) {
          if (this.faqList[idx].id !== curIndex) this.faqList[idx].active = false;
        }
      },
      setFaqs() {
        this.faqList = this.block.selected_faq.map((item, index) => ({
          title: item.post_title,
          content: item.post_content,
          active: false,
          id: index,
        }));
      },
    },
    created() {
      this.setFaqs();
    },
  };
</script>

<style lang="scss" scoped>

  .block-content p {
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
