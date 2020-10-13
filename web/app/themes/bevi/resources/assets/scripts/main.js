/* eslint-disable import/extensions */
import Vue from 'vue';

// Plugins

// Components
import SiteHeader from '~/components/core/SiteHeader/SiteHeader';
import SiteNavigation from '~/components/core/SiteNavigation/SiteNavigation';
import SiteFooter from '~/components/core/SiteFooter/SiteFooter';
import SiteFooterNavigation from '~/components/core/SiteFooterNavigation/SiteFooterNavigation';

// Register Plugins

// Register Components
Vue.component('SiteHeader', SiteHeader);
Vue.component('SiteNavigation', SiteNavigation);
Vue.component('SiteFooter', SiteFooter);
Vue.component('SiteFooterNavigation', SiteFooterNavigation);

// eslint-disable-next-line no-new
new Vue({
  el: '#app',
});
