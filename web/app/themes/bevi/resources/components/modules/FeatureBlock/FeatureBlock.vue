<template>
  <section
    class="feature-block"
    :class="sectionClasses"
  >
    <div
      class="py-32 container xl:max-w-5xl flex flex-col items-center gsap-fade-section"
      :class="block.image_position !== 'Left' ? 'md:flex-row' : 'md:flex-row-reverse'"
    >
      <div
        class="w-full md:w-1/2 mb-8 md:mb-0"
        :class="block.image_position !== 'Left' ? 'md:pr-10 lg:pr-20' : 'md:pl-10 lg:pl-20'"
      >
        <h3
          v-if="block.title"
          class="h3 gsap-fade"
        >
          {{ block.title }}
        </h3>
        <div
          v-html="block.text"
          class="mt-2 md:mt-6 block-content gsap-fade"
        />
      </div>
      <div
        class="w-full md:w-1/2 flex justify-center relative"
        :class="block.image_position !== 'Left' ? 'md:pl-10 lg:pl-20' : 'md:pr-10 lg:pr-20'"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="66.877"
          height="67.268"
          viewBox="0 0 66.877 67.268"
          class="bubble-small parallax"
          data-speed="1"
        >
          <path
            id="Path_148630"
            data-name="Path 148630"
            d="M-440.228,564.667c-.148,12.217-11.52,21.556-23.758,21.556s-25.384-9.045-23.575-21.858c1.711-12.118,11.337-21.707,23.575-21.707S-440.053,550.2-440.228,564.667Z"
            transform="translate(155.025 754.115) rotate(131)"
            fill="none"
            stroke="#dbdbdb"
            stroke-miterlimit="10"
            stroke-width="2"
            fill-rule="evenodd"
          />
        </svg>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="96.316"
          height="92.164"
          viewBox="0 0 96.316 92.164"
          class="bubble-large parallax"
          data-speed="2.6"
        >
          <path
            id="Path_148631"
            data-name="Path 148631"
            d="M-394.921,582.692c3.942,24.262-23.3,55.775-47.918,55.775s-48.221-23.324-44.582-49.1c3.442-24.38,19.96-40.061,44.582-40.061S-398.87,558.389-394.921,582.692Z"
            transform="translate(489.344 -547.803)"
            fill="none"
            stroke="#e5e5e5"
            stroke-miterlimit="10"
            stroke-width="3"
            fill-rule="evenodd"
          />
        </svg>
        <ImageBlob :image="block.image.sizes.large" />
      </div>
    </div>
    <wave
      v-if="block.wave"
      :wave="block.wave"
      wave-id="feature-wave"
    />
  </section>
</template>

<script>
  import GSAPFade from '~/mixins/GSAPFade.js';
  import GSAPParallax from '~/mixins/GSAPParallax.js';

  export default {
    mixins: [GSAPFade, GSAPParallax],
    props: {
      block: {
        required: true,
        type: [Object, Array],
      },
    },
    computed: {
      sectionClasses() {
        const classes = [];
        if (this.block.colour_scheme) {
          classes.push(`color-scheme-${ this.block.colour_scheme }`);
        }

        if (this.block.wave) {
          classes.push('wave');
        }
        
        return classes;
      },
    },
  };
</script>

<style lang="scss" scoped>
.feature-block {
  
  &.color-scheme-Default {
    .h3 {
      @apply text-blue-600;
    }
    svg path {
      @apply stroke-blue;
    }
  }
  &.color-scheme-Green {
    @apply bg-green-100;
    .h3 {
      @apply text-green-600;
    }
    svg path {
      @apply stroke-green;
    }
  }
  &.color-scheme-Blue {
    @apply bg-blue-100;
    .h3 {
      @apply text-blue-600;
    }
    svg path {
      @apply stroke-blue;
    }
  }
  &.color-scheme-Red {
    @apply bg-red-200;
    .h3 {
      @apply text-blue-600;
    }
    svg path {
      @apply stroke-red;
    }
  }
  .bubble-small {
    @apply absolute w-10;
    left: 0%;

    @screen md {
      top: 40%;
    }
  }
  .bubble-large {
    @apply absolute right-0 w-12;
    right: 10%;
    bottom: 0%;
  }
}

.wave {
  clip-path: url(#feature-wave);
  @apply -mb-6 relative;
}
</style>
