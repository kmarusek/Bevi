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
*:focus {
    -webkit-tap-highlight-color: transparent;
    outline: none;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
}
.gform_body.gform-body {
  font-family: Space Grotesk, sans-serif !important;
}
.gform_wrapper.gravity-theme input:not([type=radio]):not([type=checkbox]):not([type=image]):not([type=file]) {
  line-height: 0;
}
.quiz {
  padding-top: 8vw;
}
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
.quiz .gform-body #gform_page_2_4 {
  background-image: url("/wp-content/themes/bevi/dist/images/quiz/question-2.4.svg");
}
.quiz .gform-body .gform_page.page3 {
  background-image: url("/wp-content/themes/bevi/dist/images/quiz/question-3.1.svg");
}
.quiz .gform-body .gform_page.result {
  background-image: none;
}
#gform_page_2_1 .gform_page_footer.top_label {
  margin-top: 60px !important;
}
#gform_page_2_4 .gform_page_footer {
  position: absolute;
  bottom: 28px;
}
#gform_page_2_4 #field_2_61 {
  margin-bottom: 48px;
}
#gform_page_2_4 .gfield_label {
  color: #aaaaaa;
  font-size: 14px;
  font-weight: 400;
  line-height: 1.71;
  letter-spacing: 0.56px;
}
#gform_page_2_4 #input_2_61{
  border-bottom: 2px solid #f5f5f5;
}
#gform_page_2_4 .gform_button {
  margin-left: 75px !important;
}
#gform_page_2_4 .house-svg img{
    height: 100px;
    margin-bottom: 48px;
}
#gform_page_2_11 {
  padding-top: 48px !important;
}
#input_2_50{
  display: flex;
  align-items: flex-end;
}
.quiz .quiz-1-title {
  max-width: 500px;
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
#gform_next_button_2_1 {
  margin-left: 0px !important;
 }
.quiz .quiz-1-title.w-full {
  width: 100%;
  color: #787878;
}
.quiz-result-cta {
  color: #246eff;
  font-weight: 600;
  position: relative !important;
}
.quiz-result-cta:hover {
  color: #5179CC;
}
.quiz-terms {
  font-size: 10px;
}
.quiz-terms-cta {
  text-decoration: underline;
  color: #246eff;
}
.desctption {
  max-width: 432px;
  margin: 24px 0 24px;
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
#gform_fields_2_11 label {
  font-size: 14px;
  font-weight: 600;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.21;
  letter-spacing: normal;
  font-family: Space Grotesk, sans-serif;
  text-align: left;
  color: #000;
}
.quiz .gform_wrapper.gravity-theme .gform_footer, .gform_wrapper.gravity-theme .gform_page_footer {
  margin-top: 48px !important;
  margin-bottom: 0 !important;
}
.gform_wrapper.gravity-theme .gfield_checkbox label, .gform_wrapper.gravity-theme .gfield_radio label{
  font-size: 16px;
}
.quiz .gform_wrapper.gravity-theme .imageinput .gfield-choice-input+label {
  cursor: pointer;
  margin-bottom: 24px;
  vertical-align: middle;
  display: flex;
  width: 100%;
  height: 100%;
  align-items: flex-end;
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
  /* color: */
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
  font-size: 16px;
}

.quiz .check-with-label:checked + .label-for-check {
  font-weight: bold;
}
#field_2_54 {
  margin-bottom: 24px;
}
.quiz .gchoice {
  cursor: pointer;
  height: 64px;
  margin-bottom: 24px;
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
#gform_page_2_4 .gform_button {
  background-color: #4b273a;
}
.quiz .gform_button,
.quiz #gform_submit_button_1,
.quiz .gform_next_button {
  cursor: pointer;
  width: 160px;
  height: 48px;
  display: inline;
  padding: 16px 20px;
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
.quiz .gform_wrapper.gravity-theme .imageinput .gfield-choice-input:checked+label {
  filter: invert(32%) sepia(73%) saturate(3215%) hue-rotate(213deg) brightness(102%) contrast(101%);
}
/*
* Recommendation Pages
 */
 #gform_page_2_10 .quiz-1-title {
  font-size: 48px;
  line-height: 1.08;
 }



