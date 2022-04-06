<template>
  <section class="container max-w-5xl py-20 lg:py-32 gsap-fade-section">
    <h2
      class="mb-6 md:mb-8 font-semibold h3 text-center text-blue-600 gsap-fade"
    >
      {{ block.faq_title }}
    </h2>
    <div class="flex flex-wrap -m-2">
      <dl
        class="p4-4 md:w-1/2"
        v-for="(faq, index) in faqList"
        :key="faq.id"
      >
        <div class="rounded-md border border-gray-400 p-4 m-3 gsap-fade">
          <dt
            @click="handleClick(faq.id)"
            class="h-12 flex pr-12 items-center relative cursor-pointer"
          >
            <span
              class="font-semibold text-sm md:text-lg leading-tight w-full text-blue-600"
            >
              {{ faq.title }}
            </span>
            <button
              :aria-controls="getControlsId(index)"
              class="accordion-toggle-button"
              aria-label=""
            >
              <i
                class="icon fas fa-chevron-down"
                :class="{active: faq.active}"
              >
                <span class="visually-hidden">
                  {{ faq.active ? `accordion ${faq.title} - open` : ` accordion ${faq.title} - closed` }}
                </span>

              </i>
            </button>
          </dt>
          <dd
            :id="getControlsId(index)"
            class="faq-content block-content text-sm"
            :class="{open: faq.active}"
            v-html="faq.content"
            :hidden="!faq.active"
          />
        </div>
      </dl>
    </div>

    <div class="block-content lg:w-1/2 lg:px-6 mx-auto my-6 md:my-8 text-center max-w-md gsap-fade">
      <p
        v-if="block.description"
        v-html="block.description"
      />
      <a
        v-if="block.cta.url"
        class="btn mt-6"
        :href="block.cta.url"
        :target="block.cta.target"
      >
        {{ block.cta.title }}
      </a>
    </div>
  </section>
</template>

<script>
  import GSAPFade from '~/mixins/GSAPFade.js';

  export default {
    mixins: [GSAPFade],
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

        this.faqList = this.faqList.filter((item) => item.title && item.content);
      },
      getControlsId(index) {
        const date = new Date();
        return `content-${ index }-${ date.getTime() }`;
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

  .accordion-toggle-button {
    @apply absolute right-0;

    &:active,&:focus {
      box-shadow: 0px 0px 1px 1px theme('colors.blue-600');
    }
  }

   .icon {
   @apply border-gray-400 bg-no-repeat bg-center transform rotate-0;
    transition: all 0.3s ease;

    &.active {
      @apply rotate-180;
    }
  }

</style>
