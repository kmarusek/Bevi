<template>
  <section
    class="bg-cover bg-no-repeat flex relative overflow-hidden"
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
    <div class="bubbles">
      <span class="bubble1 bubble">
        <img
          :src="require('~/assets/images/bubbles/bubble1.svg')"
        >
      </span>
      <span class="bubble2 bubble">
        <img
          :src="require('~/assets/images/bubbles/bubble2.svg')"
        >
      </span>
      <span class="bubble3 bubble">
        <img
          :src="require('~/assets/images/bubbles/bubble3.svg')"
        >
      </span>
      <span class="bubble4 bubble">
        <img
          :src="require('~/assets/images/bubbles/bubble4.svg')"
        >
      </span>
      <span class="bubble5 bubble">
        <img
          :src="require('~/assets/images/bubbles/bubble5.svg')"
        >
      </span>
      <span class="bubble6 bubble">
        <img
          :src="require('~/assets/images/bubbles/bubble6.svg')"
        >
      </span>
      <span class="bubble7 bubble">
        <img
          :src="require('~/assets/images/bubbles/bubble7.svg')"
        >
      </span>
      <span class="bubble8 bubble">
        <img
          :src="require('~/assets/images/bubbles/bubble8.svg')"
        >
      </span>
      <span class="bubble9 bubble">
        <img
          :src="require('~/assets/images/bubbles/bubble9.svg')"
        >
      </span>
      <span class="bubble10 bubble">
        <img
          :src="require('~/assets/images/bubbles/bubble10.svg')"
        >
      </span>
    </div>
    <div
      class="container flex relative"
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
            class="font-space font-medium md:text-lg"
          >
            {{ block.small_title }}
          </h6>
          <h1
            v-if="block.large_title"
            :class="block.text_position === 'Center' ? 'heading-one' : 'my-2 heading-two'"
          >
            {{ block.large_title }}
          </h1>

          <div
            v-if="block.main_text && block.text_position != 'Center'"
            v-html="block.main_text"
            class="post-content"
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
        class="flex-1 flex"
        :class="block.text_position === 'Center' ? '' : ' mt-10 md:mt-20 lg:mt-auto'"
      >
        <img
          v-if="block.feature_image"
          :src="block.feature_image.sizes.large"
          :alt="block.feature_image.alt"
          class="h-auto mx-auto mt-auto"
          :class="block.text_position === 'Center' ? 'hero-image-small' : ' hero-image'"
        >
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
    data: () => ({
      windowWidth: window.innerWidth,
    }),
    mounted() {
      window.onresize = () => {
        this.windowWidth = window.innerWidth;
      };
    },
    methods: {
      backgroundImage() {
        if (this.block.mobile_background_image && this.windowWidth <= 767) {
          return this.block.mobile_background_image.sizes.medium_large;
        }
        return this.block.background_image.sizes.large;
      },
    },
  };
</script>

<style lang="scss" scoped>
.center-bottom {
  @apply absolute;
  bottom: 5%;
  left: 50%;
  transform: translateX(-50%);
}

.hero-image-small {
  max-height: 350px;
  
  @screen md {
    max-height: 65vh;
  }
}
.hero-image {
  max-height: 350px;
  
  @screen md {
    max-height: 65vh;
  }

  @screen lg {
    max-height: 80vh;
  }
}

.bubbles {
  @apply z-0 h-full absolute;
  width: 90%;
  left: 5%;

  @screen md {
    width: 50%;
    left: 25%;
  }
  
  .bubble {
    @apply absolute inline-block;
    top: 110%;
    will-change: transform;
  }
  .bubble1 {
    left: 31%;
    animation: bubbles 4.5s linear infinite 0.75s;

    img {
      animation: sideWays 6s ease-in-out infinite alternate;
    }
  }
  .bubble2 {
    left: 22%;
    animation: bubbles 6s linear infinite 0.25s;

    img {
      animation: sideWays 4s ease-in-out infinite alternate;
    }
  }
  .bubble3 {
    left: 86%;
    animation: bubbles 12s linear infinite 1.5s;
    img {
      animation: sideWays 4s ease-in-out infinite alternate;
    }
  }
  .bubble4 {
    left: 70%;
    animation: bubbles 6s linear infinite 2.5s;
    img {
      animation: sideWays 4s ease-in-out infinite alternate;
    }
  }
  .bubble5 {
    left: 90%;
    animation: bubbles 8s linear infinite 0.5s;
    img {
      animation: sideWays 4s ease-in-out infinite alternate;
    }
  }
  .bubble6 {
    left: 43%;
    animation: bubbles 7s linear infinite 1s;
    img {
      animation: sideWays 4s ease-in-out infinite alternate;
    }
  }
  .bubble7 {
    left: 65%;
    animation: bubbles 9s linear infinite 2s;
    img {
      animation: sideWays 4s ease-in-out infinite alternate;
    }
  }
  .bubble8 {
    left: 10%;
    animation: bubbles 9s linear infinite 1s;
    img {
      animation: sideWays 4s ease-in-out infinite alternate;
    }
  }
  .bubble9 {
    left: 13%;
    animation: bubbles 9s linear infinite 1s;
    img {
      animation: sideWays 4s ease-in-out infinite alternate;
    }
  }
  .bubble10 {
    left: 26%;
    animation: bubbles 8s linear infinite 2s;
    img {
      animation: sideWays 4s ease-in-out infinite alternate;
    }
  }
}

@keyframes bubbles {
  0% {
    opacity: 0;
    transform: translateY(15%);
   }
   20% {
    opacity: 1;
    transform: translateY(-20%);
   }
   100% {
      opacity: 0;
      transform: translateY(-1000%);
   }
}
@keyframes sideWays {
  0% {
    transform: translateX(0);
  }
  100% {
     transform: translateX(50px);
  }
}
</style>
