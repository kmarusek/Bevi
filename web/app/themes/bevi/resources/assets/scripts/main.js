/* eslint-disable import/extensions */
import Vue from 'vue';

// Plugins
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';
import VueAwesomeSwiper from 'vue-awesome-swiper';

// Layout
import SiteHeader from '~/components/core/SiteHeader/SiteHeader';
import SiteNavigation from '~/components/core/SiteNavigation/SiteNavigation';
import SiteFooter from '~/components/core/SiteFooter/SiteFooter';
import SiteFooterNavigation from '~/components/core/SiteFooter/SiteFooterNavigation';
import SiteFooterCopyright from '~/components/core/SiteFooter/SiteCopyright';

// Components
import TextBlock from '~/components/modules/TextBlock/TextBlock';
import FeatureBlock from '~/components/modules/FeatureBlock/FeatureBlock';
import ImageCarousel from '~/components/modules/ImageCarousel/ImageCarousel';

// Common
import ImageBlob from '~/components/common/ImageBlob/ImageBlob';

// Register Plugins
gsap.registerPlugin(ScrollTrigger);
Vue.use(VueAwesomeSwiper);

// Register Components
Vue.component('SiteHeader', SiteHeader);
Vue.component('SiteNavigation', SiteNavigation);
Vue.component('SiteFooter', SiteFooter);
Vue.component('SiteFooterNavigation', SiteFooterNavigation);
Vue.component('SiteFooterCopyright', SiteFooterCopyright);
Vue.component('TextBlock', TextBlock);
Vue.component('FeatureBlock', FeatureBlock);
Vue.component('ImageCarousel', ImageCarousel);
Vue.component('ImageBlob', ImageBlob);

// eslint-disable-next-line no-new
new Vue({
  el: '#app',
});