@media screen and (max-width: 430px) {
 #gform_page_2_1 .gform_page_footer.top_label{
  justify-content: space-around;
  margin-top: 30px !important;
 }
 #gform_next_button_2_1 {
  float: none;
 }
  .quiz .gform-body .gform_page {
  padding: 30px 24px 24px;
  }
  .quiz .quiz-1-title {
    font-size: 30px;
    letter-spacing: 1.5px;
  }
  #gform_page_2_4 #field_2_61 {
  margin-bottom: 40px;
  }
  .quiz .gform-body #gform_page_2_4 {
  background-image: none;
  }
  .quiz .gform_button,
  .quiz #gform_submit_button_1,
  .quiz .gform_next_button {
  line-height: 0 !important;
  }
  #field_2_54 {
  margin-bottom: 10px;
  }
  .quiz .gform-body .gform_page {
    background-image: none !important;
    margin: 20px;
  }
  .quiz #gform_submit_button_1,
  .quiz .gform_next_button {
    float: none;
  }
  /*
  * Progress Bar
  */
  .quiz .gform_wrapper.gravity-theme .gf_progressbar_blue {
    width: 330px;
  }
  /*
  * Buttons 
  */
  .gform_page_footer.top_label {
    display: flex;
    justify-content: space-between;
  }
  .quiz .gform_button,
  .quiz #gform_submit_button_1,
  .quiz .gform_next_button {
  margin-left: 10px !important;
  }
  /**
  * Unit unavailable screen
  */
   #field_2_68 {
    margin-bottom: 12px;
    text-align: right;
   }
   #gform_page_2_4 .gfield_label {
    color: #aaaaaa;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.71;
    letter-spacing: 0.56px;
  }
  #gform_page_2_4 #input_2_68{
  border-bottom: 2px solid #f5f5f5;
  }
  #gform_page_2_4 .gform_page_footer {
  bottom: 25px;
  padding: 0;
  }
  #gform_page_2_4 .house-svg img{
    height: 64px;
    margin-bottom: 40px;
  }
  /*
  * Radio Buttons
 */
 .gfield_radio{
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
 }
  .quiz .imageinput .gchoice {
    float: left;
    width: 100%;
  }
  .quiz .bubbles {
  z-index: -1;
  }

  /**
  * Other Radio
  */
  #input_2_34 .gchoise {
    width: 100%;
  }
  #input_2_40 .gchoice {
    width: 100%;
  }

  /*
  * Recommendation 
   */
  #gform_page_2_10 .quiz-1-title {
  font-size: 30px;
  line-height: normal;
 }
 /*
 * Final Page
  */
  #gform_page_2_12{
    padding-top: 30px;
  }
}
@media screen and (max-width: 400px) {
   /*
  * Progress Bar
  */
  .quiz .gform_wrapper.gravity-theme .gf_progressbar_blue {
    width: 250px;
  }
  .gform_wrapper.gravity-theme .gfield input.medium, .gform_wrapper.gravity-theme .gfield select.medium {
    width: 100% !important;
}
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
.quiz .gform_wrapper input[type=tel],
.quiz .gform_wrapper input[type=email],
.quiz .gform_wrapper input[type=text] {
  border-bottom: 1px solid #a8c3e4;  font-size: 14px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.71;
  letter-spacing: 0.56px;
  text-align: left;
  color: #aaa;
}
.quiz .gform_wrapper select:active,
.quiz .gform_wrapper input[type=tel]:active,
.quiz .gform_wrapper input[type=email]:active,
.quiz .gform_wrapper input[type=text]:active {
  caret-color: #246dff;
  border-bottom: 2px solid#246dff
}
.quiz .gform_wrapper select:focus,
.quiz .gform_wrapper input[type=tel]:focus,
.quiz .gform_wrapper input[type=email]:focus,
.quiz .gform_wrapper input[type=text]:focus {
  caret-color: #246dff;
  border-bottom: 2px solid#246dff
}
.quiz .gform_wrapper select {
  height: 41px;
}
.gform_wrapper.gravity-theme .gform_page_footer, .quiz .gform_wrapper.gravity-theme .gform_footer {
    margin-top: 30px!important;
}
.quiz .gform_wrapper{
  padding: 0 30px;
}
.quiz .gform_wrapper input[type=text] {
  background-position: left;
  background-repeat: no-repeat;
  background-size: initial;
}

.quiz .ginput_address_zip input {
  background-image: url("/wp-content/themes/bevi/dist/images/quiz/map.svg");
}

/* .quiz .people .ginput_container_select,
.quiz .people input {
  background-image: url("/wp-content/themes/bevi/dist/images/quiz/people.svg");
} */

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