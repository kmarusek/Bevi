<template>
  <section
    class="bg-cover bg-no-repeat bg-center flex relative overflow-hidden main-banner large-banner pb-32"
    :style="{ 'background-image': 'url(' + backgroundImage() + ')' }"
    :class="[block.text_position === 'Center' ? 'min-h-auto lg:min-h-screen ' : 'min-h-screen ', { wave : block.wave }]"
  >
    <video
      v-if="block.add_background_video && windowWidth >= 768"
      :poster="block.background_image.sizes.large"
      autoplay
      muted
      loop
      class="absolute w-full h-full top-20 left-0 z-1 object-cover"
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
          :src="require(`~/assets/images/bubbles/bubble${index}.svg`).default"
          alt="bubble icon"
        >
      </span>
    </div>
    <div
      class="container flex relative z-10"
      :class="{
        'text-left flex-col lg:flex-row' : block.text_position === 'Right',
        'text-left flex-col lg:flex-row-reverse' : block.text_position === 'Left',
        'flex-col text-center' : block.text_position === 'Center'
      }"
    >
      <div
        :class="{
          'pt-20 xs:py-32 lg:w-2/4' : block.text_position === 'Left' && block.feature_image || block.text_position === 'Right' && block.feature_image,
          'py-20 pb-64 xs:py-32 xs:pb-32 lg:w-2/4' : block.text_position === 'Left' && !block.feature_image || 'Right' && !block.feature_image,
          'py-32 lg:w-full' : block.text_position === 'Center' && !block.feature_image,
          'py-20 lg:pt-32 lg:w-full' : block.text_position === 'Center' && block.feature_image,
          'narrower': block.narrower_content,
        }"
        class="flex items-center"
      >
        <div
          class="w-full"
          :class="[
            {
              'md:pl-20' : block.text_position === 'Left' && block.feature_image,
              'md:pr-20' : block.text_position === 'Right' && block.feature_image,
              'md:text-center' : block.text_position === 'Center' && block.feature_image,
              'p-8 bg-white bg-opacity-50 rounded-3xl': block.show_opaque_box && block.add_background_video && windowWidth >= 768,
            },
            block.text_color.value
          ]"
        >
          <h6
            v-if="block.small_title"
            class="font-space font-medium md:text-lg"
          >
            {{ block.small_title }}
          </h6>
          <h1
            v-if="block.large_title"
            :class="block.text_position === 'Center' ? 'heading-one' : 'my-4 heading-two'"
          >
            {{ block.large_title }}
          </h1>

          <div
            v-if="block.main_text && block.text_position != 'Center'"
            v-html="block.main_text"
            class="block-content"
          />

          <a
            v-if="block.link"
            :href="block.link.url"
            :target="block.link.target"
            class="btn mt-4"
            :class="{'center-bottom' : block.text_position === 'Center'}"
          >
            {{ block.link.title }}
          </a>
        </div>
      </div>
      <div
        v-if="block.feature_image"
        class="flex-1 block flex mt-10 pt-32 lg:mt-0 -z-10"
        :class="block.align_feature_image_bottom ? 'items-end' : 'items-center'"
      >
        <img
          v-if="block.feature_image"
          :src="block.feature_image.sizes.large"
          :alt="block.feature_image.alt"
          class="h-auto mx-auto"
          :class="block.text_position === 'Center' ? 'hero-image-small' : ' hero-image'"
        >
      </div>
    </div>
    <wave
      v-if="block.wave"
      :wave="block.wave"
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
        return this.block.background_image.url;
      },
      startAnimation() {
        gsap.utils.toArray('.gsap-fade-sections').forEach((section) => {
          const elems = section.querySelectorAll('.gsap-fades');

          gsap.set(elems, { opacity: 0 });

          ScrollTrigger.create({
            trigger: section,
            start: 'top 85%',
            scrub: true,
            onEnter: () => gsap.to(elems, {
              opacity: 1,
              duration: 0.1,
              stagger: 0.05,
              delay: 0.05,
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
  .text-blue-600 {
    @apply text-blue-600;
  }
  .text-white {
    @apply text-white;
  }
  .text-blue {
    @apply text-blue;
  }
  .text-green {
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

.wave {
  clip-path: url(#banner-wave);
  @apply -mb-6 relative;
}

.narrower {
  @screen lg {
    max-width: 33%;
  }
}

.block-content:deep(p, ul) {
  @apply text-gray-550;
}

.block-content:deep(h2) {
  @apply font-body text-3xl text-blue-600 font-semibold;
  @screen md {
    @apply text-4xl leading-tight;
  }
  @screen lg {
    @apply text-4.5xl;
  }
}

.block-content:deep(h3) {
  @apply font-body text-3xl font-semibold leading-tight;
  @screen md {
    @apply text-4xl;
  }
}

.block-content:deep(h4) {
  @apply font-body text-3xl mb-2;
}

.block-content:deep(h5) {
  @apply font-semibold text-xl mb-2;
}

.block-content:deep(h6) {
  @apply font-extrabold uppercase tracking-widest text-xs mb-2;
}

.block-content:deep(ul) {
  @apply list-disc pl-10;
  list-style-position: outside;
}

.block-content:deep(ol) {
  @apply list-decimal pl-10;
  list-style-position: outside;
}
</style>
