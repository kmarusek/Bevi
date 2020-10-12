/* eslint-disable import/extensions */
import Vue from 'vue';

// Plugins

// Components
import SiteHeader from '~/components/core/SiteHeader/SiteHeader';
import SiteNavigation from '~/components/core/SiteNavigation/SiteNavigation';

// Register Plugins

// Register Components
Vue.component('SiteHeader', SiteHeader);
Vue.component('SiteNavigation', SiteNavigation);

// eslint-disable-next-line no-new
new Vue({
  el: '#app',
});
