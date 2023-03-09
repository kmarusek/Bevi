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

<!-- 
      <div class="comparison--nav block-content py-16 pb-0 px-0 lg:px-48 text-center  block md:hidden z-10">
        <span id="prev" class="navigator bg-blue" data-index="1"> </span>
        <span id="next" class="navigator bg-gray-200" data-index="2"> </span>
      </div> -->

      <!-- <div class="comparison--nav block-content pb-0 px-0 lg:px-48 text-right  block md:hidden z-10">
        <a class="nextarrow" id="nexttop"><img class="pr-3 float-right" width="65" src="/wp-content/themes/bevi/dist/images/arrow.svg" /></a>
      </div> -->

      <div id="comparison-slide" class="comparison--blocks container block-content pb-4 px-0 lg:px-48 z-10">

        <div class="swipe-wrap block md:flex">

          <div
              v-for="(item, idx) in block.dispenser"
              :key="idx"
              class="comparison--block bg-grey-light p-3 md:p-0 md:m-3">

            <div class="w-full bg-gray-200 md:bg-transparent rounded-2xl pt-10 md:pt-24 ">
              <div class="w-full text-center">
                <div class="max-h-full flex items-end">
                  <div class="w-full content-center">
                    <img
                        :src="item.image"
                        class="max-h-full inline object-bottom"
                        alt="Dispenser"
                    />
                  </div>
                </div>
                <h3 class="gsap-fades mt-4 text-2.5xl font-semibold mt-6 text-blue-500">{{ item.title }}</h3>
                <p class="gsap-fades mt-0 mb-12 px-16 text-small">{{ item.description }}</p>
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
                    :class="item.cta_color === true? 'btn w-full mb-2' : 'btn outline-blue w-full mb-2'"
                >{{ item.cta.title }}</a>

              </div>
            </div>

          </div>

        </div>

      </div>

      <div class="comparison--nav block-content pb-4 px-0 lg:px-48 text-center  block md:hidden z-10">
        <span id="prev" class="navigator bg-blue" data-index="1"> </span>
        <span id="next" class="navigator bg-gray-200" data-index="2"> </span>
      </div>

      <div class="comparison--nav block-content pb-4 px-0 lg:px-48 text-center mb-16 block md:hidden z-10">
        <a class="nextarrow bg-gray-200 inline-block w-16 h-16 rounded-full pt-6" id="nextbottom"><img class="m-auto" width="45" src="/wp-content/themes/bevi/dist/images/arrow.svg" /></a>
      </div>

    </div>
  </section>

  <!--section id="demo">
    <div class="demo-controls">
      <a class="btn btn-primary" id="prev">Prev</a>
      <a class="btn btn-primary" id="next">Next</a>
    </div>
  </section-->

</template>

<style>

.comparison {
  background-image: url("/wp-content/themes/bevi/dist/images/bluewavebig.png");
  background-position: left bottom -600px;
  background-repeat: no-repeat;
  background-size: cover;
}
#comparison-slide .block{
  background-color: transparent;
  padding: 0;
}
.comparison--block p {
  min-height: 115px; 
}
@media screen and (max-width: 425px) {
  .comparison {
    background-position: left bottom -350px;
  }
}

.max-h-80 {
  max-height: 314px;
}
.navigator {
  display: inline-block;
  height: 8px;
  width: 24px;
  margin: 4px;
  border-radius: 4px;
}


   /* Swipe 2 required styles */

 .swipe {
   overflow: hidden;
   visibility: hidden;
   position: relative;
 }
.swipe-wrap {
  overflow: hidden;
  position: relative;
}
.swipe-wrap > div {
  float: left;
  width: 100%;
  position: relative;
}

/* END required styles */


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
/*
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
      */



    let carouselcompares;
    jQuery(document).ready(function () {
      // pure JS
      var element = document.getElementById('comparison-slide'),
          nextBtnTop = document.getElementById('nexttop'),
          nextBtnBottom = document.getElementById('nextbottom');

      // using jQuery
      carouselcompares = jQuery('#comparison-slide');
      if ( carouselcompares.find('.swipe-wrap').css('display') === 'block' ) {
        window.mySwipe = carouselcompares.Swipe({
          startSlide: 0,
          draggable: true,
          continuous: true,
          stopPropagation: true,
          disableScroll: true,
          transitionEnd: function(index, element) {
            jQuery('.comparison--nav .navigator').toggleClass("bg-blue bg-gray-200");
          }
        }).data('Swipe');
      }

      nextBtnTop.onclick = mySwipe.next;
      nextBtnBottom.onclick = mySwipe.next;
    });
  },
};
</script>