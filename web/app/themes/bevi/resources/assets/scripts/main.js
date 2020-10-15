/* eslint-disable import/extensions */
import Vue from 'vue';

// Plugins
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

// Components
import SiteHeader from '~/components/core/SiteHeader/SiteHeader';
import SiteNavigation from '~/components/core/SiteNavigation/SiteNavigation';
import SiteFooter from '~/components/core/SiteFooter/SiteFooter';
import SiteFooterNavigation from '~/components/core/SiteFooter/SiteFooterNavigation';
import SiteFooterCopyright from '~/components/core/SiteFooter/SiteCopyright';
import TextBlock from '~/components/modules/TextBlock/TextBlock';

gsap.registerPlugin(ScrollTrigger);

// Register Plugins
Vue.use(gsap);

// Register Components
Vue.component('SiteHeader', SiteHeader);
Vue.component('SiteNavigation', SiteNavigation);
Vue.component('SiteFooter', SiteFooter);
Vue.component('SiteFooterNavigation', SiteFooterNavigation);
Vue.component('SiteFooterCopyright', SiteFooterCopyright);
Vue.component('TextBlock', TextBlock);

// eslint-disable-next-line no-new
new Vue({
  el: '#app',
});
