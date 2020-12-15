<template>
  <section class="text-center">
    <div class="parallax-container h-screen overflow-hidden">
      <video
        v-if="block.video.url"
        id="videos"
        :poster="block.poster.sizes.large"
        muted
        loop
        preload="auto"
        class="z-1 h-full w-full object-cover"
      >
        <source
          :src="block.video.url"
          type="video/mp4"
        >
      </video>
      <div class="h-full bg-white flex items-center w-full text-area z-10 absolute top-0">
        <div class="container max-w-3xl">
          <h2
            class="h1 scroll-animate"
            v-if="block.title"
          >
            {{ block.title }}
          </h2>
          <div
            v-html="block.text"
            class="mt-2 md:mt-6 block-content md:px-8 scroll-animate"
          />
        </div>
      </div>
      <div class="h-full text-white flex items-center w-full second-text-area z-10 absolute top-0">
        <div class="container max-w-4xl">
          <h2
            class="h1 text-white second-title"
            v-if="block.second_title"
          >
            {{ block.second_title }}
          </h2>
          <a
            v-if="block.cta"
            :href="block.cta.url"
            :target="block.cta.target"
            class="btn second-button"
          >
            {{ block.cta.title }}
          </a>
        </div>
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
      playVideo() {
        document.getElementById('videos').play();
      },
      startAnimation() {
        gsap.registerPlugin(ScrollTrigger);

        gsap.set('.scroll-animate', { opacity: 0, y: 50 });
        gsap.set('.second-title', { opacity: 0, y: 50 });
        gsap.set('.second-button', { opacity: 0, y: 50 });

        gsap.utils.toArray('.scroll-animate').forEach((el) => {
          gsap.to(el, {
            scrollTrigger: {
              trigger: el,
              start: 'top 80%',
              end: 'top 60%',
            },
            opacity: 1,
            y: 0,
          });
        });

        const tl = gsap.timeline({
          scrollTrigger: {
            trigger: '.parallax-container',
            pin: '.parallax-container',
            scrub: true,
          },
        });
        tl.to('.text-area', {
          ease: 'power1.out',
          opacity: 0,
          duration: 1,
        }, '+=0.3')
          .call(this.playVideo)
          .to('.second-title', {
            ease: 'power1.out',
            opacity: 1,
            y: 0,
            duration: 0.3,
          })
          .to('.second-button', {
            ease: 'power1.out',
            opacity: 1,
            y: 0,
            duration: 0.3,
          }, '-=0.2');
      },
    },
  };
</script>

<style lang="scss" scoped>
video {
  z-index: -1;
}
.second-button {
  @apply absolute;
  bottom: 10%;
  left: 50%;
  transform: translateX(-50%);
}
</style>
