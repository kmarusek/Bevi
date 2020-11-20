<template>
  <section class="text-center parallax-container">
    <video
      v-if="block.video.url"
      muted
      loop
      class="absolute w-full top-0 left-0 z-1 object-fill h-screen"
    >
      <source
        :src="block.video.url"
        type="video/mp4"
      >
    </video>
    <div class="h-screen bg-white flex items-center w-full text-area z-10 relative">
      <div class="container max-w-3xl">
        <h2
          class="h1"
          v-if="block.title"
        >
          {{ block.title }}
        </h2>
        <div
          v-html="block.text"
          class="mt-2 md:mt-6 block-content md:px-8"
        />
      </div>
    </div>
    <div class="h-screen text-white flex items-center w-full second-text-area z-10 relative">
      <div class="container max-w-3xl">
        <h2
          class="h1"
          v-if="block.title"
        >
          FUCK OFF
        </h2>
        <div
          v-html="block.text"
          class="mt-2 md:mt-6 block-content md:px-8"
        />
      </div>
    </div>
  </section>
</template>

<script>
  import { gsap } from 'gsap';
  import ScrollTrigger from 'gsap/ScrollTrigger';
    
  export default {
    props: {
      block: {
        required: true,
        type: Object,
      },
    },
    mounted() {
      this.startAnimation();
    },
    methods: {
      startAnimation() {
        gsap.registerPlugin(ScrollTrigger);

        const tl = gsap.timeline({
          scrollTrigger: {
            trigger: '.parallax-container',
            pin: '.parallax-container',
            end: '+=200vh',
            scrub: true,
            markers: true,
          },
        });
        tl.to('.text-area', {
          ease: 'power1.out',
          opacity: 0,
        });
        tl.to('.second-text-area', {
          ease: 'power1.out',
          opacity: 1,
        });
      },
    },
  };
</script>

<style>

</style>
