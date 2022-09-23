<template>

  <section class="quiz w-full bg-gray-200">
    <div class="relative flex flex-col overflow-hidden">
      <div class="block-content">
        <div v-html="block.the_form" class="my-4"></div>
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

    </div>
  </section>

</template>

<style>

.quiz .gform-body .gform_page {
  max-width: 736px;
  min-height: 400px;
  margin: auto;
  border-radius: 24px;
  background-color: #fff;
  padding: 48px;
}

.quiz .gform-body .gform_page {
  background-image: url("/wp-content/themes/bevi/dist/images/quiz/question-1.svg");
  background-position: right;
  background-repeat: no-repeat;
  background-size: contain;
}

.quiz .gform-body .gform_page.page2 {
  background-image: url("/wp-content/themes/bevi/dist/images/quiz/question-2.1.svg");
}

.quiz .gform-body .gform_page.page3 {
  background-image: url("/wp-content/themes/bevi/dist/images/quiz/question-3.1.svg");
}

.quiz .gform-body .gform_page.result {
  background-image: none;
  padding-top: 0;
}

.quiz .quiz-1-title {
  max-width: 400px;
  font-size: 40px;
  font-weight: 600;
  font-stretch: expanded;
  font-style: normal;
  line-height: normal;
  letter-spacing: 2px;
  text-align: left;
  color: #00223e;
  margin-bottom: 48px;
}

.quiz .quiz-1-title.w-full {
  width: 100%;
  color: #787878;
}

.desctption {
  width: 432px;
  margin: 24px 0 48px;
  font-size: 16px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.88;
  letter-spacing: 0.64px;
  text-align: left;
  color: #787878;
  overflow: hidden;
}

.desctption.w-full {
  width: 100%;
}

.desctption.readmore {
  height: 85px;
  margin-bottom: 5px;
}

.desctption.readmore.open {
  height: auto;
}

.readmorelink {
  margin-bottom: 48px;
  display: inline-block;
}

.quiz .gform-body .label {
  font-size: 14px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.71;
  letter-spacing: 0.56px;
  text-align: left;
  color: #000;
}

.quiz .gform_wrapper.gravity-theme .gform_footer, .gform_wrapper.gravity-theme .gform_page_footer {
  margin-top: 48px !important;
  margin-bottom: 0 !important;
}

.quiz .gform_wrapper.gravity-theme .imageinput .gfield-choice-input+label {
  cursor: pointer;
  margin-bottom: 24px;
  vertical-align: middle;
  display: flex;
  width: 100%;
  align-items: center;
}

.quiz .gform_wrapper.gravity-theme .fancyradio .gfield-choice-input+label {
  cursor: pointer;
  margin-bottom: 24px;
  width: 100%;
  display: flex;
  align-items: center;
  padding-left: 48px;
  background-position: left;
  background-repeat: no-repeat;
  background-size: initial;
  background-image: url("/wp-content/themes/bevi/dist/images/quiz/radio-unchecked.svg");
}

.quiz .gform_wrapper.gravity-theme .fancyradio .gfield-choice-input:checked+label {
  background-image: url("/wp-content/themes/bevi/dist/images/quiz/radio-checked.svg");
  color:
}

.quiz .gform_wrapper.gravity-theme .gf_progressbar_blue {
  background: #c2d1d9;
  height: 5px;
  width: 400px;
  margin: 48px auto;
}

.quiz .gform_wrapper.gravity-theme .gf_progressbar_percentage.percentbar_blue {
  background-color: #246dff;
  height: 5px;
}

.quiz div#gf_1 {
  margin-top: 60px;
}

.quiz .gf_progressbar_title,
.quiz .gform_wrapper.gravity-theme .gf_progressbar_percentage.percentbar_blue span {
  display: none;
}

.quiz .gform_wrapper.gravity-theme .gfield-choice-input+label img {
  margin-right: 10px;
}

.quiz .gform_wrapper.gravity-theme .imageinput .gfield-choice-input:checked+label {
  filter: invert(32%) sepia(73%) saturate(3215%) hue-rotate(213deg) brightness(102%) contrast(101%);
}

.quiz .check-with-label:checked + .label-for-check {
  font-weight: bold;
}

.quiz .gchoice {
  cursor: pointer;
  height: 60px;
  margin: 16px 0 18px 10px;
  font-size: 16px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.88;
  letter-spacing: 0.64px;
  text-align: left;
  color: #424b5a;
}

.quiz .imageinput .gchoice {
  float: left;
  width: 40%;
}

.quiz .fancyradio .gchoice input,
.quiz .imageinput .gchoice input {
  display: none !important;
}

.quiz .ginput_container.ginput_container_text {
  width: 80%;
}

.quiz .gform_button,
.quiz #gform_submit_button_1,
.quiz .gform_next_button {
  cursor: pointer;
  width: 160px;
  height: 48px;
  display: inline;
  padding: 16px 40px;
  border-radius: 24px;
  background-color: #246dff;
  font-size: 16px;
  font-weight: bold;
  font-stretch: normal;
  font-style: normal;
  line-height: 1;
  letter-spacing: normal;
  text-align: center;
  color: #f5f5f5;
  margin-left: 48px !important;
}

@media screen and (max-width: 425px) {
  .quiz .gform-body .gform_page {
    background-image: none !important;
    margin: 20px;
  }
  .quiz #gform_submit_button_1,
  .quiz .gform_next_button {
    float: right;
  }
}

.quiz .bubbles {
  z-index: -1;
}

.quiz .gform_previous_button {
  cursor: pointer;
  width: 48px;
  height: 48px;
  background-image: url("/wp-content/themes/bevi/dist/images/quiz/back.png");
  background-position: right;
  background-repeat: no-repeat;
  background-size: contain;
  text-indent: -9999px;
  white-space: nowrap;
}

.quiz .gform_wrapper .gfield_label {
  cursor: pointer;
  flex-grow: 0;
  font-size: 14px;
  font-weight: 600;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.21;
  letter-spacing: normal;
  text-align: left;
  color: #000;
}

.quiz .gform_wrapper select,
.quiz .gform_wrapper input[type=text] {
  border-bottom: 1px solid #a8c3e4;  font-size: 14px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.71;
  letter-spacing: 0.56px;
  text-align: left;
  color: #aaa;
  padding-left: 30px !important;
  background-position: left;
  background-repeat: no-repeat;
  background-size: initial;
}

.quiz .ginput_address_zip input {
  background-image: url("/wp-content/themes/bevi/dist/images/quiz/map.svg");
}

.quiz .people input {
  background-image: url("/wp-content/themes/bevi/dist/images/quiz/people.svg");
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
    jQuery(document).ready(function () {
      var fulltext = jQuery('.desctption.readmore');
      var readmorelink = jQuery('.readmorelink');
      readmorelink.on("click", function (e) {
        e.preventDefault();
        fulltext.toggleClass('open');
        jQuery(this).html(jQuery(this).html() == 'Read less' ? 'Read more' : 'Read less');
      });
    });
  },
};
</script>