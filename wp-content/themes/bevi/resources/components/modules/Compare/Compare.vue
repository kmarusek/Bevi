<template>

  <section class="w-full bg-gray-100 comparison">
    <div class="relative flex min-h-screen flex-col overflow-hidden">

      <div class="block-content py-16 pb-4 px-0 lg:px-48 text-center">
        <h1 class="gsap-fades my-4 heading-two text-blue-500">{{ block.title }}</h1>
        <h2 class="gsap-fades my-4 font-space">{{ block.subtitle }}</h2>
      </div>

      <div class="bubbles">
        <img
            :src="require(`~/assets/images/bubbles/bubble9.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-24 -left-36 -top-40 opacity-50"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble2.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-8 -left-30 top-64"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble10.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-12 -right-20 top-20 opacity-50"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble9.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-24 top-40 -right-12"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble10.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-12 -right-8 top-98"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble9.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-12 -left-30 bottom-36 opacity-50"
            aria-hidden="true"
        >
        <img
            :src="require(`~/assets/images/bubbles/bubble10.svg`).default"
            alt="bubble icon"
            class="hidden lg:block absolute w-16 -left-16 bottom-24 opacity-50"
            aria-hidden="true"
        >
      </div>


      <div class="comparison--nav block-content py-16 pb-4 px-0 lg:px-48 text-center  block md:hidden">
      </div>

      <div class="comparison--blocks container block-content py-10 pb-4 px-0 mb-16 lg:px-48 flex-columns md:flex z-10">

        <div
            v-for="(item, idx) in block.dispenser"
            :key="idx"
            class="comparison--block grow md:w-1/2 bg-grey-light m-3">

          <div class="w-full text-center">
            <div class="h-62 flex items-end">
              <div class="w-full content-center">
                <img
                    :src="item.image"
                    class="max-h-80 inline object-bottom"
                    alt="Dispenser"
                />
              </div>
            </div>
            <h3 class="gsap-fades mt-4 text-2.5xl font-semibold mt-6 text-blue-500">{{ item.title }}</h3>
            <p class="gsap-fades mt-0 mb-12 px-24 text-small">{{ item.description }}</p>
          </div>
          <div class="w-full bg-gray-200 px-8 py-6 rounded-2xl">

            <div
                v-for="(feature, idx) in item.features"
                :key="idx"
                class="flex my-6 gsap-fade">
              <div class="flex-auto w-5/6 text-gray-700 text-lg">{{ feature.feature }}</div>
              <div class="flex-auto text-right">
                <img
                    :src= "feature.enabled === true ? '/wp-content/themes/bevi/dist/images/chk-true.png' : '/wp-content/themes/bevi/dist/images/chk-false.png'"
                    class="inline"
                    alt=""
                >
              </div>
            </div>

            <div
                v-for="(property, idx) in item.properties"
                :key="idx"
                class="flex my-2 py-2 border-t border-gray mt-4">
              <div class="flex-auto w-1/2 text-gray-700 text-lg">{{ property.property }}</div>
              <div class="flex-auto w-1/2 text-right">
                <div class="w-full font-bold text-lg">
                  {{ property.property_value }}
                </div>
                <div class="w-full text-sm">
                  {{ property.property_info }}
                </div>
              </div>
            </div>

            <a
                v-if="item.cta"
                v-bind:href="item.cta.url"
                :class="item.cta_color === true? 'btn w-full mb-2' : 'block rounded-full font-bold py-3 px-3 text-center w-full mb-2 text-blue bg-transparent border border-2 border-blue hover:text-white hover:bg-blue transform transition duration-500'"
            >{{ item.cta.title }}</a>

          </div>

        </div>

      </div>

    </div>
  </section>

</template>

<style>
.comparison {
  background-image: url("/wp-content/themes/bevi/dist/images/bluewave.png");
  background-position: bottom;
  background-repeat: no-repeat;
  background-size: cover;
}

.max-h-80 {
  max-height: 314px;
}
.navigator {
  display: inline-block;
  height: 4px;
  width: 24px;
  margin: 4px;
  border-radius: 2px;
}
</style>

<script>
export default {
  props: {
    block: {
      required: true,
      type: Object,
    },
  },
  mounted() {

    jQuery('.comparison--blocks').each(function () {
      var isMobile = (jQuery(this).children('.comparison--block:not(:first-child)').css('display') === 'none');
      var touchstartX = 0;
      var touchendX = 0;

      var totalHpBlurb = 0;
      jQuery(this).children('.comparison--block').each(function () {
        totalHpBlurb++;
        jQuery(this).attr('data-index', totalHpBlurb);
        jQuery('.comparison--nav').append('<span class="navigator" data-index="' + totalHpBlurb + '"> </span>');
      })
      jQuery('.comparison--nav .navigator:first-child').addClass('bg-blue');
      jQuery('.comparison--nav .navigator:not(:first-child)').addClass('bg-gray-200');

      jQuery(this).children('.comparison--block').on("touchstart", function (e) {
        touchstartX = e.changedTouches[0].screenX;
      });

      jQuery(this).children('.comparison--block').on("touchend", function (e) {

        touchendX = e.changedTouches[0].screenX;

        if ( Math.abs(touchstartX - touchendX) > 100 && isMobile) {
          var index = jQuery(this).attr('data-index');
          jQuery(this).hide().removeClass('active');
          jQuery('.comparison--nav .navigator[data-index="' + index + '"]').removeClass('bg-blue').addClass('bg-gray-200');

          if (index < totalHpBlurb) {
            index++;
            jQuery('.comparison--block[data-index="' + index + '"]').show().addClass('active');
            jQuery('.comparison--nav .navigator[data-index="' + index + '"]').removeClass('bg-gray-200').addClass('bg-blue');
          } else {
            jQuery('.comparison--block[data-index="1"]').show().addClass('active');
            jQuery('.comparison--nav .navigator[data-index="1"]').removeClass('bg-gray-200').addClass('bg-blue');
          }
        }
      });

    });
  },
};
</script>