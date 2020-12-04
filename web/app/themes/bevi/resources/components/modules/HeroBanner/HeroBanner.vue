<template>
  <section
    class="bg-cover bg-no-repeat flex relative overflow-hidden main-banner wave"
    :style="{ 'background-image': 'url(' + backgroundImage() + ')' }"
    :class="block.text_position === 'Center' ? 'lg:min-h-screen ' : 'min-h-screen '"
  >
    <video
      v-if="block.add_background_video"
      :poster="block.background_image.sizes.large"
      autoplay
      muted
      loop
      class="absolute w-full h-full top-0 left-0 z-1 object-fill"
    >
      <source
        :src="block.video.url"
        type="video/mp4"
      >
    </video>
    <div
      class="bubbles"
      v-if="block.show_bubbles"
    >
      <span
        v-for="(n, index) in 12"
        :key="index"
        class="bubble"
        :class="`bubble${index}`"
      >
        <img
          :src="require(`~/assets/images/bubbles/bubble${index}.svg`)"
          alt="bubble icon"
        >
      </span>
    </div>
    <div
      class="container flex relative gsap-fade-sections z-10"
      :class="{
        'text-left flex-col md:flex-row' : block.text_position === 'Right',
        'text-left flex-col md:flex-row-reverse' : block.text_position === 'Left',
        'flex-col text-center' : block.text_position === 'Center'
      }"
    >
      <div
        :class="{
          'pt-20 xs:py-32 lg:w-2/4' : block.text_position === 'Left' && block.feature_image || block.text_position === 'Right' && block.feature_image,
          'py-20 xs:py-32 lg:w-2/4' : block.text_position === 'Left' && !block.feature_image || 'Right' && !block.feature_image,
          'md:flex-1 py-32 lg:w-full' : block.text_position === 'Center' && !block.feature_image,
          'md:flex-1 pt-20 lg:pt-32 lg:w-full' : block.text_position === 'Center' && block.feature_image,
        }"
        class="flex items-center"
      >
        <div
          class="w-full"
          :class="[
            {
              'md:pl-20' : block.text_position === 'Left' && block.feature_image,
              'md:pr-20' : block.text_position === 'Right' && block.feature_image
            },
            block.text_color.value
          ]"
        >
          <h6
            v-if="block.small_title"
            class="font-space font-medium md:text-lg gsap-fades"
          >
            {{ block.small_title }}
          </h6>
          <h1
            v-if="block.large_title"
            :class="block.text_position === 'Center' ? 'heading-one' : 'my-4 heading-two'"
            class="gsap-fades"
          >
            {{ block.large_title }}
          </h1>

          <div
            v-if="block.main_text && block.text_position != 'Center'"
            v-html="block.main_text"
            class="gsap-fades"
          />

          <a
            v-if="block.link"
            :href="block.link.url"
            :target="block.link.target"
            class="btn mt-4 gsap-fades"
            :class="{'center-bottom' : block.text_position === 'Center'}"
          >
            {{ block.link.title }}
          </a>
        </div>
      </div>
      <div
        v-if="block.feature_image"
        class="flex-1 flex"
        :class="block.text_position === 'Center' ? '' : ' mt-10 md:mt-20 lg:mt-auto'"
      >
        <img
          v-if="block.feature_image"
          :src="block.feature_image.sizes.large"
          :alt="block.feature_image.alt"
          class="h-auto mx-auto mt-auto gsap-fades"
          :class="block.text_position === 'Center' ? 'hero-image-small' : ' hero-image'"
        >
      </div>
    </div>
    <wave
      wave="1"
      wave-id="banner-wave"
    />
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
    data: () => ({
      windowWidth: window.innerWidth,
    }),
    mounted() {
      window.onresize = () => {
        this.windowWidth = window.innerWidth;
      };
      this.startAnimation();
    },
    methods: {
      backgroundImage() {
        if (this.block.mobile_background_image && this.windowWidth <= 767) {
          return this.block.mobile_background_image.sizes.medium_large;
        }
        return this.block.background_image.sizes.large;
      },
      startAnimation() {
        gsap.utils.toArray('.gsap-fade-sections').forEach((section) => {
          const elems = section.querySelectorAll('.gsap-fades');
        
          gsap.set(elems, { y: 10, opacity: 0 });
        
          ScrollTrigger.create({
            trigger: section,
            start: 'top 80%',
            scrub: true,
            onEnter: () => gsap.to(elems, {
              y: 0,
              opacity: 1,
              duration: 1.2,
              stagger: 0.8,
              delay: 0.8,
              ease: 'power3.out',
              overwrite: 'auto',
            }),
          });
        });
      },
    },
  };
</script>

<style lang="scss" scoped>
.main-banner {
  &.wave {
    clip-path: url(#banner-wave);
    @apply -mb-6;
  }
  .text-blue-600 {
    @apply text-blue-600;
  }
  .text-white {
    @apply text-white;
  }
  .text-blue {
    @apply text-blue;
  }
  .text-green  {
    @apply text-green;
  }
  .text-purple {
    @apply text-purple;
  }
  .text-red {
    @apply text-red;
  }
  .text-orange {
    @apply text-orange;
  }
  .text-yellow {
    @apply text-yellow;
  }
  .text-gray {
    @apply text-gray;
  }
}
.center-bottom {
  @apply absolute;
  bottom: 5%;
  left: 50%;
  transform: translateX(-50%);
}

.hero-image-small {
  max-height: 350px;
  z-index: -1;
  
  @screen md {
    max-height: 65vh;
  }
}
.hero-image {
  max-height: 350px;
  z-index: -1;
  
  @screen md {
    max-height: 65vh;
  }

  @screen lg {
    max-height: 80vh;
  }
}
</style>
