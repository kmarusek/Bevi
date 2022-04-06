<template>
  <button
    class="skip-link btn rounded-full"
    @click="onClick()"
  >
    Skip Navigation
  </button>
</template>

<script>
  import GSAPFade from '~/mixins/GSAPFade.js';

  export default {
    mixins: [GSAPFade],
    props: {},
    data: () => ({
      skipLinkActive: false,
      title: null,
    }),
    methods: {
      onFocus() {
        this.skipLinkActive = true;
      },
      onClick() {
        this.skipLinkActive = false;

        if (this.title) {
          const viewportOffset = this.title.getBoundingClientRect();
          this.title.tabIndex = -1;
          this.title.focus();
          const { top } = viewportOffset;
          const offsetScrollValue = top - 80;
          const skipLink = document.querySelector('.skip-link');
          window.scrollTo({
            offsetScrollValue,
            behavior: 'smooth',
          });

          skipLink.blur();
        }
      },
    },
    mounted() {
      if (document.querySelector('.heading-one')) {
        this.title = document.querySelector('.heading-one');
      } else if (document.querySelector('h1')) {
        this.title = document.querySelector('h1');
      } else if (document.querySelector('h2')) {
        this.title = document.querySelector('h2');
      } else if (document.querySelector('h3')) {
        this.title = document.querySelector('h3');
      }
    },
  };
</script>

<style lang="scss" scoped>

  .skip-link {
    position: absolute;
    left: 0;
    top: 50px;
    opacity: 0;
    transition: opacity ease-in-out 200ms;

    &:focus {
      opacity: 1;
    }
  }
</style>
